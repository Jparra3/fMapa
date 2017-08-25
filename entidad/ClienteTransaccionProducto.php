<?php
namespace entidad;
require_once '../entidad/TransaccionProducto.php';
require_once '../entidad/Cliente.php';
class clienteTransaccionProducto{
    
    private $idClienteTransaccionProducto;
    private $transaccionProducto;
    private $idTabla;
    private $tabla;
    private $cliente;
    private $estado;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    
    function getTabla() {
        return $this->tabla;
    }

    function setTabla($tabla) {
        $this->tabla = $tabla;
    }
    
    function getIdClienteTransaccionProducto() {
        return $this->idClienteTransaccionProducto;
    }

    function getTransaccionProducto() {
        return $this->transaccionProducto;
    }

    function getIdTabla() {
        return $this->idTabla;
    }

    function getCliente() {
        return $this->cliente;
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

    function setIdClienteTransaccionProducto($idClienteTransaccionProducto) {
        $this->idClienteTransaccionProducto = $idClienteTransaccionProducto;
    }

    function setTransaccionProducto(\entidad\TransaccionProducto $transaccionProducto) {
        $this->transaccionProducto = $transaccionProducto;
    }

    function setIdTabla($idTabla) {
        $this->idTabla = $idTabla;
    }

    function setCliente(\entidad\Cliente $cliente) {
        $this->cliente = $cliente;
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

