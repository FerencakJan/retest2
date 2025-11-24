<?php
session_start();

require_once('../../src/Portal.php');
include_once('../../src/helpers/pageHelpers.php');

if (!isset($_GET['id'])) {
    throw new Error('Missing id');
}

if (!isset($_GET['page'])) {
    throw new Error('Missing page');
}

$portal = new Portal();
$brokersService = new Brokers($portal);

$translator = $portal->getGlobalListResource();
$propertiesMaxOnPage = 6;
$id = $_GET['id'];
$page = $_GET['page'];

$propertiesOffset = ($page * $propertiesMaxOnPage - $propertiesMaxOnPage);

$brokers = $brokersService->findByCompanyIdPaginated($id, $propertiesOffset, $propertiesMaxOnPage + 1);

$response = [
    'rows' => '',
    'haveNext' => count($brokers) > $propertiesMaxOnPage
];

foreach (array_slice($brokers, 0, $propertiesMaxOnPage) as $broker) {
    $response['rows'] .= $portal->render('team-member.php', [
        'member' => $broker,
    ]);
}

header('Content-Type: application/json; charset=utf-8"');
echo json_encode($response);
