<?php

require_once("../../resources/php/classes/MenuRepository.class.php");
$menuRepo = new MenuRepository();
$menu_id = intval($_REQUEST['menu_id']);
$dinner_id = intval($_REQUEST['dinner_name']);
$numberOfPortions = intval($_REQUEST['number_of_portions']);
$meal_date = filter_var($_REQUEST['menu_date'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
$action = $menu_id === -1 ? 'new' : 'update';
$error_msg = 'Nastąpił nieoczekiwany błąd podczas zapisu!';

if ($action === 'new') {
    $result = $menuRepo->saveMenu($dinner_id, $meal_date, $numberOfPortions);
    $success_msg = 'Posiłek został dodany.';
} else {
    $result = $menuRepo->updateMenu($dinner_id, $meal_date, $numberOfPortions, $menu_id);
    $success_msg = 'Posiłek został zaktualizowany.';
}

$jsonResult = array('action' => $action, 'success' => $result, 'msg' => $result ? $success_msg : $error_msg);
echo json_encode($jsonResult);
?>