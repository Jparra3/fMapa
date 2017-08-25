<?php
require_once('configuracion.php');
class Conexion{
    private $conn = null;
    private $recordSet = null;
    
    function __construct(){
        $this->conn = new PDO("pgsql:host=".SERVER_NAME_DATABASE.";port=".PORT_DATABASE.";dbname=".DATABASE.";user=".USER_DATABASE.";password=".PASSWORD_DATABASE);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    public function ejecutar($sentenciaSql){
        $this->recordSet = $this->conn->query($sentenciaSql);
        if($this->recordSet == false)
            throw new Exception('Error ejecutando la sentencia Sql'.$this->conn->error, E_USER_ERROR);
    }   
    public function obtenerObjeto(){
        return $this->recordSet->fetch(PDO::FETCH_OBJ);
    }
    public function obtenerObjetoCompleto(){
        return $this->recordSet->fetchAll(PDO::FETCH_OBJ);
    }
    public function obtenerNumeroRegistros(){
        return $this->recordSet->rowCount();
    }
    public function buscarRegistro($numeroFila){
        $this->recordSet->fetch(PDO::FETCH_OBJ, PDO::FETCH_ORI_ABS, $numeroFila );
    }
    public function iniciarTransaccion(){
        $this->conn->beginTransaction();
    }
    public function cancelarTransaccion(){
        $this->conn->rollBack();
    }
    public function confirmarTransaccion(){
        $this->conn->commit();
    }
    function __destruct(){
         if($this->recordSet);
            $this->recordSet = null;
            if($this->conn);
                $this->conn = null;
                
            }
    }
?>
