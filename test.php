<?php
require 'inc/conexion.php';
require 'inc/funciones.php';
require 'clases/Comando.php';

$insert = "INSERT INTO BAKTMP (COD_EMPR) VALUES (5)";
$delete = "DELETE FROM BAKTMP WHERE COD_EMPR = 5";
//$select = "SELECT ID,COD_EMPR,COD_SUCU,USUARIO,CLAVE,NIVEL FROM CONTASEG WHERE USUARIO = 'juan' AND CLAVE = '123' AND COD_EMPR = 1 AND COD_SUCU = 1";
$comando1 = Comando::noRecordSet($pdo,$insert);
$comando2 = Comando::noRecordSet($pdo,$delete);
var_dump($comando1);
//var_dump($comando2);
