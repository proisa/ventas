<?php
require '../inc/conexion.php';
require '../inc/funciones.php';
require '../clases/Comando.php';

$id = $_GET['area_id'];
$departamentos = Comando::recordSet($pdo,"SELECT DE_CODIGO,ar_descri,are_codigo FROM IVBDDEPT WHERE AR_PRESENT=1 AND are_codigo = '{$id}' ORDER BY are_codigo ASC");

//print_pre($departamentos);
?>
 <div class="col-md-3">
    <a href="#" class="btn btn-lg btn-custom btn-block menu-btn back-main-menu"> <i class="fa fa-arrow-left"></i> Areas</a> 
</div>
<div class="col-md-3 ">
    <div class="alert alert-success text-center">
       <?=$_GET['area_nombre']?>
    </div>                
</div>
<div class="col-md-12">
<hr>
</div>

<?php if($departamentos):?>
    <?php  foreach($departamentos as $departamento):?>       
        <div class="col-md-3">
            <a href="#" class="btn btn-custom btn-lg departamento btn-block" data-id="<?=trim($departamento['DE_CODIGO'])?>" data-nombre="<?=$departamento['ar_descri']?>" style="margin-bottom:10px !important;"><?=$departamento['ar_descri']?></a>        
        </div>
    <?php  endforeach;?>
<?php endif;?>

<input type="hidden" id="area_id" value="<?=$_GET['area_id']?>">
<input type="hidden" id="area_nombre" value="<?=$_GET['area_nombre']?>">

<script>

    $('.departamento').on('click',function(){
    var dep_id = $(this).attr('data-id');
    var dep_nombre = $(this).attr('data-nombre');
    var area_id = $("#area_id").val();
    var area_nom = $("#area_nombre").val();
    $.ajax({
        url: "pages/articulos.php?dep_id="+dep_id+"&dep_nombre="+dep_nombre+"&area_id="+area_id+"&area_nombre="+area_nom,
        success: function(result){
            $(".articulos_container").html(result);
        }
    });
    $(".menu_dep").fadeOut();
   // $('#menu-btn').fadeIn();
});

$('.back-main-menu').on('click',function(){
    $('.menu').fadeIn();
    $(".menu_dep").fadeOut();
    $(this).fadeOut();
});

</script>