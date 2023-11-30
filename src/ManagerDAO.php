<?php

class ManagerDAO{
    private $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }
}