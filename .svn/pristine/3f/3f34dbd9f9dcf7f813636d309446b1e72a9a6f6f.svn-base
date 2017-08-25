<?php
namespace modelo;
session_start();
require_once($_SESSION['ruta'].'entorno/Conexion.php');
class UnidadMedida {
    
    public $conexion = null;
    private $whereAnd = null;
    private $condicion = null;
    
    private $idUnidadMedida;
    private $unidadMedida;
    private $fechaCreacion;
    private $fechaModificacion;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $estado;
    
    
    public function __construct(\entidad\UnidadMedida $unidadMedida){
        $this->idUnidadMedida = $unidadMedida->getIdUnidadMedida();
        $this->unidadMedida = $unidadMedida->getUnidadMedida();
        $this->estado = $unidadMedida->getEstado();
        $this->idUsuarioCreacion = $unidadMedida->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $unidadMedida->getIdUsuarioModificacion();
        $this->fechaCreacion = $unidadMedida->getFechaCreacion();
        $this->fechaModificacion = $unidadMedida->getFechaModificacion();
        
        $this->conexion =  new \Conexion();
    }
    public function consultar(){
       $this->obtenerCondicion();
       $sentenciaSql = "
                        SELECT
                            um.id_unidad_medida
                            ,um.unidad_medida
                            ,um.estado
                         FROM
                            facturacion_inventario.unidad_medida as um
                           $this->condicion
                    "; 
       $this->conexion->ejecutar($sentenciaSql);
       $contador = 0;
       while($fila = $this->conexion->obtenerObjeto() ){
            $retorno[$contador]['idUnidadMedida'] = $fila->id_unidad_medida;
            $retorno[$contador]['unidadMedida'] = $fila->unidad_medida;
            $retorno[$contador]['estado'] = $fila->estado;
            $contador++;
        }
        return $retorno;
       
    }
    
    public function adicionar(){
        $sentenciaSql = "
            INSERT INTO
                facturacion_inventario.unidad_medida
                (
                      unidad_medida
                    , estado
                    , id_usuario_creacion
                    , id_usuario_modificacion
                    , fecha_creacion
                    , fecha_modificacion
                )
            VALUES
                (
                      '$this->unidadMedida'
                    , TRUE
                    , $this->idUsuarioCreacion
                    , $this->idUsuarioModificacion
                    , NOW()
                    , NOW()
                )
        ";
        $this->conexion->ejecutar($sentenciaSql);
        return $this->obtenerMaximo();
    }
           
    public function obtenerMaximo(){
        $sentenciaSql = "
            SELECT
                MAX(id_unidad_medida) AS id_unidad_medida
             FROM
                facturacion_inventario.unidad_medida
        "; 
       $this->conexion->ejecutar($sentenciaSql);
       $fila = $this->conexion->obtenerObjeto();
       return $fila->id_unidad_medida;
    }
    
    function obtenerCondicion() {
        $this->condicion = "";
        $this->whereAnd = " WHERE ";
        
        if ($this->idUnidadMedida != "" && $this->idUnidadMedida != "null") {
            $this->condicion = $this->condicion.$this->whereAnd." um.id_unidad_medida = $this->idUnidadMedida";
            $this->whereAnd = " AND ";
        }
        if ($this->unidadMedida != "" && $this->unidadMedida != "null" && $this->unidadMedida != null) {
            $this->condicion = $this->condicion.$this->whereAnd." um.unidad_medida LIKE '%$this->unidadMedida%'";
            $this->whereAnd = " AND ";
        }
        if ($this->estado != "" && $this->estado != "null") {
            $this->condicion = $this->condicion.$this->whereAnd." um.estado = $this->estado";
            $this->whereAnd = " AND ";
        }
        
    }
}

?>
