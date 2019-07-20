<?php 
session_start();
ini_set('display_errors',1);
$SERVER_NAME = "dbserver";
$DATABASE="facfoxsql";
$DB_USER="sa";
$DB_PASSWORD='pr0i$$a';
try
{
  $pdo = new PDO("sqlsrv:server=$SERVER_NAME;DATABASE=$DATABASE",$DB_USER,$DB_PASSWORD);
  $pdo->query("SET NAMES latin1");
  $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION ); 
  $pdo->beginTransaction();

}catch(PDOException $e){
    
  if($pdo->inTransaction()){
        $pdo->rollBack();
  }
  echo $e->getMessage();
    
    //die();
} 



