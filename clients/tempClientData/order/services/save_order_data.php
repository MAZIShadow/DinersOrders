<?php
require_once("../../../../resources/php/classes/MySqlDBConnection.class.php");
require_once("../../config/clientData.php");
$db_handle = new MySqlDBConnection();
$result = false;
$meal_id = intval($_REQUEST['meal_id']);
$meal_name = filter_var($_REQUEST['user_name'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
$meal_amount = intval($_REQUEST['meal_amount']);
$meal_date = date('Y-m-d  H:i:s');
$action = $meal_id === -1 ? 'error' : 'new';
$error_msg = 'Nastąpił nieoczekiwany błąd podczas zapisu!';
$result = false;

if ($action === 'new') {
	$queryClient = sprintf('SELECT id FROM %1$s.CLIENT WHERE name = ?', $db_handle->dbName);
	$params = array("s", $clientName);
	$resultQuery = $db_handle->runPreparedQuery($queryClient, $params);
	
	if ($resultQuery != null) {
		$result = true;
		$queryStr = sprintf('INSERT INTO %1$s.ORDER (name, date, amount, dinner_id, client_id) VALUES (?,?,?,?,?)', $db_handle->dbName);
		
		foreach ($resultQuery as $row) {
			$clientId = $row['id'];
			$stmt = $db_handle->prepareQuery($queryStr);
			$stmt->bind_param("ssiii", $meal_name, $meal_date, $meal_amount, $meal_id, $clientId);
			$stmt->execute();
			$stmt->close();
		}
		
		$success_msg = 'Zamówienie złożone.';
	} else {
		$error_msg = 'Nieznany klient!';
	}
} else {
    $error_msg = "Nieprawłowe żadanie!";
	$result = false;
}

if ($result){
    $jsonResult = array('action'=>$action,'success'=>true,'msg'=>$success_msg);
	$db_handle->commit();
} else {
    $jsonResult = array('action'=>$action,'success'=>false,'msg'=>$error_msg);
	$db_handle->rollback();
}

echo json_encode($jsonResult); 
?>