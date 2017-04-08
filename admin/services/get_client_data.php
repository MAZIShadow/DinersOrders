<?php
require_once("../../resources/php/classes/MySQLDBConnection.class.php");
$db_handle = new MySQLDBConnection();
$result["total"] = 0;
$result["success"] = false;
$queryStr = sprintf('SELECT * FROM %1$s.client', MySQLDBConnection::DB_NAME);
$rs = $db_handle->runQuery($queryStr);

if ($rs != null) {	
	$result['total'] = sizeof($rs);	
	$result["success"] = true;
	$result["rows"] = $rs;
	$result["success"] = true;
	$result["msg"] = 'Klienci pobrani';
} else {
	$result["msg"] = 'Sorry, unable to retrieve the rows from the database!';
}

echo json_encode($result);
?>