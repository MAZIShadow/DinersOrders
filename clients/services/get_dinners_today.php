<?php

require_once("../../resources/php/classes/MenuRepository.class.php");
$dateToday = date('Y-m-d');
$menuRepo = new MenuRepository();
$result = $menuRepo->getDinnersForDate($dateToday);
$resultset['success'] = $result != null;
$resultset['data'] = null;

if ($result != null) {
    $resultset['data'] = $result;
}

echo json_encode($resultset);
?>
