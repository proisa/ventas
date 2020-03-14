<?php 
require '../inc/conexion.php';
require '../inc/funciones.php';
require '../clases/Comando.php';

$articulo_id = $_GET['articulo_id'];
$busqueda = (isset($_GET['busqueda']) && $_GET['busqueda'] !='' ) ? true : false;


$query = "SELECT ar_codigo,ar_descri,AR_DESCOR,ar_predet,AR_COCINA,ar_bar,ar_bar2,ar_postre,ar_caja,ar_cocina,ar_tipococ,ar_tipoarea,ar_acompa as guarnicion,ar_tipguar as tipo_guarnicion,AR_TIPOCOC,AR_BAR,AR_CAJA,AR_POSTRE,AR_TERMINO,AR_INGRE as ingrediente 
FROM ivbdarti 
WHERE 
AR_CODIGO='{$articulo_id}' AND
ar_control='S'
AND ar_activado=' ' 
ORDER BY ar_cosfob asc ";

$articulo_data = Comando::recordSet($pdo,$query);
//print_pre($articulo_data);
//print_pre($_GET);
?>
<div class="row">
    <?php if(!$busqueda): ?>
    <div class="col-md-3">
        <a href="#" id="back-articulos" class="btn btn-lg btn-custom btn-block menu-btn"> <i class="fa fa-arrow-left"></i> Volver atras</a> 
    </div>
    <div class="col-md-3 ">
        <div class="alert alert-success text-center">
        <?=$_GET['dep_nombre']?> 
        </div>                
    </div>
    <?php endif;?>

    <div class="col-md-12">
        <hr>
        <h3 style="color:#337ab7;"><?=$articulo_data[0]['ar_descri']?></h3>
        <input type="hidden" id="id" value="<?=trim($articulo_id)?>"> 
        <input type="hidden" id="desc" value="<?=trim($articulo_data[0]['ar_descri'])?>"> 
        <input type="hidden" id="precio" value="<?=trim($articulo_data[0]['ar_predet'])?>"> 
        <input type="hidden" id="area_id" value="<?=trim($_GET['area_id'])?>">
        <input type="hidden" id="area_nombre" value="<?=trim($_GET['area_nombre'])?>">
        <input type="hidden" id="dep_id" value="<?=trim($_GET['dep_id'])?>">
        <input type="hidden" id="dep_nombre" value="<?=trim($_GET['dep_nombre'])?>">
        <input type="hidden" id="ar_bar" value="<?=$articulo_data[0]['ar_bar']?>">
        <input type="hidden" id="ar_bar2" value="<?=$articulo_data[0]['ar_bar2']?>">
        <input type="hidden" id="ar_postre" value="<?=$articulo_data[0]['ar_postre']?>">
        <input type="hidden" id="ar_caja" value="<?=$articulo_data[0]['ar_caja']?>">
        <input type="hidden" id="ar_cocina" value="<?=$articulo_data[0]['ar_cocina']?>">
        <input type="hidden" id="ar_tipococ" value="<?=$articulo_data[0]['ar_tipococ']?>">
        <input type="hidden" id="ar_tipoarea" value="<?=$articulo_data[0]['ar_tipoarea']?>">
        <input type="hidden" id="busqueda" value="<?=$busqueda?>">
        <hr>
    </div>
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-10">
                <p style="font-size:20px;">Precio</p>
                <p style="font-size:18px;"><b>RD$<?=$articulo_data[0]['ar_predet']?></b></p>
            </div>

            <div class="col-md-2">
                <label for="" style="font-size:20px;">Cantidad</label>
                <!-- <input type="number" id="cantidad" min="1" class="form-control " value="1"> -->
                <div class="input-group input-group-lg">
                    <span class="input-group-btn">
                        <button class="btn btn-default" id="menos" type="button">-</button>
                    </span>
                    <input type="number" min="1" max="100" id="cantidad" class="form-control text-center" value="1" onkeypress="return isNumber(event)">
                    <span class="input-group-btn">
                        <button class="btn btn-default" id="mas" type="button">+</button>
                    </span>
                </div><!-- /input-group -->
            </div>
        </div>
        <hr>
    </div>
    <?php if($articulo_data[0]['AR_COCINA'] == 1):?>
        <?php if($articulo_data[0]['guarnicion'] == 1): 
            $guarniciones = Comando::recordSet($pdo,"SELECT a.*,b.ar_codigo as ARTICULOINV FROM pvbdguarni A LEFT JOIN PVBDACOM B ON A.ac_codigo=B.AC_CODIGO WHERE A.AR_CODIGO='{$articulo_data[0]['ar_codigo']}'");
        ?>
         <?php if($guarniciones):?>
        <div class="col-md-4">
            <p style="font-size:20px;">Guarnicion</p>
            <?php 
            $i=1;
            foreach($guarniciones as $guarnicion):?>
            <div class="form-check" style="margin-bottom:10px;">
                <input class="form-check-input guarnicion" type="radio" data-nombre="<?=trim($guarnicion['ac_descri'])?>" name="guarnicion" id="gn<?=$i?>" value="<?=$guarnicion['ID']?>">
                <label class="form-check-label" style="color:#337ab7; font-size:18px;" for="gn<?=$i?>">
                   <?=$guarnicion['ac_descri']?>
                </label>
            </div>
            <?php
            $i++; 
             endforeach;?>
        </div>
            <?php endif;?>
        <?php endif;?>


        <?php if($articulo_data[0]['ingrediente'] == 1): 
             $ingredientes = Comando::recordSet($pdo,"SELECT A.AR_CODIGO,A.IN_CODIGO,B.IN_DESCRI FROM IVBDINGREARTI A INNER JOIN PVBDINGRE B ON A.IN_codigo=B.In_Codigo
             WHERE A.AR_CODIGO='{$articulo_data[0]['ar_codigo']}'");
        ?>
         <?php if($ingredientes):?>
        <div class="col-md-4">
            <p>Ingrediente</p>
           
            <?php 
            $i=1;
            foreach($ingredientes as $ingrediente):?>
             <div class="form-check"  style="margin-botton:15px;">
                <input class="form-check-input ingrediente" type="checkbox" name="ingrediente[]" id="tr<?=$i?>" value=" <?=$ingrediente['IN_DESCRI']?>">
                <label class="form-check-label" for="tr<?=$i?>">
                   <?=$ingrediente['IN_DESCRI']?>
                </label>
            </div>
            <?php
            $i++; 
            endforeach;?>
            <?php endif;?>
        </div>

        <?php endif;?>
        <?php if($articulo_data[0]['AR_TERMINO'] == 1): 
             $terminos = Comando::recordSet($pdo,"SELECT A.AR_CODIGO,A.TE_CODIGO,B.TE_DESCRI FROM IVBDARTITERMINOS A INNER JOIN IVBDTERMINOS B ON A.TE_CODIGO=B.TE_CODIGO
             WHERE A.AR_CODIGO='{$articulo_data[0]['ar_codigo']}'");    
        ?>
        <?php if($terminos):?>
        <div class="col-md-4">
            <p>Termino</p>
           
            <?php 
            $i=1;
            foreach($terminos as $termino):?>
            <div class="form-check"  style="margin-botton:15px;">
                <input class="form-check-input termino" type="radio" data-nombre=" <?=$termino['TE_DESCRI']?>" name="termino" id="tr<?=$i?>" value=" <?=$termino['TE_CODIGO']?>">
                <label class="form-check-label" for="tr<?=$i?>">
                   <?=$termino['TE_DESCRI']?>
                </label>
            </div>
            <?php
            $i++; 
            endforeach;?>
        </div>
        <?php endif;?>
        <?php endif;?>
    <?php endif;?>
    <div class="col-md-12">
    <hr>
    <label for="nota" style="font-size:20px;">Nota</label>
    <textarea name="nota" style="font-size:18px;" class="form-control" id="nota" cols="30" rows="4"></textarea>
        <hr>
        <a href="#" class="btn btn-lg btn-success btn-block" id="agregar">Agregar al carrito <i class="fa fa-shopping-cart"></i></a>
    </div> 
</div>

<input type="hidden" id="area_id" value="<?=$_GET['area_id']?>">
<input type="hidden" id="area_nombre" value="<?=$_GET['area_nombre']?>">

<script>



$('#agregar').click(function(){
    var arr = [];
    if(sessionStorage.getItem('item') != null){
        arr = JSON.parse(sessionStorage.getItem('item'));
    }

    var art_data = {
        'id':$('#id').val(),
        'cantidad':$('#cantidad').val(),
        'descripcion':$('#desc').val(),
        'nota':$('#nota').val(),
        'precio':parseFloat($('#precio').val()),
        'guarnicion_id':$("input[name='guarnicion']:checked").val(),
        'guarnicion_nombre':$("input[name='guarnicion']:checked").attr('data-nombre'),
        'termino_id':$("input[name='termino']:checked").val(),
        'termino_nombre':$("input[name='termino']:checked").attr('data-nombre'),
        'ingrediente':$("input[name='ingrediente[]']:checked").map(function(){return $(this).val().trim();}).get(),
        'ar_bar':$('#ar_bar').val(),
        'ar_bar2':$('#ar_bar2').val(),
        'ar_postre':$('#ar_postre').val(),
        'ar_caja':$('#ar_caja').val(),
        'ar_cocina':$('#ar_cocina').val(),
        'ar_tipococ':$('#ar_tipococ').val(),
        'ar_tipoarea':$('#ar_tipoarea').val(),
    }
    arr.push(art_data);
    sessionStorage.setItem('item',JSON.stringify(arr));
    console.log(JSON.parse(sessionStorage.getItem('item')));

    $("#cliente").val($("#cliente_nombre").attr('data-nombre'));

    fillCart();
    // Back

    var busqueda = $('#busqueda').val();

    if(!busqueda){
        var area_id = $("#area_id").val();
        var area_nom = $("#area_nombre").val();
        $.ajax({
            url: "pages/departamentos.php?area_id="+area_id+"&area_nombre="+area_nom,
            success: function(result){
                $(".menu_dep").fadeIn();
                $(".menu_dep").html(result);
            }
        });
    }else{
        $(".menu").show();
        $(".areas-cont").show();
        $("#busqueda").val("");
    }

    $(".articulos_container").empty();     
    $("#menu-btn").click();
});

$('#back-articulos').click(function(){
    var area_id = $("#area_id").val();
    var area_nom = $("#area_nombre").val();
    var dep_id = $("#dep_id").val();
    var dep_nom = $("#dep_nombre").val();
    $.ajax({
        url: "pages/articulos.php?dep_nombre="+dep_nom+"&dep_id="+dep_id+"&area_id="+area_id+"&area_nombre="+area_nom,
        success: function(result){
            $(".articulos_container").html(result);
        }
    });
    $(".articulos_container").empty();
});

//--------------------------------------
$("#mas").click(function(){
    var cant =  parseInt($("#cantidad").val());
    if(cant < 100){
        cant = cant+1;
    }
	$("#cantidad").val(cant);
});
// -------------------------------------
$("#menos").click(function(){
	var cant =  parseInt($("#cantidad").val());
    if(cant > 1){
        cant = cant-1;
    }
	$("#cantidad").val(cant);
});


</script>
