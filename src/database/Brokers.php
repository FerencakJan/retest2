<?php

require_once('Db.php');

class Brokers
{
    const COUNT_ROWS = 'select FOUND_ROWS() as total;';

    const BY_COMPANY_ID = 'select * from fastest__brokers where company_id = :companyId and nezobrazovat = 0 order by _order asc';
    const BY_COMPANY_ID_WEB = 'select * from fastest__brokers where company_id = :companyId and nezobrazovat_web = 0 order by _order asc';

    const BY_IN_COMPANY_ID_WEB = 'select * from fastest__brokers where company_id IN (:companyId) and nezobrazovat_web = 0 order by _order asc';

    const BY_BROKER_ID = 'select * from fastest__brokers where broker_id = :brokerId';

    const LIMIT = ' LIMIT :offset, :max;';

    protected $database;

    /**
     * Property constructor.
     */
    public function __construct(Portal $portal)
    {
        $this->database = new DB($portal);
    }

    public function findByCompanyId($companyId)
    {
        return $this->database->query(self::BY_COMPANY_ID, array('companyId' => $companyId));
    }

    public function findByCompanyIdDoNotDisplayWeb($companyId)
    {
        return $this->database->query(self::BY_COMPANY_ID_WEB, array('companyId' => $companyId));
    }

    public function findInCompanyIdDoNotDisplayWeb(array $companyIds)
    {
        return $this->database->query(self::BY_IN_COMPANY_ID_WEB, array('companyIds' => implode(',', $companyIds)));
    }

    public function findByBrokerId($brokerId)
    {
        return $this->database->row(self::BY_BROKER_ID, array('brokerId' => $brokerId));
    }

    private function getTotalRowsCount()
    {
        return $this->database->single(self::COUNT_ROWS);
    }

    public function findByCompanyIdPaginated($companyId, $offset, $max)
    {
        return $this->database->query(self::BY_COMPANY_ID . self::LIMIT, array(
            'companyId' => $companyId,
            'offset' => $offset,
            'max' => $max)
        );
    }

    public function countBrokersCompanyId($companyId)
    {
        $res = $this->database->row("SELECT COUNT(*) as cnt FROM fastest__brokers where company_id = :companyId ;",array(
                'companyId' => $companyId,
        ));

        return $res['cnt'];
    }
}
