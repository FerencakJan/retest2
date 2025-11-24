<?php
// src/helpers/user_content.php

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

/** Bezpečné čítanie cookie hodnoty (URL-decode). */
function getCookieVal($name, $default = null) {
  return isset($_COOKIE[$name]) ? urldecode($_COOKIE[$name]) : $default;
}

/** Bezpečné JSON decode → pole */
function json_arr($str) {
  $d = json_decode($str ?? '', true);
  return is_array($d) ? $d : [];
}

/**
 * Spoľahlivé načítanie kontextu:
 * - VŽDY zmerguje SESSION aj COOKIES (cookies majú prioritu),
 * - drží tvar: geo, last_detail, recent_searches (max 3).
 */
function getUserContext(): array {
  $sess = isset($_SESSION['user_ctx']) && is_array($_SESSION['user_ctx']) ? $_SESSION['user_ctx'] : [];

  // načítaj z cookies
  $cookieCtx = [
    'geo' => [
      'lat'       => is_numeric(getCookieVal('geo_lat'))       ? (float)getCookieVal('geo_lat')       : null,
      'lng'       => is_numeric(getCookieVal('geo_lng'))       ? (float)getCookieVal('geo_lng')       : null,
      'precision' => is_numeric(getCookieVal('geo_precision')) ? (float)getCookieVal('geo_precision') : null,
      'ts'        => is_numeric(getCookieVal('geo_ts'))        ? (int)getCookieVal('geo_ts')          : null,
    ],
    'last_detail'     => json_arr($_COOKIE['last_detail']     ?? ''),
    'recent_searches' => json_arr($_COOKIE['recent_searches'] ?? ''),
  ];

  // merge: cookies majú prioritu (novšie)
  $ctx = array_replace_recursive([
    'geo' => ['lat'=>null,'lng'=>null,'precision'=>null,'ts'=>null],
    'last_detail' => [],
    'recent_searches' => []
  ], $sess, $cookieCtx);

  // drž iba posledné 3
  if (!empty($ctx['recent_searches'])) {
    $ctx['recent_searches'] = array_values(array_slice($ctx['recent_searches'], -3));
  }

  // ulož späť do session (zjednotený stav)
  $_SESSION['user_ctx'] = $ctx;
  return $ctx;
}

/** Ulož kontext do SESSION + COOKIES (30 dní). */
function saveUserContext(array $ctx): void {
  // udrž konzistentný tvar
  $ctx['geo']             = $ctx['geo']             ?? ['lat'=>null,'lng'=>null,'precision'=>null,'ts'=>null];
  $ctx['last_detail']     = $ctx['last_detail']     ?? [];
  $ctx['recent_searches'] = array_values(array_slice(($ctx['recent_searches'] ?? []), -3));

  $_SESSION['user_ctx'] = $ctx;

  if (headers_sent()) {
    // keď už sú hlavičky poslané, aspoň držíme session; cookie sa teraz nenastaví.
    // Dbaj, aby saveUserContext() bežal PRED výstupom (u teba to už tak je v detail/listing).
    return;
  }

  $opts = [
    'expires'  => time() + 60*60*24*30,
    'path'     => '/',
    'samesite' => 'Lax',
    // 'secure'   => true,     // zapni na produkcii s HTTPS
    // 'httponly' => false,    // čítame aj z JS geolokácie
  ];

  // GEO
  if (!empty($ctx['geo']['lat'])) setcookie('geo_lat', (string)$ctx['geo']['lat'], $opts);
  if (!empty($ctx['geo']['lng'])) setcookie('geo_lng', (string)$ctx['geo']['lng'], $opts);
  if (isset($ctx['geo']['precision'])) setcookie('geo_precision', (string)$ctx['geo']['precision'], $opts);
  if (isset($ctx['geo']['ts']))        setcookie('geo_ts', (string)$ctx['geo']['ts'], $opts);

  // LAST DETAIL
  setcookie('last_detail', json_encode($ctx['last_detail'], JSON_UNESCAPED_UNICODE), $opts);

  // RECENT SEARCHES – max 3
  setcookie('recent_searches', json_encode($ctx['recent_searches'], JSON_UNESCAPED_UNICODE), $opts);
}

/** Pridaj jedno hľadanie (deduplikácia + max 3). */
function uc_add_recent_search(array &$ctx, array $searchItem): void {
  // normalizuj jednoduché polia na skalár tam, kde dáva zmysel (kvôli porovnaniu)
  foreach (['advert_type_eu','advert_subtype_eu','advert_function_eu'] as $k) {
    if (isset($searchItem[$k]) && is_array($searchItem[$k]) && count($searchItem[$k]) === 1) {
      $searchItem[$k] = $searchItem[$k][0];
    }
  }
  $searchItem['ts'] = time();

  if (!isset($ctx['recent_searches']) || !is_array($ctx['recent_searches'])) {
    $ctx['recent_searches'] = [];
  }

  // deduplikácia: ak je posledný záznam rovnaký, nepushuj
  $last = end($ctx['recent_searches']);
  if ($last) {
    $cmpKeys = [
      'advert_type_eu','advert_subtype_eu','advert_function_eu',
      'locality_latitude','locality_longitude',
      'locality_stat_kod','locality_kraj_kod','locality_okres_kod','locality_obec_kod',
      'price_from','price_to','area_from','area_to','rooms'
    ];
    $same = true;
    foreach ($cmpKeys as $k) {
      $lv = $last[$k] ?? null;
      $nv = $searchItem[$k] ?? null;
      if ($lv !== $nv) { $same = false; break; }
    }
    if ($same) return;
  }

  $ctx['recent_searches'][] = $searchItem;
  $ctx['recent_searches'] = array_values(array_slice($ctx['recent_searches'], -3));
}

/**
 * Doplní do $queryData defaulty z kontextu IBA ak chýbajú (user > context).
 * Poradie: last_detail (typy + lokalita) -> geo (lat/lng + km=20) -> CZ=263.
 */
function mergeContextIntoQuery(array $queryData, array $ctx): array {
  // typy/podtypy/funkcia z last_detail
  if (!empty($ctx['last_detail'])) {
    foreach (['advert_type_eu','advert_subtype_eu','advert_function_eu'] as $k) {
      if (!isset($queryData[$k]) && isset($ctx['last_detail'][$k])) {
        $queryData[$k] = $ctx['last_detail'][$k];
      }
    }
    // lokalita z detailu, ak chýba
    foreach (['locality_latitude','locality_longitude','locality_stat_kod','locality_kraj_kod','locality_okres_kod','locality_obec_kod'] as $k) {
      if (!isset($queryData[$k]) && isset($ctx['last_detail'][$k])) {
        $queryData[$k] = $ctx['last_detail'][$k];
      }
    }
  }

  // ak stále nemáme lokalitu → GEO
  if (!isset($queryData['locality_latitude']) && !empty($ctx['geo']['lat']) && !empty($ctx['geo']['lng'])) {
    $queryData['locality_latitude']  = (float)$ctx['geo']['lat'];
    $queryData['locality_longitude'] = (float)$ctx['geo']['lng'];
    if (!isset($queryData['km'])) $queryData['km'] = 20;
  }

  // fallback: CZ
  if (!isset($queryData['locality_latitude']) && !isset($queryData['locality_stat_kod'])) {
    $queryData['locality_stat_kod'] = 263; // CZ
  }

  // normalizácie – ak tvoja search() chce polia
  foreach (['advert_type_eu','advert_subtype_eu','advert_function_eu'] as $k) {
    if (isset($queryData[$k]) && !is_array($queryData[$k])) $queryData[$k] = [$queryData[$k]];
  }

  return $queryData;
}

/** (Voliteľné) Vymaž kontext – na testovanie. */
function clearUserContext(): void {
  foreach (['geo_lat','geo_lng','geo_precision','geo_ts','last_detail','recent_searches'] as $c) {
    setcookie($c, '', time() - 3600, '/');
  }
  unset($_SESSION['user_ctx']);
}
