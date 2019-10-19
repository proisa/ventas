<?php
require '../inc/conexion.php';

require '../clases/Comando.php';



$fecha1 = isset($_POST['fecha1']) ? $_POST['fecha1'] : date('Y-m-d');
$fecha2 = isset($_POST['fecha2']) ? $_POST['fecha2'] : date('Y-m-d');

if(isset($_POST['consultar'])){

    if($_POST['rango'] == "dia"){
        $query = "SELECT CONVERT(DATE,HE_FECHA) as fecha,
        DAY(HE_FECHA) as dia,
        SUM(HE_MONTO) AS MBRUTO,
        SUM(HE_DESC) AS MDESCUENTOS,
        SUM(HE_ITBIS) AS MITBIS,
        SUM(HE_TOTLEY) AS MLEY,
        SUM(HE_NETO) AS ventas
            FROM IVBDHEPE
        WHERE HE_FECHA>='{$fecha1}' AND HE_FECHA<='{$fecha2}'
        GROUP BY HE_FECHA
        ORDER BY HE_FECHA";
    }elseif($_POST['rango'] == "mes"){
        $query = "SELECT MONTH(HE_FECHA),DATENAME(MONTH,HE_FECHA) AS fecha,YEAR(HE_FECHA) AS AÑO,SUM(HE_NETO) AS ventas from IVBDHEPE
        WHERE  HE_FECHA>='{$fecha1}' AND HE_FECHA<='{$fecha2}'
        GROUP BY MONTH(HE_FECHA),DATENAME(MONTH,HE_FECHA),YEAR(HE_FECHA) 
        ORDER BY MONTH(HE_FECHA),DATENAME(MONTH,HE_FECHA)";
    }

    $resp = Comando::recordSet($pdo,$query);
    echo json_encode($resp);
    exit();
}

if(isset($_POST['comparar'])){
    $anio1 = $_POST['anio1'];
    $anio2 = $_POST['anio2'];

    $fecha1 = $anio1.'-01-01';
    $fecha2 = $anio1.'-12-31';
    $fecha3 = $anio2.'-01-01';
    $fecha4 = $anio2.'-12-31';

    $query = "SELECT *,
        CASE WHEN MES = 1 THEN 'Enero'
            WHEN MES = 2 THEN 'Febrero'
            WHEN MES = 3 THEN 'Marzo'
            WHEN MES = 4 THEN 'Abril'
            WHEN MES = 5 THEN 'Mayo'
            WHEN MES = 6 THEN 'Junio'
            WHEN MES = 7 THEN 'Julio'
            WHEN MES = 8 THEN 'Agosto'
            WHEN MES = 9 THEN 'Setiempbre'
            WHEN MES =10 THEN 'Octubre'
            WHEN MES =11 THEN 'Noviembre'
            WHEN MES =12 THEN 'Diciembre' END AS MESL
        FROM (
        SELECT 
        MONTH(HE_FECHA) AS MES,
        SUM(CASE WHEN YEAR(HE_FECHA)='$anio1' THEN HE_NETO ELSE 0000000000.00 END) AS HE_NETO1,
        SUM(CASE WHEN YEAR(HE_FECHA)='$anio2' THEN HE_NETO ELSE 0000000000.00 END) AS HE_NETO2,
        SUM(HE_NETO) AS HE_NETO
        FROM IVBDHEPE 
        WHERE COD_EMPR=1 AND ( (HE_FECHA >= '$fecha1' AND HE_FECHA <= '$fecha2') OR (HE_FECHA >= '$fecha3' AND HE_FECHA < '$fecha4') )
        GROUP BY MONTH(HE_FECHA)
        ) X
        ORDER BY 1";

        $resp = Comando::recordSet($pdo,$query);
        echo json_encode($resp);
        exit();
}

//print_pre(json_encode($resp));
require '../header.php';
?>

<h1>Resumen de ventas</h1>
<div class="box box-primary">
    <div class="box-header with-border">
        <!-- <h3><span class="pull-right">Desde: <?=dateFormat($fecha1)?> - Hasta: <?=dateFormat($fecha2)?></span> </h3> -->
        <h3>Ventas por dias o meses</h3>
        <form class="form-inline" >
        <div class="form-group">
            <label for="exampleInputName2">Fecha 1: </label>
            <input type="text" class="form-control date" id="fecha1">
        </div>
        <div class="form-group">
            <label for="exampleInputEmail2">Fecha 2: </label>
            <input type="text" class="form-control date" id="fecha2">
        </div>
        <select name="" id="rango" class="form-control" id="">
            <option value="dia">Dia</option>
            <option value="mes">Mes</option>
        </select>
        <button type="button" id="consultar" class="btn btn-default">Consultar</button>
        </form>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-12">
                <div id="ventas_diarias"></div>
            </div>
        </div>
    </div>
</div>

<div class="box box-primary">
    <div class="box-header with-border">
        <!-- <h3><span class="pull-right">Desde: <?=dateFormat($fecha1)?> - Hasta: <?=dateFormat($fecha2)?></span> </h3> -->
        <h3>Comparativo por años</h3>
        <form class="form-inline" >
        <div class="form-group">
            <label for="exampleInputName2">Fecha 1: </label>
            <input type="text" class="form-control year" id="anio1">
        </div>
        <div class="form-group">
            <label for="exampleInputEmail2">Fecha 2: </label>
            <input type="text" class="form-control year" id="anio2">
        </div>
        <button type="button" id="comparar" class="btn btn-default">Consultar</button>
        </form>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-12">
                <div id="comparativo"></div>
            </div>
        </div>
    </div>
</div>
<?php 
    require '../footer.php';
?>
<script>
$("#cart-btn").hide();


$('.date').datepicker({
        format: 'yyyy/mm/dd',
});

$('.year').datepicker({
        format: 'yyyy',
        viewMode: "years", 
    minViewMode: "years"
});

$("#consultar").click(function(){
    $("#ventas_diarias").empty();
    var fecha1 = $("#fecha1").val();
    var fecha2 = $("#fecha2").val();
    var rango = $("#rango").val();

    $.ajax({
            url: "resumen_ventas.php",
            type:'post',
            dataType: "json",
            data: "consultar=true&fecha1="+fecha1+"&fecha2="+fecha2+"&rango="+rango,
            success: function(result){

                Morris.Bar({
                    element: 'ventas_diarias',
                    data: result,
                    xkey: 'fecha',
                    ykeys: ['ventas'],
                    labels: ['ventas'],
                    xLabels: 'day',
                    }).on('click', function(i, row){
                    console.log(i, row);
                    });
                } // End result
    }); // End Ajax
});

$("#comparar").click(function(){
    $("#comparativo").empty();
    var anio1 = $("#anio1").val();
    var anio2 = $("#anio2").val();
    $.ajax({
            url: "resumen_ventas.php",
            type:'post',
            dataType: "json",
            data: "comparar=true&anio1="+anio1+"&anio2="+anio2,
            success: function(result){

                Morris.Bar({
                    element: 'comparativo',
                    data: result,
                    xkey: 'MESL',
                    ykeys: ['HE_NETO1','HE_NETO2'],
                    labels: [anio1,anio2],
                    xLabels: 'day',
                   /* barColors: ['#000','#ccc'],*/
                    }).on('click', function(i, row){
                    console.log(i, row);
                    });
                } // End result
    }); // End Ajax
});
</script>