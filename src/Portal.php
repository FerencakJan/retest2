<?php

use Portal\Helpers\Resources\CategoryList;
use Portal\Helpers\Resources\CompanyListResource;
use Portal\Helpers\Resources\FullCompanyListResource;
use Portal\Helpers\Resources\GlobalList;
use Portal\Helpers\Resources\LocationsAllResource;
use Portal\Helpers\Resources\LocationsCategoryResource;
use Portal\Helpers\Resources\LocationsHomepageResource;
use Portal\Helpers\Translators\AbstractTranslator;
use Portal\Helpers\Translators\CompanyTranslator;

require_once("helpers/functions.php");
require_once("helpers/listsHelpers.php");
require_once("helpers/dataServices.php");
require_once("helpers/memcachedResources.php");
require_once("helpers/CssClassHelper.php");
require_once("helpers/Constants.php");
require_once("helpers/LastQuerySession.php");
require_once("helpers/CustomerHelper.php");
require_once("helpers/PhoneRenderer.php");
require_once("database/Auctions.php");
require_once("database/Blog.php");
require_once("database/Brokers.php");
require_once("database/Office.php");
require_once("database/Property.php");
require_once('helpers/mobileDetect.php');

class Portal
{
  const DEFAULT_QUESTION = 'Dobrý den, zaujala mne tato nemovitost. Prosím o poskytnutí více informací na uvedeném telefonním čísle nebo uvedené emailové adrese. Děkuji';

  const SORT_BY_ADDED = 1;
  const SORT_BY_ADDED_MAX = 2;
  const SORT_BY_PRICE = 3;
  const SORT_BY_PRICE_MAX = 4;
  const REQUEST_FORM = 'request-form';
  const CUSTOMER_INFO_LAST_ANSWER = 'last_answer';
  const CUSTOMER_INFO_FIRST_NAME = 'first_name';
  const CUSTOMER_INFO_LAST_NAME = 'last_name';
  const CUSTOMER_INFO_EMAIL = 'email';
  const CUSTOMER_INFO_PHONE = 'phone';

  /** @var string */
  protected $serverName;

  /** @var string */
  protected $clientIp;

  /** @var array */
  protected $portalInfo;

  protected $customerInfo;

  /** @var array */
  protected $pageData;

  protected $language;

  protected $menuItems;

  /** @var GlobalList */
  protected $globalListResource;

  /** @var \Portal\Helpers\Translators\CompanyTranslator */
  public $companyTranslator;

  /** @var \Portal\Helpers\Translators\CompanyTranslator */
  public $fullTranslator;

  /** @var bool */
  protected $metaIndex = false;

  /** @var bool */
  protected $metaFollow = false;

  protected $favoriteAdvertIds = array();

  /** @var CustomerHelper */
  private $customerHelper;

  protected $getDataResponse;

  protected $mobileDetect;

  private $version = null;

  protected $logs = array();

  public function __construct(string $serverName = null)
  {
    if (null === $serverName) {
      if (isset($_SESSION['portal_info']['url'])) {
        $this->serverName = $_SESSION['portal_info']['url'];
      } else {
        $this->serverName = $_SERVER['SERVER_NAME'];
      }
    } else {
      $this->serverName = $serverName;
    }

    $this->clientIp = getClientIP();

    // Tohle bych asi presunul do redisu
    if (!isset($_SESSION['portal_info']) || $_SESSION['portal_info']['url'] !== $this->serverName) {
      $companyService = new CompanyService();
      $this->portalInfo = $companyService->postCompany($this->serverName, $this->clientIp);
      $_SESSION['portal_info'] = $this->portalInfo;
    } else {
      $this->portalInfo = $_SESSION['portal_info'];
    }

    if(isset($_COOKIE['favorites']))
    {
      $this->favoriteAdvertIds = json_decode($_COOKIE['favorites'], true);
    }

    $this->language = $this->portalInfo['company_lang_id'];

    $companyListResource = new CompanyListResource($this->serverName);
    $categoryList = new CategoryList();
    $locationAllResource = new LocationsAllResource($this->language);
    $this->globalListResource = new GlobalList($this->language);

    $locationCategoryResource = new LocationsCategoryResource($this->language);
    $fullListResource = new FullCompanyListResource($this->getPortalCountryId());

    $this->companyTranslator = new CompanyTranslator($companyListResource, $this->globalListResource, $categoryList, $locationAllResource, $locationCategoryResource);
    $this->fullTranslator = new CompanyTranslator($fullListResource, $this->globalListResource, $categoryList, $locationAllResource, $locationCategoryResource);
    $this->customerHelper = new CustomerHelper();
    $this->mobileDetect = new \Mobile_Detect();
  }

  public function startRenderAction(string $action = null, bool $followRedirect = true, bool $seoNo = false, string $urlGet = null): void
  {
    $this->pageData = [
      'action' => $action,
      'id' => null,
      'template' => 'actions/listing.php',
      'getData' => null,
      'portalInfo' => $this->portalInfo
    ];

    $pageType = 'hp';
    $dataForGetDataCall = [];

    switch ($action) {
      case 'hp':
        $this->pageData['template'] = 'actions/homepage.php';
        break;
      case 'office';
        $pageType = 'prehled_rk';
        $this->pageData['template'] = 'actions/estate-agency-detail.php';
        break;
      case 'detail':
        $pageType = 'detail';
        $this->pageData['template'] = 'actions/detail.php';
        $this->pageData['id'] = (integer)$_GET['after'];
        break;
      case 'detail-rk':
        $pageType = 'detail_rk';
        $this->pageData['template'] = 'actions/estate-agency-detail.php';
        $dataForGetDataCall['page_number'] = solvePageNumber($_SERVER['REQUEST_URI']);
        $this->pageData['page_number'] = $dataForGetDataCall['page_number'];
        $this->pageData['id'] = (integer)$_GET['after'];

        $dataForGetDataCall['page_number'] = solvePageNumber($_SERVER['REQUEST_URI']);
        break;
      case 'detail-broker':
        $pageType = 'detail_broker';
        $this->pageData['template'] = 'actions/broker-detail.php';
        $dataForGetDataCall['page_number'] = solvePageNumber($_SERVER['REQUEST_URI']);
        $this->pageData['page_number'] = $dataForGetDataCall['page_number'];
        $this->pageData['id'] = (integer)$_GET['after'];

        $dataForGetDataCall['page_number'] = solvePageNumber($_SERVER['REQUEST_URI']);
        break;
      case 'textdetail':
        $pageType = 'text';
        $this->pageData['template'] = 'actions/typography.php';
        $this->pageData['id'] = (integer)$_GET['id'];
        break;
      case 'blog':
        $pageType = 'blog_list';
        $this->pageData['template'] = 'actions/blog.php';
        $dataForGetDataCall['page_number'] = solvePageNumber($_SERVER['REQUEST_URI']);
        $this->pageData['page_number'] = $dataForGetDataCall['page_number'];
        break;
      case 'blogDetail':
        $pageType = 'blog_text';
        $this->pageData['template'] = 'actions/blog-detail.php';
        $this->pageData['id'] = (integer)$_GET['id'];
        $dataForGetDataCall['page_id'] = solvePageNumber($_SERVER['REQUEST_URI']);
        break;
      case 'magazinedetail':
        $pageType = 'text';
        $this->pageData['template'] = 'actions/typography.php';
        $this->pageData['id'] = (integer)$_GET['id'];
        $dataForGetDataCall['magazine'] = 1;
        break;
      case 'offices':
        $pageType = 'prehled_rk';
        $this->pageData['template'] = 'actions/estate-agency.php';
        $dataForGetDataCall['page_number'] = solvePageNumber($_SERVER['REQUEST_URI']);
        $this->pageData['page_number'] = $dataForGetDataCall['page_number'];

        break;
      case 'brokerlist':
        $pageType = 'brokerslist';
        // TODO check template ??
        $this->pageData['template'] = 'actions/typography.php';
        break;
      case "auctions":
        $pageType = 'auctionslist';
        $this->pageData['template'] = 'actions/auction.php';
        break;
      case "auction-detail":
        $pageType = 'auction-detail';
        $this->pageData['template'] = 'actions/auction-detail.php';
        $this->pageData['id'] = (integer)$_GET['id'];
        $dataForGetDataCall['vydrazeno'] = 1;
        break;
      case "auctions-done":
        $pageType = 'auctions-done';
        // TODO check template ??
        $this->pageData['template'] = 'actions/auction.php';
        break;
      case 'searchform':
        $pageType = 'prehled';
        $this->pageData['template'] = 'actions/listing.php';
        if (strtolower($_SERVER['REQUEST_METHOD']) === 'post'){
          LastQuerySession::deleteLastQueryData();
            $_SESSION['post-data'] = $_POST['sql'];

          header('Location: /search-form/');
          die();
        }else{
          if(!LastQuerySession::hasLastQuerySaved()){
            $urlGet = 'LISTING-NONE';
            $dataForGetDataCall['sql'] = $_SESSION['post-data'];
            $_SESSION['post-data'] = null;
          }else{
            $dataForGetDataCall['sql'] = LastQuerySession::getLastQueryData();
          }
        }

        $this->setMetaRobots(false, false);

        if(isset($dataForGetDataCall['sql']['poptavka']['enable']) && $dataForGetDataCall['sql']['poptavka']['enable'] == 1)
        {
          $this->customerHelper->saveCustomerInfo(CustomerHelper::CUSTOMER_INFO_FIRST_NAME, $dataForGetDataCall['sql']['poptavka']['jmeno']);
          $this->customerHelper->saveCustomerInfo(CustomerHelper::CUSTOMER_INFO_LAST_NAME, $dataForGetDataCall['sql']['poptavka']['prijmeni']);
          $this->customerHelper->saveCustomerInfo(CustomerHelper::CUSTOMER_INFO_EMAIL, $dataForGetDataCall['sql']['poptavka']['email']);
          $this->customerHelper->saveCustomerInfo(CustomerHelper::CUSTOMER_INFO_PHONE, $dataForGetDataCall['sql']['poptavka']['telefon']);
        }

        break;
      case 'brokerrealites':
        $pageType = 'prehled';
        $this->pageData['template'] = 'actions/listing.php';
        $dataForGetDataCall['sql']['listing_broker_id'] = (integer)$_GET['id'];
        break;
      case 'favorites':
        $this->pageData['template'] = 'actions/favorites.php';
        break;
      case 'notknown':
        $pageType = 'prehled';
        $this->pageData['template'] = 'actions/listing.php';
        break;
    }

    if ($seoNo) {
      $dataForGetDataCall['seoNo'] = true;
    }

    if ($urlGet) {
      $actualURL = $urlGet;
    } else {
      $actualURL = $_SERVER['REQUEST_URI'];
    }

    $getDataService = new GetDataService($this);

    $this->pageData['getData'] = $getDataService->pageAccessCall($pageType, $this->pageData['id'], $actualURL, $dataForGetDataCall, $followRedirect);
  }

  public function getPostParameters(): array
  {
    return [
      'company_id' => $this->portalInfo['company_id'],
      'company_name' => $this->portalInfo['company_name'],
      'company_country_id' => $this->portalInfo['company_country_id'],
      'company_id_array' => $this->portalInfo['company_id_array'],
      'company_lang_id' => $this->portalInfo['company_lang_id'],
      'company_type' => $this->portalInfo['company_type'],
      'client_ip' => $this->clientIp,

      'url_host' => 'retest.eurobydleni.cz',// $_SERVER['HTTP_HOST'],
      'url_type' => 'https',// isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http',
      'http_referer' => '', //$_SERVER['HTTP_REFERER'] ?? ''
    ];
  }

  public function getPortalCountryId()
  {
    return $this->portalInfo['company_country_id'];
  }

  public function formsTargetUrl()
  {
    return "https://retest.eurobydleni.cz";
  }

  public function render($template, $params = array(), $outside = false)
  {
    ob_start();

    $params['portal'] = $this;
    extract($params, EXTR_OVERWRITE);

    if ($outside) {
      require($template);
    } else {
      require(__DIR__ . '/pageComponents/' . $template);
    }

    $var = ob_get_contents();

    ob_end_clean();

    return $var;
  }

  public function getServerName(): string
  {
    return $this->serverName;
  }

  /**
   * @return string
   */
  public function getClientIp(): string
  {
    return $this->clientIp;
  }

  /**
   * @return array
   */
  public function getPortalInfo(): array
  {
    return $this->portalInfo;
  }

  /**
   * @return array
   */
  public function getPageData(): array
  {
    return $this->pageData;
  }

  public function getPageNumber()
  {
    return $this->pageData["page_number"];
  }

  public function getPortalId()
  {
    return $this->pageData["id"];
  }

  /**
   * @return GlobalList
   */
  public function getGlobalListResource(): GlobalList
  {
    return $this->globalListResource;
  }


  /**
   * @return AbstractTranslator
   */
  public function getCompanyTranslator($full = false)
  {
    if ($full) {
      return $this->fullTranslator;
    }

    return $this->companyTranslator;
  }

  public function renderRichSnippets()
  {
    // 9089197 = localhost
    return in_array($this->getPortalId(), array('1', '3743', '3759' ,'9089197'));
  }

  public function getUrl()
  {
    if(isset($this->portalInfo['url']))
    {
      return '//' . $this->portalInfo['url'];
    }

    return '/';
  }

  public function isPropertyAdvertIdInFavorites($advertId)
  {
    return in_array($advertId, $this->favoriteAdvertIds, false);
  }

  public function getPortalCompanyType()
  {
    return $this->portalInfo['company_type'];
  }

  /**
   * @return mixed
   */
  public function getGetDataResponse()
  {
    return $this->getDataResponse;
  }

  public function setMetaRobots($index, $follow)
  {
    $this->metaIndex = $index;
    $this->metaFollow = $follow;
  }

  /**
   * @return mixed
   */
  public function getLocationHomepage()
  {
    $locationHomepageResource = new LocationsHomepageResource($this->language);

    return $locationHomepageResource->getData();
  }

  public function getCustomerInfo($key, $default = '')
  {
    if($key === self::CUSTOMER_INFO_LAST_ANSWER)
    {
      return isset($this->customerInfo[$key]) && $this->customerInfo[$key] != '' ? $this->customerInfo[$key] : self::DEFAULT_QUESTION;
    }

    return isset($this->customerInfo[$key])? $this->customerInfo[$key] : $default;
  }

  public function getMetaRobots()
  {
    if(strpos($_SERVER['SERVER_NAME'], 'retest.eurobydleni.cz') !== false){
      return 'noindex,nofollow';
    }

    return ($this->metaIndex ? 'index' : 'noindex') . ',' . ($this->metaFollow ? 'follow' : 'nofollow');
  }

  /**
   * @return mixed
   */
  public function getLanguage()
  {
    return $this->language;
  }

  public function getPortalName()
  {
    return $this->portalInfo['company_name'];
  }

  public function getPortalIdArray()
  {
    return $this->portalInfo['company_id_array'];
  }

  public function getPortalLangId()
  {
    return $this->portalInfo['company_lang_id'];
  }

  /**
   * @return mixed
   */
  public function getMenuItems()
  {
    return $this->menuItems;
  }

  /**
   * @return \Mobile_Detect
   */
  public function getMobileDetect()
  {
    return $this->mobileDetect;
  }

  public function getAssetsVersion(): string
  {
    if(null === $this->version){
      try{
        $this->version = trim(shell_exec("git rev-parse --short HEAD"));

      }catch(\Exception $e){
        $this->version = (new \DateTime())->format('Y-m-d-H');
      }
    }

    return $this->version;
  }

  public function addLog(array $log){
    $this->logs[] = $log;
  }

  public function getLogs()
  {
    return $this->logs;
  }
}
