<?php
require '../inc/conexion.php';
require '../header.php';
require '../clases/Comando.php';

$fecha1 = isset($_GET['fecha1']) ? $_GET['fecha1'] : date('Y-m-d');
$fecha2 = isset($_GET['fecha2']) ? $_GET['fecha2'] : date('Y-m-d');

$query = "SELECT HE_FECHA,
    SUM(HE_MONTO) AS MBRUTO,
    SUM(HE_DESC) AS MDESCUENTOS,
    SUM(HE_ITBIS) AS MITBIS,
    SUM(HE_TOTLEY) AS MLEY,
    SUM(HE_NETO) AS MNETO
        FROM IVBDHEPE
    WHERE HE_FECHA>='{$fecha1}' AND HE_FECHA<='{$fecha2}'
    GROUP BY HE_FECHA
    ORDER BY HE_FECHA";

$resp = Comando::recordSet($pdo,$query);

print_pre($resp[0]);

?>

<h1>Resumen de ventas</h1>

<div class="box box-primary">
    <div class="box-header with-border">
        <h3>Ventas por dias <span class="pull-right">Desde: <?=$fecha1?> - Hasta: <?=$fecha2?></span> </h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-12">
                <div id="ventas_diarias"></div>
            </div>
        </div>
    </div>
</div>





<?php 
    require '../footer.php';
?>

<script>

// $.ajax({
//             url: "pedidos.php?detalle=true",
//             type:'post',
//             dataType: "json",
//             data: "&orden="+orden+"&sec="+sec,
//             success: function(result){

//             }
// });

datos = [
    {fecha: '2019-01-01', ventas: '5000.00'},
    {fecha: '2019-01-02', ventas: '6000.00'},
    {fecha: '2019-01-03', ventas: '7000.00'},
    {fecha: '2019-01-04', ventas: '3000.00'},
    {fecha: '2019-01-05', ventas: '2000.00'}
    ];

Morris.Bar({
    element: 'ventas_diarias',
    data: datos,
    xkey: 'fecha',
    ykeys: ['ventas'],
    labels: ['ventas'],
    xLabels: 'day',
    }).on('click', function(i, row){
    console.log(i, row);
    });
</script>