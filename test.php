<?php

require 'inc/conexion.php';
require 'inc/funciones.php';
require 'clases/Comando.php';
require 'clases/PrintUtil.php';
require 'clases/Config.php';

// $select = "SELECT * FROM ORDENESIMPRESION";
// //$select = "SELECT ID,COD_EMPR,COD_SUCU,USUARIO,CLAVE,NIVEL FROM CONTASEG WHERE USUARIO = 'juan' AND CLAVE = '123' AND COD_EMPR = 1 AND COD_SUCU = 1";
// $comando1 = Comando::recordSet($pdo,$select);
// //$comando2 = Comando::noRecordSet($pdo,$delete);
// print_pre($comando1);

function getLetraNombre($numero){

    $cero = '0';
    if($numero < 10){
        return $numero = $cero.$numero;
    }
    return $numero;
    
}

$num = "00";

$num += 1;

echo getLetraNombre($num);






//var_dump($comando2);
?>
