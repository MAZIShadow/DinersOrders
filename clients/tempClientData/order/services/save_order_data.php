<?php

require_once("../../../../resources/php/classes/MySQLDBConnection.class.php");
require_once("../../config/clientData.php");

$db_handle = new MySQLDBConnection();
$meal_id = intval($_REQUEST['meal_id']);
$meal_name = filter_var($_REQUEST['user_name'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
$meal_amount = intval($_REQUEST['meal_amount']);
$meal_date = date('Y-m-d  H:i:s');
$action = $meal_id === -1 ? 'error' : 'new';
$error_msg = 'Nastąpił nieoczekiwany błąd podczas zapisu!';
$result = false;

if ($action === 'new') {
    $queryClient = sprintf('SELECT ID, ORDER_TIME_LIMIT FROM %1$s.client WHERE NAME = ?', MySQLDBConnection::DB_NAME);
    $params = array("s", $clientName);
    $resultQuery = $db_handle->runPreparedQuery($queryClient, $params);

    if ($resultQuery != null) {
        $format = "H:i:s";
        $serverTime = new DateTime('now');
        $serverTimeStr = $serverTime->format($format);
        $clientTime = new DateTime($resultQuery[0]['ORDER_TIME_LIMIT']);        
        $clientTimeStr = $clientTime->format($format);
        
        if ($clientTimeStr < $serverTimeStr) {
            $error_msg = "Przekroczenie czasu zamówienia!";
        } else {
            $result = true;
            $queryStr = sprintf('INSERT INTO %1$s.order (NAME, DATE, AMOUNT, DINNER_ID, CLIENT_ID) VALUES (?,?,?,?,?)', MySQLDBConnection::DB_NAME);

            foreach ($resultQuery as $row) {
                $clientId = $row['ID'];
                $stmt = $db_handle->prepareQuery($queryStr);
                $stmt->bind_param("ssiii", $meal_name, $meal_date, $meal_amount, $meal_id, $clientId);
                $stmt->execute();
                $stmt->close();
            }

            $success_msg = 'Zamówienie złożone.';
        }
    } else {
        $error_msg = 'Nieznany klient!';
    }
} else {
    $error_msg = "Nieprawłowe żadanie!";
}

if ($result) {
    $jsonResult = array('action' => $action, 'success' => true, 'msg' => $success_msg);
    $db_handle->commit();
} else {
    $jsonResult = array('action' => $action, 'success' => false, 'msg' => $error_msg);
    $db_handle->rollback();
}

echo json_encode($jsonResult);
?>
