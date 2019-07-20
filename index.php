<?php 
require 'inc/conexion.php';
//require 'inc/funciones.php';
require 'header.php';
require 'clases/Comando.php';
getItbis($pdo);
$departamentos = Comando::recordSet($pdo,"SELECT DE_CODIGO,ar_descri FROM IVBDDEPT WHERE AR_PRESENT=1 ORDER BY ar_ORDEN ASC");
$mesa = $_GET['ma'];
$data = Comando::recordSet($pdo,"SELECT * FROM IVBDHETE WHERE ma_codigo='{$mesa}' AND He_tipfac <> 'C'");
//print_pre($data);

//echo count($data[0]);

$hide = '';
?>

<style>
    .btn-custom {
        background: #222D32;
        color:#fff;
    }

    a.btn-custom:hover {
        color:yellow;
    }
</style>


<div class="box box-primary">
    <div class="box-body" id="main-content">
        <div class="row">
           <div class="col-md-3">
           <a href="#" id="menu-btn" class="btn btn-lg btn-custom btn-block menu-btn" style="display:none"> <i class="fa fa-arrow-left"></i> Menu</a> 
           <hr>
           </div>
        </div>   
        <div class="row">
            
            <?php if(count($data[0]) > 0): 
                $hide = "style='display:none'";
                $data_pedidos = Comando::recordSet($pdo,"SELECT a.*,b.ar_tipo,ISNULL(b.ar_premin,0),ISNULL(b.ar_predet,0),ISNULL(b.ar_premay,0),ISNULL(B.AR_ITBIS,' ') AS DIMPUESTO,B.AR_VALEXI FROM ivbddete a LEFT JOIN ivbdarti b 
                ON A.AR_CODIGO=B.AR_CODIGO WHERE a.ma_codigo='{$mesa}' AND a.de_tipfac<>'C'");

                //print_pre($data_pedidos);
            ?>
            <div id="pedidos">
            <div class="col-md-12">
            <table class="table">
                    <thead>
                        <th>Cant</th>
                        <th>Descripcion</th>
                        <th class="text-right">Precio</th>
                    </thead>
                    <tbody>
                    <?php foreach($data_pedidos as $item): ?>
                        <tr>
                        <?php if(!empty(trim($item['AR_CODIGO']))):?>
                            <td><?=$item['DE_CANTID']?></td>
                            <td>
                                <?=$item['DE_DESCRI']?>
                            </td>
                            <td class="text-right" >
                                <?=$item['DE_PRECIO']?>
                            </td>
                            <?php else:?>
                                <td></td>
                                <td>
                                    <i><?=$item['DE_DESCRI']?></i>   
                                </td>
                                <td></td>
                            <?php endif;?>
                        </tr>
                    <?php endforeach;?> 
                    </tbody>
                </table>
                <table class="table">
                    <tr>
                        <td class="text-right">
                        Sub-total: <br>
                        Itbis:<br>
                        % de Ley:<br>
                        Total a pagar:
                        </td>
                        <td class="text-right">
                            <span><?=$data[0]['HE_MONTO']?></span><br>
                            <span><?=$data[0]['HE_ITBIS']?></span><br>
                            <span><?=$data[0]['HE_LEY']?></span><br>
                            <span><?=$data[0]['HE_NETO']?></span><br>
                        </td>
                    </tr>
                </table>
                <hr>
            <a href="#" id="menu-btn" class="btn btn-lg btn-custom btn-block menu-btn"> <i class="fa fa-plus"></i> Agregar orden</a>    
            </div>
            </div>   
           
            <?php endif;?>

            <div id="menu" <?=$hide;?> > 
                <?php foreach($departamentos as $departamento):?>
                
                    <div class="col-md-3">
                        <a href="#" class="btn btn-custom departamento btn-block" data-id="<?=trim($departamento['DE_CODIGO'])?>" data-nombre="<?=$departamento['ar_descri']?>" style="margin-bottom:4px !important;"><?=$departamento['ar_descri']?></a>        
                    </div>
                <?php endforeach;?>
            </div>

            <div class="col-md-12">
                <div id="articulos_container">

                </div>
            </div>
        </div>
    </div>

</div>

<?php 

require 'footer.php';
?>

<script>

$('.departamento').click(function(){
    var dep_id = $(this).attr('data-id');
    var dep_nombre = $(this).attr('data-nombre');
    $.ajax({
        url: "pages/articulos.php?dep_id="+dep_id+"&dep_nombre="+dep_nombre,
        success: function(result){
            $("#articulos_container").html(result);
        }
    });
    $("#menu").fadeOut();
    $('#menu-btn').fadeIn();
});

$('.menu-btn').click(function(){
    $('#menu').fadeIn();
    $(this).fadeOut();
    $("#articulos_container").empty();
    $("#pedidos").empty();
});

</script>