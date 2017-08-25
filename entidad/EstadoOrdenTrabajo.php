<?php
namespace entidad;
class EstadoOrdenTrabajo{
    private $idEstadoOrdenTrabajo;
    private $estadoOrdenTrabajo;
    private $principal;
    private $estado;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    
    function getPrincipal() {
        return $this->principal;
    }

    function setPrincipal($principal) {
        $this->principal = $principal;
    }
    
    function getIdEstadoOrdenTrabajo() {
        return $this->idEstadoOrdenTrabajo;
    }

    function getEstadoOrdenTrabajo() {
        return $this->estadoOrdenTrabajo;
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

    function setIdEstadoOrdenTrabajo($idEstadoOrdenTrabajo) {
        $this->idEstadoOrdenTrabajo = $idEstadoOrdenTrabajo;
    }

    function setEstadoOrdenTrabajo($estadoOrdenTrabajo) {
        $this->estadoOrdenTrabajo = $estadoOrdenTrabajo;
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
