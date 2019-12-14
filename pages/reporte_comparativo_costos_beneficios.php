<?php
require '../inc/conexion.php';
require '../inc/funciones.php';
require '../clases/Comando.php';

if(isset($_POST['comparar'])){
    $anio = $_POST['anio'];
    $query = "SELECT month(de_fecha) as mes ,SUM(de_cantid*de_precio) as total,SUM(de_cantid*de_costo) as costo,
    (SUM(de_cantid*de_precio)-SUM(de_cantid*de_costo)) AS beneficio
     from ivbddepe 
    where year(DE_FECHA)='{$anio}'
    and de_cantid > 0
    group by month(de_fecha)
    ";
        $resp = Comando::recordSet($pdo,$query);
        echo json_encode($resp);
        exit();
}

//print_pre(json_encode($resp));
require '../header.php';
?>

<style>

#charge1,#charge2 {
    display:none;
}

</style>

<h1>Comparativo Inversion/Venta/Beneficios</h1>
<div class="box box-primary">
    <div class="box-header with-border">
        <!-- <h3><span class="pull-right">Desde: <?=dateFormat($fecha1)?> - Hasta: <?=dateFormat($fecha2)?></span> </h3> -->
        <h3></h3>
        <form class="form-inline" >
        <div class="form-group">
            <label for="exampleInputName2">Año</label>
            <input type="text" class="form-control year" id="anio">
        </div>
        <button type="button" id="comparar" class="btn btn-success">Consultar <span id="charge2"><i class="fa fa-circle-o-notch fa-spin"></i></span></button>
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


$("#comparar").click(function(){
    $(this).attr('disabled','disabled');
    $("#charge2").show();
    $("#comparativo").empty();
    var anio = $("#anio").val();
    $.ajax({
            url: "reporte_comparativo_costos_beneficios.php",
            type:'post',
            dataType: "json",
            data: "comparar=true&anio="+anio,
            success: function(result){
                Morris.Bar({
                    element: 'comparativo',
                    data: result,
                    xkey: 'mes',
                    ykeys: ['costo','total','beneficio'],
                    labels: ['Inversion','Venta','Beneficio'],
                   /* barColors: ['#000','#ccc'],*/
                    }).on('click', function(i, row){
                        console.log(i, row);
                    });
                    $("#charge2").hide();
                    $("#comparar").removeAttr('disabled');
                } // End result
    }); // End Ajax
});
</script>