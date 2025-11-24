<?php

require_once('Db.php');

class Property
{
    const COUNT_ROWS = 'select FOUND_ROWS() as total;';
    const COUNT = 'SELECT count(*) as totalSum FROM `fastest__eurobydlenis` fe ';
    const BASIC = 'SELECT SQL_CALC_FOUND_ROWS fe.advert_id as advert_id, advert_type_eu, title, advert_function_eu, title, advert_description, advert_locality, advert_price, advert_price_unit_eu, photo, photo_listing, advert_subtype_eu, area, usable_area, estate_area, locality_latitude, locality_longitude, alias_, advert_status_eu,advert_price_recnt, deleted FROM `fastest__eurobydlenis` fe ';
    const LINKS_ID = 'SELECT SQL_CALC_FOUND_ROWS fe.advert_id as advert_id, alias_ FROM `fastest__eurobydlenis` fe ';
    const BY_ADVERT_IDS = 'SELECT advert_id, advert_type_eu, advert_function_eu, title, advert_description, advert_locality, advert_price, advert_price_unit_eu, photo, photo_listing, advert_subtype_eu, area, usable_area, estate_area, locality_latitude, locality_longitude,advert_status_eu, alias_,advert_price_recnt, deleted FROM `fastest__eurobydlenis` fe';
    const BY_COMPANY_ID = ' where fe.company_id = :companyId and deleted = 0 and advert_status_eu in (51,52)';
    const BY_COMPANY_ID_NEXT_ITEMS = 'SELECT * FROM `fastest__eurobydlenis` WHERE company_id = :companyId';

    const BASIC_WITH_PHOTOS = 'SELECT SQL_CALC_FOUND_ROWS fe.advert_id as advert_id, advert_type_eu, title, advert_function_eu, title, advert_description, advert_locality, advert_price, advert_price_unit_eu, photo, photo_listing, advert_subtype_eu, area, usable_area, estate_area, locality_latitude, locality_longitude, alias_, advert_status_eu, photos_listing as photos, advert_price_recnt, deleted,locality_ulice_kod,locality_street,locality_city,locality_stat_kod,locality_cobce_kod,description_add,description,locality_psc FROM `fastest__eurobydlenis` fe';

    const FULL_SEARCH = 'SELECT * FROM `fastest__eurobydlenis` fe JOIN `fastest__eurobydleni_others` feo ON feo.advert_id = fe.advert_id';
    const FULL_SEARCH_DELETED = 'SELECT * FROM `fastest__eurobydlenis_deleted` fe ';

    const PROPERTIES = 'SELECT type_id, eu_id FROM `fastest__eurobydleni_properties` WHERE advert_id = :advert_id ORDER BY type_id';
    const MARKERS = 'SELECT fe.advert_id as advert_id,fe.company_id as company_id, fe.broker_id as broker_id, locality_latitude, locality_longitude, title, advert_price, advert_price_unit_eu, alias_, do_not_show_marker FROM `fastest__eurobydlenis` fe ';

    const JOIN_LISTING = ' JOIN `fastest__eurobydlenis_listing` fel ON fel.advert_id = fe.advert_id';
    const JOIN_OTHERS = ' JOIN `fastest__eurobydleni_others` feo ON feo.advert_id = fe.advert_id';
    const JOIN_PROPERTIES = ' JOIN `fastest__eurobydleni_properties` fep ON fep.advert_id = fe.advert_id';

    const BY_LOCALITY = 'SELECT fe.locality_latitude, fe.locality_longitude, fe.advert_id, fe.title, fe.advert_price, fe.advert_price_unit_eu, fe.photo, fe.alias_, SQRT(POW(69.1 * (fe.locality_latitude - :startlat), 2) + POW(69.1 * (:startlng - fe.locality_longitude) * COS(fe.locality_latitude / 57.3), 2)) AS distance FROM `fastest__eurobydlenis` fe HAVING distance < 10 ORDER BY distance';

    protected $database;

    /**
     * Property constructor.
     */
    public function __construct(Portal $portal)
    {
        $this->database = new DB($portal);
    }

    private function getTotalRowsCount()
    {
        return $this->database->single(self::COUNT_ROWS);
    }

    public function getForHomepage($advertIds, $limit = 3)
    {
        $paramsPrepared = ':' . implode(',:', range(0,count($advertIds) - 1));

        return $this->database->query(self::BASIC_WITH_PHOTOS . " WHERE fe.advert_id IN ($paramsPrepared) AND deleted = 0 ORDER BY RAND() LIMIT {$limit}", $advertIds);
    }

    public function getRandom3ForHomepage($companyId)
    {
        return $this->database->query(self::BASIC . ' WHERE company_id = :companyId AND deleted = 0 ORDER BY RAND() LIMIT 3', array('companyId' =>$companyId));
    }

  public function getLast30ForHomepageMap()
  {
    return $this->database->query(self::BASIC . ' WHERE deleted = 0 LIMIT 30');
  }

    public function getRandomForHomepage($companyId, $sum)
    {
        return $this->database->query(self::BASIC_WITH_PHOTOS . ' WHERE company_id = :companyId AND deleted = 0 ORDER BY RAND() LIMIT :sum', array('companyId' => $companyId, 'sum' => $sum));
    }

    public function findById($propertyId)
    {
        $this->database->query('SET CHARACTER SET utf8;');
        return current($this->database->query(self::FULL_SEARCH . ' WHERE fe.advert_id = :id', array('id' =>$propertyId)));
    }

    public function findByIdInDeleted($propertyId)
    {
        $this->database->query('SET CHARACTER SET utf8;');
        return current($this->database->query(self::FULL_SEARCH_DELETED . ' WHERE fe.advert_id = :id', array('id' =>$propertyId)));
    }

    public function findByIdInDeletedByTable($propertyId, $table)
    {
        $this->database->query('SET CHARACTER SET utf8;');
        return current($this->database->query('SELECT * FROM `'.$table.'` fe  WHERE fe.advert_id = :id', array('id' =>$propertyId)));
    }

    public function findByCompanyId($companyId, $offset, $max)
    {
        $rows = $this->database->query(self::BASIC_WITH_PHOTOS . self::BY_COMPANY_ID . ' ORDER BY advert_id desc LIMIT :offset, :max', array('companyId' => $companyId, 'max' => (integer)$max, 'offset' => (integer)$offset));
        $count = $this->getTotalRowsCount();
        $markers = $this->database->query(self::MARKERS . self::BY_COMPANY_ID. ' AND locality_latitude != 0 AND locality_longitude != 0 AND do_not_show_marker = 0' .' LIMIT 0, 500', array('companyId' => $companyId));

        return array('rows' => $rows, 'totalSum' => $count, 'markers' => $markers);
    }

  public function findCompanyNextItemsByCompanyId($companyId, $offset, $max)
  {
    $rows = $this->database->query(
      self::BASIC_WITH_PHOTOS .
      self::BY_COMPANY_ID .
      ' ORDER BY fe.advert_id DESC
          LIMIT :offset, :max',
      array(
        'companyId' => (int) $companyId,
        'max'       => (int) $max,
        'offset'    => (int) $offset
      )
    );

    return array(
      'rows'     => $rows,
      'totalSum' => $this->getTotalRowsCount()
    );
  }


  public function findPropertiesForProperty($propertyId)
    {
        return $this->database->query(self::PROPERTIES, array('advert_id'=>$propertyId));
    }

  public function byBrokerId($companyId, $brokerId, $offset, $max)
  {
    $rows = $this->database->query(
      self::BASIC_WITH_PHOTOS .
      ' WHERE fe.company_id = :company_id
            AND fe.broker_id = :broker_id
            AND fe.deleted = 0
            AND fe.advert_status_eu IN (51, 52)
          ORDER BY fe.advert_id DESC
          LIMIT :offset, :max',
      array(
        'company_id' => (int) $companyId,
        'broker_id'  => (int) $brokerId,
        'max'        => (int) $max,
        'offset'     => (int) $offset,
      )
    );

    $totalSum = $this->getTotalRowsCount();

    return array(
      'rows'     => $rows,
      'totalSum' => $totalSum,
    );
  }

    public function getPropertiesBasedOnUserLocation(float $latitude, float $longitude)
    {
      $query = "SELECT
       fe.advert_id,
       fe.title,
       fe.advert_price,
       fe.advert_price_unit_eu,
       fe.photo,
       fe.locality_latitude,
       fe.locality_longitude,
       fe.alias_,
       SQRT(POW(69.1 * (fe.locality_latitude - {$latitude}), 2) +
            POW(69.1 * ({$longitude} - fe.locality_longitude) * COS(fe.locality_latitude / 57.3), 2)) AS distance
       FROM `fastest__eurobydlenis` fe
       HAVING distance < 10 and fe.locality_latitude != 0 and fe.locality_longitude != 0
       ORDER BY distance
       LIMIT 30";

      return $this->database->query($query);
    }

    public function getPropertiesByOkres($okres) {
      $query = "select
       fe.advert_id,
       fe.title,
       fe.advert_price,
       fe.advert_price_unit_eu,
       fe.photo,
       fe.locality_latitude,
       fe.locality_longitude,
       fe.alias_
       from fastest__eurobydlenis as fe
       where fe.locality_okres_kod = {$okres}
       limit 30";

      return $this->database->query($query);
    }

    public function search($parameters, $offset, $max, $orderBy = null, $disableMarkers = false, $noCount = false)
    {
        $debugParams = array();
        $query = array();
        $joinListing = false;
        $queryParams = array();
        $queryParams['offset'] = $offset;
        $queryParams['max'] = $max;

        $parameters['deleted'] = 0;

        $this->solveParams($parameters, $query, $queryParams, $joinListing);

        $fullQRY = ($joinListing? self::JOIN_LISTING : '').' WHERE ' . implode(' AND ',$query);

        $order = $this->getOrderByClasule($orderBy);

        $selectQuery = self::BASIC_WITH_PHOTOS . $fullQRY . $order . ' LIMIT :offset, :max';

        if($noCount){
            $selectQuery = str_replace('SQL_CALC_FOUND_ROWS', '', $selectQuery);
        }
        $properties = $this->database->query($selectQuery, $queryParams);

        $count = 0;
        if(!$noCount){
            $count = $this->getTotalRowsCount();
        }

        $markers = null;
        if(!$disableMarkers){
            $queryParams['offset'] = 0;
            $queryParams['max'] = 1000;
            $markersQuery = self::MARKERS . $fullQRY. ' AND (locality_latitude > 0 OR locality_longitude > 0) AND do_not_show_marker = 0 LIMIT :offset, :max';
            $markers = $this->database->query($markersQuery, $queryParams);
        }

        return array('rows' =>$properties, 'totalSum' => $count, 'markers' => $markers, 'fullQuery' => $fullQRY, 'params' => $queryParams, 'debug' => $debugParams);
    }

    public function searchBySolved($fullQry, $queryParams, $offset, $max, $orderBy = null)
    {
        $queryParams['offset'] = $offset;
        $queryParams['max'] = $max;

        $order = $this->getOrderByClasule($orderBy);

        $properties = $this->database->query(self::BASIC_WITH_PHOTOS . $fullQry . $order . ' LIMIT :offset, :max', $queryParams);

        $count = $this->getTotalRowsCount();

        $queryParams['offset'] = 0;
        $queryParams['max'] = 1000;
        $markers = $this->database->query(self::MARKERS . $fullQry. ' AND (locality_latitude > 0 OR locality_longitude > 0) AND do_not_show_marker = 0 LIMIT :offset, :max', $queryParams);

        return array('rows' =>$properties, 'totalSum' =>$count, 'markers' => $markers);
    }

    public function findLinkIds($fullQry, $queryParams, $offset, $max, $orderBy = null)
    {
        $order = $this->getOrderByClasule($orderBy);
        $queryParams['offset'] = $offset;
        $queryParams['max'] = $max;

        return $this->database->query(self::LINKS_ID . $fullQry . $order . ' LIMIT :offset, :max', $queryParams);
    }

    private function createInSyntax(array $params, $prefix, $tableAlis = '', $in = 'IN')
    {
        $paramsConverted = array();
        if(count($params) === 1){
            if($in === "IN"){
                $query = "{$prefix} = :{$prefix}";
            }else{
                $query = "{$prefix} != :{$prefix}";
            }
            $paramsConverted[$prefix] = (integer)current($params);
        }else{
            $value = array();

            foreach($params as $key => $keyVal)
            {
                $value[] = ":{$prefix}_{$keyVal}";
                $paramsConverted["{$prefix}_{$keyVal}"] = (integer)$keyVal;
            }

            $query = "{$tableAlis}{$prefix} {$in} (" . implode(',', $value) . ')';
        }

        return array('query' => $query, 'params' => $paramsConverted);
    }

    public function findByIds($propertyIds, $offset, $max, $orderBy = null)
    {
        $queryPrepare = $this->createInSyntax($propertyIds, 'advert_id', 'fe.');

        $queryParams = $queryPrepare['params'];
        $queryParams['max'] = (integer)$max;
        $queryParams['offset'] = (integer)$offset;

        $order = $this->getOrderByClasule($orderBy);

        $rows = $this->database->query(self::BASIC_WITH_PHOTOS . ' WHERE ' . $queryPrepare['query'] . $order . ' LIMIT :offset, :max', $queryParams);
        $count = $this->getTotalRowsCount();
        $markers = $this->database->query(self::MARKERS . ' WHERE ' . $queryPrepare['query']. ' AND locality_latitude != 0 AND locality_longitude != 0 AND do_not_show_marker = 0 '. $order .' LIMIT 0, 500', $queryPrepare['params']);

        return array('rows' => $rows, 'totalSum' => $count, 'markers' => $markers);
    }

    public function countForHomepageForm($queryData)
    {
        $query = array();
        $queryParams = array();
        $joinListing = false;

        $this->solveParams($queryData, $query, $queryParams, $joinListing);

        $fullQRY = ($joinListing? self::JOIN_LISTING : '').' WHERE ' . implode(' AND ',$query) ;

        return $this->database->single(self::COUNT . $fullQRY, $queryParams);
    }

    public function countPropertiesByCategoryAndCity()
    {
      $properties = $this->database->query('select advert_type, advert_subtype, locality_okres_kod from fastest__eurobydlenis');

      /*
       * Categories
       * 7 = flats
       * 8 = houses
       * 9 = recreationProperties
       * 11 = commercialProperties
       * 12 = grounds
       * 13 = others
       *
       * Cities
       * 19 = Praha
       * 35 = České Budějovice
       * 43 = Plzeň
       * 51 = Karlovy Vary
       * 60 = Ústí nad Labem
       * 78 = Liberec
       * 86 = Hradec Králové
       * 94 = Pardubice
       * 108 = Jihlava
       * 116 = Brno
       * 124 = Olomouc
       * 132 = Ostrava
       * 141 = Zlín
      */
      $numberOfPropertiesByCategoryAndCity = [
        'propertyCountByCategory' => [
          7 => 0,
          8 => 0,
          9 => 0,
          11 => 0,
          12 => 0,
          13 => 0,
        ],
        'propertyCountByCity' => [
          19 => 0,
          35 => 0,
          43 => 0,
          51 => 0,
          60 => 0,
          78 => 0,
          86 => 0,
          94 => 0,
          108 => 0,
          116 => 0,
          124 => 0,
          132 => 0,
          141 => 0,
        ]
      ];
      $recreationSubTypes = [43, 33, 44];

      foreach ($properties as $property) {
        switch ($property['advert_type']) {
          case 1:
            $numberOfPropertiesByCategoryAndCity['propertyCountByCategory'][7]++;
            break;
          case 2:
            if (in_array($property['advert_subtype'], $recreationSubTypes)) {
              $numberOfPropertiesByCategoryAndCity['propertyCountByCategory'][9]++;
            } else {
              $numberOfPropertiesByCategoryAndCity['propertyCountByCategory'][8]++;
            }
            break;
          case 3:
            $numberOfPropertiesByCategoryAndCity['propertyCountByCategory'][12]++;
            break;
          case 4:
            $numberOfPropertiesByCategoryAndCity['propertyCountByCategory'][11]++;
            break;
          case 5:
            $numberOfPropertiesByCategoryAndCity['propertyCountByCategory'][13]++;
            break;
        }
      }

      foreach ($properties as $property) {
        switch ($property['locality_okres_kod']) {
          case 19:
          case 27:
          case 35:
          case 43:
          case 51:
          case 60:
          case 78:
          case 86:
          case 94:
          case 108:
            $numberOfPropertiesByCategoryAndCity['propertyCountByCity'][19]++;
            break;
          case 3301:
            $numberOfPropertiesByCategoryAndCity['propertyCountByCity'][35]++;
            break;
          case 3405:
          case 3406:
          case 3407:
            $numberOfPropertiesByCategoryAndCity['propertyCountByCity'][43]++;
            break;
          case 3403:
            $numberOfPropertiesByCategoryAndCity['propertyCountByCity'][51]++;
            break;
          case 3510:
            $numberOfPropertiesByCategoryAndCity['propertyCountByCity'][60]++;
            break;
          case 3505:
            $numberOfPropertiesByCategoryAndCity['propertyCountByCity'][78]++;
            break;
          case 3602:
            $numberOfPropertiesByCategoryAndCity['propertyCountByCity'][86]++;
            break;
          case 3606:
            $numberOfPropertiesByCategoryAndCity['propertyCountByCity'][94]++;
            break;
          case 3707:
            $numberOfPropertiesByCategoryAndCity['propertyCountByCity'][108]++;
            break;
          case 3702:
          case 3703:
            $numberOfPropertiesByCategoryAndCity['propertyCountByCity'][116]++;
            break;
          case 3805:
            $numberOfPropertiesByCategoryAndCity['propertyCountByCity'][124]++;
            break;
          case 3807:
            $numberOfPropertiesByCategoryAndCity['propertyCountByCity'][132]++;
            break;
          case 3705:
            $numberOfPropertiesByCategoryAndCity['propertyCountByCity'][141]++;
            break;
        }
      }

      return $numberOfPropertiesByCategoryAndCity;
    }

    private function solveParams($parameters, &$query, &$queryParams, &$joinListing)
    {
        if(isset($parameters['advert_type_eu']) && count($parameters['advert_type_eu']) > 0)
        {
            $queryPrepare = $this->createInSyntax($parameters['advert_type_eu'], 'advert_type_eu');
            $query[] = $queryPrepare['query'];
            $queryParams = array_merge($queryParams, $queryPrepare['params']);
        }

        if(isset($parameters['advert_subtype_eu']) && count($parameters['advert_subtype_eu']) > 0)
        {
            $queryPrepare = $this->createInSyntax($parameters['advert_subtype_eu'], 'advert_subtype_eu');
            $query[] = $queryPrepare['query'];
            $queryParams = array_merge($queryParams, $queryPrepare['params']);
        }

        if(isset($parameters['advert_function_eu']) && count($parameters['advert_function_eu']) > 0)
        {
            $queryPrepare = $this->createInSyntax($parameters['advert_function_eu'], 'advert_function_eu');
            $query[] = $queryPrepare['query'];
            $queryParams = array_merge($queryParams, $queryPrepare['params']);
        }

        if(isset($parameters['advert_status_eu']) && count($parameters['advert_status_eu']) > 0)
        {
            $queryPrepare = $this->createInSyntax($parameters['advert_status_eu'], 'advert_status_eu');
            $query[] = $queryPrepare['query'];
            $queryParams = array_merge($queryParams, $queryPrepare['params']);
        }

        if(isset($parameters['advert_price_min']) || isset($parameters['advert_price_max']))
        {
            $priceMin = (integer)str_replace(' ', '',$parameters['advert_price_min']);
            $priceMax = (integer)str_replace(' ', '',$parameters['advert_price_max']);
            $query[] = 'advert_price BETWEEN :advert_price_min AND :advert_price_max';

            $queryParams = array_merge($queryParams, array('advert_price_min' => $priceMin, 'advert_price_max' => $priceMax === 0? 999999999: $priceMax));
        }

        if(isset($parameters['usable_area_min']) || isset($parameters['usable_area_max']))
        {
            $usableAreaMin = (integer)str_replace(' ', '',$parameters['usable_area_min']);
            $usableAreaMax = (integer)str_replace(' ', '',$parameters['usable_area_max']);
            $query[] = 'usable_area BETWEEN :usable_area_min AND :usable_area_max';

            $queryParams = array_merge($queryParams, array('usable_area_min' => $usableAreaMin, 'usable_area_max' => $usableAreaMax === 0? 999999999: $usableAreaMax));
        }

        if(isset($parameters['estate_area_min']) || isset($parameters['estate_area_max']))
        {
            $estateAreaMin = (integer)str_replace(' ', '',$parameters['estate_area_min']);
            $estateAreaMax = (integer)str_replace(' ', '',$parameters['estate_area_max']);
            $query[] = 'estate_area BETWEEN :estate_area_min AND :estate_area_max';

            $queryParams = array_merge($queryParams, array('estate_area_min' => $estateAreaMin, 'estate_area_max' => $estateAreaMax === 0? 999999999: $estateAreaMax));
        }

        if(isset($parameters['locality_okres_kod']) || isset($parameters['locality_kraj_kod']))
        {
            $localityQuery = array();
            $localityParams = array();
            foreach($parameters['locality_kraj_kod'] as $localityKrajKod)
            {
                if(isset($parameters['locality_okres_kod']) && array_key_exists($localityKrajKod, $parameters['locality_okres_kod']))
                {
                    foreach($parameters['locality_okres_kod'][$localityKrajKod] as $localityOkrajKod)
                    {
                        $key = "locality_okres_kod_{$localityKrajKod}_{$localityOkrajKod}";
                        $localityQuery[] = "locality_okres_kod = :{$key}";
                        $localityParams[$key] = (integer)$localityOkrajKod;
                    }
                }
                else
                {
                    $key = "locality_kraj_kod_{$localityKrajKod}";
                    $localityQuery[] = "locality_kraj_kod = :{$key}";
                    $localityParams[$key] = (integer)$localityKrajKod;
                }
            }
            $query[] = '(' . implode(' OR ',$localityQuery) . ')';
            $queryParams = array_merge($queryParams, $localityParams);
        }

        if((isset($parameters['zahranicni']) && $parameters['zahranicni'] == 0) || !isset($parameters['zahranicni']))
        {
            $query[] = 'locality_stat_kod = :locality_stat_kod';
            $queryParams['locality_stat_kod'] = (integer)$parameters['company_country_id'];
        }
        else
        {
            if(isset($parameters['locality_stat_kod']) && count($parameters['locality_stat_kod']) > 0)
            {
                $queryPrepare = $this->createInSyntax($parameters['locality_stat_kod'], 'locality_stat_kod');
                $query[] = $queryPrepare['query'];
                $queryParams = array_merge($queryParams, $queryPrepare['params']);
            }else{
                $query[] = 'locality_stat_kod != :locality_stat_kod';
                $queryParams['locality_stat_kod'] = (integer)$parameters['company_country_id'];
            }
        }

        if(isset($parameters['locality_obec_kod']) && $parameters['locality_obec_kod'] > 0)
        {
            $query[] = 'locality_obec_kod = :locality_obec_kod';
            $queryParams['locality_obec_kod'] = $parameters['locality_obec_kod'];
        }

        if(isset($parameters['locality_cast_obce_kod']) && $parameters['locality_cast_obce_kod'] > 0)
        {
            $query[] = 'locality_cobce_kod = :locality_cobce_kod';
            $queryParams['locality_cobce_kod'] = $parameters['locality_cast_obce_kod'];
        }

        if(isset($parameters['company_id']))
        {
            if(is_array($parameters['company_id']) && count($parameters['company_id'])){
                $queryPrepare = $this->createInSyntax($parameters['company_id'], 'company_id');
                $query[] = $queryPrepare['query'];
                $queryParams = array_merge($queryParams, $queryPrepare['params']);
            }else{
                $query[] = 'fe.company_id = :company_id';
                $queryParams['company_id'] = (integer)$parameters['company_id'];
            }
        }

        if(isset($parameters['gps']))
        {
            $query[] = 'locality_latitude <= :gps_locality_north AND locality_latitude >= :gps_locality_south AND locality_longitude <= :gps_locality_east AND locality_longitude >= :gps_locality_west';
            $queryParams['gps_locality_north'] = $parameters['gps']['viewport']['north'];
            $queryParams['gps_locality_south'] = $parameters['gps']['viewport']['south'];
            $queryParams['gps_locality_east'] = $parameters['gps']['viewport']['east'];
            $queryParams['gps_locality_west'] = $parameters['gps']['viewport']['west'];
        }

        if(isset($parameters['ownership_eu']))
        {
            $queryPrepare = $this->createInSyntax($parameters['ownership_eu'], 'ownership_eu');
            $query[] = $queryPrepare['query'];
            $queryParams = array_merge($queryParams, $queryPrepare['params']);
        }

        if(isset($parameters['building_type_eu']))
        {
            $queryPrepare = $this->createInSyntax($parameters['building_type_eu'], 'building_type_eu');
            $query[] = $queryPrepare['query'];
            $queryParams = array_merge($queryParams, $queryPrepare['params']);
        }

        if(isset($parameters['object_kind']))
        {
            $queryPrepare = $this->createInSyntax($parameters['object_kind'], 'object_kind_eu');
            $query[] = $queryPrepare['query'];
            $queryParams = array_merge($queryParams, $queryPrepare['params']);
        }

        if(isset($parameters['object_location']))
        {
            $queryPrepare = $this->createInSyntax($parameters['object_location'], 'object_location_eu');
            $query[] = $queryPrepare['query'];
            $queryParams = array_merge($queryParams, $queryPrepare['params']);
        }

        if(isset($parameters['broker_id'])){
            $query[] = 'fe.broker_id = :broker_id';
            $queryParams['broker_id'] = (integer)$parameters['broker_id'];
        }

        if(isset($parameters['broker_ids'])){
            $queryPrepare = $this->createInSyntax($parameters['broker_ids'], 'broker_id');
            $query[] = $queryPrepare['query'];
            $queryParams = array_merge($queryParams, $queryPrepare['params']);
        }

        if(isset($parameters['listing_broker_id']) && $parameters['listing_broker_id'] > 0){
            $query[] = 'fe.broker_id = :listing_broker_id';
            $queryParams['listing_broker_id'] = (integer)$parameters['listing_broker_id'];
        }

        if(isset($parameters['deleted'])){
            $query[] = 'fe.deleted = :deleted';
            $queryParams['deleted'] = $parameters['deleted'];
        }

        if(isset($parameters['search_ec']) && $parameters['search_ec'] !== ''){
            $query[] = 'fe.advert_code = :search_ec';
            $queryParams['search_ec'] = "{$parameters['search_ec']}";
        }

        if(isset($parameters['search_locality']) && $parameters['search_locality'] !== ''){
            $query[] = '(fe.locality_city like :search_locality_1 or fe.locality_citypart like :search_locality_2 or fe.locality_street like :search_locality_3)';

            $queryParams['search_locality_1'] = "%{$parameters['search_locality']}%";
            $queryParams['search_locality_2'] = "%{$parameters['search_locality']}%";
            $queryParams['search_locality_3'] = "%{$parameters['search_locality']}%";
        }

        if(isset($parameters['not_id']))
        {
            $queryPrepare = $this->createInSyntax($parameters['not_id'], 'advert_id', '', 'NOT IN');
            $query[] = $queryPrepare['query'];
            $queryParams = array_merge($queryParams, $queryPrepare['params']);
        }

        if(isset($parameters['advert_ids']))
        {
            $queryPrepare = $this->createInSyntax($parameters['advert_ids'], 'advert_id');
            $query[] = $queryPrepare['query'];
            $queryParams = array_merge($queryParams, $queryPrepare['params']);
        }

        foreach(array('advert_equipment','advert_character','advert_sites') as $listingKey)
        {
            if(array_key_exists($listingKey, $parameters))
            {
                $joinListing = true;
                foreach($parameters[$listingKey] as $key => $listingValue)
                {
                    if ($listingValue == 400)
                    {
                        $query[] = 'col_405 = 1';
                    }else{
                        $query[] = "col_{$listingValue} = 1";
                    }
                }
            }
        }
    }

    public function findAtLeast($rows, $originSearch, $companyId)
    {



        $parameters = array();
        foreach($originSearch as $key => $values){
            if(in_array($key, array('not_id', 'company_country_id','advert_type_eu', 'zahranicni', 'locality_kraj_kod','locality_okres_kod','advert_subtype_eu',  'locality'))){
                $parameters[$key] = $values;
            }
        }

        $search = $this->search($parameters, 0, $rows, null, true);

        if($rows <= $search['totalSum'] && $search['totalSum'] < 50){
            $search['type'] = 'first';
            return $search;
//        }elseif($search['totalSum'] > 100){
//            $adding = array('advert_subtype_eu',  'locality');
//            do{
//                $index = array_pop($adding);
//                if(in_array($index, $originSearch, true)){
//                    $parameters[$index] = $originSearch[$index];
//
//                    $newSearch = $this->search($parameters, 0, $rows);
//                    if($rows <= $newSearch['totalSum'] && $newSearch['totalSum'] < 100){
//                        $search['type'] = 'second - enhanced';
//                        return $search;
//                    }
//                }
//            }while(count($adding) > 0);

//            $search['type'] = 'second';
//            return $search;

//        }elseif($search['totalSum'] <= $rows){
        }else{
            do{
                $parameters = array_slice($parameters, 0 , count($parameters) - 1);
                $search = $this->search($parameters, 0, $rows, null, true);
                if($search['totalSum'] >= $rows){
                    $search['type'] = 'second';
                    return $search;
                }

            }while(count($parameters) > 4);
        }

        return array('rows' => $this->getRandomForHomepage($companyId, $rows), 'type' => 'random');
    }

    private function getOrderByClasule($order)
    {
        switch ($order)
        {
            case CustomerHelper::SORT_BY_ADDED:
                return ' ORDER BY created DESC ';
            case CustomerHelper::SORT_BY_ADDED_MAX:
                return ' ORDER BY created ASC ';
            case CustomerHelper::SORT_BY_PRICE:
                return ' ORDER BY advert_price ASC ';
            case CustomerHelper::SORT_BY_PRICE_MAX:
                return ' ORDER BY advert_price DESC ';
            case false:
                return "";
            default:
                return ' ORDER BY sort_date DESC ';
        }
    }
}
