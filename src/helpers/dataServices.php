<?php

class DataService
{
    protected $baseUrl;

    /**
     * DataService constructor.
     */
    public function __construct()
    {
      // TODO DEV ONLY
        $this->baseUrl = 'https://retest.eurobydleni.cz/mlift/';
//        $this->baseUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/mlift/';
    }

    public function getData($url,array $params = array())
    {
        $paramQuery = http_build_query($params);

        $url .= (strpos($url, '?') === false ? '?' : '&') . $paramQuery;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = json_decode(curl_exec($ch), true);
        curl_close($ch);
        return $result !== null? $result : array();
    }

    public function postData($url, array $data = array())
    {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, count($data));
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        // Marek dev
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST , 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER , 0);

        $result = json_decode(curl_exec($ch), true);

        curl_close($ch);

        return $result !== null? $result : array();
    }

    public function tryFollowRedirect($data)
    {
        if(isset($data['replace']['status']) && $data['replace']['status'] > 0)
        {
            if ($data['replace']['status'] == 999) {
                echo 'Kontaktuje podporu na info@eurobydleni.cz';
                die();
            }

            if($data['replace']['status'] && $data['replace']['status'] == 404){
                require($_SERVER['CONTEXT_DOCUMENT_ROOT'] . '/404.php');
                die();
            } else if($data['replace']['status'] == 404) {
                require(__DIR__ . '/../../../404.php');
                die();
            } else {
                header('Location: ' . $data['replace']['url']);
                die();
            }
        }
    }
}

class CompanyService extends DataService
{
    public function getCompany($serverName, $followRedirect = true)
    {
        $data = $this->getData($this->baseUrl . 'services/get_company.php', array('set_url'=> $serverName));

        if($followRedirect)
        {
            $this->tryFollowRedirect($data);
        }

        return $data;
    }

    public function postCompany($serverName, $clientId, $followRedirect = true)
    {
        $data = $this->postData($this->baseUrl . 'services/get_company.php', array(
            'url' => $serverName,
            'client_ip' => $clientId,
            'secure' => 'YVfun8BCVEkwv7BLo7C'
        ));

        if($followRedirect)
        {
            $this->tryFollowRedirect($data);
        }

        return $data;
    }
}

class GlobalListsService extends DataService
{
    public function getAll($language)
    {
        return $this->getData($this->baseUrl . "json/ciselniky_{$language}_list.json");
    }
}

class CategoriesListService extends DataService
{
    public function getAll()
    {
        return $this->getData($this->baseUrl . 'json/ciselniky_cat.json');
    }
}

class LocationListService extends DataService
{
    public function getAll($language)
    {
        return $this->getData($this->baseUrl . "json/lokalita_{$language}_all.json");
    }

    public function getCategories($language)
    {
        return $this->getData($this->baseUrl . "json/lokalita_{$language}_cat.json");
    }

    public function getHP($language)
    {
        return $this->getData($this->baseUrl . "json/lokalita_{$language}_hp.json");
    }
}

class MenuService extends DataService
{
    public function getMenu($url)
    {
        return $this->getData($this->baseUrl . 'services/get_menu.php', array('set_url' => $url));
    }

    public function getMenuPost(Portal $portal)
    {
        $params = $portal->getPostParameters();

        return $this->postData($this->baseUrl . 'services/get_menu.php', $params);
    }
}

class CompanyList extends DataService
{
    public function getAll($url)
    {
        return $this->getData($this->baseUrl . 'services/get_ciselnik.php', array('set_url' => $url));
    }

    public function getByCountry($companyCountryId)
    {
        if($companyCountryId == 263)
        {
            return $this->getData($this->baseUrl . 'json_konf/3743/ciselniky.json');
        }
        elseif($companyCountryId == 264)
        {
            return $this->getData($this->baseUrl . 'json_konf/3759/ciselniky.json');
        }
    }

    public function getByCompanyId($companyId)
    {
        return $this->getData($this->baseUrl . 'json_konf/' . $companyId . '/ciselniky.json');
    }
}

class GetTextService extends DataService
{
    private $portal;

    /**
     * GetTextService constructor.
     */
    public function __construct(Portal $portal)
    {
        parent::__construct();

        $this->portal = $portal;
    }

    public function getText($text_id)
    {
        $params = $this->portal->getPostParameters();
        $params['text_id'] = $text_id;

        return $this->postData($this->baseUrl . 'services/get_text.php', $params);
    }

    public function getTextByPosition($positionId)
    {
        $params = $this->portal->getPostParameters();
        $params['pozice_id'] = $positionId;

        return $this->postData($this->baseUrl . 'services/get_text.php', $params);
    }

    public function getMagazine($text_id)
    {
        $params = $this->portal->getPostParameters();
        $params['text_id'] = $text_id;
        $params['magazine'] = 1;

        return $this->postData($this->baseUrl . 'services/get_text.php', $params);
    }
}

class GetDataService extends DataService
{
    private $portal;

    /**
     * GetDataService constructor.
     *
     * @param $portal
     */
    public function __construct(Portal $portal)
    {
        parent::__construct();

        $this->portal = $portal;
    }

    public function pageAccessCall($pageType, $pageId = null, $urlGet = null, array $paramsAdd = null, $followRedirect = true)
    {
        $params = $this->portal->getPostParameters();

        $params['page_type'] = $pageType;

        if(null !== $pageId)
        {
            $params['page_id'] = $pageId;
        }

        if(null !== $urlGet && $urlGet !== 'LISTING-NONE')
        {
            $params['url_get'] = $urlGet;
        }

        if(null !== $paramsAdd && count($paramsAdd) !== 0)
        {
            $params = array_merge($params, $paramsAdd);
        }

        $data = $this->postData($this->baseUrl . 'services/get_data.php', $params);

        if($followRedirect)
        {
            $this->tryFollowRedirect($data);
        }

        $this->portal->addLog($params);

        return $data;
    }
}

class HomepageOffer extends DataService
{
    private $companyId;

    public function __construct($companyId)
    {
        parent::__construct();

        $this->companyId = $companyId;
    }

    public function getIds()
    {
        return $this->getData($this->baseUrl . "json_konf/{$this->companyId}/hp_zakazky.json");
    }
}

class Software extends DataService
{
    public function getAll()
    {
        return $this->getData($this->baseUrl . 'json/sw.json');
    }
}

class Slider extends DataService
{
    private $portal;

    public function __construct(Portal $portal)
    {
        parent::__construct();

        $this->portal = $portal;
    }

    public function getAll()
    {
        return $this->postData($this->baseUrl . 'services/get_slider.php', $this->portal->getPostParameters());
    }
}

class HPConfiguration extends DataService
{
    private $portal;

    public function __construct(Portal $portal)
    {
        parent::__construct();

        $this->portal = $portal;
    }

    public function getAll()
    {
        return $this->postData($this->baseUrl . 'services/get_hp_konf.php', $this->portal->getPostParameters());
    }
}

class Magazines extends DataService
{
    public function getAll($lang = '')
    {
        if($lang !== ''){
            $lang = "?lang={$lang}";
        }
        if($lang === 'CZK'){
            $lang = '';
        }

        return $this->getData($this->baseUrl . 'services/get_list_magazin.php' . $lang);
    }
}

class Articles extends DataService
{
    private $portal;

    public function __construct(Portal $portal)
    {
        parent::__construct();

        $this->portal = $portal;
    }

    public function getAll()
    {
        return $this->postData($this->baseUrl . 'services/get_list_article.php', $this->portal->getPostParameters());
    }

    public function getForPortal()
    {
        $params = $this->portal->getPostParameters();
        $params['set_url'] = $this->portal->getUrl();
        $params['url'] = $this->portal->getUrl();

        return $this->postData($this->baseUrl . 'services/get_list_article.php?set_url='. $this->portal->getUrl(), $params);
    }
}

class CompanyDetails extends DataService
{
    private $companyId;

    public function __construct($companyId)
    {
        parent::__construct();

        $this->companyId = $companyId;
    }

    public function getAll()
    {
        return $this->getData($this->baseUrl . "json_konf/{$this->companyId}/spolecnost.json");
    }
}

class MostlySearched extends DataService
{
    private $language;

    public function __construct($language)
    {
        parent::__construct();

        $this->language = $language;
    }

    public function getAll()
    {
        return $this->getData($this->baseUrl . "json/lokalita_hledane_{$this->language}_hp.json");
    }
}

class Translates extends DataService
{
    public function getAll()
    {
        return $this->getData('https://www.eurobydleni.cz/mlift/json/trans_cz.json');
    }
}

class ExportServers extends DataService
{
    private $portal;

    public function __construct(Portal $portal)
    {
        parent::__construct();

        $this->portal = $portal;
    }

    public function getAll()
    {
        return $this->postData($this->baseUrl . "services/get_export_server.php", $this->portal->getPostParameters());
    }
}

class PrBoxData extends DataService
{
    private $portal;

    public function __construct(Portal $portal)
    {
        parent::__construct();

        $this->portal = $portal;
    }

    public function getAll()
    {
        return $this->postData($this->baseUrl . "services/get_pr_box.php", $this->portal->getPostParameters());
    }
}
