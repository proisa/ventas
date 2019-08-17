<?php

class Config {
    private $pdo;
    public $id;    
    
    public function __construct($pdo){
        $this->pdo = $pdo;
        $this->numero = '809-575-0011';
    }

}