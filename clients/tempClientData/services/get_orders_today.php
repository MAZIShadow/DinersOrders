<?php

require_once("../../../resources/php/classes/MenuRepository.class.php");
require_once("../../../resources/php/classes/OrderRepository.class.php");
require_once("../config/clientData.php");

$todaysDate = new DateTime('today');
$todaysEndDate = new DateTime('today');
$todaysEndDate->setTime(23, 59, 59);
$format_date = 'Y-m-d H:i:s';
$dateToday = $todaysDate->format($format_date);
$endDateToday = $todaysEndDate->format($format_date);
$menuRepo = new MenuRepository();
$orderRepo = new OrderRepository();
$result = $menuRepo->getDinnersForOrderBetweenDates($dateToday, $endDateToday);
$resultset['success'] = $result != null;
$resultset['data'] = [];

if ($result != null) {

    foreach ($result as $row) {
        $result2 = $orderRepo->getOrderByClientNameAndMenuIdAndBetweenDates($clientName, $row['id'], $dateToday, $endDateToday);
        $objresult = (object) [
                    'dinner' => $row,
                    'orders' => $result2
        ];

        array_push($resultset['data'], $objresult);
    }
}

echo json_encode($resultset);
?>
