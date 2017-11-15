<?php

require("Autoloader.php");

class DataActor {

	private static $pdo = null;
	
	public static function get() {
		if(self::$pdo === null) {
			$dbconf = Config::getImportant("database");
			self::$pdo = new PDO("mysql:dbname=".$dbconf["name"].";host=".$dbconf["host"], $dbconf["user"], $dbconf["password"]);
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
		
		$stmt = self::get()->prepare("SELECT * FROM days WHERE ");
		
		$days = $stmt->fetchAll();
		
		return $days;
		
	}
	
}