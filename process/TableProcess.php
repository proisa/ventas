<?php 
header('Content-type: text/json');
require('../inc/conexion.php');
require('../inc/funciones.php');
require('../clases/Comando.php');

$mesa = trim($_POST['mesa']);
$camarero = trim($_POST['camarero']);

$select = "SELECT MA_OCUPA,MO_CODIGO,he_nomcli FROM PVBDMESA WHERE MA_CODIGO='{$mesa}'";
$resSelect = Comando::recordSet($pdo,$select)[0];

if($resSelect['MA_OCUPA'] == '*' && $resSelect['MO_CODIGO'] != $_SESSION['mo_codigo']){
    echo json_encode(['resp'=>'Error','msj'=>"El numero de mesa {$mesa} esta siendo utilizada"]);
    exit();
}

$data = [];

$query = "UPDATE PVBDMESA SET MO_CODIGO='{$camarero}',MA_OCUPA='*' WHERE MA_CODIGO='{$mesa}'";
if(Comando::noRecordSet($pdo,$query)){
    $data = ['cliente'=>$resSelect['he_nomcli']];
    echo json_encode(['resp'=>'OK','msj'=>'La mesa ha sido seleccionada','data'=>$data]);
}else{
    echo json_enconde(['resp'=>'Error','msj'=>'Ocurrio un error al ocupar la mesa']);
}
$pdo->commit();
// IF EMPTY(PVBDMESA.MO_CODIGO)
// DN_MOZO=MOZOS
// ELSE
//  DN_MOZO=PVBDMESA.MO_CODIGO
// ENDIF  
// DN_OCUPA="*"

// IF !COMANDO("UPDATE PVBDMESA SET MO_CODIGO=?DN_MOZO,MA_OCUPA=?DN_OCUPA WHERE MA_CODIGO=?MESA","TMP",M.CA)
//     =MESSAGEBOX(MESSAGE())
//     RETURN
// ENDIF;