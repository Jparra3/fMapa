<?php
namespace entidad;
class TransaccionCruce {
    private $idTransaccionCruce;
    private $idTransaccionConceptoAfectado;
    private $idTransaccionFormaPago;
    private $valor;
    private $secuencia;
    private $fecha;
    private $observacion;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    private $idTransaccionConceptoOrigen;
    
    function getIdTransaccionConceptoAfectado() {
        return $this->idTransaccionConceptoAfectado;
    }

    function getIdTransaccionConceptoOrigen() {
        return $this->idTransaccionConceptoOrigen;
    }

    function setIdTransaccionConceptoAfectado($idTransaccionConceptoAfectado) {
        $this->idTransaccionConceptoAfectado = $idTransaccionConceptoAfectado;
    }

    function setIdTransaccionConceptoOrigen($idTransaccionConceptoOrigen) {
        $this->idTransaccionConceptoOrigen = $idTransaccionConceptoOrigen;
    }

        
    function getIdTransaccionFormaPago() {
        return $this->idTransaccionFormaPago;
    }

    function setIdTransaccionFormaPago($idTransaccionFormaPago) {
        $this->idTransaccionFormaPago = $idTransaccionFormaPago;
    }
    
    function getIdTransaccionCruce() {
        return $this->idTransaccionCruce;
    }

    

    function getValor() {
        return $this->valor;
    }

    function getSecuencia() {
        return $this->secuencia;
    }

    function getFecha() {
        return $this->fecha;
    }

    function getObservacion() {
        return $this->observacion;
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

    function setIdTransaccionCruce($idTransaccionCruce) {
        $this->idTransaccionCruce = $idTransaccionCruce;
    }

    function setValor($valor) {
        $this->valor = $valor;
    }

    function setSecuencia($secuencia) {
        $this->secuencia = $secuencia;
    }

    function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    function setObservacion($observacion) {
        if($observacion != "" && $observacion != null && $observacion != "null"){
            $this->observacion = "'".$observacion."'";
        }else{
            $this->observacion = "null";
        }   
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
