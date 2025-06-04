<?php
/**
 * Base MySQL connection class
 */
class Mysql {
	protected $pdo;
	
	public function connect() {
		try {
			$dsn = "mysql:host=" . DBHOST . ";dbname=" . DBNAME . ";charset=utf8mb4";
			$options = [
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
				PDO::ATTR_EMULATE_PREPARES => false,
				PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
			];
			
			$this->pdo = new PDO($dsn, DBUSERNAME, DBPASSWORD, $options);
			return $this->pdo;
		} catch(PDOException $e) {
			throw new Exception("Database connection error: " . $e->getMessage());
		}
	}
	
	public function return($code, $message) {
		return json_encode(['code' => $code, 'message'=> $message]);
	}

	public function seflink($text){
		$find = array("/Ğ/","/Ü/","/Ş/","/İ/","/Ö/","/Ç/","/ğ/","/ü/","/ş/","/ı/","/ö/","/ç/");
		$degis = array("G","U","S","I","O","C","g","u","s","i","o","c");
		$text = preg_replace("/[^0-9a-zA-ZÄzÜŞİÖÇğüşıöç]/"," ",$text);
		$text = preg_replace($find,$degis,$text);
		$text = preg_replace("/ +/"," ",$text);
		$text = preg_replace("/ /","-",$text);
		$text = preg_replace("/\s/","",$text);
		$text = strtolower($text);
		$text = preg_replace("/^-/","",$text);
		$text = preg_replace("/-$/","",$text);
		return $text;
	}

	protected function beginTransaction() {
		return $this->pdo->beginTransaction();
	}

	protected function commit() {
		return $this->pdo->commit();
	}

	protected function rollback() {
		return $this->pdo->rollBack();
	}
}

?>