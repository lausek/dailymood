<?php

require("../class/Autoloader.php");

$user = User::load();

if($user !== null) {
	echo json_encode(DataActor::get_moods());
	http_response_code(200);
} else {
	http_response_code(401);
}