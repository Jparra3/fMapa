<?php
namespace entidad;
require_once '../../Seguridad/entidad/Tercero.php';
class Transaccion {
    
    private $idTransaccion;
    private $idTipoDocumento;
    private $tercero;
    private $idNaturaleza;
    private $numeroTipoDocumento;
    private $idTransaccionEstado;
    private $idOficina;
    private $nota;
    private $fecha;
    private $fechaVencimiento;
    private $valor;
    private $saldo;
    private $numeroImpresiones;
    private $idTransaccionAfecta;
    private $idFormatoImpresion;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    private $idTransaccionPadre;
    
    function getIdTransaccionPadre() {
        return $this->idTransaccionPadre;
    }

    function setIdTransaccionPadre($idTransaccionPadre) {
        if($idTransaccionPadre == null || $idTransaccionPadre == ""){
            $this->idTransaccionPadre = "null";
        }else{
            $this->idTransaccionPadre = $idTransaccionPadre;
        }
    }
        
    function getIdTransaccionAfecta() {
        return $this->idTransaccionAfecta;
    }

    function setIdTransaccionAfecta($idTransaccionAfecta) {
        if($idTransaccionAfecta == null || $idTransaccionAfecta == ""){
            $this->idTransaccionAfecta = "null";
        }else{
            $this->idTransaccionAfecta = $idTransaccionAfecta;
        }
    }

    function getIdTransaccion() {
        return $this->idTransaccion;
    }

    function getIdTipoDocumento() {
        return $this->idTipoDocumento;
    }

    function getTercero() {
        return $this->tercero;
    }

    function getIdNaturaleza() {
        return $this->idNaturaleza;
    }

    function getNumeroTipoDocumento() {
        return $this->numeroTipoDocumento;
    }

    function getIdTransaccionEstado() {
        return $this->idTransaccionEstado;
    }

    function getIdOficina() {
        return $this->idOficina;
    }

    function getNota() {
        return $this->nota;
    }

    function getFecha() {
        return $this->fecha;
    }

    function getFechaVencimiento() {
        return $this->fechaVencimiento;
    }

    function getValor() {
        return $this->valor;
    }

    function getSaldo() {
        return $this->saldo;
    }

    function getNumeroImpresiones() {
        return $this->numeroImpresiones;
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

    function setIdTransaccion($idTransaccion) {
        $this->idTransaccion = $idTransaccion;
    }

    function setIdTipoDocumento($idTipoDocumento) {
        $this->idTipoDocumento = $idTipoDocumento;
    }

    function setTercero(\entidad\Tercero $tercero) {
        $this->tercero = $tercero;
    }

    function setIdNaturaleza($idNaturaleza) {
        $this->idNaturaleza = $idNaturaleza;
    }

    function setNumeroTipoDocumento($numeroTipoDocumento) {
        $this->numeroTipoDocumento = $numeroTipoDocumento;
    }

    function setIdTransaccionEstado($idTransaccionEstado) {
        $this->idTransaccionEstado = $idTransaccionEstado;
    }

    function setIdOficina($idOficina) {
        $this->idOficina = $idOficina;
    }

    function setNota($nota) {        
        if($nota != "" && $nota != null && $nota != "null" && $nota != "undefined")
            $this->nota = "'".$nota."'";
        else
            $this->nota = "null";        
    }

    function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    function setFechaVencimiento($fechaVencimiento) {
        $this->fechaVencimiento = $fechaVencimiento;
    }

    function setValor($valor) {
        $this->valor = $valor;
    }

    function setSaldo($saldo) {
        $this->saldo = $saldo;
    }

    function setNumeroImpresiones($numeroImpresiones) {
        $this->numeroImpresiones = $numeroImpresiones;
    }
    function getIdFormatoImpresion() {
        return $this->idFormatoImpresion;
    }

    function setIdFormatoImpresion($idFormatoImpresion) {
        $this->idFormatoImpresion = $idFormatoImpresion;
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
