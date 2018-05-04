<?php

function handle($map) {
    $m = $_SERVER['REQUEST_METHOD'];
    if (isset($map[$m]) 
    && is_callable($handler = $map[$m])) {
        http_response_code(call_user_func($handler));
    } else {
        http_response_code(400);
    }
    exit;
}