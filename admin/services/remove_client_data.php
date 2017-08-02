<?php

require_once("../../resources/php/classes/ClientRepository.class.php");
require_once("../../resources/php/classes/DropClientFolder.class.php");
$clientRepo = new ClientRepository();
$result = false;
$client_id = intval($_REQUEST['client_id']);
$client_name = filter_var($_REQUEST['client_name'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
$error_msg = 'Nastąpił nieoczekiwany błąd podczas usuwania!';
$success_msg = 'Usunięto klienta [' . trim($client_name) . '].';
$result = $clientRepo->removeClientById($client_id);

if ($result) {
    $src = sprintf('../../clients/%s', $client_name);
    $dropClient = new DropClientFolder($src, $client_name);
    $dropClient->dropClient();
}

$jsonResult = array('action' => 'delete', 'success' => $result, 'msg' => $result ? $success_msg : $error_msg);
echo json_encode($jsonResult);
?>