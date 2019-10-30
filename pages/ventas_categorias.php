<?php
require '../inc/conexion.php';
require '../header.php';
require '../clases/Comando.php';



//$fecha1 = isset($_POST['fecha1']) ? $_POST['fecha1'] : date('Y-m-d');
//$fecha2 = isset($_POST['fecha2']) ? $_POST['fecha2'] : date('Y-m-d');

$fecha1 = '2018-01-01';
$fecha2 = '2018-01-31';
//$departamento = isset($_POST['departamento']) && $_POST['departamento'] !== ""  ? " AND b.de_codigo = ".$_POST['departamento'] : "";
//if(isset($_POST['consultar'])){

        $query = "SELECT  a.ar_codigo
		,SUM(CASE WHEN A.MA_CODIGO<>'DL' THEN A.DE_CANTID ELSE 0 END)VALOR1
		,SUM(CASE WHEN A.MA_CODIGO ='DL' THEN A.DE_CANTID ELSE 0 END)VALOR1DL
		,SUM(A.DE_COSTO*(A.DE_CANTID))VALOR2
		,SUM(CASE WHEN A.MA_CODIGO<>'DL' THEN A.DE_PRECIO*(A.DE_CANTID) ELSE 0 END)VALOR3
		,SUM(CASE WHEN A.MA_CODIGO ='DL' THEN A.DE_PRECIO*(A.DE_CANTID) ELSE 0 END)VALOR3DL
		,ISNULL(b.de_codigo,'')de_codigo,ISNULL(b.ar_descri,'')ar_descri,ISNULL(c.ar_descri,'')departa
        ,ISNULL(b.ma_codigo,'')ma_codigo,ISNULL(d.ar_descri,'')categoria
	FROM ivbddepe as a
		left join ivbdarti as b on a.ar_codigo=b.ar_codigo
		left join ivbddept as c on b.De_codigo=c.de_codigo
        left join ivbdmarc as d on b.ma_codigo=d.ma_codigo
	WHERE LEN(a.dE_TIPO)>0 and a.dE_cantid>=0 and a.DE_FECHA>= '{$fecha1}'  and a.DE_FECHA<= '{$fecha2}'  
	GROUP BY a.ar_codigo,b.de_codigo,b.ar_descri,c.ar_descri,b.ma_codigo,d.ar_descri
    order by b.ma_codigo,b.de_codigo,SUM(a.DE_CANTID)";

        
         
    $resp = Comando::recordSet($pdo,$query);
    // print_pre($resp);
    // exit();

    $depQuery = "SELECT de_codigo,ar_descri FROM ivbddept";
    $depRes = Comando::recordSet($pdo,$depQuery);

    //exit();
//}

//print_pre(json_encode($resp));

?>

<style>
    .head-tr {
        background: #f3d712 !important;
        font-size: 18px;
    }

    .head2-tr {
        background: gray !important;
        font-size: 18px;
    }

    .totales {
        background: #b3d9ff !important;
        font-size: 18px;
    }
</style>



<h1>Ventas por Categorias</h1>
<div class="box box-primary">
    <div class="box-header with-border">
        <!-- <h3><span class="pull-right">Desde: <?=dateFormat($fecha1)?> - Hasta: <?=dateFormat($fecha2)?></span> </h3> -->
        <form class="form-inline" action="" method="POST">
        <div class="form-group">
            <label for="exampleInputName2">Fecha 1: </label>
            <input type="text" class="form-control date" id="fecha1" name="fecha1" required="required" value="<?=$fecha1?>">
        </div>
        <div class="form-group">
            <label for="exampleInputEmail2">Fecha 2: </label>
            <input type="text" class="form-control date" id="fecha2" name="fecha2" required="required" value="<?=$fecha2?>">
        </div> | 
        Departamento 
        <select name="departamento" class="form-control" id="">
            <option value="">Todos</option>
            <?php foreach($depRes as $dep): ?>
                <option value="<?=$dep['de_codigo']?>"><?=$dep['ar_descri']?></option>   
            <?php endforeach;?>
        </select>
        <button type="submit" id="consultar" class="btn btn-success">Consultar</button>
        <a href="" type="" class="btn btn-danger">Limpiar datos</a>
        </form>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-12">
               <table class="table table-striped">
                    <thead>
                        <th>Codigo</th>
                        <th>Descripcion</th>
                        <th class="text-right">Cant. En Restaurante</th>
                        <th class="text-right">Cant. En Delivery</th>
                        <th class="text-right">Total Restaurante</th>
                        <th class="text-right">Total Delivery</th>
                        <th class="text-right">Total General</th>
                    </thead>
                    <tbody>
                        <?php  
                            $reg = 0;
                            $s = 0;
                            $cam1= '';
                            
                            $reg1 = 0;
                            $s1 = 0;
                            $cam2= '';

                            $t1 = 0;
                            $t1DL = 0;
                            $t2 = 0;
                            $t2DL = 0;
                            $t3 = 0;
                            $t3DL = 0;
                            $t4 = 0;
                            $t4DL = 0;
                            if($resp):
                            foreach ($resp as $dep): ?>

                            <?php  if ($reg1 > 0): ?>
                                <?php  if ($dep['de_codigo'] !== $cam2): ?>
                                <tr class="totales">
                                    <td></td>
                                    <td>Totales por Departamentos</td>
                                    <td class="text-right"><?=number_format($t1,2)?></td>
                                    <td class="text-right"><?=number_format($t1DL,2)?></td>
                                    <td class="text-right"><?=number_format($t2,2)?></td>
                                    <td class="text-right"><?=number_format($t2DL,2)?></td>
                                    <td class="text-right"><?=number_format($t2+$t2DL,2)?></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <?php 
                                     $t1 = 0;
                                     $t1DL = 0;
                                     $t2 = 0;
                                     $t2DL = 0;
                                     $s1= 0;
                                    endif;?>
                            <?php endif;?>

                            <?php  if ($reg > 0): ?>
                                <?php  if ($dep['ma_codigo'] !== $cam1): ?>
                                <tr class="totales">
                                    <td></td>
                                    <td>Totales por Categorias</td>
                                    <td class="text-right"><?=number_format($t1,2)?></td>
                                    <td class="text-right"><?=number_format($t1DL,2)?></td>
                                    <td class="text-right"><?=number_format($t2,2)?></td>
                                    <td class="text-right"><?=number_format($t2DL,2)?></td>
                                    <td class="text-right"><?=number_format($t2+$t2DL,2)?></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <?php 
                                     $t1 = 0;
                                     $t1DL = 0;
                                     $t2 = 0;
                                     $t2DL = 0;
                                     $s= 0;
                                    endif;?>
                            <?php endif;?>

                            

                            <?php  if ($s == 0): ?>
                                     <tr class="head-tr">
                                        <td><?=$dep['ma_codigo']?></td>
                                        <td><?=$dep['categoria']?></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                     </tr>
                            <?php endif;?>
                            <?php  if ($s1 == 0): ?>
                                     <tr class="head2-tr">
                                        <td><?=$dep['de_codigo']?></td>
                                        <td><?=$dep['departa']?></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                     </tr>
                            <?php endif;?>
                            
                            <tr>
                                <td><?=$dep['ar_codigo']?></td>
                                <td><?=$dep['ar_descri']?></td>
                                <td class="text-right"><?=$dep['VALOR1']?></td>
                                <td class="text-right"><?=$dep['VALOR1DL']?></td>
                                <td class="text-right"><?=number_format($dep['VALOR3'],2)?></td>
                                <td class="text-right"><?=number_format($dep['VALOR3DL'],2)?></td>
                                <td class="text-right"><?=number_format($dep['VALOR3']+$dep['VALOR3DL'],2)?></td>
                            </tr>

                        <?php 

                        $t1 = $t1 + $dep['VALOR1'];
                        $t1DL = $t1DL + $dep['VALOR1DL'];
                        $t2 = $t2 + $dep['VALOR3'];
                        $t2DL = $t2DL + $dep['VALOR3DL'];
                        $t3 = $t3 +$dep['VALOR1'];
                        $t3DL = $t3DL + $dep['VALOR1DL'];
                       // $t4 += $t2;

                        $reg++;
                        $s++;
                        $reg1++;
                        $s1++;
                        $cam1 = $dep['ma_codigo'];
                        $cam2 = $dep['de_codigo'];
                        endforeach;
                        endif;?>
                         <tr class="totales">
                                    <td></td>
                                    <td>Totales</td>
                                    <td class="text-right"><?=number_format($t1,2)?></td>
                                    <td class="text-right"><?=number_format($t1DL,2)?></td>
                                    <td class="text-right"><?=number_format($t2,2)?></td>
                                    <td class="text-right"><?=number_format($t2DL,2)?></td>
                                    <td class="text-right"><?=number_format($t2+$t2DL,2)?></td>
                        </tr>
                    </tbody>
               </table>
            </div>
        </div>
    </div>
</div>

<?php 
    require '../footer.php';
?>
<script>
$("#cart-btn").hide();


$('.date').datepicker({
        format: 'yyyy-mm-dd',
});


</script>