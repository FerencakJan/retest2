<?php

class CustomerHelper
{
  const CUSTOMER_INFO_FIRST_NAME = 'first_name';
  const CUSTOMER_INFO_LAST_NAME = 'last_name';
  const CUSTOMER_INFO_EMAIL = 'email';
  const CUSTOMER_INFO_PHONE = 'phone';
  const CUSTOMER_INFO_LAST_ANSWER = 'last_answer';
  const CUSTOMER_INFO_LISTING_SORT = 'listing_sort';
  const DEFAULT_QUESTION = 'Dobrý den, zaujala mne tato nemovitost. Prosím o poskytnutí více informací na uvedeném telefonním čísle nebo uvedené emailové adrese. Děkuji';

  const SORT_BY_ADDED = 'sort-added';
  const SORT_BY_PRICE = 'sort-price';
  const SORT_BY_DEFAULT = 'sort-default';

  const SORT_BY_PRICE_MAX = 'sort-price-max';
  const SORT_BY_ADDED_MAX = 'sort-added-max';

  private $clientInfo;
  private $customerInfo;

  public function __construct()
  {
    if (!isset($_SESSION['client_info'])) {
      $this->clientInfo = array();

      if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
      } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
      } else {
        $ip = $_SERVER['REMOTE_ADDR'];
      }

      $this->clientInfo['client_ip'] = $ip;

      $this->customerInfo = array();
      if (isset($_SESSION['customer_info'])) {
        $this->customerInfo = $_SESSION['customer_info'];
      }

      if (isset($_COOKIE['form']['email'])) {
        $this->saveCustomerInfo(self::CUSTOMER_INFO_EMAIL, $_COOKIE['form']['email']);
      }
      if (isset($_COOKIE['form']['jmeno'])) {
        $this->saveCustomerInfo(self::CUSTOMER_INFO_FIRST_NAME, $_COOKIE['form']['jmeno']);
      }
      if (isset($_COOKIE['form']['prijmeni'])) {
        $this->saveCustomerInfo(self::CUSTOMER_INFO_LAST_NAME, $_COOKIE['form']['prijmeni']);
      }
      if (isset($_COOKIE['form']['telefon'])) {
        $this->saveCustomerInfo(self::CUSTOMER_INFO_PHONE, $_COOKIE['form']['telefon']);
      }
      if (isset($_COOKIE['form']['dotaz'])) {
        $this->saveCustomerInfo(self::CUSTOMER_INFO_LAST_ANSWER, $_COOKIE['form']['dotaz']);
      }
      if (isset($_COOKIE['listing-sort'])) {
        $this->saveCustomerInfo(self::CUSTOMER_INFO_LISTING_SORT, $_COOKIE['listing-sort']);
      }
    }
  }

  public function saveCustomerInfo($key, $value)
  {
    $this->customerInfo[$key] = $value;

    $_SESSION['customer_info'] = $this->customerInfo;
  }

  public function getCustomerInfo($key, $default = '')
  {
    if ($key === self::CUSTOMER_INFO_LAST_ANSWER) {
      return isset($this->customerInfo[$key]) && $this->customerInfo[$key] != '' ? $this->customerInfo[$key] : self::DEFAULT_QUESTION;
    }

    return isset($this->customerInfo[$key]) ? $this->customerInfo[$key] : $default;
  }

  public function getListingSort()
  {
    $customerInfo = $this->getCustomerInfo(self::CUSTOMER_INFO_LISTING_SORT);

    switch ($customerInfo)
    {
      case self::SORT_BY_ADDED:
      case self::SORT_BY_ADDED_MAX:
      case self::SORT_BY_PRICE:
      case self::SORT_BY_PRICE_MAX:
        return $customerInfo;
      default:
        return self::SORT_BY_DEFAULT;
    }
  }
}
