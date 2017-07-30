<?php

require_once("../../resources/php/classes/DinnerRepository.class.php");
$dinnerRepo = new DinnerRepository();
$result = false;
$dinner_id = intval($_REQUEST['dinner_id']);
$dinner_name = filter_var($_REQUEST['dinner_name'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
$action = $dinner_id === -1 ? 'new' : 'update';
$error_msg = 'Nastąpił nieoczekiwany błąd podczas zapisu!';

if ($action === 'new') {
    $result = $dinnerRepo->saveDinner($dinner_name);
    $success_msg = 'Posiłek [' . $dinner_name . '] został dodany.';
} else {
    $result = $dinnerRepo->updateDinner($dinner_name, $dinner_id);
    $success_msg = 'Posiłek [' . $dinner_name . '] został zaktualizowany.';
}

$jsonResult = array('action' => $action, 'success' => $result, 'msg' => $result ? $success_msg : $error_msg);

echo json_encode($jsonResult);
?>