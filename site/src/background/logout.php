<?php

require("../class/Autoloader.php");

$user = User::load();

if($user !== null) {
	$user->destroy();
}

header("Location: /");
