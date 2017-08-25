<?php
namespace entidad;
class Cierre {
    private $idCierre;
    private $periodo;
    private $fechaInicial;
    private $fechaFinal;
    private $estado;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    
    function getIdCierre() {
        return $this->idCierre;
    }

    function getPeriodo() {
        return $this->periodo;
    }

    function getFechaInicial() {
        return $this->fechaInicial;
    }

    function getFechaFinal() {
        return $this->fechaFinal;
    }

    function getEstado() {
        return $this->estado;
    }

    function getIdUsuarioCreacion() {
        return $this->idUsuarioCreacion;
    }

    function getIdUsuarioModificacion() {
        return $this->idUsuarioModificacion;
    }

    function getFechaCreacion() {
        return $this->fechaCreacion;
    }

    function getFechaModificacion() {
        return $this->fechaModificacion;
    }

    function setIdCierre($idCierre) {
        $this->idCierre = $idCierre;
    }

    function setPeriodo($periodo) {
        $this->periodo = $periodo;
    }

    function setFechaInicial($fechaInicial) {
        $this->fechaInicial = $fechaInicial;
    }

    function setFechaFinal($fechaFinal) {
        $this->fechaFinal = $fechaFinal;
    }

    function setEstado($estado) {
        $this->estado = $estado;
    }

    function setIdUsuarioCreacion($idUsuarioCreacion) {
        $this->idUsuarioCreacion = $idUsuarioCreacion;
    }

    function setIdUsuarioModificacion($idUsuarioModificacion) {
        $this->idUsuarioModificacion = $idUsuarioModificacion;
    }

    function setFechaCreacion($fechaCreacion) {
        $this->fechaCreacion = $fechaCreacion;
    }

    function setFechaModificacion($fechaModificacion) {
        $this->fechaModificacion = $fechaModificacion;
    }


}
