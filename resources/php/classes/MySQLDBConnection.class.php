<?php

class MySQLDBConnection {

    const DB_HOST = 'localhost';
    const DB_LOGIN = 'root';
    const DB_PASSWORD = '';
    const DB_NAME = 'homemeals';
    private $mysqli = NULL;

    public function __construct() {
        $mysqli = $this->connectDB();
    }

    public function connectDB() {
        $this->mysqli = new mysqli(MySQLDBConnection::DB_HOST, MySQLDBConnection::DB_LOGIN, MySQLDBConnection::DB_PASSWORD, MySQLDBConnection::DB_NAME);

        if ($this->mysqli->connect_errno > 0) {
            die('Could not connect: ' . $this->mysqli->connect_error);
        }

        $this->mysqli->autocommit(FALSE);
    }

    public function commit() {
        $this->mysqli->commit();
    }

    public function rollback() {
        $this->mysqli->rollback();
    }

    private function isConnected() {
        return !$this->mysqli;
    }

    public function executeQuery($query) {
        return $this->mysqli->query($query);
    }

    public function prepareQuery($query) {
        return $this->mysqli->prepare($query);
    }

    public function runQuery($query) {
        $result = $this->mysqli->query($query);

        while ($row = $result->fetch_assoc()) {
            $resultset[] = $row;
        }

        if (!empty($resultset)) {
            return $resultset;
        }
    }
    
    public function affectedRows() {
        return $this->mysqli->affected_rows;
    }

    public function runPreparedQuery($query, $bindParams) {
        $stmt = $this->prepareQuery($query);

        if ($stmt) {
            $params = array();

            foreach ($bindParams as $key => $value) {
                $params[$key] = &$bindParams[$key];
            }

            call_user_func_array(array($stmt, 'bind_param'), $params);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $resultset[] = $row;
            }

            $stmt->close();

            if (!empty($resultset)) {
                return $resultset;
            }
        }
    }
}
?>
