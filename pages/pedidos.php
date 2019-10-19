<?php
require '../inc/conexion.php';
//require '../inc/funciones.php';
require '../clases/Comando.php';

/*
SELECT RIGHT('0000000000'+RTRIM(LTRIM(HE_SECUENCIA)),10) AS secuencia
			,RTRIM(LTRIM(a.HE_FACTURA)) AS orden
			,RTRIM(CONVERT(CHAR,a.HE_HORA,0)) AS hora
			,a.MA_CODIGO
			,isnull(b.mo_descri,'')mo_descri
			,
					'SEC.: '++'   '+
					'ORDEN: '+RTRIM(LTRIM(a.HE_FACTURA))+'   '+
					'HORA: '++'  '+CL_NOMBRE CAMPO,
		
			RIGHT('0000000000'+RTRIM(LTRIM(HE_SECUENCIA)),10)+RTRIM(LTRIM(a.HE_FACTURA)) AS SECORD
			,CASE WHEN a.MA_CODIGO='DL' THEN 1 ELSE 0 END DELY
			,a.*,ISNULL(b.mo_descri,'')mo_descri,ISNULL(b2.mo_descri,'')mo_descri2,isnull(HE_DIRE1,'')HE_DIRE1,isnull(HE_DIRE2,'')HE_DIRE2
			FROM pvbdhecocina as a 
				left join pvbdmozo as b on a.mo_codigo=b.mo_codigo
				left join pvbdmozo as b2 on a.mo_codigo2=b2.mo_codigo
				left join ivbdhedely as h on a.HE_FACTURA=h.HE_FACTURA
			WHERE a.HE_MODO='' 
            comando(X+" ORDER BY HE_SECUENCIA","RESU",m.ca)


                        [secuencia] => 0000072471
            [orden] => 0000049201
            [hora] => 7:02PM
            [MA_CODIGO] => 55   
            [mo_descri] => GIRALDY DEL ROSARIO                     
            [CAMPO] => SEC.:    ORDEN: 0000049201   HORA:   GIRALDY                                 
            [SECORD] => 00000724710000049201
            [DELY] => 0
            [COD_EMPR] => 1
            [COD_SUCU] => 1
            [HE_ID] => 72885
            [HE_SECUENCIA] => 0000072471
            [HE_FACTURA] => 0000049201
            [HE_FECOPERA] => 2018-04-01 00:00:00.000
            [HE_FECHA] => 2018-04-01 00:00:00.000
            [HE_HORA] => 19:02:47.0000000
            [MO_CODIGO] => 19   
            [MO_CODIGO2] => 19   
            [HE_TERMINAL] => BAR                 
            [CL_NOMBRE] => GIRALDY                                 
            [HE_CAJA] => 01
            [HE_TURNO] => 2
            [HE_NOTA] =>                                         
            [HE_MODO] =>  
            [HE_FECDESP] => 2018-04-01 19:12:14.000
            [HE_FECIMPRESION] => 1900-01-01 00:00:00.000
            [HE_TIEMPO] => 567
            [HE_VISTO] => 1
            [HE_TIPO] =>      
            [co_sucu] => 1
            [mo_descri2] => GIRALDY DEL ROSARIO                     
            [HE_DIRE1] =>                                         
            [HE_DIRE2] =>  
*/

$query = "SELECT TOP 50 RIGHT('0000000000'+RTRIM(LTRIM(HE_SECUENCIA)),10) AS secuencia
,RTRIM(LTRIM(a.HE_FACTURA)) AS orden
,RTRIM(CONVERT(CHAR,a.HE_HORA,0)) AS hora
,a.MA_CODIGO
,isnull(b.mo_descri,'')mo_descri
,
        'SEC.: '++'   '+
        'ORDEN: '+RTRIM(LTRIM(a.HE_FACTURA))+'   '+
        'HORA: '++'  '+CL_NOMBRE CAMPO,

RIGHT('0000000000'+RTRIM(LTRIM(HE_SECUENCIA)),10)+RTRIM(LTRIM(a.HE_FACTURA)) AS SECORD
,CASE WHEN a.MA_CODIGO='DL' THEN 1 ELSE 0 END DELY
,a.*,ISNULL(b.mo_descri,'')mo_descri,ISNULL(b2.mo_descri,'')mo_descri2,isnull(HE_DIRE1,'')HE_DIRE1,isnull(HE_DIRE2,'')HE_DIRE2
FROM pvbdhecocina as a 
    left join pvbdmozo as b on a.mo_codigo=b.mo_codigo
    left join pvbdmozo as b2 on a.mo_codigo2=b2.mo_codigo
    left join ivbdhedely as h on a.HE_FACTURA=h.HE_FACTURA
WHERE a.HE_MODO='' ORDER BY HE_SECUENCIA" ;

$ordenes = Comando::recordSet($pdo,$query);

//print_pre($ordenes);

/*

SELECT *
	FROM PVBDDECOCINA
	WHERE DE_FACTURA=?DOC AND  DE_SECUENCIA=?SEC

*/

if(isset($_GET['detalle'])){

    $sec = $_POST['sec'];
    $orden = $_POST['orden'];

    $detalleQuery = "SELECT *
        FROM PVBDDECOCINA
        WHERE DE_FACTURA='{$orden}' AND  DE_SECUENCIA='{$sec}'";

    $detalleResponse = Comando::recordSet($pdo,$detalleQuery);
    echo json_encode(['resp'=>'Ok','data'=>$detalleResponse]);
    //echo json_encode(['cod'=>'01','msj'=>'Funca']);
    exit();
}



require '../header.php';

?>

<div class="box box-primary">
    <div class="box-body">
        <h2>Ordenes en cocina</h2>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped">
                    <thead>
                        <th>Secuencia</th>
                        <th>Orden</th>
                        <th>Fecha / Hora</th>
                        <th>Mesa</th>
                        <th>Camarero</th>
                        <th></th>
                    </thead>
                    <tbody>
                    <?php foreach($ordenes as $orden):?>
                        <tr>
                            <td><?=$orden['secuencia']?></td>
                            <td><?=$orden['orden']?></td>
                            <td><?=dateFormat($orden['HE_FECHA'])?> / <?=$orden['hora']?></td>
                            <td><?=$orden['MA_CODIGO']?></td>
                            <td><?=$orden['mo_descri']?></td>
                            <td>
                                <button class="btn btn-info btn-flat detalles" data-orden="<?=$orden['orden']?>" data-sec="<?=$orden['secuencia']?>" data-mesa="<?=$orden['MA_CODIGO']?>" data-camarero="<?=$orden['mo_descri']?>" data-cliente="<?=$orden['CL_NOMBRE']?>" data-hora="<?=$orden['hora']?>">Ver detalles</button>
                            </td>
                        </tr>
                    <?php endforeach;?>
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
        <h4 class="modal-title" id="myModalLabel">Detalle del  pedido</h4>
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

    $('.detalles').click(function(){
        $('.modal-body').empty();

        var cliente = $(this).attr('data-cliente');
        var orden = $(this).attr('data-orden');
        var sec = $(this).attr('data-sec');
        var mesa = $(this).attr('data-mesa');
        var camarero = $(this).attr('data-camarero');
        var hora = $(this).attr('data-hora');

        var li = '';
        var entrada_header = '';
        var plato_fuerte_header = '';
        var template = `<h3>Cliente: ${cliente}</h3>
       <h3> Secuencia: ${sec}  <span class="pull-right">Orden: ${orden}</span><br>
        Mesa: ${mesa} <span class="pull-right">Hora: ${hora}</span> <br> Camarero: ${camarero}</h3>
        <hr>
        `;
    
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
                  var detalle = ''; 
                  var art = '';
                 
                  if(item.DE_TIPOCOC == 'E'){
                        entradas_validacion++;

                        li += `<li>${item.DE_CANTID}</li>`;

                        if(item.DE_CANTID !== '.00'){
                            art =  item.DE_CANTID+' '+item.DE_DESCRI;
                        }else if(item.DE_CANTID == '.00' && item.DE_MODO == '*'){
                            detalle = `<ul>
                                    <li>${item.DE_DESCRI}</li>
                                    </ul>`;  
                        }else{
                            art = item.DE_DESCRI;
                        }
                      
                        entrada += `<li>${art}${detalle}</li>`;
                  }

                  if(item.DE_TIPOCOC == 'F'){
                        plato_fuerte_validacion++;

                        li += `<li>${item.DE_CANTID}</li>`;

                        if(item.DE_CANTID !== '.00'){
                            art =  item.DE_CANTID+' '+item.DE_DESCRI;
                        }else if(item.DE_CANTID == '.00' && item.DE_MODO == '*'){
                            detalle = `<ul>
                                    <li>${item.DE_DESCRI}</li>
                                    </ul>`;  
                        }else{
                            art = item.DE_DESCRI;
                        }

                        plato_fuerte +=  `<li>${art}${detalle}</li>`;

                  }



                  //li += `<li>${item.DE_CANTID}</li>`;

                //   if(item.DE_CANTID !== '.00' && item.DE_TIPOCOC == 'F'){
                //     art =  item.DE_CANTID+' '+item.DE_DESCRI;
                //   }else if(item.DE_CANTID == '.00' && item.DE_MODO == '*'){
                //     detalle = `<ul>
                //             <li>${item.DE_DESCRI}</li>
                //             </ul>`;  
                //   }else{
                //       art = item.DE_DESCRI;
                //   }
                 
                //    template += `<ul>
                //             <li>${art}${detalle}</li>
                //    </ul>`;     

                });

                if(entradas_validacion > 0){
                    entrada_header = '********************************* ENTRADAS *********************************';
                }

                if(plato_fuerte_validacion > 0){
                    plato_fuerte_header = '**************************** PLATOS FUERTES *************************';
                }

                template += `${entrada_header}<br>
                                <ul>${entrada}</ul>
                         ${plato_fuerte_header}<br>
                                <ul>${plato_fuerte}</ul>
                            <hr>`;

                $('.modal-body').append(template);  
               // console.log(li);
            }
        });
        $('#myModal').modal('show');
    });
</script>


