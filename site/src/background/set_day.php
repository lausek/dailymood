<?php

require("../Autoloader.php");

$user = User::load();

// not logged in
if($user === null) {
	
	http_response_code(401);

// missing arguments
} elseif(!isset($_POST["ondate"]) || !isset($_POST["mood"])) {
	
	http_response_code(400);
	
} else {
	
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
			http_response_code(200);
		} else {
			foreach($changeQuery->errorInfo() as $msg) {
				echo $msg."\n";
			}	
			http_response_code(400);
		}

		//TODO: test affected rows


	} catch(Exception $e) {
		
		http_response_code(400);

	}
	
}
