<?php

class Config {
    
	private static const PATH_CONFIG_SERVICE = "";
	
	private static $initialized = false;
	private static $obj = null;
	
	public static function init() {
		if(!self::$initialized) {
			$obj = yaml_parse_file(PATH_CONFIG_SERVICE);
			self::$initialized = true;
		}
	}
	
	public static function get($attr) {
		return self::$obj[$attr];
	}
	
}