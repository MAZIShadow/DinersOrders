<?php

require_once("../../../../resources/php/classes/ClientRepository.class.php");
require_once("../../../../resources/php/classes/OrderRepository.class.php");
require_once("../../../../resources/php/classes/MenuRepository.class.php");
require_once("../../../../resources/php/classes/Consts.php");
require_once("../../config/clientData.php");

$clientRepo = new ClientRepository();
$orderRepo = new OrderRepository();
$menu_id = intval($_REQUEST['menu_id']);
$user_name = filter_var($_REQUEST['user_name'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
$meal_amount = intval($_REQUEST['meal_amount']);
$meal_date = date(Consts::FULL_DATE_FORMAT);
$action = $menu_id === -1 ? 'error' : 'new';
$error_msg = 'Nastąpił nieoczekiwany błąd podczas zapisu!';
$result = false;

if ($action === 'new') {
    $resultRow = $clientRepo->getOrderTimeLimitByClientName($clientName);

    if ($resultRow != null) {
        $serverTime = new DateTime('now');
        $serverTimeStr = $serverTime->format(Consts::HOUR_DATE_FORMAT);
        $clientTime = $resultRow['orderTimeLimit'];
        $clientTimeStr = $clientTime->format(Consts::HOUR_DATE_FORMAT);

        if ($clientTimeStr < $serverTimeStr) {
            $error_msg = "Przekroczenie czasu zamówienia!";
        } else {
            $result = $orderRepo->saveOrder($user_name, $menu_id, $meal_amount, $resultRow['clientId']);

            if ($result === true) {
                $success_msg = 'Zamówienie złożone.';
            } else {
                $error_msg = "B��d zapisu";
            }
        }
    } else {
        $error_msg = 'Nieznany klient!';
    }
} else {
    $error_msg = "Nieprawłowe żadanie!";
}

$jsonResult = array('action' => $action, 'success' => $result, 'msg' => $result ? $success_msg : $error_msg);
echo json_encode($jsonResult);
?>
