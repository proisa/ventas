<?php 
require '../inc/conexion.php';
require '../inc/config.php';
require '../inc/funciones.php';
require '../clases/Comando.php';

$id_departamento = isset($_GET['dep_id']) ? $_GET['dep_id'] : 0;
$nombre_departamento = isset($_GET['dep_nombre']) ? $_GET['dep_nombre'] : 0;
$area_id = isset($_GET['area_id']) ? $_GET['area_id'] : 0;
$area_nombre = isset($_GET['area_nombre']) ? $_GET['area_nombre'] : 0;

$busqueda = isset($_GET['buscar']) ? true : false;
//print_pre($_GET);

$query = "SELECT A.AR_CODIGO,A.AR_DESCRI,A.AR_FOTO,A.AR_DESCOR,A.AR_PREDET,AR_SELECT,AR_DETALLE FROM IVBDARTI A
WHERE A.DE_CODIGO='{$id_departamento}' AND A.AR_control='S' and a.ar_activado=' ' 
ORDER BY A.ar_cosfob asc";

if($busqueda){
    $nombre_articulo = $_GET['buscar'];
    $query = "SELECT A.AR_CODIGO,A.AR_DESCRI,A.AR_FOTO,A.AR_DESCOR,A.AR_PREDET,AR_SELECT,AR_DETALLE FROM IVBDARTI A
    WHERE A.AR_DESCRI LIKE '%{$nombre_articulo}%' AND A.AR_control='S' AND a.ar_activado=' ' 
    ORDER BY A.ar_cosfob asc";
}

$articulos = Comando::recordSet($pdo,$query);
//print_pre($articulos);
?>

<?php if(!$busqueda): ?>
<div class="row">
    <div class="col-md-3">
        <a href="#" id="back-current-area" class="btn btn-lg btn-custom btn-block menu-btn"> <i class="fa fa-arrow-left"></i> Departamentos</a> 
    </div>
    <div class="col-md-3 ">
        <div class="alert alert-success text-center">
        <?=$nombre_departamento?> 
        </div>                
    </div>
</div>
<?php endif;?>

<div class="list-group">
    <?php
    if($articulos):
        foreach($articulos as $articulo): 
        if($articulo['AR_SELECT'] == 'S'){
            $disable = 'disabled';
            $icon = "<i style='color:red' class='fa fa-ban fa-2x'></i>";
        }else{
            $disable = ''; 
            $icon = ''; 
        }
    ?>  
     <a href="#" class="list-group-item list-group-item-action articulo <?=$disable?>" <?=$disable?> data-id="<?=trim($articulo['AR_CODIGO'])?>">
    <div class="d-flex w-100 justify-content-between">
      <h4 class="mb-1" style="color:#337ab7; font-weight:bold;"><?=$articulo['AR_DESCRI']?> <?=$icon?> </h4>
      <p style="font-size:16px;"><?=$articulo['AR_DETALLE']?></p>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?php if(trim($articulo['AR_FOTO'])):?>
                 <img src="<?=url_base();?><?=$articulo['AR_FOTO']?>" alt="" width="80px;">
            <?php endif; ?>
        </div>
        <div class="col-md-6">
        <p class="text-right" style="font-size:24px;"> <span>RD$ <?=number_format($articulo['AR_PREDET'],2)?></span> </p>
        </div>
    </div>
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

<input type="hidden" id="area_id" value="<?=$area_id?>">
<input type="hidden" id="area_nombre" value="<?=$area_nombre?>">
<input type="hidden" id="dep_id" value="<?=$id_departamento?>">
<input type="hidden" id="dep_nombre" value="<?=$nombre_departamento?>">
<input type="hidden" id="busqueda" value="<?=$busqueda?>">


<script>

$('.articulo').click(function(){
    //alert('funca');
    var ar_id = $(this).attr('data-id');
    var dep_id = $("#dep_id").val();
    var dep_nom = $("#dep_nombre").val();
    var area_id = $("#area_id").val();
    var area_nom = $("#area_nombre").val();
    var busqueda = $("#busqueda").val();
    $.ajax({
        url: "pages/agregar_a_carrito.php?articulo_id="+ar_id+"&dep_nombre="+dep_nom+"&dep_id="+dep_id+"&area_id="+area_id+"&area_nombre="+area_nom+"&busqueda="+busqueda,
        success: function(result){
            $(".articulos_container").html(result);
        }
    });
    $("#menu").fadeOut();
    $('#menu-btn').fadeIn();
});

$('#back-current-area').click(function(){
    var area_id = $("#area_id").val();
    var area_nom = $("#area_nombre").val();
    $.ajax({
        url: "pages/departamentos.php?area_id="+area_id+"&area_nombre="+area_nom,
        success: function(result){
            $(".menu_dep").fadeIn();
            $(".menu_dep").html(result);
        }
    });
//    $("#menu").fadeOut();
//     $('#menu').fadeIn();
//     $(this).fadeOut();
    $(".articulos_container").empty();
});

</script>
