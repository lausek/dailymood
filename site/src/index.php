<?php

require("class/Autoloader.php");

$user = User::load();

if($user === null) {
    (new View)->render("login.html");
} else {
    (new View)->render("sheet.html", [
    		"moods" => DataActor::get_moods(),
    		"days" => DataActor::get_days($user),
    ]);
}
