<?php

require_once("MySQLDBConnection.class.php");

class ClientRepository {

    public function getAllClients() {
        $db_handle = new MySQLDBConnection();
        $query = sprintf('SELECT * FROM %1$s.client', MySQLDBConnection::DB_NAME);

        return $db_handle->runQuery($query);
    }

    public function getClientById($clientId) {
        $db_handle = new MySQLDBConnection();
        $query = sprintf('SELECT * FROM %1$s.client C where C.ID = ?', MySQLDBConnection::DB_NAME);
        $params = array("i", $clientId);

        return $db_handle->runPreparedQuery($query, $params);
    }

    public function getClientByName($clientName) {
        $db_handle = new MySQLDBConnection();
        $query = sprintf('SELECT * FROM %1$s.client C where C.NAME = ?', MySQLDBConnection::DB_NAME);
        $params = array("s", $clientName);

        return $db_handle->runPreparedQuery($query, $params);
    }

    public function getOrderTimeLimitByClientName($clientName) {
        $db_handle = new MySQLDBConnection();
        $query = sprintf('SELECT ID, ORDER_TIME_LIMIT FROM %1$s.client WHERE NAME = ?', MySQLDBConnection::DB_NAME);
        $params = array("s", $clientName);
        $resultQuery = $db_handle->runPreparedQuery($query, $params);
        
        if ($resultQuery != null) {
            $result['orderTimeLimit'] = new DateTime($resultQuery[0]['ORDER_TIME_LIMIT']);
            $result['clientId'] = intval($resultQuery[0]['ID']);
            
            return $result;
        }
        
        return null;
    }

}

?>
