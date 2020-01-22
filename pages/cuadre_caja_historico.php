<?php
require '../inc/conexion.php';
require '../clases/Comando.php';
require '../header.php';

$fechaShow = isset($_POST['fecha']) ? $_POST['fecha'] : date('d/m/Y');

$fecha =clearDate($fechaShow);

$caja = isset($_POST['caja']) ? $_POST['caja'] :'01';
$turno = isset($_POST['turno']) ? $_POST['turno'] :'1';



if(isset($_GET['egresos'])){
    $query = Comando::recordSet($pdo, "SELECT fecha,rebaja,nombre,concep,valor FROM pvbdreba WHERE fecha='{$fecha}' and caja='{$caja}' and turno='{$turno}'
    ");
    echo json_encode($query);
    //exit();
}

/* 

Egresos


select fecha,rebaja,nombre,concep,valor from pvbdreba where fecha='' and caja='' and turno=''


Entradas 

select fecha,entrada,nombre,concep,valor from pvbdenta where fecha='' and caja='' and turno=''



Ingresos Cxc

select a.he_fecha,a.he_docum,a.cl_codigo,isnull(b.cl_nombre,'') as cl_nombre,a.he_monto from ccbdhere a
left join ccbdclie b on a.cl_codigo=b.cl_codigo
 where a.he_fecha='' and a.he_caja='' and a.he_turno=''





*/



// echo clearDate($_POST['fecha']);
// echo '<br>';
$q = "SELECT * FROM IVBDHEPE  WHERE  he_tipfac!='' AND HE_FECHA = '{$fecha}' AND HE_CAJA='{$caja}' AND HE_TURNO = '{$turno}' ";
//echo $q;
$query = Comando::recordSet($pdo,$q);
//$queryDeli = Comando::recordSet($pdo,"SELECT * FROM IVBDHEDELY  WHERE  he_tipfac!=''");
//print_pre($queryDeli);
//exit();
//print_pre($query);
$contado = 0;
$credito = 0;
$cheque = 0;
$tarjeta = 0;
$baucher = 0;
$nota_credito = 0;
$ley = 0;
$total_ventas = 0;

$contadoDL = 0;
$creditoDL = 0;
$chequeDL = 0;
$tarjetaDL = 0;
$baucherDL = 0;
$nota_creditoDL = 0;
$leyDL = 0;
$total_ventasDL = 0;


if($query){
    foreach($query as $resp){
        if(trim($resp['MA_CODIGO']) !== 'DL'){
            if($resp['HE_TIPO'] == '2' || $resp['HE_TIPO'] == '5'){
                if($resp['HE_DESC'] > 0 && ($resp['HE_ECHEQUE']+$resp['HE_ETARJE']+$resp['HE_BAUCHER']+ $resp['HE_VALNCRE']) == 0){
                     $contado += $resp['HE_NETO'];
                }else{
                    $contado +=  ($resp['HE_NETO']-($resp['HE_ECHEQUE']+$resp['HE_ETARJE']+$resp['HE_BAUCHER']+ $resp['HE_VALNCRE']));
                }
         
                $cheque +=  $resp['HE_ECHEQUE'];
                $tarjeta +=  $resp['HE_ETARJE'];
                $nota_credito +=  $resp['HE_VALNCRE'];
                $baucher +=  $resp['HE_BAUCHER'];
         
                $ley +=  (($resp['HE_MONTO']-$resp['HE_DESC'])*($resp['HE_LEY']/100));
         
            }elseif($resp['HE_TIPO'] == '1'){
                 $credito +=  $resp['HE_NETO'];
            }elseif($resp['HE_TIPO'] == '3'){
                 $cheque +=  $resp['HE_NETO']; 
                 $ley +=  ($resp['HE_NETO'] * ($resp['HE_LEY']/100));
            }elseif($resp['HE_TIPO'] == '4'){
             $tarjeta += $resp['HE_NETO'];
             $ley +=  ($resp['HE_NETO'] * ($resp['HE_LEY']/100));
            }
         
            $total_ventas +=  $resp['HE_NETO'];
        }else{
            if($resp['HE_TIPO'] == '2' || $resp['HE_TIPO'] == '5'){
                if($resp['HE_DESC'] > 0 && ($resp['HE_ECHEQUE']+$resp['HE_ETARJE']+$resp['HE_BAUCHER']+ $resp['HE_VALNCRE']) == 0){
                     $contadoDL +=  $resp['HE_NETO'];
                }else{
                    $contadoDL +=  ($resp['HE_NETO']-($resp['HE_ECHEQUE']+$resp['HE_ETARJE']+$resp['HE_BAUCHER']+ $resp['HE_VALNCRE']));
                }
         
                $chequeDL +=  $resp['HE_ECHEQUE'];
                $tarjetaDL +=  $resp['HE_ETARJE'];
                $nota_creditoDL +=  $resp['HE_VALNCRE'];
                $baucherDL +=  $resp['HE_BAUCHER'];
         
                $leyDL += $leyDL + (($resp['HE_MONTO']-$resp['HE_DESC'])*($resp['HE_LEY']/100));
         
            }elseif($resp['HE_TIPO'] == '1'){
                 $creditoDL +=  $resp['HE_NETO'];
            }elseif($resp['HE_TIPO'] == '3'){
                 $chequeDL +=   $resp['HE_NETO']; 
                 $leyDL +=  ($resp['HE_NETO'] * ($resp['HE_LEY']/100));
            }elseif($resp['HE_TIPO'] == '4'){
                 $tarjetaDL += $resp['HE_NETO'];
             $leyDL +=  ($resp['HE_NETO'] * ($resp['HE_LEY']/100));
            }
         
            $total_ventasDL +=  $resp['HE_NETO'];
        }
    
    }
}




$datalle = "  SELECT sum(bb.de_cantid) as Cantidad,sum(bb.de_cantid*bb.de_precio)  as Total ,bb.de_codigo,bb.categoria,
isnull(c.AR_DESCRI,'') as NOMBREDEPARTAMENTO FROM 
(
  SELECT A.AR_CODIGO,A.DE_CANTID,A.DE_PRECIO,A.DE_DESCRI,b.de_codigo,C.AR_DESCRI,ISNULL(B.MA_CODIGO,'') as CATEGORIA  FROM IVBDDEPE AS A LEFT JOIN IVBDARTI as b ON A.AR_CODIGO=B.AR_CODIGO 
  LEFT JOIN IVBDDEPT AS C ON B.DE_CODIGO=C.DE_CODIGO 
  WHERE A.DE_CANTID>=0 AND A.DE_TIPFAC='C' AND A.DE_FECHA='{$fecha}' AND DE_CAJA='{$caja}' AND DE_TURNO='{$turno}'
  ) AS BB 
    LEFT JOIN IVBDDEPT AS C ON bb.DE_CODIGO=C.DE_CODIGO 
    GROUP BY bb.de_codigo,bb.categoria,c.ar_descri
    Order BY bb.DE_codigo
";
/*




*/


$detalleResp = Comando::recordSet($pdo,$datalle);

$egresos = Comando::recordSet($pdo,"SELECT ISNULL(SUM(VALOR),0) as Egresos FROM PVBDREBA WHERE fecha='{$fecha}' AND caja='{$caja}' AND turno='{$turno}'");
$ingresos = Comando::recordSet($pdo,"SELECT ISNULL(SUM(VALOR),0) as Ingresos FROM PVBDENTA WHERE fecha='{$fecha}' AND caja='{$caja}' AND turno='{$turno}'");
$ingresos_cxc = Comando::recordSet($pdo,"SELECT ISNULL(SUM(HE_MONTO),0) as Ingresos_cxc FROM CCBDHERE WHERE HE_FECHA='{$fecha}' AND HE_CAJA='{$caja}' AND HE_TURNO='{$turno}'");

$queryEgresos = Comando::recordSet($pdo, "SELECT fecha,rebaja,nombre,concep,valor FROM pvbdreba WHERE fecha='{$fecha}' and caja='{$caja}' and turno='{$turno}'
");

$queryEntrada = Comando::recordSet($pdo, "SELECT fecha,entrada,nombre,concep,valor FROM pvbdenta WHERE fecha='{$fecha}' and caja='{$caja}' and turno='{$turno}'");

$queryIngresosCxC = Comando::recordSet($pdo, "SELECT a.he_fecha,a.he_docum,a.cl_codigo,isnull(b.cl_nombre,'') AS cl_nombre,a.he_monto FROM ccbdhere a
LEFT JOIN ccbdclie b ON a.cl_codigo=b.cl_codigo
 WHERE a.he_fecha='{$fecha}' AND a.he_caja='{$caja}' AND a.he_turno='{$turno}'");

//print_pre($queryIngresosCxC);

?>
<h1>Cuadre de caja historico</h1>

<div class="box box-primary">
    <div class="box-body">
        <div class="row">
            <form action="cuadre_caja_historico.php" method="post">
            <div class="col-md-2 text-center">
                    <label for="">Fecha</label>
                    <input type="text" name="fecha" class="form-control date" value="<?=$fechaShow?>">
            </div>
            <div class="col-md-2 text-center">
                    <label for="">Caja</label>
                    <select name="caja" id="caja" class="form-control">
                    <?php for($i=1;$i<=12;$i++):?>
                        <option <?=selected($caja,getNumeroConCero($i))?> value="<?=getNumeroConCero($i)?>"><?=getNumeroConCero($i)?></option>
                    <?php endfor;?>
                    </select>
            </div>
            <div class="col-md-2 text-center">
                    <label for="">Turno</label>
                    <select name="turno" id="turno" class="form-control">
                    <?php for($i=1;$i<=12;$i++):?>
                        <option <?=selected($turno,$i)?> value="<?=$i?>"><?=$i?></option>
                    <?php endfor;?>
                    </select>
            </div>
            <div class="col-md-2 text-center">
                    <button class="btn btn-success" style="margin-top:25px;">Buscar  <i class="fa fa-search"> </i></button>
            </div>
            </form>            
        </div>
    </div>
</div>
<h3 class="text-center"><?=getDateString($fecha)?></h3>
<div class="row">
    <div class="col-md-6">
        <div class="box box-primary">
            <div class="box-header with-border">
            <h4 class="t-cuadre">Ventas en Restaurante</h4>
        </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped">
                            <thead>
                                <th>Ventas al Contado</th>
                                <th>Ventas a Credito</th>
                                <th>Total en Restaurante</th>
                            </thead>
                        <tbody>
                            <tr>
                                <td>RD$ <?=number_format($contado+$tarjeta+$cheque+$baucher+$nota_credito,2)?></td>
                                <td>RD$ <?=number_format($credito,2)?></td>
                                <td>RD$ <?=number_format($total_ventas,2)?></td>
                            </tr>
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h4 class="t-cuadre">Ventas en Delivery</h4>
            </div>
            <div class="box-body">
                <div class="row">
                <div class="col-md-12">
                        <table class="table table-striped">
                            <thead>
                                <th>Ventas al Contado</th>
                                <th>Ventas a Credito</th>
                                <th>Total en Delivery</th>
                            </thead>
                            <tbody>
                            <tr>
                                <td>RD$ <?=number_format($contadoDL+$tarjetaDL+$chequeDL+$baucherDL+$nota_creditoDL,2)?></td>
                                <td>RD$ <?=number_format($creditoDL,2)?></td>
                                <td>RD$ <?=number_format($total_ventasDL,2)?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="box box-primary">
    <div class="box-body">
        <div class="row">
            <div class="col-md-12 text-center">
                    <h3 class=" t-cuadre">Total de Ventas: RD$ <?=number_format($total_ventas + $total_ventasDL,2)?> </h3>
            </div>
        </div>
    </div>
</div>

<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="t-cuadre">Formas de pago</h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <td>Efectivo</td>
                            <td>RD$ <?=number_format($contado + $contadoDL,2)?></td>
                        </tr>
                        <tr>
                            <td>Cheque</td>
                            <td>RD$ <?=number_format($cheque +  $chequeDL,2)?></td>
                        </tr>
                        <tr>
                            <td>Tarjeta</td>
                            <td>RD$ <?=number_format($tarjeta + $tarjetaDL,2)?></td>
                        </tr>
                        <tr>
                            <td>Notas de credito</td>
                            <td>RD$ <?=number_format($nota_credito + $nota_creditoDL,2)?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>    

<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-body">
                        <!-- 
                <div class="row">
                    <div class="col-md-12">
                        <table class="table ">
                        <tbody>
                            <tr>
                                <td width="20%"><p class="t-cuadre-green" style="width:100%;">Egresos de Caja</p></td>
                                <td><p style="padding-top:13px;">RD$ <?=number_format($egresos[0]['Egresos'],2)?>
                                    <button class="btn btn-primary btn-sm pull-right"> <i class="fa fa-eye"></i> </button>
                                </p></td>
                            </tr>
                            <tr>
                                <td width="20%"><p class="t-cuadre-green" style="width:100%;">Entradas de Caja</p></td>
                                <td><p style="padding-top:13px;">RD$ <?=number_format($ingresos[0]['Ingresos'],2)?>
                                <button class="btn btn-primary btn-sm pull-right"> <i class="fa fa-eye"></i> </button>
                                </p></td>
                            </tr>
                            <tr>
                                <td width="20%"><p class="t-cuadre-green" style="width:100%;">Ingresos de Cuentas x Cobrar</p></td>
                                <td><p style="padding-top:13px;">RD$ <?=number_format($ingresos_cxc[0]['Ingresos_cxc'],2)?>
                                <button class="btn btn-primary btn-sm pull-right"> <i class="fa fa-eye"></i> </button>
                                </p></td>
                            </tr>
                        </tbody>
                        </table>
                    </div>
                </div>
                -->

                <div class="box-group" id="accordion">
                <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                <div class="panel box ">
                  <div class="box-header with-border">
                    <h4 >
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" class="collapsed">
                      Egresos de Caja
                      </a> 
                     <span class="pull-right">RD$ <?=number_format($egresos[0]['Egresos'],2)?></span>
                    </h4>
                  </div>
                  <div id="collapseOne" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                    <div class="box-body">
                     <table class="table">
                        <thead>
                            <th>Fecha</th>  
                            <th>Nombre</th>  
                            <th>Concepto</th>  
                            <th>Valor</th>  
                        </thead>
                        <tbody>
                        <?php if($queryEgresos):?>
                            <?php foreach($queryEgresos as $egreso):?>
                                <tr>
                                    <td><?=$egreso['fecha']?></td>
                                    <td><?=$egreso['nombre']?></td>
                                    <td><?=$egreso['concep']?></td>
                                    <td><?=number_format($egreso['valor'])?></td>
                                </tr>
                            <?php endforeach;?>    
                        <?php endif; ?>
                        </tbody>
                     </table>
                    </div>
                  </div>
                </div>
                <div class="panel box ">
                  <div class="box-header with-border">
                    <h4>
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" class="collapsed" aria-expanded="false">
                      Entradas de Caja
                      </a>
                      <span class="pull-right">RD$ <?=number_format($ingresos[0]['Ingresos'],2)?></span>
                    </h4>
                  </div>
                  <div id="collapseTwo" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                    <div class="box-body">
                    <table class="table">
                        <thead>
                            <th>Fecha</th>  
                            <th>Nombre</th>  
                            <th>Concepto</th>  
                            <th>Valor</th>  
                        </thead>
                        <tbody>
                        <?php if($queryEntrada):?>
                            <?php foreach($queryEntrada as $entrada):?>
                                <tr>
                                    <td><?=$entrada['fecha']?></td>
                                    <td><?=$entrada['nombre']?></td>
                                    <td><?=$entrada['concep']?></td>
                                    <td><?=number_format($entrada['valor'])?></td>
                                </tr>
                            <?php endforeach;?>    
                        <?php endif; ?>
                        </tbody>
                     </table>
                    </div>
                  </div>
                </div>
                <div class="panel box ">
                  <div class="box-header with-border">
                    <h4>
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree" class="collapsed" aria-expanded="false">
                      Ingresos de Cuentas x Cobrar
                      </a>
                      <span class="pull-right">RD$ <?=number_format($ingresos_cxc[0]['Ingresos_cxc'],2)?></span>
                    </h4>
                  </div>
                  <div id="collapseThree" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                    <div class="box-body">
                    <table class="table">
                        <thead>
                            <th>Fecha</th>  
                            <th>Nombre</th>  
                            <th>Codigo Cliente</th>  
                            <th>Valor</th>  
                        </thead>
                        <tbody>
                        <?php if($queryIngresosCxC):?>
                            <?php foreach($queryIngresosCxC as $ingresos):?>
                                <tr>
                                    <td><?=$ingresos['he_fecha']?></td>
                                    <td><?=$ingresos['cl_nombre']?></td>
                                    <td><?=$ingresos['cl_codigo']?></td>
                                    <td><?=number_format($ingresos['he_monto'])?></td>
                                </tr>
                            <?php endforeach;?>    
                        <?php endif; ?>
                        </tbody>
                     </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>

    <!-- <div class="col-md-6">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h4 class="t-cuadre-green">Cuentas abiertas en Delivery</h4>
            </div>
            <div class="box-body">
                <div class="row">
                <div class="col-md-12">
                        <table class="table table-striped">
                            <thead>
                            <th>Cantidad de mesas</th>
                            <th>Total</th>
                            </thead>
                            <tbody>
                            <tr>
                            <td><?=$m_abiertas_deli[0]['NMESAS']?></td>
                                <td>RD$ <?=number_format($m_abiertas_deli[0]['Mneto'],2)?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

</div>

<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="t-cuadre">Ventas por departamento</h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped">
                    <tbody>
                        <thead>
                            <th>Codigo</th>
                            <th>Departamento</th>
                            <th>Total</th>
                        </thead>
                       <?php if($detalleResp):?>
                        <?php foreach($detalleResp as $dep):?>
                            <tr>
                                <td><?=$dep['de_codigo']?></td>
                                <td><?=$dep['NOMBREDEPARTAMENTO']?></td>
                                <td>RD$ <?=number_format($dep['Total'],2)?></td>
                            </tr>
                            <?php endforeach;?>
                       <?php endif;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div> 

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 style="font-size: 24px;"  id="myModalLabel">Detalle del  pedido</h4>
      </div>
      <div class="modal-body">
       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
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
        format: 'dd/mm/yyyy',
});




$('.detalles').click(function(){
        $('.modal-body').empty();
    
        $.ajax({
            url: "pedidos.php?detalle=true",
            type:'post',
            dataType: "json",
            data: "&orden="+orden+"&sec="+sec,
            success: function(result){

                var entradas_validacion = 0;
                var plato_fuerte_validacion = 0;
                var entrada = '';
                var plato_fuerte = '';    
                $.each(result.data, function(i, item) {
   

                });

                $('.modal-body').append(template);  
               // console.log(li);
            }
        });
        $('#myModal').modal('show');
    });

</script>