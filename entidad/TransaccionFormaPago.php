<?php
namespace entidad;
class TransaccionFormaPago {
    private $idTransaccionFormaPago;
    private $idFormaPago;
    private $idTransaccion;
    private $valor;
    private $nota;
    private $estado;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    
    function getIdUsuarioModificacion() {
        return $this->idUsuarioModificacion;
    }

    function setIdUsuarioModificacion($idUsuarioModificacion) {
        $this->idUsuarioModificacion = $idUsuarioModificacion;
    }

    function getIdTransaccionFormaPago() {
        return $this->idTransaccionFormaPago;
    }

    function getIdFormaPago() {
        return $this->idFormaPago;
    }

    function getIdTransaccion() {
        return $this->idTransaccion;
    }

    function getValor() {
        return $this->valor;
    }

    function getNota() {
        return $this->nota;
    }

    function getEstado() {
        return $this->estado;
    }

    function getIdUsuarioCreacion() {
        return $this->idUsuarioCreacion;
    }

    function getFechaCreacion() {
        return $this->fechaCreacion;
    }

    function getFechaModificacion() {
        return $this->fechaModificacion;
    }

    function setIdTransaccionFormaPago($idTransaccionFormaPago) {
        $this->idTransaccionFormaPago = $idTransaccionFormaPago;
    }

    function setIdFormaPago($idFormaPago) {
        $this->idFormaPago = $idFormaPago;
    }

    function setIdTransaccion($idTransaccion) {
        $this->idTransaccion = $idTransaccion;
    }

    function setValor($valor) {
        $this->valor = $valor;
    }

    function setNota($nota) {
        if($nota != "" && $nota != null && $nota != "null"){
            $this->nota = "'".$nota."'";
        }else{
            $this->nota = "null";
        }
    }

    function setEstado($estado) {
        $this->estado = $estado;
    }

    function setIdUsuarioCreacion($idUsuarioCreacion) {
        $this->idUsuarioCreacion = $idUsuarioCreacion;
    }

    function setFechaCreacion($fechaCreacion) {
        $this->fechaCreacion = $fechaCreacion;
    }

    function setFechaModificacion($fechaModificacion) {
        $this->fechaModificacion = $fechaModificacion;
    }


}
