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

//print_pre(json_encode($resp));
require '../header.php';
?>

<h1>Resumen de ventas</h1>
<div class="box box-primary">
    <div class="box-header with-border">
        <!-- <h3><span class="pull-right">Desde: <?=dateFormat($fecha1)?> - Hasta: <?=dateFormat($fecha2)?></span> </h3> -->
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
<?php 
    require '../footer.php';
?>
<script>

$('.date').datepicker({
        format: 'yyyy/mm/dd',
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
</script>