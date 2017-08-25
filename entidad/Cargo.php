<?php
namespace entidad;
require_once '../modelo/Cargo.php';
class Cargo{
    private $idCargo;
    private $cargo;
    private $estado;
    private $fechaCreacion;
    private $fechaModificacion;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    
    public function getIdCargo() {
        return $this->idCargo;
    }

    public function getCargo() {
        return $this->cargo;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function getFechaCreacion() {
        return $this->fechaCreacion;
    }

    public function getFechaModificacion() {
        return $this->fechaModificacion;
    }

    public function getIdUsuarioCreacion() {
        return $this->idUsuarioCreacion;
    }

    public function getIdUsuarioModificacion() {
        return $this->idUsuarioModificacion;
    }

    public function setIdCargo($idCargo) {
        $this->idCargo = $idCargo;
    }

    public function setCargo($cargo) {
        $this->cargo = $cargo;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

    public function setFechaCreacion($fechaCreacion) {
        $this->fechaCreacion = $fechaCreacion;
    }

    public function setFechaModificacion($fechaModificacion) {
        $this->fechaModificacion = $fechaModificacion;
    }

    public function setIdUsuarioCreacion($idUsuarioCreacion) {
        $this->idUsuarioCreacion = $idUsuarioCreacion;
    }

    public function setIdUsuarioModificacion($idUsuarioModificacion) {
        $this->idUsuarioModificacion = $idUsuarioModificacion;
    }


}
?>