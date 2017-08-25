<?php
namespace entidad;
class TransaccionProductoImpuesto {
    private $idTransaccionProductoImpuesto;
    private $idTransaccionProducto;
    private $idImpuesto;
    private $impuestoValor;
    private $valor;
    private $base;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    
    function getBase() {
        return $this->base;
    }

    function setBase($base) {
        $this->base = $base;
    }

        
    function getIdTransaccionProductoImpuesto() {
        return $this->idTransaccionProductoImpuesto;
    }

    function getIdTransaccionProducto() {
        return $this->idTransaccionProducto;
    }

    function getIdImpuesto() {
        return $this->idImpuesto;
    }

    function getImpuestoValor() {
        return $this->impuestoValor;
    }

    function getValor() {
        return $this->valor;
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

    function setIdTransaccionProductoImpuesto($idTransaccionProductoImpuesto) {
        $this->idTransaccionProductoImpuesto = $idTransaccionProductoImpuesto;
    }

    function setIdTransaccionProducto($idTransaccionProducto) {
        $this->idTransaccionProducto = $idTransaccionProducto;
    }

    function setIdImpuesto($idImpuesto) {
        $this->idImpuesto = $idImpuesto;
    }

    function setImpuestoValor($impuestoValor) {
        $this->impuestoValor = $impuestoValor;
    }

    function setValor($valor) {
        $this->valor = $valor;
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
