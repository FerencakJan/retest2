<?php

class LastQuerySession
{
  private function __construct()
  {
    // Static functions are enough
  }

  public static function saveLastQuery(
    string $fullQuery,
    array $params,
    array $data,
    string $link,
    int $maxPage = 0
  )
  {
    $_SESSION['last_query'] = array();

    $_SESSION['last_query']['full_query'] = $fullQuery;
    $_SESSION['last_query']['data'] = $data;
    $_SESSION['last_query']['query_params'] = $params;
    $_SESSION['last_query']['link'] = $link;
    $_SESSION['last_query']['max-page'] = $maxPage;

    $now = new \DateTime();
    $_SESSION['last_query']['save-date'] = $now->getTimestamp();
  }

  public static function hasLastQuerySaved(): bool
  {
    return isset($_SESSION['last_query']['full_query']);
  }

  public static function getLastFullQuery(): ?string
  {
    return $_SESSION['last_query']['full_query'] ?? null;
  }

  public static function getLastQueryParams()
  {
    return $_SESSION['last_query']['query_params'] ?? null;
  }

  public static function getLastQueryData()
  {
    return $_SESSION['last_query']['data'] ?? null;
  }

  public static function getLastLink(): ?string
  {
    return $_SESSION['last_query']['link'] ?? null;
  }

  public static function getLastMaxPage(): ?int
  {
    return $_SESSION['last_query']['max-page'] ?? null;
  }

  public static function haveLastQuery(): bool
  {
    return isset($_SESSION['last_query']['full_query']);
  }

  public static function deleteLastQueryData()
  {
    $_SESSION['last_query'] = null;
  }
}
