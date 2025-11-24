<?php

namespace Portal\Helpers\Translators;

require_once('memcachedResources.php');

use Portal\Helpers\Resources\CategoryList;
use Portal\Helpers\Resources\CompanyListResource;
use Portal\Helpers\Resources\GlobalList;
use Portal\Helpers\Resources\LocationsAllResource;
use Portal\Helpers\Resources\LocationsCategoryResource;
use Portal\Helpers\Resources\MemcachedResources;
use Portal\Helpers\Resources\TranslatesResource;
use Portal\Helpers\Services\LocationListService;

interface AbstractTranslator
{
    public function getStates();

    public function getRegions();

    public function getProperty($property);
}

class CompanyTranslator implements AbstractTranslator
{
    protected $companyListResource;
    protected $globalListResource;
    protected $categoryList;
    protected $locationsAllResource;
    protected $locationCategoryResource;

    public function __construct(
        MemcachedResources $companyListResource,
        GlobalList $globalList,
        CategoryList $categoryList,
        LocationsAllResource $locationsAllResource,
        LocationsCategoryResource $categoryResource
    ) {
        $this->companyListResource = $companyListResource;
        $this->globalListResource = $globalList;
        $this->locationsAllResource = $locationsAllResource;
        $this->locationCategoryResource = $categoryResource;
        $this->categoryList = $categoryList;
    }

    public function getStates()
    {
        $states = array();

        $locationsAllResoucesData = $this->locationsAllResource->getData();
        $locationAll = $locationsAllResoucesData['staty'];

        $companyListResourceData = $this->companyListResource->getData();
        foreach($companyListResourceData['staty'] as $state)
        {
            if(isset($locationAll[$state])){
                $states[$state] = array('name' => $locationAll[$state]['nazev'], 'url' => $locationAll[$state]['url']);
            }
        }

        return $states;
    }

    public function getRegions()
    {
        $regions = array();
        $regionsTranslates = $this->locationsAllResource->findInData('kraje');
        $localitesTranslates = $this->locationsAllResource->findInData('okresy');

        $localitesAll = $this->locationCategoryResource->findInData('lokality');

        $companyRegions = $this->companyListResource->findInData('kraje');
        $companyLocalites = $this->companyListResource->findInData('okresy');

        foreach($companyRegions as $region)
        {
            $regions[$region] = array('name' => $regionsTranslates[$region]['nazev'], 'url' => $regionsTranslates[$region]['url'],'subRegions' => array());

            foreach($localitesAll[$region] as $categoryRegion)
            {
                if(in_array($categoryRegion, $companyLocalites, false))
                {
                    $regions[$region]['subRegions'][$categoryRegion] = array('name'=>$localitesTranslates[$categoryRegion]['nazev'],'url'=>$localitesTranslates[$categoryRegion]['url']);
                }
            }
        }

        return $regions;
    }

    public function getProperty($property)
    {
        $properties = array();

        $globalList = $this->globalListResource->getData();

        $cisData = $this->companyListResource->findInData('cis');
        $companyProperties = $cisData[$property];

        $categoryList = $this->categoryList->findInData($property);

        foreach($companyProperties as $companyProperty)
        {
            if(in_array($companyProperty,$categoryList) && array_key_exists($companyProperty,$globalList))
            {
                $properties[$companyProperty] = array('name' => $globalList[$companyProperty]['nazev'],'url' => $globalList[$companyProperty]['url']);
            }
        }

        return $properties;
    }

    public function getCis()
    {
        $data = $this->companyListResource->getData();
        return $data['cis'];
    }
}

class FullTranslator implements AbstractTranslator
{
    protected $globalListResource;
    protected $categoryList;
    protected $locationsAllResource;
    protected $locationCategoryResource;

    public function __construct(
        GlobalList $globalList,
        CategoryList $categoryList,
        LocationsAllResource $locationsAllResource,
        LocationsCategoryResource $categoryResource
    ) {
        $this->globalListResource = $globalList;
        $this->locationsAllResource = $locationsAllResource;
        $this->locationCategoryResource = $categoryResource;
        $this->categoryList = $categoryList;
    }

    public function getStates()
    {
        $states = array();

        $locationsAllResoucesData = $this->locationsAllResource->getData();
        $locationAll = $locationsAllResoucesData['staty'];

        foreach($locationAll as $key => $state)
        {
            $states[$key] = array('name' => $state['nazev'], 'url' => $state['url']);
        }

        return $states;
    }

    public function getRegions()
    {
        $regions = array();
        $regionsTranslates = $this->locationsAllResource->findInData('kraje');
        $localitesTranslates = $this->locationsAllResource->findInData('okresy');

        $localitesAll = $this->locationCategoryResource->findInData('lokality');

        foreach($localitesAll as $key => $region)
        {
            $regions[$key] = array('name' => $regionsTranslates[$key]['nazev'], 'url' => $regionsTranslates[$key]['url'],'subRegions' => array());

            foreach($localitesAll[$key] as $categoryRegion)
            {
                $regions[$key]['subRegions'][$categoryRegion] = array('name'=>$localitesTranslates[$categoryRegion]['nazev'],'url'=>$localitesTranslates[$categoryRegion]['url']);
            }
        }

        return $regions;
    }

    public function getProperty($property)
    {
        $properties = array();

        $globalList = $this->globalListResource->getData();

        $categoryList = $this->categoryList->findInData($property);

        foreach($categoryList as $companyProperty)
        {
            $properties[$companyProperty] = array('name' => $globalList[$companyProperty]['nazev'],'url' => $globalList[$companyProperty]['url']);
        }

        return $properties;
    }
}

class Translator
{
    protected $currentLanguage;

    protected $translateResource;
    protected $data;

    public function __construct($language)
    {
        $this->currentLanguage = $language;

        $this->translateResource = new TranslatesResource($this->currentLanguage);

        $this->data = $this->translateResource->loadData();
    }

    public function getLanguage()
    {
        return $this->currentLanguage;
    }

    public function getString($key)
    {
        return isset($this->data[$key])? $this->data[$key] : '';
    }
}
