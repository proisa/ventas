<?php 
session_start();
$SERVER_NAME = "dbserver";
$DATABASE="facfoxsql";
$DB_USER="sa";
$DB_PASSWORD='pr0i$$a';
try
{
  $pdo = new PDO("sqlsrv:server=$SERVER_NAME;DATABASE=$DATABASE",$DB_USER,$DB_PASSWORD);
  $pdo->query("SET NAMES latin1");
  $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION ); 

}catch(PDOException $e){
    echo $e->getMessage();
    //die();
} 



