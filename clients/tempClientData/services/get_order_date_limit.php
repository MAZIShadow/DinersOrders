<?php

require_once("../../../resources/php/classes/MySQLDBConnection.class.php");
require_once("../config/clientData.php");

$db_handle = new MySQLDBConnection();
$queryClient = sprintf('SELECT ID, ORDER_TIME_LIMIT FROM %1$s.client WHERE NAME = ?', MySQLDBConnection::DB_NAME);
$params = array("s", $clientName);
$resultQuery = $db_handle->runPreparedQuery($queryClient, $params);
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
