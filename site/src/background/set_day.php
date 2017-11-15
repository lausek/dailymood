<?php

require("../Autoloader.php");

$user = User::load();

// not logged in
if($user === null) {
	
	http_response_code(401);

// missing arguments
} elseif(!isset($_POST["date"]) || !isset($_POST["mood"])) {
	
	http_response_code(400);
	
} else {
	
	try {
		
		$writeDate = new DateTime($_POST["date"]);
		$now = new DateTime;
		//TODO: test diff check
		if($now < $writeDate || $now->diff($writeDate)->format("%d") > 7) {
			throw new Exception("Date is invalid");
		}
		
		DataActor::get()->prepare("SELECT ");
	
	} catch(Exception $e) {
		
	}
	
}