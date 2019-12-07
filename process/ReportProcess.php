<?php 
require('../inc/conexion.php');
require('../inc/funciones.php');
require('../clases/Comando.php');

$fecha = isset($_GET['fecha']) ? clearDate($_GET['fecha']) : date('Ymd');
$caja = isset($_GET['caja']) ? $_GET['caja'] :'01';
$turno = isset($_GET['turno']) ? $_GET['turno'] :'1';

if(isset($_GET['egresos'])){
    // 02/15/19
    $query = Comando::recordSet($pdo, "SELECT fecha,rebaja,nombre,concep,valor FROM pvbdreba WHERE fecha='{$fecha}' and caja='{$caja}' and turno='{$turno}'
    ");
    print_pre($query);
   // echo json_encode($query);
    exit();
}


if(isset($_GET['entrada_caja'])){
    $query = Comando::recordSet($pdo, "SELECT fecha,entrada,nombre,concep,valor FROM pvbdenta WHERE fecha='{$fecha}' and caja='{$caja}' and turno='{$turno}'");
    print_pre($query);
   // echo json_encode($query);
    exit();
}

if(isset($_GET['ingresos_cxc'])){
    //03/01/2019
    $query = Comando::recordSet($pdo, "SELECT a.he_fecha,a.he_docum,a.cl_codigo,isnull(b.cl_nombre,'') AS cl_nombre,a.he_monto FROM ccbdhere a
    LEFT JOIN ccbdclie b ON a.cl_codigo=b.cl_codigo
     WHERE a.he_fecha='{$fecha}' AND a.he_caja='{$caja}' AND a.he_turno='{$turno}'");
    print_pre($query);
   // echo json_encode($query);
    exit();
}
