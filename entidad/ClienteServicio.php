<?php
namespace entidad;
require_once '../entidad/Cliente.php';
require_once '../entidad/OrdenTrabajo.php';
require_once '../entidad/OrdenTrabajoCliente.php';
class ClienteServicio{
    private $idClienteServicio;
    private $cliente;
    private $idProductoComposicion;
    private $fechaInicial;
    private $fechaFinal;
    private $ordenTrabajoCliente;
    private $idTransaccion;
    private $idEstadoClienteServicio;
    private $valor;
    private $estado;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    
    function getValor() {
        return $this->valor;
    }

    function setValor($valor) {
        $this->valor = $valor;
    }

    function getIdClienteServicio() {
        if($this->idClienteServicio == "" || $this->idClienteServicio == null || $this->idClienteServicio == "null"){
            return "null";
        }else{
            return $this->idClienteServicio;
        }
    }

    function getCliente() {
        return $this->cliente;
    }

    function getIdProductoComposicion() {
        return $this->idProductoComposicion;
    }

    function getFechaInicial() {
        return $this->fechaInicial;
    }

    function getFechaFinal() {
        return $this->fechaFinal;
    }

    function getOrdenTrabajoCliente() {
        return $this->ordenTrabajoCliente;
    }

    function getIdTransaccion() {
        return $this->idTransaccion;
    }

    function getIdEstadoClienteServicio() {
        return $this->idEstadoClienteServicio;
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

    function setIdClienteServicio($idClienteServicio) {
        $this->idClienteServicio = $idClienteServicio;
    }

    function setCliente(\entidad\Cliente $cliente) {
        $this->cliente = $cliente;
    }

    function setIdProductoComposicion($idProductoComposicion) {
        $this->idProductoComposicion = $idProductoComposicion;
    }

    function setFechaInicial($fechaInicial) {
        $this->fechaInicial = $fechaInicial;
    }

    function setFechaFinal($fechaFinal) {
        if($fechaFinal == "" || $fechaFinal == null || $fechaFinal == "null"){
            $this->fechaFinal = "NULL";
        }else{
            //$this->fechaFinal = "'".$fechaFinal."'";
            $this->fechaFinal = $fechaFinal;
        }
    }

    function setOrdenTrabajoCliente(\entidad\OrdenTrabajoCliente $ordenTrabajoCliente) {
        $this->ordenTrabajoCliente = $ordenTrabajoCliente;
    }

    function setIdTransaccion($idTransaccion) {
        $this->idTransaccion = $idTransaccion;
    }

    function setIdEstadoClienteServicio($idEstadoClienteServicio) {
        $this->idEstadoClienteServicio = $idEstadoClienteServicio;
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
}
?>
