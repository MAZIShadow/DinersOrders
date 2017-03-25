<?php
require_once("../../../resources/php/classes/MySqlDBConnection.class.php");
require_once("../config/clientData.php");

$db_handle = new MySqlDBConnection();
$result = $db_handle->executeQuery("SELECT * FROM " . $db_handle->dbName .".DINNER");
$resultset = [];

while($row = $result->fetch_assoc()) {
	$query = sprintf('SELECT O.* FROM %1$s.ORDER O LEFT JOIN %1$s.CLIENT C ON O.CLIENT_ID = C.ID WHERE O.DINNER_ID = ? AND C.NAME = ?', $db_handle->dbName);
	$params = array("is", $row['ID'], $clientName);
	$result2 = $db_handle->runPreparedQuery($query, $params);
	$objresult = (object) [
		'dinner' => $row,
		'orders' => $result2
	];
	
	array_push($resultset, $objresult);
}

echo json_encode($resultset);
?>