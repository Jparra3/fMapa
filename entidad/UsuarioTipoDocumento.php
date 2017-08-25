<?php
namespace entidad;
class UsuarioTipoDocumento {
    private $idUsuarioTipoDocumento;
    private $idUsuario;
    private $idTipoDocumento;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    
    function getIdUsuarioTipoDocumento() {
        return $this->idUsuarioTipoDocumento;
    }

    function getIdUsuario() {
        return $this->idUsuario;
    }

    function getIdTipoDocumento() {
        return $this->idTipoDocumento;
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

    function setIdUsuarioTipoDocumento($idUsuarioTipoDocumento) {
        $this->idUsuarioTipoDocumento = $idUsuarioTipoDocumento;
    }

    function setIdUsuario($idUsuario) {
        $this->idUsuario = $idUsuario;
    }

    function setIdTipoDocumento($idTipoDocumento) {
        $this->idTipoDocumento = $idTipoDocumento;
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