<?php
require 'inc/conexion.php';
require 'inc/funciones.php';
require 'clases/Comando.php';
require 'clases/PrintUtil.php';
require 'clases/Config.php';

echo dateFormat('2019-11-23 12:30:00','hora');
echo '<br>';
$print = new PrintUtil(new Config($pdo));

$test = [[
    'codigo'=>1,
    'cantidad'=>1,
    'descripcion'=>'Pechuga',
    'precio'=>number_format(200,2)
],
[
    'codigo'=>2,
    'cantidad'=>1,
    'descripcion'=>'Costillita',
    'precio'=>number_format(400,2)
],
[
    'codigo'=>3,
    'cantidad'=>2,
    'descripcion'=>'Agua',
    'precio'=>number_format(15,2)
],
[
    'codigo'=>3,
    'cantidad'=>2,
    'descripcion'=>'Agua',
    'precio'=>number_format(15,2)
],
[
    'codigo'=>3,
    'cantidad'=>2,
    'descripcion'=>'Agua',
    'precio'=>number_format(15,2)
]

];

$datos = [
    'titulo1'=>'O R D E N - D E - P A G O',
    'orden'=>'000000876',
    'mesa'=>'06',
    'camarero'=>'JOSE',
    'fecha'=>date('d/m/Y'),
    'caja'=>'1',
    'turno'=>'2',
    'apertura'=>date('H:m:s').' PM',
    'nota1'=>'Una nota',
    'detalles'=>$test
];

//print_pre($datos['detalles']);

foreach($datos['detalles'] as $k => $v){

    echo $v['cantidad'].' - '.$v['descripcion']. ' - '.$v['precio'].'<br>';
}

exit();

// echo '<pre>';
// echo $print->FormatTicketPAGO($datos);
// echo '</pre>';
//phpinfo();

$insert = "INSERT INTO BAKTMP (COD_EMPR) VALUES (5)";
$delete = "DELETE FROM BAKTMP WHERE COD_EMPR = 5";
$select = "SELECT LETRERO1,LETRERO2,LETRERO3,LETRERO4,LETRERO5 FROM FABDPROC";
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