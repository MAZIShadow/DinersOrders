<?php
$date = isset($_GET['date']) ? $_GET['date'] : ''; 
require_once("../../resources/php/classes/DinnerRepository.class.php");
$dinnerRepo = new DinnerRepository();
$result["total"] = 0;
$rs = $dinnerRepo->getAllDinners();
$result["success"] = $rs != null;

if ($rs != null) {	
	$result['total'] = sizeof($rs);	
	$result["rows"] = $rs;
	$result["msg"] = 'Products successfuly requested.';
} else {
	$result["msg"] = 'Sorry, unable to retrieve the rows from the database!';
}

echo json_encode($result);
?>