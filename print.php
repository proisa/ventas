<?php
require 'inc/conexion.php';
require 'inc/funciones.php';
require 'clases/Comando.php';
require 'clases/PrintUtil.php';
require 'clases/Config.php';


$print = new PrintUtil(new Config($pdo),'movil');
$data_pedidos = Comando::recordSet($pdo,"SELECT a.DE_FACTURA,a.DE_FECHA, a.DE_CAJA, a.DE_TURNO, a.DE_FECENT, SUM(a.DE_CANTID) as cantidad,a.DE_DESCRI as descripcion,a.DE_PRECIO as precio FROM ivbddete a LEFT JOIN ivbdarti b 
ON A.AR_CODIGO=B.AR_CODIGO WHERE a.ma_codigo='{$_GET['mesa']}' AND a.de_tipfac<>'C' AND a.DE_PRECIO > 0 GROUP BY a.DE_FACTURA,a.DE_FECHA, a.DE_CAJA, a.DE_TURNO, a.DE_FECENT, a.AR_CODIGO,a.DE_DESCRI,a.DE_PRECIO");

// print_pre($_SESSION);
// print_pre($data_pedidos);

$datos = [
    'titulo1'=>'O R D E N - D E - P A G O',
    'orden'=>$data_pedidos[0]['DE_FACTURA'],
    'mesa'=>$_GET['mesa'],
    'camarero'=>$_SESSION['nombre'],
    'fecha'=>dateFormat($data_pedidos[0]['DE_FECHA']),
    'caja'=>$data_pedidos[0]['DE_CAJA'],
    'turno'=>$data_pedidos[0]['DE_TURNO'],
    'apertura'=>dateFormat($data_pedidos[0]['DE_FECENT'],'hora'),
    'detalles'=>
        $data_pedidos
    
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        pre {
        display: block;
        font-family:'Courier New';
        white-space: pre;
        margin: 1em 0;
        }

       b {
           font-size:1.2em;
       }


    </style>
</head>
<body>
        <pre>
        <?=$print->FormatTicketPAGO($datos);?>
        </pre>
       

    <script>
        //window.print();
    </script>
</body>
</html>