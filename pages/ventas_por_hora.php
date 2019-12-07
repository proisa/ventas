<?php
require '../inc/conexion.php';
require '../clases/Comando.php';
require '../header.php';

$fechaShow = isset($_POST['fecha']) ? $_POST['fecha'] : date('d/m/Y');
$fecha = isset($_POST['fecha']) ? clearDate($_POST['fecha']) : date('Ymd');

$query = "SELECT hE_FECHA, DATEPART(HH, HE_FECENT) AS HORA,
dbo.FN_DT_H_AMPM(HE_FECENT) AS AMPM, 
SUM(he_neto) AS mneto
from IVBDHEPE where HE_FECHA='{$fecha}'
group by HE_FECHA, DATEPART(HH, HE_FECENT),dbo.FN_DT_H_AMPM(HE_FECENT)";

$resp = Comando::recordSet($pdo,$query);

//print_pre($resp);

?>

<h1>Ventas por Hora</h1>


<div class="box box-primary">
    <div class="box-body">
        <div class="row">
        <form action="ventas_por_hora.php" method="post">
            <div class="col-md-2 text-center">
                    <label for="">Fecha</label>
                    <input type="text" name="fecha" class="form-control date" value="<?=$fechaShow?>">
            </div>
            <div class="col-md-2 text-center">
                    <button class="btn btn-success" style="margin-top:25px;">Buscar  <i class="fa fa-search"> </i></button>
            </div>
            </form>         
        </div>

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

    </div>
</div>


<?php 
    require '../footer.php';
?>

<script>
$("#cart-btn").hide();
$('.date').datepicker({
        format: 'mm/dd/yyyy',
});
</script>