<?php 
require '../inc/conexion.php';
require '../clases/Comando.php';

$departamento = $_GET['dep_id'];
$nombre = $_GET['dep_nombre'];

$query = "SELECT A.AR_CODIGO,A.AR_DESCRI,A.AR_DESCOR,A.AR_PREDET FROM IVBDARTI A
WHERE A.DE_CODIGO='{$departamento}' AND A.AR_control='S' and a.ar_activado=' ' 
ORDER BY A.ar_cosfob asc";
$articulos = Comando::recordSet($pdo,$query);
//print_pre($articulos);
?>
<div class="list-group">
    <h3 ><?=$nombre?></h3>
    <?php
    if($articulos):
        foreach($articulos as $articulo): 
    ?>  
    <!-- <a href="#" class="list-group-item list-group-item-action"><?=$articulo['AR_DESCRI']?>  -  <span class="text-right">RD$<?=number_format($articulo['AR_PREDET'],2)?></span></a> -->

     <a href="#" class="list-group-item list-group-item-action articulo" data-id="<?=$articulo['AR_CODIGO']?>">
    <div class="d-flex w-100 justify-content-between">
      <h5 class="mb-1"><?=$articulo['AR_DESCOR']?></h5>
      <!-- <small>3 days ago</small> -->
    </div>
    <p class="mb-1"><?=$articulo['AR_DESCRI']?></p>
    <p class="text-right">RD$<?=number_format($articulo['AR_PREDET'],2)?></p>
    </a>


    <?php
        endforeach;
    else:    
    ?>  

    <div class="alert alert-warning">
        No hay articulos para esta departamento.
    </div>

    <?php endif;?>
</div>


<script>

$('.articulo').click(function(){
    var ar_id = $(this).attr('data-id');
    $.ajax({
        url: "pages/agregar_a_carrito.php?articulo_id="+ar_id,
        success: function(result){
            $("#articulos_container").html(result);
        }
    });
    $("#menu").fadeOut();
    $('#menu-btn').fadeIn();
});

$('#menu-btn').click(function(){
    $('#menu').fadeIn();
    $(this).fadeOut();
    $("#articulos_container").empty();
});

</script>
