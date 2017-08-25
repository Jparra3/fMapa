<?php

namespace entidad;
class FormaPago {
    private $idFormaPago;
    private $formaPago;
    private $estado;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    private $principal;
    private $idTipoDocumento;
    private $visualiza;
    
    function getIdFormaPago() {
        return $this->idFormaPago;
    }

    function getFormaPago() {
        return $this->formaPago;
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

    function getPrincipal() {
        return $this->principal;
    }

    function getIdTipoDocumento() {
        return $this->idTipoDocumento;
    }

    function getVisualiza() {
        return $this->visualiza;
    }

    function setIdFormaPago($idFormaPago) {
        $this->idFormaPago = $idFormaPago;
    }

    function setFormaPago($formaPago) {
        $this->formaPago = $formaPago;
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

    function setPrincipal($principal) {
        $this->principal = $principal;
    }

    function setIdTipoDocumento($idTipoDocumento) {
        $this->idTipoDocumento = $idTipoDocumento;
    }

    function setVisualiza($visualiza) {
        $this->visualiza = $visualiza;
    }


}
