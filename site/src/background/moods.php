<?php

require("../class/Autoloader.php");
require("__common.php");

User::load_or_die();

handle([
    'GET' => function() {
        echo json_encode(DataActor::get_moods());
        return 200;
    }
]);