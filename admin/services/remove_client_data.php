<?php
require_once("../../resources/php/classes/MySqlDBConnection.class.php");
require_once("../../resources/php/classes/DropClientFolder.class.php");
$db_handle = new MySqlDBConnection();
$result = false;
$client_id = intval($_REQUEST['client_id']);
$client_name = filter_var($_REQUEST['client_name'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
$error_msg = 'Nastąpił nieoczekiwany błąd podczas usuwania!';
$queryStr = sprintf('DELETE FROM %1$s.CLIENT WHERE ID = ?', $db_handle->dbName);
$stmt = $db_handle->prepareQuery($queryStr);
$stmt->bind_param("i", $client_id);
$success_msg = 'Usunięto klienta [' . trim($client_name) . '].';
$result = $stmt->execute();
$stmt->close();
$jsonResult = null;

if ($result){
	$src = sprintf('../../clients/%s',$client_name);
	$dropClient = new DropClientFolder($src, $client_name);
	$dropClient->dropClient();
    $jsonResult = array('action'=>'delete','success'=>true,'msg'=>$success_msg);
	$db_handle->commit();
} else {
    $jsonResult = array('action'=>'delete','success'=>false,'msg'=>$error_msg);
	$db_handle->rollback();
}

echo json_encode($jsonResult); 
?>