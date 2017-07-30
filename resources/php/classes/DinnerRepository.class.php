<?php

require_once("MySQLDBConnection.class.php");

class DinnerRepository {

    public function getAllDinners() {
        $db_handle = new MySQLDBConnection();
        $query = sprintf('SELECT * FROM %1$s.dinner', MySQLDBConnection::DB_NAME);

        return $db_handle->runQuery($query);
    }

    public function getDinnerById($dinnerId) {
        $db_handle = new MySQLDBConnection();
        $query = sprintf('SELECT * FROM %1$s.dinner D where D.ID = ?', MySQLDBConnection::DB_NAME);
        $params = array("i", $dinnerId);

        return $db_handle->runPreparedQuery($query, $params);
    }

    public function getActiveDinners() {

        return $this->getDinnersByStatus(1);
    }

    private function getDinnersByStatus($statusValue) {
        $db_handle = new MySQLDBConnection();
        $query = sprintf('SELECT * FROM %1$s.dinner D where D.STATUS = ?', MySQLDBConnection::DB_NAME);
        $params = array("i", $statusValue);

        return $db_handle->runPreparedQuery($query, $params);
    }

    public function saveDinner($dinnerName) {
        $db_handle = new MySQLDBConnection();
        $query = sprintf('INSERT INTO %1$s.dinner (NAME) VALUES (?)', MySQLDBConnection::DB_NAME);
        $stmt = $db_handle->prepareQuery($query);
        $stmt->bind_param("s", $dinnerName);
        $result = $stmt->execute();
        $stmt->close();

        if ($result) {
            $db_handle->commit();
        } else {
            $db_handle->rollback();
        }

        return $result;
    }

    public function updateDinner($dinnerName, $dinnerId) {
        $db_handle = new MySQLDBConnection();
        $query = sprintf('UPDATE %1$s.dinner SET NAME = ? WHERE ID = ?', MySQLDBConnection::DB_NAME);
        $stmt = $db_handle->prepareQuery($query);
        $stmt->bind_param("si", $meal_name, $meal_id);
        $result = $stmt->execute();
        $stmt->close();

        if ($result) {
            $db_handle->commit();
        } else {
            $db_handle->rollback();
        }

        return $result;
    }

    public function deleteDinnerById($dinnerId) {
        $db_handle = new MySQLDBConnection();
        $query = sprintf('DELETE FROM %1$s.dinner WHERE ID = ?', MySQLDBConnection::DB_NAME);
        $stmt = $db_handle->prepareQuery($query);
        $stmt->bind_param("i", $dinnerId);
        $result = $stmt->execute();
        $stmt->close();

        if ($result) {
            $db_handle->commit();
        } else {
            $db_handle->rollback();
        }

        return $result;
    }

}

?>
