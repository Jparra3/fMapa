<?php
namespace entidad;
class PeriodicidadVisita {
    private $idPeriodicidadVisita;
    private $periodicidadVisita;
    private $idPeriodicidad;
    private $estado;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    
    public function getIdPeriodicidadVisita() {
        return $this->idPeriodicidadVisita;
    }
    public function getPeriodicidadVisita() {
        return $this->periodicidadVisita;
    }

    public function getIdPeriodicidad() {
        return $this->idPeriodicidad;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function getIdUsuarioCreacion() {
        return $this->idUsuarioCreacion;
    }

    public function getIdUsuarioModificacion() {
        return $this->idUsuarioModificacion;
    }

    public function getFechaCreacion() {
        return $this->fechaCreacion;
    }

    public function getFechaModificacion() {
        return $this->fechaModificacion;
    }

    public function setIdPeriodicidadVisita($idPeriodicidadVisita) {
        $this->idPeriodicidadVisita = $idPeriodicidadVisita;
    }

    public function setPeriodicidadVisita($periodicidadVisita) {
        $this->periodicidadVisita = $periodicidadVisita;
    }

    public function setIdPeriodicidad($idPeriodicidad) {
        $this->idPeriodicidad = $idPeriodicidad;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

    public function setIdUsuarioCreacion($idUsuarioCreacion) {
        $this->idUsuarioCreacion = $idUsuarioCreacion;
    }

    public function setIdUsuarioModificacion($idUsuarioModificacion) {
        $this->idUsuarioModificacion = $idUsuarioModificacion;
    }

    public function setFechaCreacion($fechaCreacion) {
        $this->fechaCreacion = $fechaCreacion;
    }

    public function setFechaModificacion($fechaModificacion) {
        $this->fechaModificacion = $fechaModificacion;
    }
}
