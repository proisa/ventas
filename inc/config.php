<?php

function url_base(){
    //return 'http://localhost/ventas';
    return 'http://10.0.10.25/ventas';
}

function getConfig($pdo){

    $query = "SELECT ITBIS,D_LEY FROM FABDPROC WHERE cod_sucu=1 AND cod_empr=1";
    $result = Comando::recordSet($pdo,$query);

    $_SESSION['itbis'] = $result[0]['ITBIS'];
    $_SESSION['ley'] = $result[0]['D_LEY'];
    $url = url_base();
    echo "
        <script>
            sessionStorage.removeItem('itbis');
            sessionStorage.removeItem('ley');
            sessionStorage.removeItem('url_base');
            sessionStorage.setItem('itbis','".$result[0]['ITBIS']."');
            sessionStorage.setItem('ley','".$result[0]['D_LEY']."');
            sessionStorage.setItem('url_base','".$url."');
        </script>
    ";

}

?>