<?php
$date = isset($_GET['date']) ? $_GET['date'] : ''; 
require_once("../../resources/php/classes/MySqlDBConnection.class.php");
$db_handle = new MySqlDBConnection();
$result["total"] = 0;
$result["success"] = false;
$queryStr = sprintf('SELECT d.id id, d.name name, COUNT(o.DINNER_ID) count FROM %1$s.dinner d LEFT JOIN %1$s.order o ON d.ID = o.DINNER_ID WHERE d.DATE = ? GROUP BY d.ID, d.NAME', $db_handle->dbName);
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