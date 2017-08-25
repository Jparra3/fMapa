<?php
namespace entidad;
require_once '../../Seguridad/entidad/Persona.php';
class Empleado {
    private $idEmpleado;
    private $persona;
    private $idEps;
    private $idArl;
    private $estado;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    private $idCargo;
    
    public function getIdCargo() {
        return $this->idCargo;
    }

    public function setIdCargo($idCargo) {
        $this->idCargo = $idCargo;
    }

    function getIdEmpleado() {
        return $this->idEmpleado;
    }

    function getPersona() {
        return $this->persona;
    }

    function getIdEps() {
        return $this->idEps;
    }

    function getIdArl() {
        return $this->idArl;
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

    function setIdEmpleado($idEmpleado) {
        $this->idEmpleado = $idEmpleado;
    }

    function setPersona(\entidad\Persona $persona) {
        $this->persona = $persona;
    }

    function setIdEps($idEps) {
        $this->idEps = $idEps;
    }

    function setIdArl($idArl) {
        $this->idArl = $idArl;
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
?>