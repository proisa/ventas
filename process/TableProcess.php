<?php 
header('Content-type: text/json');
require('../inc/conexion.php');
require('../inc/funciones.php');
require('../clases/Comando.php');

$mesa = trim($_POST['mesa']);
$camarero = trim($_POST['camarero']);

$select = "SELECT MA_OCUPA,MO_CODIGO,he_nomcli,LETRA FROM PVBDMESA WHERE MA_CODIGO='{$mesa}'";
$resSelect = Comando::recordSet($pdo,$select)[0];



if($resSelect['MA_OCUPA'] == '*' && $resSelect['MO_CODIGO'] != $_SESSION['mo_codigo']){
    echo json_encode(['resp'=>'Error','msj'=>"El numero de mesa {$mesa} esta siendo utilizada"]);
    exit();
}

$data = [];

if(isset($_POST["dividir"])){

    if(empty(trim($resSelect['LETRA']))){
        $queryUp = "UPDATE PVBDMESA SET LETRA='01',MA_COBRAR=1 WHERE MA_CODIGO='{$mesa}'";
        Comando::noRecordSet($pdo,$queryUp);
        $mesa = $mesa.'A';
    }else{

        if($resSelect['LETRA'] > 26){
            echo json_encode(['resp'=>'Error','msj'=>'Numero de submesas agotado','data'=>'']);
            exit();
        }

        $letra = getNumeroConCero($resSelect['LETRA']+1); 
        $selecLetra = "SELECT LE_NOMBRE FROM PVBDLETRA WHERE LE_CODIGO = '{$letra}'";
        $resLetra = Comando::recordSet($pdo,$selecLetra)[0];

        $queryUp = "UPDATE PVBDMESA SET LETRA='{$letra}',MA_COBRAR=MA_COBRAR+1 WHERE MA_CODIGO='{$mesa}'";
        Comando::noRecordSet($pdo,$queryUp);
        $mesa = $mesa.$resLetra['LE_NOMBRE'];
    }

    $query = "UPDATE PVBDMESA SET MO_CODIGO='{$camarero}',MA_OCUPA='*' WHERE MA_CODIGO='{$mesa}'";
    Comando::noRecordSet($pdo,$query);
    $data = [
        'cliente'=>'',
        'mesa'=>$mesa,
        'mesa_padre'=>trim($_POST['mesa'])
    ];

    echo json_encode(['resp'=>'OK','msj'=>'La mesa ha sido seleccionada','data'=>$data]);
    $pdo->commit();
    exit();

}

if(!empty(trim($resSelect['LETRA']))){
    echo json_encode(['resp'=>'submesas','mesa'=>$mesa]);
    exit();
}

$query = "UPDATE PVBDMESA SET MO_CODIGO='{$camarero}',MA_OCUPA='*' WHERE MA_CODIGO='{$mesa}'";
if(Comando::noRecordSet($pdo,$query)){
    $data = [
        'cliente'=>$resSelect['he_nomcli'],
        'mesa'=>$mesa
    ];
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