<?php
namespace entidad;
require_once '../modelo/Regional.php';
class Regional{
    private $idRegional;
    private $regional;
    private $estado;
    private $fechaCreacion;
    private $fechaModificacion;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    
    public function getIdRegional() {
        return $this->idRegional;
    }

    public function getRegional() {
        return $this->regional;
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

    public function setIdRegional($idRegional) {
        $this->idRegional = $idRegional;
    }

    public function setRegional($regional) {
        $this->regional = $regional;
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