<?php

namespace entidad;
require_once '../../Seguridad/entidad/Persona.php';

class Vendedor {
    private $idVendedor;
    private $persona;
    private $idZona;
    private $estado;
    private $fechaCreacion;
    private $fechaModificacion;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    
    public function getEstado() {
        return $this->estado;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
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

    function getIdVendedor() {
        return $this->idVendedor;
    }

    function getPersona() {
        return $this->persona;
    }

    function getIdZona() {
        return $this->idZona;
    }

    function setIdVendedor($idVendedor) {
        $this->idVendedor = $idVendedor;
    }

    function setPersona(\entidad\Persona $persona) {
        $this->persona = $persona;
    }

    function setIdZona($idZona) {
        $this->idZona = $idZona;
    }


}
