<?php

require("Twig/Autoloader.php");
require("Autoloader.php");

Twig_Autoloader::register();

class View {

    private $twig;

    public function __construct() {
        $loader = new Twig_Loader_Filesystem('../template');
        $this->twig = new Twig_Environment($loader, [
            //'cache' => '../cache',
        ]);
        $this->twig->addGlobal('env', [
        		'statuscode' => http_response_code(),
        		'post' => $_POST,
        		'get' => $_GET,
        ]);
    }

    public function render($temp, $vars=[]) {
        echo $this->twig->render($temp, ['vars' => $vars]);
        exit;   
    }

}
