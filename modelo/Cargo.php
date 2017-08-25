<?php
namespace modelo;
require_once '../entorno/Conexion.php';
class Cargo{
    public $conexion;
    private $whereAnd;
    private $condicion;
    
    private $idCargo;
    private $cargo;
    private $estado;
    private $fechaCreacion;
    private $fechaModificacion;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    
    public function __construct(\entidad\Cargo $cargoE) {
        $this->idCargo = $cargoE ->getIdCargo();
        $this->cargo = $cargoE ->getCargo();
        $this->estado = $cargoE ->getEstado();
        $this->fechaCreacion = $cargoE ->getFechaCreacion();
        $this->fechaModificacion = $cargoE ->getFechaModificacion();
        $this->idUsuarioCreacion = $cargoE ->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $cargoE ->getIdUsuarioModificacion();
        
        $this->conexion = new \Conexion();
    }
    
    public function consultar(){
        $sentenciaSql = "
            SELECT
                  c.id_cargo
                , c.cargo
                , c.estado
            FROM
                general.cargo AS c
            $this->condicion
        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while($fila = $this->conexion->obtenerObjeto()){
            $retorno[$contador]["idCargo"] = $fila->id_cargo;
            $retorno[$contador]["cargo"] = $fila->cargo;
            $retorno[$contador]["estado"] = $fila->estado;
            
            $contador++;
        }
        return $retorno;
    }
    
    public function obtenerCondicion(){
        $this->condicion = "";
        $this->whereAnd = " WHERE ";
        
        if($this->idCargo != null && $this->idCargo != 'null' && $this->idCargo != ''){
            $this->condicion .= $this->whereAnd . " c.id_cargo = " . $this->idCargo;
            $this->whereAnd = " AND ";
        }
        
        if($this->estado != null && $this->estado != 'null' && $this->estado != ''){
            $this->condicion .= $this->whereAnd . " c.estado = " . $this->estado;
            $this->whereAnd = " AND ";
        }
    }
}