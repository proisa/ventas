<?php

class Client{
    
    public $con;
    public $success = '';
    public $error = '';
    
    public function __construct($pdo){
        $this->con = $pdo;    
    }

    public function list(){
        $query = $this->con->query("SELECT TOP(300) CL_CODIGO,CL_NOMBRE,CL_DIREC1,CL_TELEF1,ZO_CODIGO,CL_LIMCRE FROM CCBDCLIE WHERE CL_ACTIVO <> 'D' AND COD_EMPR = 1 AND COD_SUCU = 1 ORDER BY CL_ID DESC ");
        $datos = $query->fetchAll(PDO::FETCH_ASSOC);
        $total_registros = $query->rowCount();
        return [
            'datos'=>$datos,
            'total_registros'=>$total_registros
        ];
    }

    public function getCode(){
        $code = "BEGIN TRAN
        SET NOCOUNT ON
        DECLARE @CODNEW VARCHAR(20),@COD_EMPR INT=1 ,@COD_SUCU INT=1 
        WHILE 1=1 BEGIN
        UPDATE CCBDPROC SET cliente = cliente + 1 WHERE COD_EMPR=@COD_EMPR 
        SELECT @CODNEW = CAST(cliente AS VARCHAR) FROM CCBDPROC WHERE COD_EMPR=@COD_EMPR 
        IF NOT EXISTS(SELECT 1 FROM CCBDCLIE WHERE COD_EMPR=@COD_EMPR AND CL_CODIGO = @CODNEW) BREAK
        END
        COMMIT TRAN
        SELECT @CODNEW as cliente";

        // $query = $this->con->query();
        $queryCode = $this->con->query($code);
        $codigo = $queryCode->fetch(PDO::FETCH_ASSOC);
        return $codigo['cliente'];
    }


    public function getClient($codigo){
        $queryCli = $this->con->prepare("SELECT CL_CODIGO,CL_NOMBRE,CL_DIREC1,CL_TELEF1,ZO_CODIGO,CL_LIMCRE,CL_DETALLE,CL_FOTO,CL_EMAIL FROM CCBDCLIE WHERE CL_CODIGO = :codigo");
        $queryCli->bindValue(':codigo',$codigo);
        $queryCli->execute();
        $datosCli = $queryCli->fetch(PDO::FETCH_ASSOC);
        return $datosCli;
    }

    public function create(array $data){
        try {
            $this->con->beginTransaction();
            
            $codigo = empty($data['codigo']) ? $this->getCode() : $data['codigo']; 

            $insert = $this->con->prepare("INSERT INTO CCBDCLIE (CL_CODIGO,CL_NOMBRE,CL_DIREC1,CL_TELEF1,ZO_CODIGO,CL_LIMCRE,COD_SUCU,CL_DETALLE,CL_FOTO) VALUES (:codigo,:nombre,:direccion,:telefono,:zona,:limite,1,:detalle,:foto)");
            $insert->bindValue(':codigo',$codigo);
            $insert->bindValue(':nombre',$data['nombre']);
            $insert->bindValue(':direccion',$data['direccion']);
            $insert->bindValue(':telefono',$data['telefono']);
            $insert->bindValue(':zona',$data['zona']);
            $insert->bindValue(':limite',$data['limite']);
            $insert->bindValue(':detalle',$data['detalle']);
            $insert->bindValue(':foto',$data['foto']);
    
            $insert->execute();
            $this->con->commit();

            return true;
            
        }catch(Exception $e){
            //An exception has occured, which means that one of our database queries
            //failed.
            //Print out the error message.
            //Rollback the transaction.
        
            if($this->con->inTransaction()){
                $this->con->rollBack();
            }

            $this->error = $e->getMessage();
            return false;
        }
    }

    public function edit(array $data){
        try {
            $this->con->beginTransaction();
            $update = $this->con->prepare("UPDATE CCBDCLIE SET CL_NOMBRE = :nombre,CL_DIREC1 = :direccion,CL_TELEF1 = :telefono,ZO_CODIGO = :zona,CL_LIMCRE = :limite, CL_DETALLE = :detalle, CL_FOTO = :foto WHERE CL_CODIGO = :codigo AND COD_EMPR = 1 AND COD_SUCU = 1");
            $update->bindValue(':nombre',$data['nombre']);
            $update->bindValue(':direccion',$data['direccion']);
            $update->bindValue(':telefono',$data['telefono']);
            $update->bindValue(':zona',$data['zona']);
            $update->bindValue(':limite',$data['limite']);
            $update->bindValue(':codigo',$data['codigo']);
            $update->bindValue(':detalle',$data['detalle']);
            $update->bindValue(':foto',$data['foto']);
            $update->execute();
        
            $this->con->commit();
            return true;

        }catch(Exception $e){
            //An exception has occured, which means that one of our database queries
            //failed.
            //Print out the error message.
            echo $e->getMessage();
            //Rollback the transaction.
        
            if($this->con->inTransaction()){
                $this->con->rollBack();
            }

            return false;
        }
    }

    public function delete($codigo){
        try {
            $this->con->beginTransaction();    
            $update = $this->con->prepare("UPDATE CCBDCLIE SET CL_ACTIVO = 'D' WHERE CL_CODIGO = :codigo");
            $update->bindValue(':codigo',$codigo);
            $update->execute();
            $this->con->commit();
            return true;
        }catch(Exception $e){
            //An exception has occured, which means that one of our database queries
            //failed.
            //Print out the error message.
            echo $e->getMessage();
            //Rollback the transaction.
        
            if($this->con->inTransaction()){
                $this->con->rollBack();
            }

            return false;
        }    
    }

    public function grafica($codigo){

        $code = "SET NOCOUNT ON 
        select cl_codigo as Codigo_Cliente,sum(he_neto) as Monto_Ventas,YEAR(he_fecha) as Anio from IVBDHEPE 
        where cl_codigo= '".$codigo."'
        group by CL_CODIGO,YEAR(he_fecha)
        order by cl_codigo,YEAR(he_fecha)";

        // $query = $this->con->query();
        //echo $code;
        $query = $this->con->query($code);
        $datos = $query->fetchAll(PDO::FETCH_ASSOC);
        
        if($query->rowCount() != 0){
            return $datos;   
        }else{
            return false;
        }
       
    }

    public function estado($codigo){
        $code = "SET NOCOUNT ON 
        EXEC FACTURAS_PENDIENTES_CLIENTE '".$codigo."'
        ";
        // $query = $this->con->query();
        //echo $code;
        $query = $this->con->query($code);
        $datos = $query->fetchAll(PDO::FETCH_ASSOC);
        if($query->rowCount() != 0){
            return $datos;   
        }else{
            return false;
        }
    }

}