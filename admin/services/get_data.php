<?php
$date = isset($_GET['date']) ? $_GET['date'] : ''; 
require_once("../../resources/php/classes/MySqlDBConnection.class.php");
$db_handle = new MySqlDBConnection();
$result["total"] = 0;
$result["success"] = false;
$queryStr = sprintf('SELECT name FROM %1$s.DINNER WHERE date = ?', $db_handle->dbName);
$params = array("s", $date);
$rs = $db_handle->runPreparedQuery($queryStr, $params);

if ($rs != null) {	
	$result['total'] = sizeof($rs);	
	$result["success"] = true;
	$result["rows"] = $rs;
	$result["success"] = true;
	$result["msg"] = 'Products successfuly requested.';
} else {
	$result["msg"] = 'Sorry, unable to retrieve the rows from the database!';
}

echo json_encode($result);
?>