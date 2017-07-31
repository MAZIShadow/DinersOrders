<?php
require_once("../../resources/php/classes/ClientRepository.class.php");
$clientRepo = new ClientRepository();
$result['success'] = false;
$result['data'] = null;
$queryResult = $clientRepo->getAllClients();

if ($queryResult != null) {
	$result['success'] = true;
	$result['data'] = $queryResult;
}

echo json_encode($result);
?>