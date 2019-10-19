<?php
require '../inc/conexion.php';
require '../clases/Comando.php';

/*
 SELECT de_factura,ar_codigo,de_cantid,de_descri,de_precio,de_cantid*de_precio as Total FROM ivbddete
 where de_factura=Nro_factura

*/

if(isset($_GET['detalle'])){

    $factura = $_POST['factura'];

    $query = "SELECT de_factura,ar_codigo,de_cantid,de_descri,de_precio,de_cantid*de_precio as Total FROM ivbddete
    where de_factura= '{$factura}' ";

    $detalle = Comando::recordSet($pdo,$query);

    echo json_encode(['resp'=>'Ok','data'=>$detalle]);
    //echo json_encode(['cod'=>'01','msj'=>'Funca']);
    exit();
}

$query =  "SELECT he_fecha,he_factura,he_nombre,ma_codigo,mo_codigo,he_monto,he_itbis,he_totley,he_neto,he_caja,he_turno FROM ivbdhete WHERE he_tipfac=''";

$mesas = Comando::recordSet($pdo,$query);

require '../header.php';

?>


<div class="box box-primary">
    <div class="box-body">
        <h2>Mesas abiertas</h2>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <th>Factura</th>
                        <th>Fecha / Hora</th>
                        <th>Nombre</th>
                        <th>Mesa</th>
                        <th>Camarero</th>
                        <th>Monto</th>
                        <th>Itbis</th>
                        <th>Imp. Ley</th>
                        <th>Total Neto</th>
                        <th></th>
                    </thead>
                    <tbody>
                    <?php  foreach($mesas as $mesa):?>
                        <tr>
                            <td><?=$mesa['he_factura']?></td>
                            <td><?=dateFormat($mesa['he_fecha']);?> / <?=dateFormat($mesa['he_fecha'],'hora');?></td>
                            <td><?=$mesa['he_nombre']?></td>
                            <td><?=$mesa['ma_codigo']?></td>
                            <td><?=$mesa['mo_codigo']?></td>
                            <td><?=$mesa['he_monto']?></td>
                            <td><?=$mesa['he_itbis']?></td>
                            <td><?=$mesa['he_totley']?></td>
                            <td><?=$mesa['he_neto']?></td>
                            <td>
                                <button class="btn btn-info btn-flat detalles" data-mesa="<?=$mesa['mesa']?>" data-fac="<?=$mesa['he_factura']?>" data-mesa="<?=$mesa['ma_codigo']?>" data-camarero="<?=$mesa['mo_codigo']?>" data-cliente="<?=$mesa['he_nombre']?>" data-fecha="<?=$mesa['he_fecha']?>">Ver detalles</button>
                            </td>
                        </tr>
                    <?php  endforeach;?>
                    </tbody>
                </table>
                                
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Detalle del  pedido</h4>
      </div>
      <div class="modal-body">
       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<?php 
    require '../footer.php';
?>


<script>

$("#cart-btn").hide();

    $('.detalles').click(function(){
        $('.modal-body').empty();

        var cliente = $(this).attr('data-cliente');
        var orden = $(this).attr('data-orden');
        var factura = $(this).attr('data-fac');
        var mesa = $(this).attr('data-mesa');
        var camarero = $(this).attr('data-camarero');
        var hora = $(this).attr('data-hora');
        var tr = '';
        var template = ``;
    
        $.ajax({
            url: "mesas_abiertas.php?detalle=true",
            type:'post',
            dataType: "json",
            data: "&factura="+factura,
            success: function(result){
                $.each(result.data, function(i, item) {

                    var cant = (item.de_cantid != '.00') ? item.de_cantid : '';
                    var precio = (item.de_precio != '.00') ? item.de_precio : '';
                    var total = (item.Total != '.0000') ? item.Total : '';

                    tr +=   `<tr>
                                <td>${cant}</td>
                                <td>${item.de_descri}</td> 
                                <td>${precio}</td>
                                <td>${total}</td>
                            </tr>`;
                });

                template += `<table class="table table-striped">
                            <thead>
                                <th>Cant.</th>
                                <th>Descrip.</th>
                                <th>Precio</th>
                                <th>Total</th>
                            </thead>
                            <tbody>
                                ${tr}
                            </tbody>
                            </table>`;

                $('.modal-body').append(template);  
               // console.log(li);
            }
        });
        $('#myModal').modal('show');
    });
</script>