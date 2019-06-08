<?php

class Invoice{
    
    public $con;
    public $success = '';
    public $error = '';
    
    public function __construct($pdo){
        $this->con = $pdo;    
    }

    public function list($pagina,$fecha1,$fecha2){
        
        // Formato recibido d/m/Y
        if(!empty($fecha1)){
            $fecha1 = explode('/',$fecha1);
            $fecha1 = $fecha1[2].$fecha1[1].$fecha1[0];
        }else{
            $fecha1 = date('Ymd');
        }
       
        if(!empty($fecha2)){
            $fecha2 = explode('/',$fecha2);
            $fecha2 = $fecha2[2].$fecha2[1].$fecha2[0];
        }else{
            $fecha2 = date('Ymd');
        }

        $where_codigo = '';

        $query_total = $this->con->query("SELECT COUNT(he_factura) as total FROM IVBDHEPE  WHERE HE_FECHA>='{$fecha1}' and HE_FECHA<='{$fecha2}'");
        $total_registros = $query_total->fetch(PDO::FETCH_ASSOC)['total'];

        $total = $total_registros;
        $muestra = 500;
        $total_por_pagina = ceil($total/$muestra);
        $desde = ($pagina-1) * $muestra;
        $hasta = $pagina * $muestra; 
        $query = $this->con->query("WITH OrderedTable AS
                                (
                                SELECT 
                                CONVERT(VARCHAR,he_fecha,101) AS FECHA,
                                he_factura AS NRO_FACTURA,
                                cl_codigo AS CLIENTE,
                                he_nombre AS NOMBRE,
                                HE_RNC AS RNC,
                                he_monto AS MONTO_BRUTO,
                                he_desc AS DESCUENTO,
                                (he_monto-he_desc) as SUB_TOTAL,
                                he_itbis AS ITBIS,
                                he_totley AS PORCIENTO_DE_LEY,
                                he_neto  AS VALOR_NETO, ROW_NUMBER() OVER (ORDER BY he_factura) as rowNumber
                                    FROM IVBDHEPE WHERE  HE_FECHA>='{$fecha1}' and HE_FECHA<='{$fecha2}' {$where_codigo}
                                )
                            SELECT * FROM OrderedTable
                            WHERE rowNumber > {$desde} AND rowNumber <= {$hasta}
                        "); 

        $datos = $query->fetchAll(PDO::FETCH_ASSOC);

        return [
            'datos'=>$datos,
            'total_registros'=>$total,
            'muestra'=>$muestra,
            'total_paginas'=>$total_por_pagina
        ];
    }

    public function reporte($fecha1,$fecha2){
        if(!empty($fecha1)){
            $fecha1 = explode('/',$fecha1);
            $fecha1 = $fecha1[2].$fecha1[1].$fecha1[0];
        }else{
            $fecha1 = date('Ymd');
        }
       
        if(!empty($fecha2)){
            $fecha2 = explode('/',$fecha2);
            $fecha2 = $fecha2[2].$fecha2[1].$fecha2[0];
        }else{
            $fecha2 = date('Ymd');
        }

        $query = $this->con->query("SELECT 
        CONVERT(VARCHAR,he_fecha,101) AS FECHA,
        he_factura AS NRO_FACTURA,
        cl_codigo AS CLIENTE,
        he_nombre AS NOMBRE,
        HE_RNC AS RNC,
        he_monto AS MONTO_BRUTO,
        he_desc AS DESCUENTO,
        (he_monto-he_desc) as SUB_TOTAL,
        he_itbis AS ITBIS,
        he_totley AS PORCIENTO_DE_LEY,
        he_neto  AS VALOR_NETO, ROW_NUMBER() OVER (ORDER BY he_factura) as rowNumber
            FROM IVBDHEPE WHERE  HE_FECHA>='{$fecha1}' and HE_FECHA<='{$fecha2}'");

        $datos = $query->fetchAll(PDO::FETCH_ASSOC);

        return [
            'datos'=>$datos,
        ];

    }

}