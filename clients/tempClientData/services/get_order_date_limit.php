<?php

require_once("../../../resources/php/classes/ClientRepository.class.php");
require_once("../config/clientData.php");

$clientRepo = new ClientRepository();
$resultQuery = $clientRepo->getClientByName($clientName);
$format = "H:i:s";
$serverTime = new DateTime('now');
$serverTimeStr = $serverTime->format($format);

if ($resultQuery) {
    $clientTime = new DateTime($resultQuery[0]['ORDER_TIME_LIMIT']);        
    $clientTimeStr = $clientTime->format($format);
    $jsonResult = array('success' => $clientTimeStr >= $serverTimeStr, 'clientTime' => $clientTimeStr, 'serverTime' => $serverTimeStr);
} else {
    $jsonResult = array('success' => false, 'serverTime' => $serverTimeStr);
}

echo json_encode($jsonResult);
?>
