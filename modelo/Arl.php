<?php

namespace modelo;

require_once '../entorno/Conexion.php';

class Arl {

    public $conexion;
    private $whereAnd;
    private $condicion;
    private $idArl;
    private $arl;
    private $estado;
    private $fechaCreacion;
    private $fechaModificacion;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;

    public function __construct(\entidad\Arl $arlE) {
        $this->idArl = $arlE->getIdArl();
        $this->arl = $arlE->getArl();
        $this->estado = $arlE->getEstado();
        $this->fechaCreacion = $arlE->getFechaCreacion();
        $this->fechaModificacion = $arlE->getFechaModificacion();
        $this->idUsuarioCreacion = $arlE->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $arlE->getIdUsuarioModificacion();

        $this->conexion = new \Conexion();
    }

    public function consultar() {
        $this->obtenerCondicion();
        $sentenciaSql = "
            SELECT
                  arl.id_arl
                , arl.arl
                , arl.estado
            FROM 
                nomina.arl AS arl
            $this->condicion    
        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while($fila = $this->conexion->obtenerObjeto()){
            $retorno[$contador]['idArl'] = $fila -> id_arl;
            $retorno[$contador]['arl'] = $fila -> arl;
            $retorno[$contador]['estado'] = $fila -> estado;
            
            $contador++;
        }
        return $retorno;
    }

    public function obtenerCondicion() {
        $this->condicion = "";
        $this->whereAnd = " WHERE ";

        if ($this->idArl != "" && $this->idArl != "null" && $this->idArl != null) {
            $this->condicion .= $this->whereAnd . " arl.id_arl = " . $this->idArl;
            $this->whereAnd = " AND ";
        }

        if ($this->estado != "" && $this->estado != "null" && $this->estado != null) {
            $this->condicion .= $this->whereAnd . " arl.estado = " . $this->estado;
            $this->whereAnd = " AND ";
        }
    }

}

?>