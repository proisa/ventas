<?php
require '../inc/conexion.php';
require '../header.php';
require '../clases/Comando.php';

if(isset($_GET) && !empty($_GET['ma'])){
    $mesa = $_GET['ma'];

    $select = "SELECT MA_FECENT,LETRA FROM PVBDMESA WHERE MA_CODIGO='{$mesa}'";
    $resSelect = Comando::recordSet($pdo,$select)[0];

    if($resSelect['MA_FECENT'] != '1900-01-01 00:00:00.000'){
         Comando::noRecordSet($pdo,"UPDATE PVBDMESA SET MA_OCUPA='' WHERE MA_CODIGO='$mesa'");
    }else{
        Comando::noRecordSet($pdo,"UPDATE PVBDMESA SET MA_OCUPA='', MO_CODIGO='' WHERE MA_CODIGO='$mesa'");
    }   
    
    if(isset($_GET['ma_sup']) && $_GET['ma_sup'] != 'false'){

        $mesa = $_GET['ma_sup'];

        $select = "SELECT MA_FECENT,LETRA FROM PVBDMESA WHERE MA_CODIGO='{$mesa}'";
        $resSelect = Comando::recordSet($pdo,$select)[0];

        $letra = getNumeroConCero($resSelect['LETRA']-1);
        
        if($letra == '00'){
            $letra = "";
        }

        $selecLetra = "SELECT LE_NOMBRE FROM PVBDLETRA WHERE LE_CODIGO = '{$letra}'";
        $resLetra = Comando::recordSet($pdo,$selecLetra)[0];

        $queryUp = "UPDATE PVBDMESA SET LETRA='{$letra}',MA_COBRAR=MA_COBRAR-1 WHERE MA_CODIGO='{$mesa}'";
        Comando::noRecordSet($pdo,$queryUp);
        $mesa = $mesa.$resLetra['LE_NOMBRE'];

        $query = "UPDATE PVBDMESA SET MA_OCUPA='' WHERE MA_CODIGO='{$mesa}'";
        Comando::noRecordSet($pdo,$query);

    }

    $pdo->commit();
}

// echo is_numeric(10);
// echo is_numeric('10');
// echo is_numeric('A10');

$formato = Comando::recordSet($pdo,"SELECT formato78 FROM fabdproc");


if($formato[0]['formato78'] == 4){
$camarero = $_SESSION['mo_codigo'];

$camarero_data = Comando::recordSet($pdo,"SELECT MO_DESDE,MO_HASTA FROM PVBDMOZO WHERE MO_CODIGO = {$camarero}");

$desde = $camarero_data[0]['MO_DESDE'];
$hasta = $camarero_data[0]['MO_HASTA'];

    $mesas =  Comando::recordSet($pdo,"SELECT * FROM PVBDMESA where ma_id>={$desde}+1 and ma_id<={$hasta}+1 ORDER BY MA_ID");

}else{

    $mesas =  Comando::recordSet($pdo,"SELECT TOP 25 * FROM PVBDMESA ORDER BY MA_ID");

}
//print_pre($mesas);
?>
<div class="box box-primary">
    <div class="box-body">
        <div class="row">
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
                <div class="c_box <?=$color?> <?=$dividida?> ">
                <div class="mesa" data-id="<?=trim($mesa['MA_CODIGO'])?>">
                    <h2><?=$mesa['MA_CODIGO']?></h2>
                </div>
                <p class="text-center" style="font-size:22px;"><?=$mesa['HE_NOMCLI']?> &nbsp </p>
               
                <?php if(!empty(trim($mesa['MO_CODIGO']))): ?>
                    <p>
                    <button class="btn btn-primary btn-flat btn-lg dividir" data-id="<?=trim($mesa['MA_CODIGO'])?>" title="Dividir cuenta">Dividir  <i class="fa fa-clone" aria-hidden="true"></i></button>
                    </p>
                   
                <?php else:?>
                <p class="text-center"> &nbsp; </p>
                <?php endif;?>
               
                </div>
            </div>
            <input type="hidden" id="cliente<?=trim($mesa['MA_CODIGO'])?>" value="<?=$mesa['HE_NOMCLI']?>">
            <?php endforeach;?>
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
      'mesa_padre':'',
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
            }else if(result.resp == 'submesas'){
                window.location.href = 'submesas.php?ma='+result.mesa;
            }
            else{
                window.location.href = '../index.php?ma='+header_data.mesa+'&ma_sup=false&cliente='+header_data.cliente;
            }
            //console.log(result.msj);
        }
    });
});

$('.dividir').click(function(){
   
    $.ajax({
        url: "../process/TableProcess.php",
        type:'post',
        dataType: "json",
        data: 'dividir="si"&mesa='+$(this).attr('data-id')+'&camarero='+$('#camarero').val(),
        success: function(result){
            if(result.resp == 'Error'){
                alert(result.msj);
            }else{
                header_data = {
                'mesa':result.data.mesa,
                'cliente':result.data.cliente,
                'mesa_padre':result.data.mesa_padre
                }
                sessionStorage.setItem('header',JSON.stringify(header_data));
                window.location.href = '../index.php?ma='+header_data.mesa+'&ma_sup='+header_data.mesa_padre+'&cliente='+header_data.cliente+'&div=true';
            }
            //console.log(result.msj);
        }
    });
});
// setTimeout(function() {
//   location.reload();
// }, 5000);


</script>