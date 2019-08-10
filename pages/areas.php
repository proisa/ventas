<?php
require '../inc/conexion.php';
require '../inc/funciones.php';
require '../clases/Comando.php';

$areas = Comando::recordSet($pdo,"SELECT are_codigo,are_descri FROM IVBDAREA ORDER BY are_codigo ASC");
//print_pre($departamentos);
?>
<div class="col-md-12">
<hr>
<p class="lead"><?=$_GET['area_nombre']?></p>
</div>

<?php if($areas):?>
    <?php foreach($areas as $area):?> 
        <div class="col-md-3">
            <a href="#" class="btn btn-custom btn-lg area btn-block" data-id="<?=trim($area['are_codigo'])?>" data-nombre="<?=$area['are_descri']?>" style="margin-bottom:4px !important;"><?=$area['are_descri']?></a>        
        </div>
    <?php endforeach;?>
<?php endif;?>

<script>

$('.area').click(function(){
    var dep_id = $(this).attr('data-id');
    var dep_nombre = $(this).attr('data-nombre');
    $.ajax({
        url: "pages/departamentos.php?area_id="+dep_id+"&area_nombre="+dep_nombre,
        success: function(result){
            $("#menu_dep").html(result);
        }
    });
    $("#menu").fadeOut();
    //$('#menu-btn').fadeIn();
});

$('.menu-btn').click(function(){
    $('#menu').fadeIn();
    $("#menu_dep").fadeOut();
    $(this).fadeOut();
    $("#articulos_container").empty();
    $("#pedidos").empty();
});

</script>