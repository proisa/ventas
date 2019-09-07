<?php 
date_default_timezone_set('America/Santo_Domingo');
ini_set('display_errors',0);
require '../inc/conexion.php';
require '../inc/funciones.php';
require '../clases/Comando.php';
// echo '<pre>';
//   echo 'Header <br>';
//   print_r(json_decode($_POST['header']));
//   echo '------------------<br>';
//   echo 'Detalle <br>';
//   print_r(json_decode($_POST['data']));
//   echo '------------------<br>';
//   //print_r($_SESSION);
// echo '</pre>';
$header = json_decode($_POST['header']);
$detalle = json_decode($_POST['data']);

//print_pre($detalle);
// $data = [
//   'mesa'=>'02',
//   'data'=>$_POST['data']
// ];



//exit();

$mesaConPedidos = Comando::recordSet($pdo,"SELECT * FROM IVBDHETE WHERE ma_codigo='{$header->mesa}' AND He_tipfac <> 'C'")[0];
//echo 'Count: ' . count($mesaConPedidos);
//echo 'Datos de Mesa <br>';
//print_pre($mesaConPedidos);
//echo '------------------<br>';
//exit();
if(count($mesaConPedidos) == 0){
  $fecha = Comando::recordSet($pdo,'SELECT getDate() as fecha')[0]['fecha'];
  Comando::noRecordSet($pdo,"UPDATE IVBDPROC SET PEDIDO=PEDIDO+1");
  $num_factura = Comando::recordSet($pdo,"SELECT PEDIDO FROM IVBDPROC")[0]['PEDIDO'];
  $num_factura = str_pad($num_factura, 10, "0", STR_PAD_LEFT); 

  $header_data = (object) [
    'tipo'=>'',
    'mesa'=>$header->mesa,
    'factura'=>$num_factura,
    'fecha'=>$fecha,
    'monto'=>$header->subtotal,
    'persona'=>0,
    'porc_ley'=>$_SESSION['ley'],
    'porc_itbis'=>$_SESSION['itbis'],
    'descuento'=>0,
    'fecha_entrada'=>$fecha,
    'fecha_salida'=>'',
    'tipo_comp'=>'02',
    'codigo_cliente'=>1,
    'itbis'=>$header->itbis,
    'total_ley'=>$header->ley,
    'neto'=>$header->total,
    'al_codigo'=>'01',
    'caja'=>'01',
    'turno'=>'1',
    'val_desc'=>0,
    'tipo_desc'=>0,
    'mo_codigo'=>$_SESSION['mo_codigo'],
    'nombre_cliente'=>$header->cliente,
    'usuario_id'=>$_SESSION['id'],
    'dependencia_mesa'=>'',
  ]; 

}else{

  $header_data = (object) [
    'tipo'=>'',
    'mesa'=>$mesaConPedidos['MA_CODIGO'],
    'factura'=>$mesaConPedidos['HE_FACTURA'],
    'fecha'=>$mesaConPedidos['HE_FECHA'],
    'monto'=>$header->subtotal,
    'persona'=>0,
    'porc_ley'=>$_SESSION['ley'],
    'porc_itbis'=>$_SESSION['itbis'],
    'descuento'=>0,
    'fecha_entrada'=>$mesaConPedidos['HE_FECHA'],
    'fecha_salida'=>'',
    'tipo_comp'=>'02',
    'codigo_cliente'=>1,
    'itbis'=>$header->itbis,
    'total_ley'=>$header->ley,
    'neto'=>$header->total,
    'al_codigo'=>'01',
    'caja'=>'01',
    'turno'=>'1',
    'val_desc'=>0,
    'tipo_desc'=>0,
    'mo_codigo'=>$_SESSION['mo_codigo'],
    'nombre_cliente'=>$mesaConPedidos['HE_NOMBRE'],
    'usuario_id'=>$_SESSION['id'],
    'dependencia_mesa'=>'',
  ]; 
}
// echo '<pre>';
// print_r($header_data);
// echo '</pre>';
$header_query = "INSERT IVBDHETE 
(he_tipo,ma_codigo,he_factura,he_fecha,he_monto,he_persona,
 HE_LEY,He_imp,he_desc,he_fecent,HE_FECSAL,IM_CODIGO,cl_codigo,he_itbis,he_TOTLEY,he_neto,
 AL_CODIGO,he_caja,he_turno,he_valdesc,he_tipdes,MO_CODIGO,HE_NOMBRE,HE_USUARIO,MA_DEPEN)
VALUES
  ('{$header_data->tipo}','{$header_data->mesa}','{$header_data->factura}','{$header_data->fecha}',{$header_data->monto},{$header_data->persona},
  '{$header_data->porc_ley}','{$header_data->porc_itbis}','{$header_data->descuento}','{$header_data->fecha_entrada}','{$header_data->fecha_salida}','{$header_data->tipo_comp}','{$header_data->codigo_cliente}','{$header_data->itbis}','{$header_data->total_ley}','{$header_data->neto}',
  '{$header_data->al_codigo}','{$header_data->caja}','{$header_data->turno}','{$header_data->val_desc}','{$header_data->tipo_desc}','{$header_data->mo_codigo}','{$header_data->nombre_cliente}','{$header_data->usuario_id}','{$header_data->dependencia_mesa}')";

if(count($mesaConPedidos) > 0){
    
  $header_query = "UPDATE IVBDHETE SET he_monto = he_monto + $header_data->monto, he_itbis = he_itbis + $header_data->itbis,he_TOTLEY = he_TOTLEY+$header_data->total_ley ,he_neto = he_neto + $header_data->neto";

}

//echo $header_query;
Comando::noRecordSet($pdo,$header_query);
//echo '<br>';
foreach($detalle as $k => $v){
  $docum = '';
  $nota = !empty($v->nota) ? $v->nota : '';
  $tipo_orden = 'COCINA';
  $tipo_cocina = '';
  $guarnicion = isset($v->guarnicion_id) ? $v->guarnicion_id : '';

  if($guarnicion != '' || $nota != ''){
     Comando::noRecordSet($pdo,"UPDATE IVBDPROC SET CREAUX=CREAUX+1");
     $data = Comando::recordSet($pdo,"SELECT CREAUX FROM IVBDPROC");
     $docum = $data[0]['CREAUX'];
  }

  if($v->ar_tipococ == 1){
    $tipo_cocina = 'E';
  }elseif($v->ar_tipococ == 2){
    $tipo_cocina = 'F';
  }

  if($v->ar_bar == 1 || $v->ar_bar2 == 1){
    $tipo_orden = 'BAR';
  }elseif($v->ar_postre == 1){
    $tipo_orden = 'POSTRE';
  }elseif($v->ar_caja == 1){
    $tipo_orden = 'CAJA';
  }

  $detalle_query = "INSERT INTO IVBDDETE 
  (DE_TIPO,DE_FACTURA,DE_FECHA,DE_NOMBRE,IM_CODIGO,DE_TBIS,DE_LEY,DE_ITBIS,CL_CODIGO,DE_CAJA,DE_TURNO,MA_CODIGO,MO_CODIGO,
   DE_POSICIO,DE_DESCRI,DE_CANTID,AR_CODIGO,DE_PRECIO,DE_PRECIO2,DE_COSTO,
   AC_CODIGO,AC_CANTID,DE_DOCUM,AL_CODIGO,DE_USUARIO,DE_FECENT,DE_FECSAL,MA_DEPEN,
   DE_PR1,DE_PR2,DE_PR3,AR_codigo2)
    VALUES
    ('{$header_data->tipo}','{$header_data->factura}','{$header_data->fecha}','{$header_data->nombre_cliente}','{$header_data->tipo_comp}',1,1,0,'{$header_data->codigo_cliente}','{$header_data->caja}','{$header_data->turno}','{$header_data->mesa}','{$header_data->mo_codigo}',
    0,'{$v->descripcion}','{$v->cantidad}','{$v->id}',{$v->precio},0,0,
    '{$guarnicion}',0,'{$docum}','{$header_data->al_codigo}','','{$header_data->fecha_entrada}','{$header_data->fecha_salida}','',
    0,0,0,'')";

Comando::noRecordSet($pdo,$detalle_query);

// IMPRESION 
$impresion_query = "INSERT INTO ORDENESIMPRESION (DOCUMENTO,FACTURA,MESA,FECHA,ARTICULO,DESCRIPCION,SECUENCIA,CANTIDAD,TIPO_ORDEN,TIPO_COCINA,TIPO_AREA,CAMARERO) VALUES ('{$header_data->factura}','{$header_data->factura}','{$header_data->mesa}','{$header_data->fecha}','{$v->id}','{$v->descripcion}','{$docum}','{$v->cantidad}','{$tipo_orden}','{$tipo_cocina}','{$v->ar_tipoarea}','{$header_data->mo_codigo}')";
Comando::noRecordSet($pdo,$impresion_query);


  if($guarnicion != ''){
    $guarnicion_query = "INSERT INTO IVBDDETE 
    (DE_TIPO,DE_FACTURA,DE_FECHA,DE_NOMBRE,IM_CODIGO,DE_TBIS,DE_LEY,DE_ITBIS,CL_CODIGO,DE_CAJA,DE_TURNO,MA_CODIGO,MO_CODIGO,
    DE_POSICIO,DE_DESCRI,DE_CANTID,AR_CODIGO,DE_PRECIO,DE_PRECIO2,DE_COSTO,
    AC_CODIGO,AC_CANTID,DE_DOCUM,AL_CODIGO,DE_USUARIO,DE_FECENT,DE_FECSAL,MA_DEPEN,
    DE_PR1,DE_PR2,DE_PR3,AR_codigo2)
      VALUES
      ('{$header_data->tipo}','{$header_data->factura}','{$header_data->fecha}','{$header_data->nombre_cliente}','{$header_data->tipo_comp}',1,1,0,'{$header_data->codigo_cliente}','{$header_data->caja}','{$header_data->turno}','{$header_data->mesa}','{$header_data->mo_codigo}',
      0,'{$v->guarnicion_nombre}',0,'',0,0,0,
      '',0,'{$docum}','{$header_data->al_codigo}','','{$header_data->fecha_entrada}','{$header_data->fecha_salida}','',
      0,0,0,'')";

      Comando::noRecordSet($pdo,$guarnicion_query);
    //echo '<br>';

    // IMPRESION 
    $impresion_query = "INSERT INTO ORDENESIMPRESION (DOCUMENTO,FACTURA,MESA,FECHA,ARTICULO,DESCRIPCION,SECUENCIA,CANTIDAD,TIPO_ORDEN,TIPO_COCINA,TIPO_AREA,CAMARERO) VALUES ('{$header_data->factura}','{$header_data->factura}','{$header_data->mesa}','{$header_data->fecha}','','{$v->guarnicion_nombre}','{$docum}','0','{$tipo_orden}','{$tipo_cocina}','{$v->ar_tipoarea}','{$header_data->mo_codigo}')";
    Comando::noRecordSet($pdo,$impresion_query);
  }

  if($nota != ''){
    $guarnicion_query = "INSERT INTO IVBDDETE 
    (DE_TIPO,DE_FACTURA,DE_FECHA,DE_NOMBRE,IM_CODIGO,DE_TBIS,DE_LEY,DE_ITBIS,CL_CODIGO,DE_CAJA,DE_TURNO,MA_CODIGO,MO_CODIGO,
    DE_POSICIO,DE_DESCRI,DE_CANTID,AR_CODIGO,DE_PRECIO,DE_PRECIO2,DE_COSTO,
    AC_CODIGO,AC_CANTID,DE_DOCUM,AL_CODIGO,DE_USUARIO,DE_FECENT,DE_FECSAL,MA_DEPEN,
    DE_PR1,DE_PR2,DE_PR3,AR_codigo2)
      VALUES
      ('{$header_data->tipo}','{$header_data->factura}','{$header_data->fecha}','{$header_data->nombre_cliente}','{$header_data->tipo_comp}',1,1,0,'{$header_data->codigo_cliente}','{$header_data->caja}','{$header_data->turno}','{$header_data->mesa}','{$header_data->mo_codigo}',
      0,'{$v->nota}',0,'',0,0,0,
      '',0,'{$docum}','{$header_data->al_codigo}','','{$header_data->fecha_entrada}','{$header_data->fecha_salida}','',
      0,0,0,'')";

  Comando::noRecordSet($pdo,$guarnicion_query);

      // IMPRESION 
      $impresion_query = "INSERT INTO ORDENESIMPRESION (DOCUMENTO,FACTURA,MESA,FECHA,ARTICULO,DESCRIPCION,SECUENCIA,CANTIDAD,TIPO_ORDEN,TIPO_COCINA,TIPO_AREA,CAMARERO) VALUES ('{$header_data->factura}','{$header_data->factura}','{$header_data->mesa}','{$header_data->fecha}','','{$v->nota}','{$docum}','0','{$tipo_orden}','{$tipo_cocina}','{$v->ar_tipoarea}','{$header_data->mo_codigo}')";
      Comando::noRecordSet($pdo,$impresion_query);

    //echo '<br>';
  }
}
$updateMesa = "UPDATE PVBDMESA
SET MA_FECENT='$header_data->fecha_entrada',HE_NOMCLI='$header_data->nombre_cliente',MA_OCUPA='',MO_CODIGO='$header_data->mo_codigo' 
WHERE MA_CODIGO='$header->mesa'";
Comando::noRecordSet($pdo,$updateMesa);
$pdo->commit();

// ORDENESIMPRESION
// [DOCUMENTO] [varchar](10) NOT NULL,
// [FACTURA] [varchar](10) NOT NULL,
// [MESA] [varchar](5) NOT NULL,
// [FECHA] [datetime] NOT NULL,
// [ARTICULO] [char](20) NOT NULL,
// [DESCRIPCION] [varchar](40) NOT NULL,
// [SECUENCIA] [varchar](10) NOT NULL,
// [CANTIDAD] [numeric](14, 2) NOT NULL,
// [TIPO_ORDEN] [varchar](12) NOT NULL,
// [TIPO_COCINA] [varchar](1) NOT NULL,
// [TIPO_AREA] [int] NOT NULL,
// [CAMARERO] [varchar](5) NOT NULL,

?>
<script>
 window.location.href = 'pages/mesas.php';
</script>

