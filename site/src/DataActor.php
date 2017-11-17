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
		
		$stmt = self::get()->prepare("SELECT name, icon FROM moods");
		
		if(!$stmt->execute()) {
			return [];
		}
		
		return $stmt->fetchAll();
		
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
			
// 			echo "LastDate: ".$lastDate->format("Y-m-d")." Expected:".$expected->format("Y-m-d")."<br>";
// 			echo var_dump($lastDate == $expected);
			
			if($lastDate === null
			|| $lastDate->format("Y-m-d") != $expected->format("Y-m-d")) {
				$days[$i] = ["day" => $expected->format("Y-m-d")];
			}else{
				$days[$i] = $row;
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				$lastDate = $row !== false ? new DateTime($row["day"]) : null;
			}
			
		}
		
// 		echo '<pre>';
// 		echo var_dump($days);
// 		echo '</pre>';
// 		exit;
		
		return $days;
		
	}
	
}
