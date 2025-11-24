<?php
session_start();

require_once('../../src/Portal.php');
include_once('../../src/helpers/pageHelpers.php');

if (!isset($_GET['id'])) {
    throw new Error('Missing id');
}

if(!isset($_GET['type'])){ 
    throw new Error('Missing type');
}

if (!isset($_GET['page'])) {
    throw new Error('Missing page');
}

$portal = new Portal();
$propertyService = new Property($portal);
$translator = $portal->getGlobalListResource();

$propertiesMaxOnPage = 8;
$id = $_GET['id'];
$type = $_GET['type'];
$page = $_GET['page'];

$propertiesOffset = ($page * $propertiesMaxOnPage - $propertiesMaxOnPage);

$properties = null;

switch($type){
    case 'broker':
        $properties = $propertyService->byBrokerId($id, $propertiesOffset, $propertiesMaxOnPage + 1)['rows'];
        break;
    case 'company':
        $properties = $propertyService->findCompanyNextItemsByCompanyId($id, $propertiesOffset, $propertiesMaxOnPage + 1)['rows'];
        break;
    default:
        throw new Error('Unknown type');
}

$response = [
    'rows' => '',
    'haveNext' => count($properties) > $propertiesMaxOnPage,
];

$properties = array_slice($properties, 0, $propertiesMaxOnPage);

foreach ($properties as $property) {
    $response['rows'] .= $portal->render('/estate-next.php', [
        'similarProperty' => $property,
        'globalListResource' => $translator,
    ]);
}

header('Content-Type: application/json; charset=utf-8"');
echo json_encode($response);
