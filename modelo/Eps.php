<?php

namespace modelo;

require_once '../entorno/Conexion.php';

class Eps {

    public $conexion;
    private $whereAnd;
    private $condicion;
    
    private $idEps;
    private $eps;
    private $estado;
    private $fechaCreacion;
    private $fechaModificacion;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;

    public function __construct(\entidad\Eps $epsE) {
        $this->idEps = $epsE->getIdEps();
        $this->eps = $epsE->getEps();
        $this->estado = $epsE->getEstado();
        $this->fechaCreacion = $epsE->getFechaCreacion();
        $this->fechaModificacion = $epsE->getFechaModificacion();
        $this->idUsuarioCreacion = $epsE->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $epsE->getIdUsuarioModificacion();
        
        $this->conexion = new \Conexion();
    }

    public function consultar() {
        $this->obtenerCondicion();
        $sentenciaSql = "
            SELECT
                  eps.id_eps
                , eps.eps
                , eps.estado
            FROM
                nomina.eps AS eps
            $this->condicion    
        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while($fila = $this->conexion->obtenerObjeto()){
            $retorno[$contador]['idEps'] = $fila -> id_eps;
            $retorno[$contador]['eps'] = $fila -> eps;
            $retorno[$contador]['estado'] = $fila -> estado;
            
            $contador++;
        }
        return $retorno;
    }

    public function obtenerCondicion() {
        $this->condicion = "";
        $this->whereAnd = " WHERE ";

        if ($this->idEps != null && $this->idEps != 'null' && $this->idEps != '') {
            $this->condicion .= $this->whereAnd . " eps.id_eps = " . $this->idEps;
            $this->whereAnd = " AND ";
        }

        if ($this->estado != null && $this->estado != 'null' && $this->estado != '') {
            $this->condicion .= $this->whereAnd . " eps.estado = " . $this->estado;
            $this->whereAnd = " AND ";
        }
    }

}

?>
