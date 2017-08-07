<?php

require_once("../../resources/php/classes/MenuRepository.class.php");
require_once("../../resources/php/classes/Consts.php");
$dateToday = date(Consts::DAY_DATE_FORMAT);
$menuRepo = new MenuRepository();
$result = $menuRepo->getAvaiableDinnersForDate($dateToday);
$resultset['success'] = $result != null;
$resultset['data'] = null;

if ($result != null) {
    $resultset['data'] = $result;
}

echo json_encode($resultset);
?>
