<?php
require 'inc/conexion.php';
require 'inc/funciones.php';
require 'clases/Comando.php';
require 'clases/PrintUtil.php';
require 'clases/Config.php';

$print = new PrintUtil(new Config($pdo));

$datos = [
    'titulo1'=>'Titulo',
    'titulo2'=>'Titulo 2',
    'nota1'=>'La nota',
    'detalles'=>[
        'clave'=>'valor',
        'clave 2'=> 'valor 2'
    ]
];

echo '<pre>';
echo $print->FormatTicketPAGO($datos);
echo '</pre>';
//phpinfo();

$insert = "INSERT INTO BAKTMP (COD_EMPR) VALUES (5)";
$delete = "DELETE FROM BAKTMP WHERE COD_EMPR = 5";
$select = "SELECT DE_CODIGO,ar_descri FROM IVBDDEPT WHERE AR_PRESENT=1 ORDER BY ar_ORDEN ASC";
//$select = "SELECT ID,COD_EMPR,COD_SUCU,USUARIO,CLAVE,NIVEL FROM CONTASEG WHERE USUARIO = 'juan' AND CLAVE = '123' AND COD_EMPR = 1 AND COD_SUCU = 1";
//$comando1 = Comando::recordSet($pdo,$select);
//$comando2 = Comando::noRecordSet($pdo,$delete);
//print_pre($comando1);
//var_dump($comando2);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <script>
        //window.print();
    </script>
</body>
</html>