<?php

require("View.php");
require("User.php");

$user = User::load();

if($user === null) {
    (new View)->render("login.html");
} else {
    
}