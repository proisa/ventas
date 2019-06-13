<?php 
require 'inc/conexion.php';
require 'header.php';
require 'clases/Comando.php';

$departamentos = Comando::recordSet($pdo,"SELECT DE_CODIGO,ar_descri FROM IVBDDEPT WHERE AR_PRESENT=1 ORDER BY ar_ORDEN ASC");

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
    <div class="box-body">
        <div class="row">
            <div id="menu"> 
            <?php foreach($departamentos as $departamento):?>
            
                <div class="col-md-3">
                    <a href="#" class="btn btn-custom departamento btn-block" data-id="<?=trim($departamento['DE_CODIGO'])?>" style="margin-bottom:4px !important;"><?=$departamento['ar_descri']?></a>        
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
    $.ajax({
        url: "pages/articulos.php?dep_id="+dep_id, 
        success: function(result){
            $("#articulos_container").html(result);
        }
    });
    $("#menu").fadeOut();
});

</script>