<?php
require '../inc/conexion.php';
require '../header.php';
require '../clases/Comando.php';


// COLOR1=rgb(255,174,94)  &&naranja
// COLOR2=rgb(255,89,89)   &&rojo
// COLOR3=rgb(64,128,128)   &&verde
// FOTO_DIVIDIDA="\CONTAPROSQL\ICONOS\DIVIdida.JPG"


//    IF PVBDMESA.MO_CODIGO=MOZOS
//            .b&kw.backcolor=COLOR1
//     ELSE
//            .b&kw.backcolor=COLOR2
//     ENDIF   

//    IF !EMPTY(PVBDMESA.MA_OCUPA)
//           .b&kw.backcolor=COLOR2
//    ENDIF							         
//    IF !EMPTY(PVBDMESA.MA_PAGO)
//         .b&kw.backcolor=COLOR3
//    ENDIF
//    IF !EMPTY(PVBDMESA.LETRA)
//         .b&kw.PICTURE=FOTO_DIVIDIDA
//    ENDIF
//    IF !EMPTY(pvbdmesa.HE_NOMCLI)
//           .b&kw.CAPTION=STR(&kw,3,0)+" "+ALLTRIM(pvbdmesa.HE_NOMCLI)
//           .b&kw.FONTSIZE=9
//    ENDIF   


$mesas =  Comando::recordSet($pdo,"SELECT TOP 10 * FROM PVBDMESA ORDER BY MA_ID");
//print_pre($mesas);
?>
<div class="box box-primary">
    <div class="box-body">
        <div class="row">
            <?php foreach($mesas as $mesa):?>
            <?php 
                $color = '';
                
                if(!empty(trim($mesa['MA_PAGO']))){
                    $color = 'verde';
                }
                
                if($mesa['MO_CODIGO'] == $_SESSION['mo_codigo']){
                    $color = 'naranja';
                }
                
                if(!empty(trim($mesa['MO_CODIGO'])) && $mesa['MO_CODIGO'] != $_SESSION['mo_codigo']){
                    $color = 'rojo';
                }

            ?>
            <div class="col-md-3">
                <div class="c_box <?=$color?>">
                <div class="mesa" data-id="<?=$mesa['MA_CODIGO']?>">
                    <h2><?=$mesa['MA_CODIGO']?></h2>
                </div>
                <p class="text-center"><?=$mesa['HE_NOMCLI']?> &nbsp </p>
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

setTimeout(function() {
  location.reload();
}, 5000);


</script>