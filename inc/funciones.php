<?php

function url_base(){
    return 'http://10.0.0.232/ventas';
}

function print_pre($arr){
    echo '<pre>';
        print_r($arr);   
    echo '</pre>';   
}


function selected($a,$b){
    if($a == $b){
        return 'selected';
    }
}

function dateFormat($fecha){
    $d = new DateTime($fecha);
    return $d->format('d-m-Y');
}

?>