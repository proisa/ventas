<?php
require '../inc/conexion.php';
require '../header.php';
require '../clases/Comando.php';
/*
    IF !comando("SELECT * FROM IVBDHETE  WHERE  he_tipfac!='' ","RESU",m.ca)
	   MESSAGEBOX(MESSAGE())
	   RETURN
	ENDIF

go top
scan

     if he_tipo="2" .or. he_tipo="5"
        if he_desc > 0 .AND. (he_Echeque+HE_Etarje+HE_BAUCHER+ he_valncre)=0
           contado = contado + he_neto      
        else
           contado = contado + (he_neto-(he_Echeque+HE_Etarje+HE_BAUCHER+ he_valncre))  &&he_monrec         &&he_neto                 
        endif

        CHEQUE = CHEQUE + he_Echeque
        TARJEAT = TARJETA + HE_Etarje
        ncredi = ncredi + he_valncre
        BAUCHER = BAUCHER + HE_BAUCHER
        
       
        LEY = LEY + ((HE_monto-he_desc)*(HE_LEY/100))
        
        
     endif
     
     if he_tipo="1"
        credito = credito + he_neto
    
     endif
     
     if he_TIPO="3"
        CHEQUE = CHEQUE + HE_NETO          
        LEY = LEY + (HE_NETO*(HE_LEY/100))
     ENDIF
     
     if he_TIPO="4"
        TARJETA = TARJETA + HE_NETO
        LEY = LEY + (HE_NETO*(HE_LEY/100))

     ENDIF
     
       T_VENTAS = T_VENTAS + HE_NETO
       
endscan

--total de vemtas = vaiable t_ventas


      SELECT sum(a.de_cantid) as Cantidad,sum(a.de_cantid*de_precio) as Total,ISNULL(b.de_codigo,'') as de_codigo,
      isnull(C.AR_DESCRI,'') as NOMBREDEPARTAMENTO FROM IVBDDETE AS A 
      LEFT JOIN IVBDARTI as b ON A.AR_CODIGO=B.AR_CODIGO 
      LEFT JOIN IVBDDEPT AS C ON B.DE_CODIGO=C.DE_CODIGO 
      WHERE A.DE_CANTID>=0 AND A.DE_TIPFAC='C' 
      GROUP BY B.DE_CODIGO,C.AR_DESCRI
      order by B.DE_codigo



----------------------------------------------------------------------



*/

$query = Comando::recordSet($pdo,"SELECT * FROM IVBDHETE  WHERE  he_tipfac!=''");

//print_pre($query);

$contado = 0;
$credito = 0;
$cheque = 0;
$tarjeta = 0;
$baucher = 0;
$nota_credito = 0;
$ley = 0;
$total_ventas = 0;

foreach($query as $resp){
   if($resp['HE_TIPO'] == '2' || $resp['HE_TIPO'] == '5'){
       if($resp['HE_DESC'] > 0 && ($resp['HE_ECHEQUE']+$resp['HE_ETARJE']+$resp['he_baucher']+ $resp['he_valncre']) == 0){
            $contado += $contado + $resp['HE_NETO'];
       }else{
           $contado += $contado + ($resp['HE_NETO']-($resp['HE_ECHEQUE']+$resp['HE_ETARJE']+$resp['he_baucher']+ $resp['he_valncre']));
       }

       $cheque += $cheque + $resp['HE_ECHEQUE'];
       $tarjeta += $tarjeta + $resp['HE_ETARJE'];
       $nota_credito += $nota_credito + $resp['he_valncre'];
       $baucher += $baucher + $resp['he_baucher'];

       $ley += $ley + (($resp['HE_MONTO']-$resp['HE_DESC'])*($resp['HE_LEY']/100));

   }elseif($resp['HE_TIPO'] == '1'){
        $credito += $credito + $resp['HE_NETO'];
   }elseif($resp['HE_TIPO'] == '3'){
        $cheque +=  $cheque + $resp['HE_NETO']; 
        $ley += $ley + ($resp['HE_NETO'] * ($resp['HE_LEY']/100));
   }elseif($resp['HE_TIPO'] == '4'){
    $tarjeta += $tarjeta + $resp['HE_NETO'];
    $ley += $ley + ($resp['HE_NETO'] * ($resp['HE_LEY']/100));
   }

   $total_ventas += $total_ventas + $resp['HE_NETO'];
}

$datalle = "      SELECT sum(a.de_cantid) as Cantidad,sum(a.de_cantid*de_precio) as Total,ISNULL(b.de_codigo,'') as de_codigo,
isnull(C.AR_DESCRI,'') as NOMBREDEPARTAMENTO FROM IVBDDETE AS A 
LEFT JOIN IVBDARTI as b ON A.AR_CODIGO=B.AR_CODIGO 
LEFT JOIN IVBDDEPT AS C ON B.DE_CODIGO=C.DE_CODIGO 
WHERE A.DE_CANTID>=0 AND A.DE_TIPFAC='C' 
GROUP BY B.DE_CODIGO,C.AR_DESCRI
order by B.DE_codigo";

$detalleResp = Comando::recordSet($pdo,$datalle);

//print_pre($detalleResp);

?>
<h1>Cuadre de caja</h1>
<div class="box box-primary">
    <div class="box-header with-border">
        <h3>Totales</h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped">
                    <thead>
                        <th>Ventas al contado</th>
                        <th>Ventas a credito</th>
                        <th>Total de ventas</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>RD$ <?=number_format($contado,2)?></td>
                            <td>RD$ <?=number_format($credito,2)?></td>
                            <td>RD$ <?=number_format($total_ventas,2)?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="box box-primary">
    <div class="box-header with-border">
        <h3>Formas de pago</h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <td>Efectivo</td>
                            <td>RD$ <?=number_format($contado,2)?></td>
                        </tr>
                        <tr>
                            <td>Cheque</td>
                            <td>RD$ <?=number_format($cheque,2)?></td>
                        </tr>
                        <tr>
                            <td>Tarjeta</td>
                            <td>RD$ <?=number_format($tarjeta,2)?></td>
                        </tr>
                        <tr>
                            <td>Notas de credito</td>
                            <td>RD$ <?=number_format($nota_credito,2)?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>    

<div class="box box-primary">
    <div class="box-header with-border">
        <h3>Ventas por departamento</h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped">
                    <tbody>
                        <thead>
                            <th>Cantidad</th>
                            <th>Departamento</th>
                            <th>Total</th>
                        </thead>
                       <?php foreach($detalleResp as $dep):?>
                        <tr>
                            <td><?=$dep['Cantidad']?></td>
                            <td><?=$dep['NOMBREDEPARTAMENTO']?></td>
                            <td>RD$ <?=number_format($dep['Total'],2)?></td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>  

<?php 
    require '../footer.php';
?>