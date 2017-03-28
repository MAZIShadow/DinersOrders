<?php
require_once("../../resources/php/classes/MySqlDBConnection.class.php");
$db_handle = new MySqlDBConnection();
$result = false;
$meal_id = intval($_REQUEST['meal_id']);
$meal_name = filter_var($_REQUEST['meal_name'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
$meal_date = filter_var($_REQUEST['meal_date'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
$action = $meal_id === -1 ? 'new' : 'update';
$error_msg = 'Nastąpił nieoczekiwany błąd podczas zapisu!';

if ($action === 'new') {
    $queryStr = sprintf('INSERT INTO %1$s.DINNER (name, date) VALUES (?,?)', $db_handle->dbName);
	$stmt = $db_handle->prepareQuery($queryStr);
	$stmt->bind_param("ss", $meal_name, $meal_date);
    $success_msg = 'Posiłek [' . $meal_name . '] został dodany.';
} else {
    $queryStr = sprintf('UPDATE %1$s.DINNER set name = ? WHERE id = ?', $db_handle->dbName);
	$stmt = $db_handle->prepareQuery($queryStr);
	$stmt->bind_param("si", $meal_name, $meal_id);
	$success_msg = 'Posiłek [' . $meal_name . '] został zaktualizowany.';
}

$result = $stmt->execute();
$stmt->close();
$jsonResult = null;

if ($result){
    $jsonResult = array('action'=>$action,'success'=>true,'msg'=>$success_msg);
	$db_handle->commit();
} else {
    $jsonResult = array('action'=>$action,'success'=>false,'msg'=>$error_msg);
	$db_handle->rollback();
}

echo json_encode($jsonResult); 
?>