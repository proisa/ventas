<?php
require '../inc/conexion.php';
require '../header.php';
require '../clases/Comando.php';

// if(isset($_GET) && !empty($_GET['ma'])){
//     $mesa = $_GET['ma'];

//     $select = "SELECT MA_FECENT FROM PVBDMESA WHERE MA_CODIGO='{$mesa}'";
//     $resSelect = Comando::recordSet($pdo,$select)[0];

//     if($resSelect['MA_FECENT'] != '1900-01-01 00:00:00.000'){
//          Comando::noRecordSet($pdo,"UPDATE PVBDMESA SET MA_OCUPA='' WHERE MA_CODIGO='$mesa'");
//     }else{
//         Comando::noRecordSet($pdo,"UPDATE PVBDMESA SET MA_OCUPA='', MO_CODIGO='' WHERE MA_CODIGO='$mesa'");
//     }   

//     $pdo->commit();
// }


// echo is_numeric(10);
// echo is_numeric('10');
// echo is_numeric('A10');
$mesa = $_GET['ma'];
$mesas =  Comando::recordSet($pdo,"SELECT A.*,B.* FROM IVBDHETE A INNER JOIN PVBDMESA B ON A.MA_CODIGO=B.MA_CODIGO WHERE A.MA_DEPEN='{$mesa}' OR B.MA_CODIGO = '{$mesa}' AND LEN(A.HE_TIPFAC)=0 order by a.ma_codigo");
//print_pre($mesas);
?>
<div class="box box-primary">
    <div class="box-body">
        <div class="row">
            <div class="col-md-3">
                    <a href="mesas.php" class="btn btn-lg btn-custom btn-block menu-btn"> <i class="fa fa-arrow-left"></i> Volver a mesas</a> 
            </div>
           <div class="col-md-12">
           <hr></div>
        </div>
        <div class="row">   
            <?php if($mesas):?>
            <?php foreach($mesas as $mesa):?>
            <?php 
                $color = '';
                $dividida = '--';
                
                if(!empty(trim($mesa['MA_PAGO']))){
                    $color = 'verde';
                }
                
                if($mesa['MO_CODIGO'] == $_SESSION['mo_codigo']){
                    $color = 'naranja';
                }
                
                if(!empty(trim($mesa['MO_CODIGO'])) && $mesa['MO_CODIGO'] != $_SESSION['mo_codigo']){
                    $color = 'rojo';
                }

                if(!empty(trim($mesa['LETRA']))){
                    $dividida = 'dividida';
                }

            
            ?>
            <div class="col-md-3">
                <div class="c_box <?=$color?>">
                <div class="mesa" data-id="<?=$mesa['MA_CODIGO']?>">
                    <h2><?=$mesa['MA_CODIGO']?></h2>
                </div>
                <p class="text-center" style="font-size:22px;"><?=$mesa['HE_NOMCLI']?> &nbsp </p>

               
                </div>
            </div>
            <input type="hidden" id="cliente<?=trim($mesa['MA_CODIGO'])?>" value="<?=$mesa['HE_NOMCLI']?>">
            <?php endforeach;?>
            <?php else: ?>
            <p>No hay datos disponibles </p>
            <a href="mesas.php?ma=<?=trim($_GET['ma'])?>" class="btn-success btn">Volver atras</a>
            <?php endif;?>
        </div>
    </div>
</div>

<input type="hidden" id="camarero" value="<?=$_SESSION['mo_codigo']?>">


<?php 
    require '../footer.php';
?>
<script>

$(document).ready(function(){
    clearCart();
});

$("#cart-btn").hide();
$('.mesa').click(function(){
    header_data = {
      'mesa':$(this).attr('data-id'),
      'cliente':$('#cliente'+$(this).attr('data-id')).val()
    }
    sessionStorage.setItem('header',JSON.stringify(header_data));
    $.ajax({
        url: "../process/TableProcess.php",
        type:'post',
        data: 'mesa='+$(this).attr('data-id')+'&camarero='+$('#camarero').val(),
        success: function(result){
            if(result.resp == 'Error'){
                alert(result.msj);
            }else{
                window.location.href = '../index.php?ma='+header_data.mesa+'&cliente='+header_data.cliente;
            }
            //console.log(result.msj);
        }
    });
});

// setTimeout(function() {
//   location.reload();
// }, 5000);


</script>