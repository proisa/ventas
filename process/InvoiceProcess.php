<?php 
require('../inc/conexion.php');
require('../inc/funciones.php');
require('../clases/Invoice.php');
require('../vendor/autoload.php');

$invoice = new Invoice($pdo);

?>