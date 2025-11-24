<?php

require_once('Db.php');

class Office
{
    const COUNT_ROWS = 'select FOUND_ROWS() as total;';
    const SELECT = 'select SQL_CALC_FOUND_ROWS fc.company_id as company_id,name, address, logo_url, property_cnt, www, phone, mobil, alias_, locality_latitude, locality_longitude from fastest__companies fc';

    const ORDER = ' order by property_cnt desc';
    const LIMIT = ' LIMIT :offset, :max;';

    const FOR_SUMMARY = ' where country = :company_country_id';
    const FOR_SEARCH = ' where country = :company_country_id and (name like :namesearch or email like :emailsearch or www like :wwwsearch)';

    const BY_ID = 'select company_id,name, address, logo_url, property_cnt, www, phone, mobil, alias_, description, email from fastest__companies where company_id = :id;';

    const WITH_COMPANY_FROM = ' join fastest__companies_regions re on fc.company_id = re.company_id and re.okres_id = :region_id';
    const WITH_COMPANY_REGIONS = ' and fc.company_id in (select re.company_id from fastest__companies_regions re where re.okres_id = :region_id)';
    const WITH_COMPANY_KRAJ = ' join fastest__companies_regions fcr on fc.company_id = fcr.company_id ';

    protected $database;

    /**
     * Property constructor.
     */
    public function __construct(Portal $portal)
    {
        $this->database = new DB($portal);
    }

    public function forSummary($companyCountryId, $offset, $max, $searchParam = null, $regionId = null, ?string $nameOrder = null, ?string $krajFilter = null)
    {
        $order = $nameOrder !== null ? sprintf(' order by %s ', $nameOrder) : null;
        $filter = $krajFilter !== null ? sprintf(' and fcr.kraj_id = %s ', $krajFilter) : null;


      if ($searchParam == null)
        {
            $params = array('company_country_id' => $companyCountryId, 'offset' => $offset, 'max' => $max);
            if($regionId){
                $params['region_id'] = $regionId;
            }

            $rows = $this->database->query(
                self::SELECT . ($filter ? self::WITH_COMPANY_KRAJ : '') . ($regionId != null? self::WITH_COMPANY_FROM : '') . self::FOR_SUMMARY . $filter . $order . self::LIMIT,
                $params
            );

        }
        else
        {
            $params = array(
                'company_country_id' => $companyCountryId,
                'namesearch' => "%{$searchParam}%",
                'emailsearch' => "%{$searchParam}%",
                'wwwsearch' => "%{$searchParam}%",
                'offset' => $offset,
                'max' => $max
            );

            if($regionId){
                $params['region_id'] = $regionId;
            }

            $rows = $this->database->query(
              self::SELECT . ($filter ? self::WITH_COMPANY_KRAJ : '') . ($regionId != null? self::WITH_COMPANY_FROM : '') . $filter . self::FOR_SEARCH . $order . self::LIMIT,
              $params
            );
        }

        $count = $this->getTotalRowsCount();

        return array('rows' => $rows, 'totalSum' => $count);
    }

    private function getTotalRowsCount()
    {
        return $this->database->single(self::COUNT_ROWS);
    }

    public function byId($companyId)
    {
        return $this->database->row(self::BY_ID, array('id' => $companyId));
    }
}
