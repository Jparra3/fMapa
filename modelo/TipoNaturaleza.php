<?php
namespace modelo;
session_start();
require_once($_SESSION['ruta'].'entorno/Conexion.php');
class TipoNaturaleza {
    public $conexion = null;
    private $condicion;
    private $whereAnd;

    private $idTipoNaturaleza;
    private $tipoNaturaleza;
    private $estado;
    private $idUsuarioCracion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    private $codigo;
    
    public function __construct(\entidad\TipoNaturaleza $tipoNaturaleza) {
        $this->idTipoNaturaleza = $tipoNaturaleza->getIdTipoNaturaleza();
        $this->tipoNaturaleza = $tipoNaturaleza->getTipoNaturaleza();
        $this->estado = $tipoNaturaleza->getEstado();
        $this->idUsuarioCracion = $tipoNaturaleza->getIdUsuarioCracion();
        $this->idUsuarioModificacion = $tipoNaturaleza->getIdUsuarioModificacion();
        $this->fechaCreacion = $tipoNaturaleza->getFechaCreacion();
        $this->fechaModificacion = $tipoNaturaleza->getFechaModificacion();
        $this->codigo = $tipoNaturaleza->getCodigo();
        
        $this->conexion = new \Conexion();
    }
    
    public function consultar() {
        $this->obtenerCondicion();
        $sentenciaSql = "   
                        SELECT
                            id_tipo_naturaleza
                            , tipo_naturaleza
                            , codigo
                        FROM
                            contabilidad.tipo_naturaleza   
                            $this->condicion
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["idTipoNaturaleza"] = $fila->id_tipo_naturaleza;
            $retorno[$contador]["tipoNaturaleza"] = $fila->tipo_naturaleza;
            $retorno[$contador]["codigo"] = $fila->codigo;
            $contador++;
        }
        return $retorno;
    }
    
    private function obtenerCondicion() {
        $this->condicion = "";
        $this->whereAnd = " WHERE ";
        
        if($this->codigo != "" && $this->codigo != "null" && $this->codigo != null){
            $this->condicion = $this->condicion.$this->whereAnd." codigo IN (". $this->codigo .")";
            $this->whereAnd = " AND ";
        }
        
        if($this->estado != "" && $this->estado != "null" && $this->estado != null){
            $this->condicion = $this->condicion.$this->whereAnd." estado = ".$this->estado;
            $this->whereAnd = " AND ";
        }
    }
}
