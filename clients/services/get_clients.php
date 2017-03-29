<?php
require_once("../../resources/php/classes/MySqlDBConnection.class.php");
$db_handle = new MySqlDBConnection();
$result['success'] = false;
$result['data'] = null;

$queryResult = $db_handle->runQuery(sprintf('SELECT * FROM %s.CLIENT', $db_handle->dbName));

if ($queryResult != null) {
	$result['success'] = true;
	$result['data'] = $queryResult;
}

echo json_encode($result);
?>