<?php 
date_default_timezone_set('America/Santo_Domingo');
ini_set('display_errors',0);
require '../inc/conexion.php';
require '../inc/funciones.php';
require '../clases/Comando.php';

$factura = $_POST['factura'];
$documento = $_POST['documento'];
$mesa = $_POST['mesa'];
$camarero = $_POST['camarero'];

$impresion_query = "INSERT INTO ORDENESIMPRESION (DOCUMENTO,FACTURA,MESA,FECHA,ARTICULO,DESCRIPCION,SECUENCIA,CANTIDAD,TIPO_ORDEN,TIPO_COCINA,TIPO_AREA,CAMARERO) VALUES ('{$documento}','{$factura}','{$mesa}','','','','','0','FACTURA','','','{$camarero}')";

//echo Comando::noRecordSet($pdo,$impresion_query);

if(Comando::noRecordSet($pdo,$impresion_query)){

    echo json_encode(['cod'=>'00','msj'=>'Guardado correctamente']);
    $pdo->commit();
    exit();
}else{
    echo json_encode(['cod'=>'01','msj'=>'Error al guardar']);
    exit();
}


