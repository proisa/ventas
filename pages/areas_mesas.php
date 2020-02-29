<?php
require '../inc/conexion.php';
require '../header.php';
require '../clases/Comando.php';
$areas =  Comando::recordSet($pdo,"SELECT * FROM PVBDAREAMESA ORDER BY ARE_CODIGO");

if(!$areas){
    redirect('pages/mesas.php');
}

?>
<div class="box box-primary">
    <div class="box-body">
        <div class="row">
        <div class="col-md-12">
            <h2 class="text-center btn btn-lg btn-custom btn-block menu-btn" style="font-size:30px;">Areas</h2>
            <br>
        </div>
            <?php foreach($areas as $area):?>
            <div class="col-md-3">
            <a href="mesas.php?area_cod=<?=$area['are_codigo']?>&area_nom=<?=$area['are_descri']?>">
                <div class="c_box text-center" style="border-right:5px solid #3c8dbc;border-bottom:5px solid #3c8dbc;">
                     <h2 style="padding-top:40px;font-weight:bold;"><?=$area['are_descri']?></h2>
                </div>
                </a>
             </div> 
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