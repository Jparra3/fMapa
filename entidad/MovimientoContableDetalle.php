<?php
namespace entidad;
require_once '../../Seguridad/entidad/Tercero.php';
require_once '../entidad/MovimientoContable.php';
class MovimientoContableDetalle {
    private $idMovimientoContableDetalle;
    private $movimientoContable;
    private $tercero;
    private $idCuentaContable;
    private $idCentroCosto;
    private $valor;
    private $nota;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    private $estado;
    
    function getMovimientoContable() {
        return $this->movimientoContable;
    }

    function setMovimientoContable(\entidad\MovimientoContable $movimientoContable) {
        $this->movimientoContable = $movimientoContable;
    }
    
    function getIdMovimientoContableDetalle() {
        return $this->idMovimientoContableDetalle;
    }

    function getTercero() {
        return $this->tercero;
    }

    function getIdCuentaContable() {
        return $this->idCuentaContable;
    }

    function getIdCentroCosto() {
        return $this->idCentroCosto;
    }

    function getValor() {
        return $this->valor;
    }

    function getNota() {
        return $this->nota;
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

    function getEstado() {
        return $this->estado;
    }

    function setIdMovimientoContableDetalle($idMovimientoContableDetalle) {
        $this->idMovimientoContableDetalle = $idMovimientoContableDetalle;
    }

    function setTercero(\entidad\Tercero $tercero) {
        $this->tercero = $tercero;
    }

    function setIdCuentaContable($idCuentaContable) {
        $this->idCuentaContable = $idCuentaContable;
    }

    function setIdCentroCosto($idCentroCosto) {
        $this->idCentroCosto = $idCentroCosto;
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

    function setEstado($estado) {
        $this->estado = $estado;
    }


    
}
