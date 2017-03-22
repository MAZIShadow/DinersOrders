<?php
require_once("../resources/php/classes/MySqlDBConnection.class.php");
$db_handle = new MySqlDBConnection();
echo json_encode($db_handle->runQuery("SELECT * FROM CLIENTS"));
?>