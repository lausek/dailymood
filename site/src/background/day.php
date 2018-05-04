<?php

require("../class/Autoloader.php");
require("__common.php");

$user = User::load_or_die();

// missing arguments
if(!isset($_POST["ondate"]) || !isset($_POST["mood"])) {
	http_response_code(400);
	exit;	
}

handle([
	'POST' => function() use ($user) {
		return write($user);
	}
]);

function write($user) {

	try {
		
		$writeDate = new DateTime($_POST["ondate"]);
		$now = new DateTime;
		//TODO: test diff check
		if($now < $writeDate || $now->diff($writeDate)->format("%d") > 7) {
			throw new Exception("Date is invalid");
		}
		
		$stmt = DataActor::get()->prepare("SELECT 1 FROM days WHERE user = ? AND day = ?");
		$stmt->bindValue(1, $user->get_id());
		$stmt->bindValue(2, $_POST["ondate"]);

		$stmt->execute();

		$changeQuery = DataActor::get()->prepare(0 === $stmt->rowCount()
							? "INSERT INTO days (day, user, mood, note) VALUES (:d, :u, (SELECT id FROM moods WHERE name = :m), :n)" 
							: "UPDATE days SET mood = (SELECT id FROM moods WHERE name = :m), note = :n WHERE day = :d AND user = :u");

		$changeQuery->bindValue(":d", $_POST["ondate"]);	
		$changeQuery->bindValue(":u", $user->get_id());	
		$changeQuery->bindValue(":m", $_POST["mood"]);	
		$changeQuery->bindValue(":n", isset($_POST["note"]) ? $_POST["note"] : null);	

		if($changeQuery->execute()) {
			return 200;
		} else {
			throw new Exception(implode("\n", $changeQuery->errorInfo()));
		}

		//TODO: test affected rows

	} catch(Exception $e) {
		
		echo $e->getMessage();
		return 400;
	}
}