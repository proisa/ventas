<?php
require '../inc/conexion.php';
require '../inc/funciones.php';
require '../clases/Comando.php';


$fechaShow = isset($_POST['fecha']) ? $_POST['fecha'] : date('d/m/Y');

$fecha=clearDate($fechaShow);


if(isset($_POST['consultar'])){
    $query = "SELECT hE_FECHA as fecha, DATEPART(HH, HE_FECENT) AS hora_24,
    dbo.FN_DT_H_AMPM(HE_FECENT) AS hora, 
    SUM(he_neto) AS monto
    from IVBDHEPE where HE_FECHA='{$fecha}'
    group by HE_FECHA, DATEPART(HH, HE_FECENT),dbo.FN_DT_H_AMPM(HE_FECENT)";
    $resp = Comando::recordSet($pdo,$query);
    $fecha_string = getDateString($fecha);
    echo json_encode(['fecha'=>$fecha_string,'data'=>$resp]);
    exit();
}
require '../header.php';


//print_pre($resp);

?>

<h1>Ventas por Hora</h1>


<div class="box box-primary">
    <div class="box-body">
        <div class="row">
        <form action="#">
            <div class="col-md-2 text-center">
                    <label for="">Fecha</label>
                    <input type="text" name="fecha" id="fecha" class="form-control date" value="<?=$fechaShow?>">
            </div>
            <div class="col-md-2 text-center">
                    <button class="btn btn-success" id="consultar" style="margin-top:25px;">Buscar  <i class="fa fa-search"> </i></button>
            </div>
            </form>         
        </div>
        <h3 class="text-center" id="fecha_string"></h3>
        <div id="ventas_horas">
        </div>
        <!--
        <table class="table">
            <thead>
                <th>Hora</th>
                <th>Monto</th>
            </thead>
            <tbody>
                <?php if($resp):?>
                <?php foreach($resp as $v):?>
                <tr>
                    <td><?=$v['AMPM']?></td>
                    <td><?=number_format($v['mneto'],2)?></td>
                </tr>
                <?php endforeach;?>
                <?php endif;?>
            </tbody>
        </table>
        -->
    </div>
</div>


<?php 
    require '../footer.php';
?>

<script>
$("#cart-btn").hide();
$('.date').datepicker({
        format: 'dd/mm/yyyy',
});


$("#consultar").click(function(){
    $(this).attr('disabled','disabled');
    $("#ventas_horas").empty();
    $('#fecha_string').empty();
    $("#charge1").show();
    var fecha = $("#fecha").val();

    $.ajax({
            url: "ventas_por_hora.php",
            type:'post',
            dataType: "json",
            data: "consultar=true&fecha="+fecha,
            success: function(result){
                Morris.Bar({
                    element: 'ventas_horas',
                    data: result.data,
                    xkey: 'hora',
                    ykeys: ['monto'],
                    labels: ['Monto'],
                    }).on('click', function(i, row){
                    console.log(i, row);
                    });
                    $("#charge1").hide();
                    $("#consultar").removeAttr('disabled');
                    $('#fecha_string').append(result.fecha);
                } // End result
                
    }); // End Ajax
    
});
</script>