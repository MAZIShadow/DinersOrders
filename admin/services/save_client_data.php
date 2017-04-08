<?php

require_once("../../resources/php/classes/MySQLDBConnection.class.php");
require_once("../../resources/php/classes/CreateClientFolder.class.php");
require_once("../../resources/php/classes/DropClientFolder.class.php");

$db_handle = new MySQLDBConnection();
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
            $todaysEndDate = new DateTime('today');
            $todaysEndDate->setTime(11, 35, 00);
            $format_date = 'Y-m-d H:i:s';
            $dateToday = $todaysEndDate->format($format_date);
            $queryStr = sprintf('INSERT INTO %1$s.client (NAME, DESCRIPTION, ORDER_TIME_LIMIT) VALUES (?,?,?)', MySQLDBConnection::DB_NAME);
            $stmt = $db_handle->prepareQuery($queryStr);
            $stmt->bind_param("sss", $client_name, $client_desc, $dateToday);
            $success_msg = 'Klient [' . $client_name . '] został dodany.';
        } else {
            $queryStr = sprintf('UPDATE %1$s.client SET NAME = ?, DESCRIPTION = ? WHERE ID = ?', MySQLDBConnection::DB_NAME);
            $stmt = $db_handle->prepareQuery($queryStr);
            $stmt->bind_param("ssi", $client_name, $client_desc, $client_id);
            $success_msg = 'Klient [' . $client_name . '] został zaktualizowany.';
        }

        $result = $stmt->execute();
        $stmt->close();
        $jsonResult = null;
    }
}

if ($result) {
    // Copy files from temp
    $src = "../../clients/tempClientData";
    $dest = sprintf('../../clients/%s', $client_name);
    $createClient = new CreateClientFolder($src, $dest, $client_name, $client_pass);
    $copyFiles = $createClient->createClient();

    if ($copyFiles) {
        $db_handle->commit();
        $jsonResult = array('action' => $action, 'success' => true, 'copyFiles' => $copyFiles, 'msg' => $success_msg);
    } else {
        $dropClient = new DropClientFolder($dest, $client_name);
        $dropClient->dropClient();
        $db_handle->rollback();
        $jsonResult = array('action' => $action, 'success' => false, 'copyFiles' => $copyFiles, 'msg' => 'Nie utworzono klienta');
    }
} else {
    $jsonResult = array('action' => $action, 'success' => false, 'msg' => $error_msg);
    $db_handle->rollback();
}

echo json_encode($jsonResult);
?>
