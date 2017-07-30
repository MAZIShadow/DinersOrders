<?php

require_once("../../resources/php/classes/DinnerRepository.class.php");
require_once("../../resources/php/classes/DropClientFolder.class.php");
$dinnerRepo = new DinnerRepository();
$dinner_id = intval($_REQUEST['dinner_id']);
$dinner_name = filter_var($_REQUEST['dinner_name'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
$error_msg = 'Nastąpił nieoczekiwany błąd podczas usuwania!';
$success_msg = 'Usunięto klienta [' . trim($dinner_name) . '].';
$result = $dinnerRepo->deleteDinnerById($dinner_id);
$jsonResult = array('action' => 'delete', 'success' => $result, 'msg' => $result ? $success_msg : $error_msg);
echo json_encode($jsonResult);
?>