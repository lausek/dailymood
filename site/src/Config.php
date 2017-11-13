<?php

class Config {
    
	const PATH_CONFIG_SERVICE = "../conf/service.yaml";
	
	private static $initialized = false;
	private static $obj = null;
	
	private static function init() {
		self::$obj = Spyc::YAMLLoad(self::PATH_CONFIG_SERVICE);
		self::$initialized = true;
	}
	
	public static function get($attr) {
		if(!self::$initialized) {
			self::init();
		}
		// TODO: return if nothing found
		return self::$obj[$attr];
	}
	
	public static function getImportant($attr) {
		$val = self::get($attr);
		if($val === null) {
			die("Config is invalid!");
		}
		return $val;
	}
	
}
