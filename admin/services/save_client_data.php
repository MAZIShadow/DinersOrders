<?php

require_once("../../resources/php/classes/ClientRepository.class.php");
require_once("../../resources/php/classes/CreateClientFolder.class.php");
require_once("../../resources/php/classes/DropClientFolder.class.php");

$clientRepo = new ClientRepository();
$client_id = intval($_REQUEST['client_id']);
$client_name = filter_var($_REQUEST['client_name'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
$client_pass = filter_var($_REQUEST['client_password'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
$client_desc = filter_var($_REQUEST['client_desc'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
$action = $client_id === -1 ? 'new' : 'update';
$result = false;
$error_msg = 'Nastąpił nieoczekiwany błąd podczas zapisu!';

if ($client_name && $client_pass) {
    if (strtolower($client_name) == 'admin') {
        $error_msg = 'Nie można założyć konta o nazwie \'admin\'';
    } else {
        if ($action === 'new') {
            $result = $clientRepo->saveClient($client_name, $client_desc);
            $success_msg = 'Klient [' . $client_name . '] został dodany.';
        } else {
            $result = $clientRepo->updateClient($client_name, $client_desc, $client_id);
            $success_msg = 'Klient [' . $client_name . '] został zaktualizowany.';
        }
    }
}

if ($result) {
    // Copy files from temp
    $src = "../../clients/tempClientData";
    $dest = sprintf('../../clients/%s', $client_name);
    $createClient = new CreateClientFolder($src, $dest, $client_name, $client_pass);
    $copyFiles = $createClient->createClient();

    if ($copyFiles) {
        $jsonResult = array('action' => $action, 'success' => true, 'copyFiles' => $copyFiles, 'msg' => $success_msg);
    } else {
        $dropClient = new DropClientFolder($dest, $client_name);
        $dropClient->dropClient();
        $jsonResult = array('action' => $action, 'success' => false, 'copyFiles' => $copyFiles, 'msg' => 'Nie utworzono klienta');
    }
} else {
    $jsonResult = array('action' => $action, 'success' => false, 'msg' => $error_msg);
}

echo json_encode($jsonResult);
?>
