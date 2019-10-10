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

$res1[] = [
    '2018-01-01'=>'1,000',
    '2018-01-02'=>'2,000',
    '2018-01-03'=>'3,000'
];

$res2[] = [
    '2019-01-01'=>'4,000',
    '2019-01-02'=>'6,000',
    '2019-01-03'=>'2,000'
];

$resp3 = array_merge($res1,[$res2]);

print_pre($resp3);


//var_dump($comando2);
?>
