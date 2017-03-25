<?php

class MySQLDBConnection {	

	private $dbHost = "localhost";
	private $dbLogin = "root";
	private $dbPassword = "";
	public $dbName = "homemeals";
	private $mysqli = NULL;
	
	public function __construct()
    {
		$mysqli = $this->connectDB();
    }
	
	public function connectDB() {
		$this->mysqli = new mysqli($this->dbHost, $this->dbLogin, $this->dbPassword, $this->dbName);
		
		if ($this->mysqli->connect_errno > 0) {
			die('Could not connect: ' . $this->mysqli->connect_error);
		}
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
	
	function runQuery($query) {
		$result = $this->mysqli->query($query);
		
		while($row = $result->fetch_assoc()) {
			$resultset[] = $row;
		}	
		
		if(!empty($resultset))
		{
			return $resultset;
		}
	}
	
	function runPreparedQuery($query, $bindParams) {
		$stmt = $this->prepareQuery($query);
		
		if ($stmt) {
			$params = array();
			
			foreach($bindParams as $key => $value) {
				$params[$key] = &$bindParams[$key];
			}
			
			call_user_func_array(array($stmt, 'bind_param'), $params);
			$stmt->execute();
			$result = $stmt->get_result();
			
			while($row = $result->fetch_assoc()) {
				$resultset[] = $row;
			}
			
			$stmt->close();
			
			if(!empty($resultset))
			{
				return $resultset;
			}
		}
	}
}
?>