<?php
require_once("../../resources/php/classes/MySQLDBConnection.class.php");
$dateToday = date('Y-m-d');
$db_handle = new MySQLDBConnection();
$query = sprintf('SELECT * FROM %1$s.dinner WHERE DATE = ?', MySQLDBConnection::DB_NAME);
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
