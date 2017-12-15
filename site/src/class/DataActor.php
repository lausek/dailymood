<?php

require("Autoloader.php");

class DataActor {

	private static $pdo = null;
	
	public static function get() {
		if(self::$pdo === null) {
			$dbconf = Config::getImportant("database");
			if(isset($dbconf["unix_socket"])) {
				self::$pdo = new PDO("mysql:unix_socket=".$dbconf["unix_socket"],$dbconf["user"], $dbconf["password"]);
				self::$pdo->query("use {$dbconf['name']}");
			} else {
				self::$pdo = new PDO("mysql:dbname=".$dbconf["name"].";host=".$dbconf["host"], $dbconf["user"], $dbconf["password"]);
			}
		}
		return self::$pdo;
	}
	
	public static function get_moods() {
		
		$stmt = self::get()->prepare("SELECT * FROM moods");
		
		if(!$stmt->execute()) {
			throw new Exception("error in sql");
		}
		
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
		
	}
	
	public static function get_days($user) {
		
		$stmt = self::get()->prepare("SELECT * FROM days WHERE user = ? AND day BETWEEN ? AND ? ORDER BY day DESC");
		$stmt->bindValue(1, $user->get_id(), PDO::PARAM_INT);
		$stmt->bindValue(2, (new DateTime)->sub(new DateInterval("P30D"))->format("Y-m-d"), PDO::PARAM_STR);
		$stmt->bindValue(3, (new DateTime)->format("Y-m-d"), PDO::PARAM_STR);
		
		if(!$stmt->execute()) {
			foreach($stmt->errorInfo() as $msg) {
				echo $msg."<br>";
			}
			return [];
		}
		
		$days = [];
		
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$lastDate = $row !== false ? new DateTime($row["day"]) : null;		
		
		for($i = 1; $i <= 30; $i++) {
			
			$expected = (new DateTime)->sub(new DateInterval("P".($i-1)."D"));
			
			if($lastDate === null
			|| $lastDate->format("Y-m-d") != $expected->format("Y-m-d")) {
				$days[] = ["day" => $expected->format("Y-m-d")];
			}else{
				$days[] = $row;
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				$lastDate = $row !== false ? new DateTime($row["day"]) : null;
			}
			
		}
		
		return $days;
		
	}
	
}
