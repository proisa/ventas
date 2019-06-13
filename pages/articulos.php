<?php 
require '../inc/conexion.php';
require '../clases/Comando.php';

$departamento = $_GET['dep_id'];

$query = "SELECT A.AR_CODIGO,A.AR_DESCRI,A.AR_DESCOR,A.AR_PREDET FROM IVBDARTI A
WHERE A.DE_CODIGO='{$departamento}' AND A.AR_control='S' and a.ar_activado=' ' 
ORDER BY A.ar_cosfob asc";

$articulos = Comando::recordSet($pdo,$query);

//print_pre($articulos);

?>

<div class="list-group">
    <?php foreach($articulos as $articulo):?>  
    <a href="#" class="list-group-item list-group-item-action"><?=$articulo['AR_DESCRI']?></a>
    <?php endforeach;?>  
</div>
