<?php

function print_pre($arr){
    //if(isset($_GET) && $_GET['debug']){
        echo '<pre>';
            print_r($arr);   
        echo '</pre>';  
    //}
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



function getNumeroConCero($numero){

    $cero = '0';
    if($numero < 10){
        return $numero = $cero.$numero;
    }
    return $numero;

}

?>