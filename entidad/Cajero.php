<?php
namespace entidad;
class Cajero {
    private $idCajero;
    private $idUsuario;
    
    function getIdCajero() {
        return $this->idCajero;
    }

    function getIdUsuario() {
        return $this->idUsuario;
    }

    function setIdCajero($idCajero) {
        $this->idCajero = $idCajero;
    }

    function setIdUsuario($idUsuario) {
        $this->idUsuario = $idUsuario;
    }
}
