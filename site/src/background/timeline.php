<?php

require("../class/Autoloader.php");
require("__common.php");

$user = User::load_or_die();

handle([
    'GET' => function() use ($user) {
        echo json_encode(DataActor::get_days($user));
        return 200;
    }
]);