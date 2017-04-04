<?php
require_once("../../../resources/php/classes/MySqlDBConnection.class.php");
require_once("../config/clientData.php");
$todaysDate = new DateTime('today');
$todaysEndDate = new DateTime('today');
$todaysEndDate->setTime(23, 59, 59);
$format_date = 'Y-m-d H:i:s';
$dateToday = $todaysDate->format($format_date);
$endDateToday = $todaysEndDate->format($format_date);
$db_handle = new MySqlDBConnection();
$query = sprintf('SELECT * FROM %1$s.DINNER WHERE DATE BETWEEN ? AND ?', $db_handle->dbName);
$params = array("ss", $dateToday, $endDateToday);
$result = $db_handle->runPreparedQuery($query, $params);
$resultset['success'] = false;
$resultset['data'] = [];

if ($result != null) {
	$resultset['success'] = true;
	
	foreach ($result as $row) {
		$query = sprintf('SELECT O.* FROM %1$s.ORDER O LEFT JOIN %1$s.CLIENT C ON O.CLIENT_ID = C.ID WHERE O.DINNER_ID = ? AND C.NAME = ? AND O.DATE BETWEEN ? AND ?', $db_handle->dbName);
		$params = array("isss", $row['ID'], $clientName, $dateToday, $endDateToday);
		$result2 = $db_handle->runPreparedQuery($query, $params);
		$objresult = (object) [
			'dinner' => $row,
			'orders' => $result2
		];
		
		array_push($resultset['data'], $objresult);
	}
}

echo json_encode($resultset);
?>