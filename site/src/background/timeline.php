<?php

require("../class/Autoloader.php");

$user = User::load_or_die();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        echo json_encode(DataActor::get_days($user));
        http_response_code(200);
        break;

    default:
        http_response_code(400);
        break;
}