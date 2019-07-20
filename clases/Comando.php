<?php
class Comando{

    public static $data;

    public static function noRecordSet($cnn,$tsql){
        if($cnn == NULL){
            return 'Error al conectar';
        }

        try {
            $response = $cnn->query($tsql);
            return true;
        }catch(PDOException $e){
            echo substr($e->getMessage(), strrpos($e->getMessage(), "[SQL Server]")+12);
        }

        return false;

    }

    public static function recordSet($cnn,$tsql){
        if($cnn == NULL){
            return 'Error al conectar';
        }

        $response = $cnn->query($tsql);
        if($response->rowCount() != 0){
            return  $response->fetchAll(PDO::FETCH_ASSOC);
        }else{
            return false;
        }
    }



}


?>