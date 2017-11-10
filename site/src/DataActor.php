<?php

class DataActor {

    private $pdo;

    public function __construct() {
        $this->pdo = new PDO();
    }

}