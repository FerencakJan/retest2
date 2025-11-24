<?php
session_start();

//error_reporting(E_ALL);
//ini_set('display_errors', 1);

require_once('../../src/Portal.php');
include_once('../../src/helpers/pageHelpers.php');
@include '../services/get_data_detail_seo.php';
$response = array();

if (isset($_GET['propertyId']))
{
    $propertyId = $_GET['propertyId'];

    $portal = new Portal();
    $propertyService = new Property($portal);
    $property = $propertyService->findById($propertyId);

    $getData = new GetDataService($portal);

    $alias = LeadingSlash::checkLink($property['alias_']);
    $data = $getData->pageAccessCall('detail', $propertyId, null, null, false);

    $response['setUrl'] = $alias;
    $response['closeUrl'] = isset($data['urlset']['close_url'])? $data['urlset']['close_url'] : '';
    $response['propertyBlock'] = mb_convert_encoding(('<div class="property-modal">' . ($portal->render('../../src/actions/detail.php', array('ajax' =>true,'property' => $property,'propertyService' =>$propertyService, 'exit' => 'closeModal', 'data' =>$data, 'propertyId' => $propertyId,),false)) . '</div>'), 'UTF-8', 'auto');

    if($property['advert_function_eu'] == 2) {
        $response['calc'] = '<script src="https://www.hypotecnikalkulacka.cz/widget-animated?price='. $property['advert_price'] . '&ltv=75&due_date=25&partner_id=50&utm_source=partner_id_50&utm_medium=widget-animated&utm_campaign=widget_source&loan_amount_percentage=75#hypotecni_uver" type="text/javascript"></script>';
    }

    $response['pageTitle'] = isset($data['html']['title'])? $data['html']['title'] : '';

    $response['location'] = array(
        'lat' => $property['locality_latitude'],
        'lng' => $property['locality_longitude'],
    );

    $response['metatags'] = array();
    $response['metatags']['title'] = isset($data['html']['title'])? $data['html']['title'] : '';
    $response['metatags']['description'] = isset($data['html']['description'])? $data['html']['description'] : '';
    $response['metatags']['keywords'] = isset($data['html']['keywords'])? $data['html']['keywords'] : '';
    $response['metatags']['og:title'] = isset($data['html']['og:title'])? $data['html']['og:title'] : '';
    $response['metatags']['og:description'] = isset($data['html']['og:description'])? $data['html']['og:description'] : '';
    $response['metatags']['og:image'] = isset($data['html']['og:image'])? $data['html']['og:image'] : '';

    if($property['advert_function_eu'] == 2 && $property['locality_stat_kod'] == 263 && $property['advert_price'] > 300000 && $property['advert_price'] < 30000000) {
        $response['gpfCalculator'] = array(
            'theme'   => 'gpf_siroka',
            'partner' => 1793,
            'broker'  => 12404,
            'cena_nemovitosti' => $property['advert_price'],
        );
    }
}

//$response['debugLogs'] = $portal->getLogs();

header('Content-Type: application/json; charset=utf-8"');
echo json_encode($response);
