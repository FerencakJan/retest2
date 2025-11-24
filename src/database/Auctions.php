<?php

require_once('Db.php');

class Auctions
{
  const COUNT_ROWS = 'select FOUND_ROWS() as total;';
  const AUCTION_LIST = "SELECT au.*, tz.photo, tz.google_adresa, tz.gps_lat, tz.gps_lng FROM aukce au LEFT JOIN tab_zakazky tz ON au.idz = tz.id WHERE au.aukce_stav=1 and au.aukce_finale_stav<1";
  const AUCTION_LIST_DONE = "SELECT au.*, tz.photo, tz.google_adresa, tz.gps_lat, tz.gps_lng FROM aukce au LEFT JOIN tab_zakazky tz ON au.idz = tz.id WHERE aukce_finale_stav=1";
  const LIMIT = ' LIMIT :offset, :max;';
  //    const AUCTION_DETAIL = "SELECT au.*, tz.photo FROM aukce au LEFT JOIN tab_zakazky tz ON au.idz = tz.id WHERE au.aukce_stav=1 and au.datum_aukce_start <= 'datetimeNOW()' and au.datum_aukce_end >= 'datetimeNOW()'";

    public function __construct(Portal $portal)
    {
        $this->database = new DB($portal, 'urbium');
    }

    public function findAllAuctions(): array
    {
        return $this->database->query(self::AUCTION_LIST);
    }

    public function getAllAuctionsForSummary(): array
    {
      $rows = $this->findAllAuctions();
      $count = count($rows);

      return array('rows' => $rows, 'totalSum' => $count);
    }

    public function getAuctionsForSummary($offset, $max): array
    {
      $params = array(
        'offset' => $offset,
        'max' => $max
      );
      $rows = $this->database->query(self::AUCTION_LIST . self::LIMIT, $params);
      $count = $this->getAllAuctionsForSummary()['totalSum'];
      return array('rows' => $rows, 'totalSum' => $count);
    }

    public function findAllDoneAuctions(): array
    {
        return $this->database->query(self::AUCTION_LIST_DONE);
    }

    public function findAuctionDetail($id): array
    {
        $this->database->query('SET CHARACTER SET utf8;');
        return current($this->database->query('SELECT * FROM aukce au WHERE au.id = :id', array('id' => $id)));
    }

    public function findAuctionBids($id): array
    {
        return $this->database->query('select * from aukce_prihozy where id_aukce = :id order by id desc', array('id' => $id));
    }

    public function findAuctioneerName($id): array
    {
        return current($this->database->query('select jmeno, prijmeni from aukce_drazitele ad join tab_klienti tk on ad.klient_id = tk.id where ad.id = :id limit 1', array('id' => $id)));
    }

    private function getTotalRowsCount()
    {
      return $this->database->single(self::COUNT_ROWS);
    }
}
