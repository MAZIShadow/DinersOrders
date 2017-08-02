<?php

require_once("MySQLDBConnection.class.php");
require_once("Consts.php");

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

    public function removeClientById($clientId) {
        $db_handle = new MySQLDBConnection();
        $query = sprintf('DELETE FROM %1$s.client WHERE ID = ?', MySQLDBConnection::DB_NAME);
        $stmt = $db_handle->prepareQuery($query);
        $stmt->bind_param("i", $clientId);
        $result = $stmt->execute();
        $stmt->close();

        if ($result) {
            $db_handle->commit();
        } else {
            $db_handle->rollback();
        }

        return $result;
    }

    public function saveClient($clientName, $clientDesc) {
        $db_handle = new MySQLDBConnection();
        $query = sprintf('INSERT INTO %1$s.client (NAME, DESCRIPTION, ORDER_TIME_LIMIT) VALUES (?,?,?)', MySQLDBConnection::DB_NAME);
        $stmt = $db_handle->prepareQuery($query);
        $dateTime = $this->generateOrderTimeLimit();
        $stmt->bind_param("sss", $clientName, $clientDesc, $dateTime);
        $result = $stmt->execute();
        $stmt->close();

        if ($result) {
            $db_handle->commit();
        } else {
            $db_handle->rollback();
        }

        return $result;
    }
    
    public function updateClientById($clientName, $clientDesc, $clientId) {
        $db_handle = new MySQLDBConnection();
        $query = sprintf('UPDATE %1$s.client SET NAME = ?, DESCRIPTION = ? WHERE ID = ?', MySQLDBConnection::DB_NAME);
        $stmt = $db_handle->prepareQuery($query);
        $stmt->bind_param("ssi", $clientName, $clientDesc, $clientId);
        $result = $stmt->execute();
        $stmt->close();

        if ($result) {
            $db_handle->commit();
        } else {
            $db_handle->rollback();
        }

        return $result;
    }

    private function generateOrderTimeLimit() {
        $todaysEndDate = new DateTime('today');
        $todaysEndDate->setTime(21, 50, 00);
        
        return $todaysEndDate->format(Consts::FULL_DATE_FORMAT);
    }

}

?>
