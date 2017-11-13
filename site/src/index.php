<?php

require("Autoloader.php");

$user = User::load();

if($user === null) {
    (new View)->render("login.html");
} else {
    (new View)->render("sheet.html");
}