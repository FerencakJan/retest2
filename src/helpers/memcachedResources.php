<?php

namespace Portal\Helpers\Resources;
//
use Articles;
use CategoriesListService;
use CompanyDetails;
use CompanyList;
use ExportServers;
use GetTextService;
use GlobalListsService;
use HPConfiguration;
use LocationListService;
use Magazines;
use Memcached;
use MenuService;
use MostlySearched;
use Portal;
use PrBoxData;
use Property;
use Slider;
use Software;
use Translates;

require_once('dataServices.php');

// development wrapper for runtime without memcache
class MemcachedDev {
  private $values = [];

  public function addServer(string $server, int $port) {
    $this->values = [];
  }

  public function fetchAll(){
    return $this->values;
  }

  public function get(string $key) {
    return $this->values[$key] ?? null;
  }

  public function set(string $key, $value, int $expire = 60) {
    $this->values[$key] = $value;

    return $this->get($key);
  }

  public function add(string $key, $value, int $expire = 60) {
    $this->values[] = [$key => $value];
  }

  public function flush(): void {
    $this->values = [];
  }
}



class MemcacheWrapper
{
    private static $instance;

    public static function getInstance()
    {
        if (null === self::$instance) {
            if(class_exists('\Memcached')) {
              self::$instance = new \Memcached();
            }else{
              self::$instance = new MemcachedDev();
            }

            self::$instance->addServer('127.0.0.1', 11211);
        }

        return self::$instance;
    }

    public static function getAll()
    {
      return self::getInstance()->get('keys');
    }

    public static function registerKey($key)
    {
        $instance = self::getInstance();
        $keys = $instance->get('keys');

        if (false === $keys) {
            $keys = array();
        }

        if($keys === null || !in_array($key, $keys)){
            $keys[] = $key;

            return $instance->set('keys', $keys, 2 * 60 * 60);
        }

        return true;
    }

    public static function saveValue($key, $value)
    {
        $instance = self::getInstance();

        self::registerKey($key);

        return $instance->add($key, $value, 2 * 60 * 60);
    }

    public static function removeAll()
    {
        $instance = self::getInstance();
        $instance->flush();
    }
}

abstract class MemcachedResources
{
    protected $mem;
    protected $value;
    protected $userLanguage;

    /**
     * MemcachedResources constructor.
     */
    public function __construct($key, $language = 'CZK')
    {
        $this->userLanguage = $language;

        $this->mem = MemcacheWrapper::getInstance();

        if ($language !== 'CZK') {
            $key = "{$key}_{$language}";
        }

        $value = $this->mem->get($key);

            $this->value = $this->loadData();
//        if ($value === false || (is_array($value) && count($value) === 0)) {
//
//            MemcacheWrapper::saveValue($key, $this->value);
//        } else {
//            $this->value = $value;
//        }
    }

    abstract public function loadData();

    public function getData()
    {
        return $this->value;
    }

    public function findInData($key)
    {
        if (isset($this->value[$key])) {
            return $this->value[$key];
        }

        return null;
    }
}

class GlobalList extends MemcachedResources
{
    protected $language;

    /**
     * GlobalList constructor.
     */
    public function __construct($language)
    {
        $this->language = $language;

        parent::__construct('global_list_' . $language);
    }

    public function loadData()
    {
      return (new GlobalListsService())->getAll($this->language);
    }

    public function getByIdn($idn)
    {
        $property = array();
        foreach ($this->value as $key => $value) {
            if ($value['idn'] == $idn) {
                $property[$key] = $value;
            }
        }

        return $property;
    }

    public function getByNameId($id)
    {
        $data = $this->findInData($id);

        return $data['nazev'];
    }
}

class CategoryList extends MemcachedResources
{
    /**
     * GlobalList constructor.
     */
    public function __construct()
    {
        parent::__construct('category_list');
    }

    public function loadData()
    {
      return (new CategoriesListService())->getAll();
    }
}

class MenuResource extends MemcachedResources
{
    protected $url;
    protected $portal;

    /**
     * MenuResource constructor.
     */
    public function __construct($url, Portal $portal)
    {
        $this->url = $url;
        $this->portal = $portal;

        parent::__construct("menu_{$url}");
    }

    public function loadData()
    {
      return (new MenuService())->getMenuPost($this->portal);
    }
}

class CompanyListResource extends MemcachedResources
{
    protected $url;

    /**
     * MenuResource constructor.
     */
    public function __construct($url)
    {
        $this->url = $url;

        parent::__construct("list_{$url}");
    }

    public function loadData()
    {
      return (new CompanyList())->getAll($this->url);
    }
}

class FullCompanyListResource extends MemcachedResources
{
    protected $companyCountryId;

    /**
     * FullCompanyListResource constructor.
     *
     * @param $companyCountryId
     */
    public function __construct($companyCountryId)
    {
        $this->companyCountryId = $companyCountryId;

        parent::__construct("fullcompanylist_{$companyCountryId}");
    }

    public function loadData()
    {
      return (new CompanyList())->getByCountry($this->companyCountryId);
    }
}

class CompanySpecificListResource extends MemcachedResources
{
    protected $companyId;

    /**
     * CompanySpecificListResource constructor.
     * @param $companyId
     */
    public function __construct($companyId)
    {
        $this->companyId = $companyId;

        parent::__construct("companyspecificlist_{$companyId}");
    }

    public function loadData()
    {
      return (new CompanyList())->getByCompanyId($this->$companyId);
    }
}

class LocationsAllResource extends MemcachedResources
{
    protected $language;

    /**
     * LocationsResource constructor.
     */
    public function __construct($language)
    {
        $this->language = $language;

        parent::__construct("locations_all_{$language}");
    }

    public function loadData()
    {
      return (new LocationListService())->getAll($this->language);
    }
}

class LocationsCategoryResource extends MemcachedResources
{
    protected $language;

    /**
     * LocationsResource constructor.
     */
    public function __construct($language)
    {
        $this->language = $language;

        parent::__construct("locations_cat_{$language}");
    }

    public function loadData()
    {
      return (new LocationListService())->getCategories($this->language);
    }
}

class LocationsHomepageResource extends MemcachedResources
{
    protected $language;

    /**
     * LocationsResource constructor.
     */
    public function __construct($language)
    {
        $this->language = $language;

        parent::__construct("locations_hp_{$language}");
    }

    public function loadData()
    {
      return (new LocationListService())->getHP($this->language);
    }
}

class TextResource extends MemcachedResources
{
    protected $portal;
    protected $textId;

    public function __construct(Portal $portal, $textId, $language = 'CZK')
    {
        $this->portal = $portal;
        $this->textId = $textId;

        $companyId = $portal->getPortalId();

        parent::__construct("text_{$companyId}_{$textId}", $language);
    }

    public function loadData()
    {
      return (new GetTextService($this->portal))->getText($this->textId);
    }
}

class MagazineResource extends MemcachedResources
{
    protected $portal;
    protected $textId;

    public function __construct(Portal $portal, $textId, $language = 'CZK')
    {
        $this->portal = $portal;
        $this->textId = $textId;

        $companyId = $portal->getPortalId();

        parent::__construct("text_magazine_{$companyId}_{$textId}", $language);
    }

    public function loadData()
    {
      return (new GetTextService($this->portal))->getMagazine($this->textId);
    }
}

class TextHomepageResource extends MemcachedResources
{
    protected $portal;
    protected $positionId;

    public function __construct(Portal $portal, $positionId, $language = 'CZK')
    {
        $this->portal = $portal;
        $this->positionId = $positionId;

        $companyId = $portal->getPortalId();

        parent::__construct("text_{$companyId}_homepage_{$positionId}", $language);
    }

    public function loadData()
    {
      return (new GetTextService($this->portal))->getTextByPosition($this->positionId);
    }
}

class SoftwareResource extends MemcachedResources
{
    public function __construct()
    {
        parent::__construct('software');
    }

    public function loadData()
    {
      return (new Software())->getAll();
    }
}

class SliderResource extends MemcachedResources
{
    protected $portal;

    public function __construct(Portal $portal, $language = 'CZK')
    {
        $this->portal = $portal;

        parent::__construct("slider_{$this->portal->getServerName()}", $language);
    }

    public function loadData()
    {
      return (new Slider($this->portal))->getAll();
    }
}

class HPConfigurationResource extends MemcachedResources
{
    protected $portal;

    public function __construct(Portal $portal)
    {
        $this->portal = $portal;

        parent::__construct("hp_configuration_{$this->portal->getServerName()}");
    }

    public function loadData()
    {
      return (new HPConfiguration($this->portal))->getAll();
    }
}

class ArticlesResource extends MemcachedResources
{
    private $portal;

    /**
     * GlobalList constructor.
     */
    public function __construct(Portal $portal, $language = 'CZK')
    {
        $this->portal = $portal;

        parent::__construct("articles_{$portal->getPortalId()}", $language);
    }

    public function loadData()
    {
      return (new Articles($this->portal))->getAll();
    }
}

class MagazinesResource extends MemcachedResources
{
    /**
     * GlobalList constructor.
     */
    public function __construct($language = 'CZK')
    {
        parent::__construct('magazines', $language);
    }

    public function loadData()
    {
      return (new Magazines())->getAll();
    }
}

class CompanyDetailsResource extends MemcachedResources
{
    private $companyId;

    /**
     * GlobalList constructor.
     */
    public function __construct($companyId)
    {
        $this->companyId = $companyId;
        parent::__construct("company_details_{$this->companyId}");
    }

    public function loadData()
    {
      return (new CompanyDetails($this->companyId))->getAll();
    }
}

class MostlySearchedResource extends MemcachedResources
{
    protected $language;

    /**
     * LocationsResource constructor.
     */
    public function __construct($language)
    {
        $this->language = $language;

        parent::__construct("mostly_searched_{$language}");
    }

    public function loadData()
    {
      return (new MostlySearched($this->language))->getAll();
    }
}

class TranslatesResource extends MemcachedResources
{
    protected $language;

    /**
     * LocationsResource constructor.
     */
    public function __construct($language)
    {
        $this->language = $language;

        parent::__construct("translates_{$language}");
    }

    public function loadData()
    {
        $data = new Translates();
        $fullData = $data->getAll();

        return $fullData[$this->language] ?? null;
    }
}

class ConfCompanyListResource extends MemcachedResources
{
    protected $companyId;

    public function __construct($companyId)
    {
        $this->companyId = $companyId;

        parent::__construct("company_ciselniky_{$companyId}");
    }

    public function loadData()
    {
      return (new CompanyList())->getByCompanyId($this->companyId);
    }
}

class ExportServersResource extends MemcachedResources
{
    protected $portal;

    public function __construct(Portal $portal)
    {
        $this->portal = $portal;

        parent::__construct("company_export_servers_{$portal->getPortalId()}");
    }

    public function loadData()
    {
      return (new ExportServers($this->portal))->getAll();
    }
}

class PrBoxResource extends MemcachedResources
{
    protected $portal;

    public function __construct(Portal $portal)
    {
        $this->portal = $portal;

        parent::__construct("company_pr_box_{$portal->getPortalId()}");
    }

    public function loadData()
    {
      return (new PrBoxData($this->portal))->getAll();
    }
}
final class PropertyCountResource
{
  private $mem;
  private $propertyService;

  public function __construct(Portal $portal)
  {
    $this->mem = MemcacheWrapper::getInstance();
    $this->propertyService = new Property($portal);
  }

  public function getData()
  {
    $cachedPropertyCount = null;

    if ($this->mem->fetchAll()){
      $cachedPropertyCount = count($this->mem->fetchAll()) > 1 ? $this->mem->get(0)['propertyCount'] : null;
    }

    if ($cachedPropertyCount === null) {
      $this->saveProperties();
      if ($this->mem instanceof MemcachedDev){
        $cachedPropertyCount = $this->mem->get(0)['propertyCount'];
      } else {
        $cachedPropertyCount = $this->mem->get('propertyCount');
      }
    }

    return $cachedPropertyCount;
  }

  private function loadPropertyCount()
  {
    $propertyCountByCategory = $this->propertyService->countPropertiesByCategoryAndCity();

    return $propertyCountByCategory;
  }

  private function saveProperties()
  {
    MemcacheWrapper::saveValue('propertyCount', $this->loadPropertyCount());
  }
}
