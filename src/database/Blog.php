<?php

require_once('Db.php');

class Blog
{
    private const SELECT_HOMEPAGE =
                "SELECT b.id as id, image_url, perex_title, b.date as `date`, perex, br.name as brokerName
                FROM blog b
                JOIN fastest__brokers br ON b.broker_id = br.broker_id
                ORDER BY b.date DESC LIMIT 4";

    private const SELECT_BLOG =
                "SELECT b.id as id, image_url, perex_title, b.date as `date`, perex, b.broker_id as broker_id, br.name as brokerName, br.description as brokerDescription
                FROM blog b
                JOIN fastest__brokers br ON b.broker_id = br.broker_id";

    private const SELECT_BY_ID =
                "SELECT b.id as id, image_url, perex_title, b.date as `date`, perex, br.name as brokerName, b.broker_id as broker_id,  br.photo as brokerPhoto,text, br.description as brokerDescription,
                br.mobil as brokerMobil,
                br.email as brokerEmail
                FROM blog b
                JOIN fastest__brokers br ON b.broker_id = br.broker_id
                WHERE b.id = :id";

    protected $database;

    /**
     * Blog constructor.
     */
    public function __construct(Portal $portal)
    {
        $this->database = new DB($portal);
    }

    public static function generateSlug($perexTitle)
    {
        $unwanted_array = array(    'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                                    'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                                    'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
                                    'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
                                    'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y',
                                    '&#225;'=>'a', '&#233;'=>'e', '&#237;'=>'i', '&#243;'=>'o', '&#250;'=>'u',
                                    '&#193;'=>'A', '&#201;'=>'E', '&#205;'=>'I', '&#211;'=>'O', '&#218;'=>'U',
                                    '&#209;'=>'N', '&#241;'=>'n' );
        $perexTitle = strtr( $perexTitle, $unwanted_array );

        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $perexTitle)));
    }

    public static function generateUrl($perex_title, $id)
    {
        $slug = self::generateSlug($perex_title);

        return "/blog/{$slug}/{$id}/";
    }

    public function findForHomepage()
    {
        return $this->database->query(self::SELECT_HOMEPAGE);
    }

    public function findForListing($forPage, $searchParam = null, $dateOrder = null)
    {
        $result = [
          'articles' => [],
          'count' => 0
        ];
        $query = self::SELECT_BLOG;
        $order = $dateOrder != null ? sprintf(' order by b.%s ', $dateOrder) : ' order by b.date desc ';

        if($forPage === 1){
              $offset = 0;
              $limit = 16;
          }else{
              $offset = 1 + (($forPage - 1) * 16);
              $limit = 16;
          }

        $params = [
          'offset' => $offset,
          'limit' => $limit,
        ];

        if ($searchParam != null) {
          $query .= ' WHERE MATCH (perex,perex_title,`text`) AGAINST (:fulltext IN NATURAL LANGUAGE MODE)';
          $params['fulltext'] = $searchParam;
        }

        $countParams = $params;
        unset($countParams['offset']);
        unset($countParams['limit']);
        $result['count'] = count($this->database->query($query, $countParams));

        if ($order != null) {
          $query .= $order;
        }

        $query .= ' LIMIT :offset, :limit';
        $result['articles'] =  $this->database->query($query, $params);

        return $result;
    }

    public function findForDetail($id)
    {
        return $this->database->row(self::SELECT_BY_ID, array(
            'id' => $id
        ));
    }

    public function orgByMonths($articles, $order)
    {
        $articleGroups = array();
        foreach ($articles as $article) {
          $date = new DateTime($article["date"]);
          $month = $date->format("m");
          $year = $date->format("Y");
          $id = (int)$year . $month;
          $articleGroups[$id]['articles'][] = $article;
          $articleGroups[$id]['name'] = $this->genMonthName($month) . " " . $year;
        }

        $order == 'date asc' ? ksort($articleGroups) : krsort($articleGroups);

        return $articleGroups;
    }

    public function genMonthName($id){
        $monthNames = array('Leden', 'Únor', 'Březen', 'Duben', 'Květen', 'Červen', 'Červenec', 'Srpen', 'Září', 'Říjen', 'Listopad', 'Prosinec');
        return $monthNames[(int)$id - 1];
    }
}