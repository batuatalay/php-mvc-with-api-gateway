<?php
class ManagerModel extends Mysql
{
	private $id;
	private $name;
	private $phone;
	private $username;
	private $password;
	private $status;
	private $lastLogin;
	private $tableName = "MANAGERS";
	private $process;
	
	public function __construct($arr = [])
	{
		$this->pdo = $this->connect();
		foreach ($arr as $key => $value) {
			$this->$key = $value;
		}
	}
	public function get() {
		
		
	}

	public function save() {
		
		

	}
}