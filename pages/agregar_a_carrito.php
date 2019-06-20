<?php 
require '../inc/conexion.php';
require '../clases/Comando.php';

$articulo_id = $_GET['articulo_id'];

$query = "SELECT ar_codigo,ar_descri,AR_DESCOR,ar_predet,AR_COCINA,ar_acompa as guarnicion,ar_tipguar as tipo_guarnicion,AR_TIPOCOC,AR_BAR,AR_CAJA,AR_POSTRE,AR_TERMINO,AR_INGRE as ingrediente FROM ivbdarti 
WHERE 
AR_CODIGO='{$articulo_id}' AND
ar_control='S'
AND ar_activado=' ' 
ORDER BY ar_cosfob asc ";

$articulo_data = Comando::recordSet($pdo,$query);

?>

<div class="row">
    <div class="col-md-12">
        <h3><?=$articulo_data[0]['AR_DESCOR']?></h3>
        <input type="hidden" id="id" value="<?=$articulo_id?>"> 
        <input type="hidden" id="desc" value="<?=$articulo_data[0]['AR_DESCOR']?>"> 
    </div>
    <?php if($articulo_data[0]['AR_COCINA'] == 1):?>
        <?php if($articulo_data[0]['guarnicion'] == 1): 
            $guarniciones = Comando::recordSet($pdo,"SELECT a.*,b.ar_codigo as ARTICULOINV FROM pvbdguarni A LEFT JOIN PVBDACOM B ON A.ac_codigo=B.AC_CODIGO WHERE A.AR_CODIGO='{$articulo_data[0]['ar_codigo']}'");
        ?>
        <div class="col-md-4">
            <p class="">Guarnicion</p>
            <?php if($guarniciones):?>
            <?php 
            $i=1;
            foreach($guarniciones as $guarnicion):?>
            <div class="form-check">
                <input class="form-check-input guarnicion" type="radio" name="guarnicion" id="gn<?=$i?>" value="<?=$guarnicion['ID']?>">
                <label class="form-check-label" for="gn<?=$i?>">
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
        <div class="col-md-4">
            <p>Ingrediente</p>
            <?php print_r($ingredientes);?>
        </div>
        <?php endif;?>
        <?php if($articulo_data[0]['AR_TERMINO'] == 1): 
             $terminos = Comando::recordSet($pdo,"SELECT A.AR_CODIGO,A.TE_CODIGO,B.TE_DESCRI FROM IVBDARTITERMINOS A INNER JOIN IVBDTERMINOS B ON A.TE_CODIGO=B.TE_CODIGO
             WHERE A.AR_CODIGO='{$articulo_data[0]['ar_codigo']}'");    
        ?>
        <div class="col-md-4">
            <p>Termino</p>
            <?php if($guarniciones):?>
            <?php 
            $i=1;
            foreach($terminos as $termino):?>
            <div class="form-check">
                <input class="form-check-input termino" type="radio" name="termino" id="tr<?=$i?>" value=" <?=$termino['TE_CODIGO']?>">
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
        <a href="#" class="btn btn-lg btn-success btn-block" id="agregar">Agregar al carrito <i class="fa fa-shopping-cart"></i></a>
    </div> 
</div>

<script>



$('#agregar').click(function(){
    var arr = [];
    if(localStorage.getItem('item') != null){
        arr = JSON.parse(localStorage.getItem('item'));
    }

    var art_data = {
        'id':$('#id').val(),
        'descripcion':$('#desc').val()
    }
    arr.push(art_data);

    localStorage.setItem('item',JSON.stringify(arr));

    console.log(JSON.parse(localStorage.getItem('item')));
    // var ar_id = $(this).attr('data-id');
    // $.ajax({
    //     url: "pages/agregar_a_carrito.php?articulo_id="+ar_id,
    //     success: function(result){
    //         $("#articulos_container").html(result);
    //     }
    // });
    // $("#menu").fadeOut();
    // $('#menu-btn').fadeIn();

    fillCart();

});


function fillCart(){
    $("#tabla-carrito").empty();
    var data = JSON.parse(localStorage.getItem('item'));
    $.each(data, function( key, value ) {
        var template = '<tr>\
        <td> <input type="text" class="form-control"></td>\
        <td>'+value.descripcion+'</td>\
        </tr>';
        $("#tabla-carrito").append(template);
        console.log(value.id);
    });
}

</script>