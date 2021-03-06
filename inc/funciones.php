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

function dateOrderPass($hora){
    $hora_actual = date('H:i:s');
    // Difference in year, month, days 
    $time1 = date_create($hora); 
    $time2 = date_create($hora_actual); 
    $interval_hour = date_diff($time1, $time2); 

    return "{$interval_hour->h} horas, {$interval_hour->i} minutos y {$interval_hour->s} segundos";
}


function getNumeroConCero($numero){

    $cero = '0';
    if($numero < 10){
        return $numero = $cero.$numero;
    }
    return $numero;

}

function clearDate2($fecha){
    $ffecha = $fecha;
    $ffecha = explode('/',$ffecha);
    $ffecha = $ffecha[2].$ffecha[1].$ffecha[0];
    return $ffecha;
}

function clearDate($fecha){
    $ffecha = $fecha;
    $ffecha = explode('/',$ffecha);
    $ffecha = $ffecha[2].$ffecha[1].$ffecha[0];
    return $ffecha;
}

function getDateString($date){
    // Sabado 2 de Marzo del 2019
    $dias = [
        'Monday'=>'Lunes',
        'Tuesday'=>'Martes',
        'Wednesday'=>'Miércoles',
        'Thursday'=>'Jueves',
        'Friday'=>'Viernes',
        'Saturday'=>'Sábado',
        'Sunday'=>'Domingo'
    ];

    $meses = [
        '01'=>'Enero',
        '02'=>'Febrero',
        '03'=>'Marzo',
        '04'=>'Abril',
        '05'=>'Mayo',
        '06'=>'Junio',
        '07'=>'Julio',
        '08'=>'Agosto',
        '09'=>'Septiembre',
        '10'=>'Octubre',
        '11'=>'Noviembre',
        '12'=>'Diciembre',
    ];

    $dia_text = date('l',strtotime($date));
    $dia = date('d',strtotime($date));
    $mes = date('m',strtotime($date));
    $anio = date('Y',strtotime($date));

    return $dias[$dia_text].' '.$dia.' de '.$meses[$mes].' del '.$anio;    
}

function redirect($uri = '')
	{
        echo '<script>';
            echo "window.location.href = ' ".url_base().'/'.$uri." '; ";
        echo '</script>';
        exit();
	}

?>