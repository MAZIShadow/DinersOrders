<?php

class MySQLDBConnection {	

	private $dbHost = "localhost";
	private $dbLogin = "root";
	private $dbPassword = "";
	private $dbName = "rcmeal_cba_pl";
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
		
		$this->mysqli->query("SET character_set_results=utf8");
		mb_language('uni'); 
		mb_internal_encoding('UTF-8');
		$this->mysqli->query("set names 'utf8'");
	}
	
	private function isConnected() {
		return !$this->mysqli;
	}
	
	public function executeQuery($query) {
		return $this->mysqli->query($query);
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
}
?>