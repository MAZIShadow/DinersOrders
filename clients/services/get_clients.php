<?php
require_once("../../resources/php/classes/MySQLDBConnection.class.php");
$db_handle = new MySQLDBConnection();
$result['success'] = false;
$result['data'] = null;

$queryResult = $db_handle->runQuery(sprintf('SELECT * FROM %s.client', MySQLDBConnection::DB_NAME));

if ($queryResult != null) {
	$result['success'] = true;
	$result['data'] = $queryResult;
}

echo json_encode($result);
?>