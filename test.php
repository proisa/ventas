<?php

require 'inc/conexion.php';
require 'inc/funciones.php';
require 'clases/Comando.php';
require 'clases/PrintUtil.php';
require 'clases/Config.php';


$anio1 = $_GET['anio1'];
$anio2 = $_GET['anio2'];

$fecha1 = $anio1.'-01-01';
$fecha2 = $anio1.'-12-31';

$fecha3 = $anio2.'-01-01';
$fecha4 = $anio2.'-12-31';


$query = "SELECT *,
CASE WHEN MES = 1 THEN 'Enero'
     WHEN MES = 2 THEN 'Febrero'
     WHEN MES = 3 THEN 'Marzo'
     WHEN MES = 4 THEN 'Abril'
     WHEN MES = 5 THEN 'Mayo'
     WHEN MES = 6 THEN 'Junio'
     WHEN MES = 7 THEN 'Julio'
     WHEN MES = 8 THEN 'Agosto'
     WHEN MES = 9 THEN 'Setiempbre'
     WHEN MES =10 THEN 'Octubre'
     WHEN MES =11 THEN 'Noviembre'
     WHEN MES =12 THEN 'Diciembre' END AS MESL
FROM (
SELECT 
   MONTH(HE_FECHA) AS MES,
   SUM(CASE WHEN YEAR(HE_FECHA)='$anio1' THEN HE_NETO ELSE 0000000000.00 END) AS HE_NETO1,
   SUM(CASE WHEN YEAR(HE_FECHA)='$anio2' THEN HE_NETO ELSE 0000000000.00 END) AS HE_NETO2,
   SUM(HE_NETO) AS HE_NETO
FROM IVBDHEPE 
WHERE COD_EMPR=1 AND ( (HE_FECHA >= '$fecha1' AND HE_FECHA <= '$fecha2') OR (HE_FECHA >= '$fecha3' AND HE_FECHA < '$fecha4') )
GROUP BY MONTH(HE_FECHA)
) X
ORDER BY 1";

$comando = Comando::recordSet($pdo,$query);

// $select = "SELECT * FROM ORDENESIMPRESION";
// //$select = "SELECT ID,COD_EMPR,COD_SUCU,USUARIO,CLAVE,NIVEL FROM CONTASEG WHERE USUARIO = 'juan' AND CLAVE = '123' AND COD_EMPR = 1 AND COD_SUCU = 1";
// $comando1 = Comando::recordSet($pdo,$select);
// //$comando2 = Comando::noRecordSet($pdo,$delete);
// print_pre($comando1);




print_pre($comando);
?>
