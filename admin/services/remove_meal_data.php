<?php
require_once("../../resources/php/classes/MySqlDBConnection.class.php");
$db_handle = new MySqlDBConnection();
$result = false;
$meal_id = intval($_REQUEST['meal_id']);
$meal_name = filter_var($_REQUEST['meal_name'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
$error_msg = 'Nastąpił nieoczekiwany błąd podczas usuwania!';
$queryStr = sprintf('DELETE FROM %1$s.DINNER WHERE ID = ?', $db_handle->dbName);
$stmt = $db_handle->prepareQuery($queryStr);
$stmt->bind_param("i", $meal_id);
$success_msg = 'Usunięto posiłek [' . trim($meal_name) . '].';
$result = $stmt->execute();
$stmt->close();
$jsonResult = null;

if ($result){
    $jsonResult = array('action'=>'delete','success'=>true,'msg'=>$success_msg);
	$db_handle->commit();
} else {
    $jsonResult = array('action'=>'delete','success'=>false,'msg'=>$error_msg);
	$db_handle->rollback();
}

echo json_encode($jsonResult); 
?>