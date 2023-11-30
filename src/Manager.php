<?php

class Manager{
    private $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }
}