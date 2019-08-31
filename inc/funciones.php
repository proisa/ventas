<?php

function url_base(){
    //return 'http://localhost/ventas';
    return 'http://10.0.0.232/ventas';
}

function print_pre($arr){
    //if(isset($_GET) && $_GET['debug']){
        echo '<pre>';
            print_r($arr);   
        echo '</pre>';  
    //}
}

function echo_ln($content){
    echo $content;
}

function selected($a,$b){
    if($a == $b){
        return 'selected';
    }
}

function dateFormat($fecha,$tipo = 'fecha'){
    $d = new DateTime($fecha);
    if($tipo == 'hora'){
        return $d->format('H:i:s');
    }
    return $d->format('d-m-Y');
}

function getItbis($pdo){

    $query = "SELECT ITBIS,D_LEY FROM FABDPROC WHERE cod_sucu=1 AND cod_empr=1";
    $result = Comando::recordSet($pdo,$query);

    $_SESSION['itbis'] = $result[0]['ITBIS'];
    $_SESSION['ley'] = $result[0]['D_LEY'];

    echo "
        <script>
            sessionStorage.removeItem('itbis');
            sessionStorage.removeItem('ley');
            sessionStorage.setItem('itbis','".$result[0]['ITBIS']."');
            sessionStorage.setItem('ley','".$result[0]['D_LEY']."');
        </script>
    ";

}

?>