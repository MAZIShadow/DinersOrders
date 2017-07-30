<?php

require_once("../../resources/php/classes/MenuRepository.class.php");
$menuRepo = new MenuRepository();
$result = false;
$meal_id = intval($_REQUEST['menu_id']);
$error_msg = 'Nastąpił nieoczekiwany błąd podczas usuwania!';
$queryStr = sprintf('DELETE FROM %1$s.dinner WHERE ID = ?', MySQLDBConnection::DB_NAME);
$success_msg = 'Usunięto posiłek';
$result = $menuRepo->deleteMenu($meal_id);
$jsonResult = array('action' => 'delete', 'success' => $result, 'msg' => $result ? $success_msg : $error_msg);
echo json_encode($jsonResult);
?>