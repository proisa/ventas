<?php
require '../inc/conexion.php';
require '../clases/Comando.php';

//print_pre(json_encode($resp));






$hoy = date('m/d/Y');

$paraMenosUnDia = new DateTime(date('Y-m-d'));
$paraMenosUnDia->modify('-1 day');
$menosUnDia = $paraMenosUnDia->format('m/d/Y');

$paraMenos7dias = new DateTime(date('Y-m-d'));
$paraMenos7dias->modify('-7 day');
$menosSieteDias = $paraMenos7dias->format('m/d/Y');

if(isset($_POST['consultar'])){
    $fecha1Unformat = date_create_from_format('m/d/Y', $_POST['fecha1']);
    $fecha1 = date_format($fecha1Unformat, 'Ymd');

    $fecha2Unformat = date_create_from_format('m/d/Y', $_POST['fecha2']);
    $fecha2 = date_format($fecha2Unformat, 'Ymd');

    $fecha3Unformat = date_create_from_format('m/d/Y', $_POST['fecha3']);
    $fecha3 = date_format($fecha3Unformat, 'Ymd');

    $query = "SELECT
    ISNULL(SUM(CASE WHEN HE_FECHA = '{$fecha1}' THEN HE_NETO ELSE 0000000000.00 END), 0.00) AS HE_NETO_I,
    ISNULL(SUM(CASE WHEN HE_FECHA ='{$fecha2}' THEN HE_NETO ELSE 0000000000.00 END), 0.00) AS HE_NETO_F,
    ISNULL(SUM(CASE WHEN HE_FECHA ='{$fecha3}' THEN HE_NETO ELSE 0000000000.00 END), 0.00) AS HE_NETO_FS
    FROM IVBDHEPE WHERE COD_EMPR=1 AND (HE_FECHA ='{$fecha1}' OR HE_FECHA = '{$fecha2}' OR HE_FECHA = '{$fecha3}')";

    $resp = Comando::recordSet($pdo,$query);
    echo json_encode($resp);
    exit();
   //print_pre($resp);
}

require '../header.php';

?>

<style>

#charge1,#charge2 {
    display:none;
}

.monto {
    font-size:26px !important;
}

</style>

<h1>Comparativo por dias</h1>
<div class="box box-primary">
    <div class="box-header with-border">
        <!-- <h3><span class="pull-right">Desde: <?=dateFormat($fecha1)?> - Hasta: <?=dateFormat($fecha2)?></span> </h3> -->
        <!-- <h3>Ventas por dias o meses</h3> -->
        <form class="form-inline" >
        <div class="form-group">
            <label for="exampleInputName2">Para la fecha: </label>
            <input type="text" class="form-control date" value="<?=$hoy?>" id="fecha1">
        </div>
        <div class="form-group">
            <label for="exampleInputEmail2">Dia Anterior: </label>
            <input type="text" readonly="true" class="form-control" value="<?=$menosUnDia?>" id="fecha2">
        </div>
        <div class="form-group">
            <label for="exampleInputEmail2">Semana Anterior: </label>
            <input type="text" readonly="true" class="form-control" value="<?=$menosSieteDias?>" id="fecha3">
        </div>
        <button type="button" id="consultar" class="btn btn-success">Consultar <span id="charge1"><i class="fa fa-circle-o-notch fa-spin"></i></span> </button>
        </form>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-4">
                <div id="ventas1">
                    <span id="fechaString1"></span><br>
                    <span class="monto" id="monto1"></span>
                </div>
            </div>
            <div class="col-md-4">
            <div id="ventas2">
                    <span id="fechaString2"></span><br>
                    <span class="monto" id="monto2"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div id="ventas3">
                    <span id="">Diferencia dia anterior</span><br>
                    <span class="monto" id="diferencia1"></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
              
            </div>
            <div class="col-md-4">
            <div id="ventas3">
                    <span id="fechaString3"></span><br>
                    <span class="monto" id="monto3"></span>
                </div>
            </div>
            <div class="col-md-4">
                    <span id="">Diferencia semana anterior</span><br>
                    <span class="monto" id="diferencia2"></span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
            <!-- <div class="progress">
                <div class="progress-bar progress-bar-green" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:40%;height:400px;">
                
                </div>
              </div> -->
                <div id="ventas_diarias">
                </div>
            </div>
        </div>
    </div>
</div>


<?php 
    require '../footer.php';
?>
<script>
$('.date').datepicker({
        format: 'mm/dd/yyyy',
});

// function addDays(date, days) {
//   var result = new Date(date);
//   var finalDate = '';   
//   result.setDate(result.getDate() + days);
//   finalDate = result.getFullYear()+'/'+result.getMonth()+'/'+result.getDate();
//   return finalDate;
// }

function resDays(date, days) {
    var startdate = moment(date);
    startdate = startdate.subtract(days, "days");
    startdate = startdate.format("MM/DD/YYYY");
    return startdate;
}

function dateString(date){
    var event = new Date(date);
    var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    return (event.toLocaleDateString('es-ES', options));
}

$("#fecha1").change(function(){
    var valor = $(this).val();
    var menos1 = resDays(valor,1);
    var menos7 = resDays(valor,7);
    $("#fecha2").val(menos1);
    $("#fecha3").val(menos7);
});

$("#consultar").click(function(){
    //$(this).attr('disabled','disabled');
    //$("#ventas_diarias").empty();
   // $("#charge1").show();
    var fecha1 = $("#fecha1").val();
    var fecha2 = $("#fecha2").val();
    var fecha3 = $("#fecha3").val();

    $('#fechaString1').empty();
    $('#fechaString2').empty();
    $('#fechaString3').empty();
    $('#monto1').empty();
    $('#monto2').empty();
    $('#monto3').empty();
    $('#diferencia1').empty();
    $('#diferencia2').empty();

    $.ajax({
            url: "reporte_comparativo.php",
            type:'post',
            dataType: "json",
            data: "consultar=true&fecha1="+fecha1+"&fecha2="+fecha2+"&fecha3="+fecha3,
            success: function(result){
                
                $('#fechaString1').append(dateString(fecha1));
                $('#fechaString2').append(dateString(fecha2));
                $('#fechaString3').append(dateString(fecha3));

                var monto1 = result[0].HE_NETO_I;
                var monto2 = result[0].HE_NETO_F;
                var monto3 = result[0].HE_NETO_FS;
                var diff1 = monto1-monto2;
                var diff2 = monto1-monto3;
                $('#monto1').append(formatMoney(monto1));
                $('#monto2').append(formatMoney(monto2));
                $('#monto3').append(formatMoney(monto3));
                $('#diferencia1').append(formatMoney(diff1.toFixed(2)));
                $('#diferencia2').append(formatMoney(diff2.toFixed(2)));
                console.log(result);
                /*
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
                    $("#charge1").hide();
                    $("#consultar").removeAttr('disabled');
                    */
                } // End result
                
    }); // End Ajax
    
});

</script>