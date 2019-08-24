<?php

class Config {
    private $pdo;
    public $id;
    public $nombre;    
    public $numero;    
    public $direccion;    
    public $direccion2;   
    public $itbis; 
    public $ley; 
    
    public function __construct($pdo){
        $this->pdo = $pdo;
        $this->setData();
    }

    function setData(){

        $query = $this->pdo->query("SELECT LETRERO1,LETRERO2,LETRERO3,LETRERO4,LETRERO5 FROM FABDPROC");
        $response = $query->fetchObject();
        $this->nombre = $response->LETRERO1;
        $this->direccion = $response->LETRERO2;
        $this->direccion2 = $response->LETRERO3;
        $this->numero = $response->LETRERO4;

        // Impuestos 
        $imp = $this->getItbis();
        $this->itbis = $imp->ITBIS;
        $this->ley = $imp->D_LEY;
    }

    function getItbis(){

        $query = $this->pdo->query("SELECT ITBIS,D_LEY FROM FABDPROC WHERE cod_sucu=1 AND cod_empr=1");
        $response = $query->fetchObject();
        
        return $response;

    }

}