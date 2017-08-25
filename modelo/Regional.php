<?php
namespace modelo;
require_once '../entorno/Conexion.php';

class Regional{
    public $conexion;
    private $whereAnd;
    private $condicion;
    
    private $idRegional;
    private $regional;
    private $estado;
    private $fechaCreacion;
    private $fechaModificacion;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    
    public function __construct(\entidad\Regional $regionalE) {
        $this->idRegional = $regionalE->getIdRegional();
        $this->regional = $regionalE->getRegional();
        $this->estado = $regionalE->getEstado();
        $this->fechaCreacion = $regionalE->getFechaCreacion();
        $this->fechaModificacion = $regionalE->getFechaModificacion();
        $this->idUsuarioCreacion = $regionalE->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $regionalE->getIdUsuarioModificacion();
        
        $this->conexion = new \Conexion();
    }
    
    public function adicionar(){
        $sentenciaSql = "
            INSERT INTO facturacion_inventario.regional
                (
                      regional
                    , estado
                    , fecha_creacion
                    , fecha_modificacion
                    , id_usuario_creacion
                    , id_usuario_modificacion
                )
            VALUES
                (
                      '$this->regional'
                    , TRUE
                    , NOW()
                    , NOW()
                    , $this->idUsuarioCreacion
                    , $this->idUsuarioModificacion
                )
        ";
        $this->conexion->ejecutar($sentenciaSql);
        return $this->obtenerMaximo();
    }
    
    public function consultar(){
        $this->obtenerCondicion();
        $sentenciaSql = "
            SELECT
                  r.id_regional
                , r.regional
                , r.estado
            FROM
                facturacion_inventario.regional AS r
            $this->condicion
        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this-> conexion->obtenerObjeto()){
            $retorno[$contador]['idRegional'] = $fila->id_regional;
            $retorno[$contador]['regional'] = $fila->regional;
            $retorno[$contador]['estado'] = $fila->estado;
        }
        return $retorno;
    }
    
    public function obtenerMaximo(){
        $sentenciaSql = "
            SELECT
                MAX(id_regional) AS id_regional
            FROM
                facturacion_inventario.regional
        ";
        $this->conexion->ejecutar($sentenciaSql);
        $fila = $this->conexion->obtenerObjeto();
        return $fila->id_regional;
    }
    
    public function obtenerCondicion(){
        $this->condicion = "";
        $this->whereAnd = " WHERE ";
        
        if($this->idRegional != "" && $this->idRegional != null && $this->idRegional != "null"){
            $this->condicion .= $this->whereAnd . " r.id_regional = " . $this->idRegional;
            $this->whereAnd = " AND ";
        }
        
        if($this->regional != "" && $this->regional != null && $this->regional != "null"){
            $this->condicion .= $this->whereAnd . " r.regional = '" . $this->regional . "'";
            $this->whereAnd = " AND ";
        }
        
        if($this->estado != "" && $this->estado != null && $this->estado != "null"){
            $this->condicion .= $this->whereAnd . " r.estado = " . $this->estado;
            $this->whereAnd = " AND ";
        }
    }
}
?>