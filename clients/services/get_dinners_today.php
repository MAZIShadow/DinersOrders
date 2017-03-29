<?php
require_once("../../resources/php/classes/MySqlDBConnection.class.php");
$dateToday = date('Y-m-d');
$db_handle = new MySqlDBConnection();
$query = sprintf('SELECT * FROM %1$s.DINNER WHERE DATE = ?', $db_handle->dbName);
$params = array("s", $dateToday);
$result = $db_handle->runPreparedQuery($query, $params);
$resultset['success'] = false;
$resultset['data'] = null;

if ($result != null) {
	$resultset['success'] = true;
	$resultset['data'] = $result;
}

echo json_encode($resultset);
?>