<?php 
require 'inc/conexion.php';
//require 'inc/funciones.php';
require 'header.php';
require 'clases/Comando.php';
getItbis($pdo);

$departamentos = Comando::recordSet($pdo,"SELECT DE_CODIGO,ar_descri,are_codigo FROM IVBDDEPT WHERE AR_PRESENT=1 ORDER BY are_codigo ASC");
$areas = Comando::recordSet($pdo,"SELECT are_codigo,are_descri FROM IVBDAREA ORDER BY are_codigo ASC");
$mesa = $_GET['ma'];
$data = Comando::recordSet($pdo,"SELECT * FROM IVBDHETE WHERE ma_codigo='{$mesa}' AND He_tipfac <> 'C'");


if(isset($_GET['div']) && $_GET['div'] == 'true'){
    //
}

//print_pre($areas);

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
        <!-- <div class="row">
           <div class="col-md-3">
           <a href="#" id="menu-btn" class="btn btn-lg btn-custom btn-block menu-btn" style="display:none"> <i class="fa fa-arrow-left"></i> Menu</a> 
          
           </div>
        </div>    -->
        <div class="row">

       
            <?php if($data): 
                $hide = "style='display:none'";
                $data_pedidos = Comando::recordSet($pdo,"SELECT a.*,b.ar_tipo,ISNULL(b.ar_premin,0),ISNULL(b.ar_predet,0),ISNULL(b.ar_premay,0),ISNULL(B.AR_ITBIS,' ') AS DIMPUESTO,B.AR_VALEXI FROM ivbddete a LEFT JOIN ivbdarti b 
                ON A.AR_CODIGO=B.AR_CODIGO WHERE a.ma_codigo='{$mesa}' AND a.de_tipfac<>'C'");

               // print_pre($data_pedidos);
            ?>
            <div id="pedidos">
           
            <div class="col-md-3">
                    <a href="pages/mesas.php?ma=<?=trim($_GET['ma'])?>" class="btn btn-lg btn-custom btn-block menu-btn"> <i class="fa fa-arrow-left"></i> Volver a mesas</a> 
                </div>
               
            <div class="col-md-12">
            <hr>
            
           
            <table class="table">
                    <thead>
                        <th>Cant</th>
                        <th>Descripcion</th>
                        <th >Precio</th>
                        <th class="text-right">Importe</th>
                    </thead>
                    <tbody>
                    <?php foreach($data_pedidos as $item): ?>
                        <?php if($item['DE_CANTID'] >= 0):?>
                            <tr>
                            <?php if(!empty(trim($item['AR_CODIGO']))):?>
                                <td><?=$item['DE_CANTID']?></td>
                                <td>
                                    <?=$item['DE_DESCRI']?>
                                </td>
                                <td >
                                    <?=$item['DE_PRECIO']?>
                                </td>
                                <td class="text-right" >
                                    <?=$item['DE_CANTID']*$item['DE_PRECIO']?>
                                </td>
                                <?php else:?>
                                    <td></td>
                                    <td>
                                        <i><?=$item['DE_DESCRI']?></i>   
                                    </td>
                                    <td></td>
                                    <td></td>
                                <?php endif;?>
                            </tr>
                        <?php endif;?>
                    <?php endforeach;?> 
                    </tbody>
                </table>
                <table class="table" style="font-size:20px; color:#367fa9;">
                    <tr>
                        <td class="text-right">
                        Sub-total:<br>
                        Itbis:<br>
                        % de Ley:<br>
                        Total a pagar:
                        </td>
                        <td class="text-right">
                            <span><?=number_format($data[0]['HE_MONTO'],2)?></span><br>
                            <span><?=number_format($data[0]['HE_ITBIS'],2)?></span><br>
                            <span><?=number_format($data[0]['HE_TOTLEY'],2)?></span><br>
                            <span><?=number_format($data[0]['HE_NETO'],2)?></span><br>
                        </td>
                    </tr>
                </table>
                <hr>
            <a href="#" id="menu-btn" style="padding-top:15px; padding-bottom:15px;" class="btn btn-lg btn-success btn-block menu-btn"><i class="fa fa-plus"></i> Agregar orden</a>    
            <a href="#" id="imprimir" onclick="openWin('<?=$mesa?>')" style="padding-top:15px; padding-bottom:15px;" class="btn btn-primary btn-lg btn-block"> <i class="fa fa-print"></i> Imprimir </a>
            </div>
            </div>   
           
            <?php endif;?>

            <div class="menu" <?=$hide;?> > 
                <div class="col-md-3">
                    <a href="pages/mesas.php?ma=<?=trim($_GET['ma'])?>&ma_sup=<?=$_GET['ma_sup']?>&back=true" class="btn btn-lg btn-custom btn-block menu-btn"> <i class="fa fa-arrow-left"></i> Volver a mesas</a> 
                </div>
                <div class="col-md-3 ">
                    <div class="alert alert-success text-center">
                    Areas
                    </div>                
                </div>
                <div class="col-md-12">
                    <hr>
                </div>
                <?php foreach($areas as $area):?>
                    
                    <div class="col-md-3">
                        <a href="#" class="btn btn-custom btn-lg area btn-block" data-id="<?=trim($area['are_codigo'])?>" data-nombre="<?=$area['are_descri']?>" style="margin-bottom:8px !important;"><?=$area['are_descri']?></a>        
                    </div>
                <?php endforeach;?>

            </div>

            <div class="menu_dep"></div>

            <div class="col-md-12">
                <div class="articulos_container">
                </div>
            </div>
        </div>
    </div>

</div>

<?php 

require 'footer.php';
?>

<script>

$('.area').click(function(){
    var dep_id = $(this).attr('data-id');
    var dep_nombre = $(this).attr('data-nombre');
    $.ajax({
        url: "pages/departamentos.php?area_id="+dep_id+"&area_nombre="+dep_nombre,
        success: function(result){
            $(".menu_dep").fadeIn();
            $(".menu_dep").html(result);
        }
    });
    $(".menu").fadeOut();
    //$('#menu-btn').fadeIn();
});

$('.menu-btn').click(function(){
    $('.menu').fadeIn();
    $(this).fadeOut();
    $(".articulos_container").empty();
    $("#pedidos").empty();
});


function openWin(mesa)
  {
    myWindow=window.open('http://10.0.0.232/ventas/print.php?mesa='+mesa,'','width=500,height=500');
    myWindow.document.close(); //missing code
    myWindow.focus();
    myWindow.print(); 
  }

</script>