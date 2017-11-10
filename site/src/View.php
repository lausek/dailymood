<?php

require("Twig/Autoloader.php");

Twig_Autoloader::register();

class View {

    private $twig;

    public function __construct() {
        $loader = new Twig_Loader_Filesystem('../temp');
        $this->twig = new Twig_Environment($loader, [
            //'cache' => '../cache',
        ]);
    }

    public function render($temp, $vars=[]) {
        echo $this->twig->render($temp, $vars);
        exit;   
    }

}