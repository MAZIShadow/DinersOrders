<?php

require_once("../../../resources/php/classes/MySqlDBConnection.class.php");
require_once("../config/clientData.php");

$db_handle = new MySqlDBConnection();
$queryClient = sprintf('SELECT id, order_time_limit FROM %1$s.CLIENT WHERE name = ?', $db_handle->dbName);
$params = array("s", $clientName);
$resultQuery = $db_handle->runPreparedQuery($queryClient, $params);
$format = "H:i:s";
$serverTime = new DateTime('now');
$serverTimeStr = $serverTime->format($format);

if ($resultQuery) {
    $clientTime = new DateTime($resultQuery[0]['order_time_limit']);        
    $clientTimeStr = $clientTime->format($format);
    $jsonResult = array('success' => $clientTimeStr >= $serverTimeStr, 'clientTime' => $clientTimeStr, 'serverTime' => $serverTimeStr);
} else {
    $jsonResult = array('success' => false, 'serverTime' => $serverTimeStr);
}

echo json_encode($jsonResult);
?>
