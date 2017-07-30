<?php

require_once("../../resources/php/classes/ClientRepository.class.php");
$clientRepo = new ClientRepository();
$result["total"] = 0;
$result["success"] = false;
$rs = $clientRepo->getAllClients();

if ($rs != null) {
    $result['total'] = sizeof($rs);
    $result["success"] = true;
    $result["rows"] = $rs;
    $result["success"] = true;
    $result["msg"] = 'Klienci pobrani';
} else {
    $result["msg"] = 'Sorry, unable to retrieve the rows from the database!';
}

echo json_encode($result);
?>