<?php 
require('../inc/conexion.php');
require('../inc/funciones.php');
require('../clases/Auth.php');
require('../clases/Comando.php');

$auth = new Auth($pdo);
// Login 
if($auth->login($_POST['usuario'],$_POST['clave'])){
    header('Location: ../pages/mesas.php');

    // if($auth->nivel == 'G'){
    //     header('Location: ../pages/mesas.php');
    // }else{
    //     header('Location: ../pages/camareros.php');
    // }

}else{
    header('Location: ../login.php?auth=failed');
}

// Logout
if(isset($_GET['logout']) && $_GET['logout'] == true){
    $auth->logOut();
    header('Location: ../login.php');
}

