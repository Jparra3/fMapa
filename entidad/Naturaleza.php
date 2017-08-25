<?php
namespace entidad;
class Naturaleza {
    private $idNaturaleza;
    private $naturaleza;
    private $signo;
    private $idTipoNaturaleza;
    private $mueveInventario;
    private $idNaturalezaAfecta;
    private $estado;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    
    function getIdNaturaleza() {
        return $this->idNaturaleza;
    }

    function getNaturaleza() {
        return $this->naturaleza;
    }

    function getSigno() {
        return $this->signo;
    }

    function getIdTipoNaturaleza() {
        return $this->idTipoNaturaleza;
    }

    function getMueveInventario() {
        return $this->mueveInventario;
    }

    function getIdNaturalezaAfecta() {
        return $this->idNaturalezaAfecta;
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

    function setIdNaturaleza($idNaturaleza) {
        $this->idNaturaleza = $idNaturaleza;
    }

    function setNaturaleza($naturaleza) {
        $this->naturaleza = $naturaleza;
    }

    function setSigno($signo) {
        $this->signo = $signo;
    }

    function setIdTipoNaturaleza($idTipoNaturaleza) {
        $this->idTipoNaturaleza = $idTipoNaturaleza;
    }

    function setMueveInventario($mueveInventario) {
        $this->mueveInventario = $mueveInventario;
    }

    function setIdNaturalezaAfecta($idNaturalezaAfecta) {
        $this->idNaturalezaAfecta = $idNaturalezaAfecta;
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
