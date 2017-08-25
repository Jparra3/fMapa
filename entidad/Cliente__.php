<?php
namespace entidad;
class Cliente {
    private $idCliente;
    private $cliente;
    private $telefonos;
    private $correoElectronico;
    private $contrasena;
    private $direcciones;
    private $tipoLogueo;
    
    function getIdCliente() {
        return $this->idCliente;
    }

    function getCliente() {
        return $this->cliente;
    }

    function getTelefonos() {
        return $this->telefonos;
    }

    function getCorreoElectronico() {
        return $this->correoElectronico;
    }

    function getContrasena() {
        return $this->contrasena;
    }

    function getDirecciones() {
        return $this->direcciones;
    }

    function getTipoLogueo() {
        return $this->tipoLogueo;
    }

    function setIdCliente($idCliente) {
        $this->idCliente = $idCliente;
    }

    function setCliente($cliente) {
        $this->cliente = $cliente;
    }

    function setTelefonos($telefonos) {
        $this->telefonos = $telefonos;
    }

    function setCorreoElectronico($correoElectronico) {
        $this->correoElectronico = $correoElectronico;
    }

    function setContrasena($contrasena) {
        $this->contrasena = $contrasena;
    }

    function setDirecciones($direcciones) {
        $this->direcciones = $direcciones;
    }

    function setTipoLogueo($tipoLogueo) {
        $this->tipoLogueo = $tipoLogueo;
    }


}
