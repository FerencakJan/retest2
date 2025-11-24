<?php

function render($template, $params = array())
{
  ob_start();
  extract($params, EXTR_OVERWRITE);

  require($template);

  $var = ob_get_clean();

  ob_end_flush();

  return $var;
}

function getClientIP(): string
{
  if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    return $_SERVER['HTTP_CLIENT_IP'];
  }

  if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    return $_SERVER['HTTP_X_FORWARDED_FOR'];
  }

  return $_SERVER['REMOTE_ADDR'];
}

function solvePageNumber(string $url = null): int
{
  if($url == null && isset($_GET['page']))
  {
    return (integer)$_GET['page'];
  }

  $actualURl = $url == null? $_SERVER['REQUEST_URI'] : $url;

  $pos = strpos($actualURl, 'page-');

  if($pos !== false)
  {
    $pos += 5;

    return (integer)substr($actualURl, $pos);
  }

  $pos = strpos($actualURl, 'page=');

  if($pos !== false)
  {
    $pos += 5;
    $posEnd = strpos($actualURl, '&', $pos);

    if($posEnd === false)
    {
      return (integer)substr($actualURl, $pos);
    }

    return (integer)substr($actualURl, $pos, $posEnd - $pos);
  }

  return 1;
}

function generateSlug($perexTitle)
{
  $unwanted_array = array('Š' => 'S', 'š' => 's', 'Ž' => 'Z', 'ž' => 'z', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E',
    'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U',
    'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c',
    'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o',
    'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'þ' => 'b', 'ÿ' => 'y',
    '&#225;' => 'a', '&#233;' => 'e', '&#237;' => 'i', '&#243;' => 'o', '&#250;' => 'u',
    '&#193;' => 'A', '&#201;' => 'E', '&#205;' => 'I', '&#211;' => 'O', '&#218;' => 'U',
    '&#209;' => 'N', '&#241;' => 'n');
  $perexTitle = strtr($perexTitle, $unwanted_array);

  return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $perexTitle)));

}
