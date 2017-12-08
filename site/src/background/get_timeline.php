<?php

require("../class/Autoloader.php");

$user = User::load();

if($user !== null) {
	echo json_encode(DataActor::get_days($user));
	http_response_code(200);
}