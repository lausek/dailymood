<?php

require("class/Autoloader.php");

(new View)->render(User::load() === null ? "login.html" : "sheet.html");