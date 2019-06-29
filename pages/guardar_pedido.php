<?php 
echo '<pre>';
print_r(json_decode($_POST['header']));
print_r(json_decode($_POST['data']));
echo '</pre>';

$header = json_decode($_POST['header']);
$detalle = json_decode($_POST['data']);

$fecha = date('Y-m-d H:m:s');

$header_query = "INSERT IVBDHETE 
(he_tipo,ma_codigo,he_factura,he_fecha,he_monto,he_monto2,he_persona,he_netoncf,he_brutncf,he_descncf,
 HE_LEY,He_imp,he_desc,he_fecent,HE_FECSAL,IM_CODIGO,cl_codigo,he_itbis,he_TOTLEY,he_neto,he_neto2,he_netoUS,
   he_netoEU,he_TASAUS,he_TASAEU,AL_CODIGO,he_caja,he_turno,he_valdesc,he_tipdes,MO_CODIGO,HE_NOMBRE,HE_USUARIO,MA_DEPEN)
VALUES
  (?TIPO,?DN_MESA,?DN_FACTURA,{$fecha},?DN_MONTO,?DN_MONTO,?DN_PERSONA,?DN_NETONCF,?DN_BRUTNCF,?DN_DESCNCF,
  ?DN_LEY,?DN_IMP,?DN_DESC,?DN_FECENT,?DN_FECSAL,?DN_IM_CODIGO,?DN_CL_CODIGO,{$header->itbis},?DN_TOTLEY,?DN_NETO,?DN_NETO2,?DN_NETOUS,
  ?DN_NETOEU,?DN_TASAUS,?DN_TASAEU,?DN_AL_CODIGO,?DN_CAJA,?DN_TURNO,?DN_VALDESC,?DN_TIPDES,?DN_MO_CODIGO,?DN_NOMCLI,?USUARIO1,?DN_MESADEP)";


foreach($detalle as $k => $v){
    echo $v->id.'<br>';
}


  $detalle_query = "INSERT INTO IVBDDETE 
  (DE_TIPO,DE_FACTURA,DE_FECHA,DE_NOMBRE,IM_CODIGO,DE_TBIS,DE_LEY,DE_ITBIS,CL_CODIGO,DE_CAJA,DE_TURNO,MA_CODIGO,MO_CODIGO,
   DE_POSICIO,DE_DESCRI,DE_CANTID,AR_CODIGO,DE_PRECIO,DE_PRECIO2,DE_COSTO,
   AC_CODIGO,AC_CANTID,DE_DOCUM,AL_CODIGO,DE_USUARIO,DE_FECENT,DE_FECSAL,MA_DEPEN,
   DE_PR1,DE_PR2,DE_PR3,AR_codigo2)
    VALUES
    (?TIPO,?DN_FACTURA,?DN_FECHA,?DN_NOMCLI,?DN_IM_CODIGO,?DN_CHECK2,?DN_CHECK1,?DN_IMP,?DN_CL_CODIGO,?DN_CAJA,?DN_TURNO,?DN_MESA,?DN_MO_CODIGO,
    ?temporal.posicion,?temporal.descri,?temporal.cantid,?temporal.codigo,?temporal.precio,?temporal.precio2,?TEMPORAL.COSTO,
    ?TEMPORAL.ACOMPANA,?TEMPORAL.ac_cantid,?TEMPORAL.D_DOCUM,?DN_AL_CODIGO,?USUARIO1,?DN_FECENT,?DN_FECSAL,?DN_MESADEP,
    ?TEMPORAL.PR1,?TEMPORAL.PR2,?TEMPORAL.PR3,?TEMPORAL.CODIGO2)";

?>