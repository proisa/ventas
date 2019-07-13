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


$camareros =  Comando::recordSet($pdo,"SELECT TOP 10 * FROM PVBDMOZO ORDER BY MO_CODIGO");
//print_pre($camareros);
?>
<div class="box box-primary">
    <div class="box-body">
        <h2>Camareros</h2>
        <div class="row">
            <?php foreach($camareros as $camarero):?>
            <div class="col-md-3">
                <div class="camarero verde" data-id="<?=$camarero['MO_CODIGO']?>" >
                    <h3><?=$camarero['MO_DESCRI']?></h3>
                </div>
            </div>
            <?php endforeach;?>
        </div>
    </div>
</div>
<?php 
require '../footer.php';
?>

<script>

$('.mesa').click(function(){
    header_data = {
      'mesa':$(this).attr('data-id')
    }
    sessionStorage.setItem('header',JSON.stringify(header_data));
    window.location.href = '../index.php';
});



</script>