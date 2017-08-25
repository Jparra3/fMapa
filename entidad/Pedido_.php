<?php
namespace entidad;
require_once 'Cliente.php';
require_once 'Vendedor.php';


class Pedido {
    private $idPedido;
    private $cliente;
    private $idPedidoEstado;
    private $vendedor;
    private $idZona;
    private $fecha;
    private $nota;
    private $tipoPedido;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaModificacion;
    private $fechaCreacion;
    
    function getTipoPedido() {
        return $this->tipoPedido;
    }

    function setTipoPedido($tipoPedido) {
        $this->tipoPedido = $tipoPedido;
    }
    
    function getVendedor() {
        return $this->vendedor;
    }

    function getIdZona() {
        return $this->idZona;
    }

    function setVendedor(\entidad\Vendedor $vendedor) {
        $this->vendedor = $vendedor;
    }

    function setIdZona($idZona) {
        $this->idZona = $idZona;
    }
    
    function getIdPedido() {
        return $this->idPedido;
    }

    function getCliente() {
        return $this->cliente;
    }

    function getIdPedidoEstado() {
        return $this->idPedidoEstado;
    }

    function getFecha() {
        return $this->fecha;
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

    function getFechaModificacion() {
        return $this->fechaModificacion;
    }

    function getFechaCreacion() {
        return $this->fechaCreacion;
    }

    function setIdPedido($idPedido) {
        $this->idPedido = $idPedido;
    }

    function setCliente(\entidad\Cliente $cliente) {
        $this->cliente = $cliente;
    }

    function setIdPedidoEstado($idPedidoEstado) {
        $this->idPedidoEstado = $idPedidoEstado;
    }

    function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    function setNota($nota) {
        $this->nota = $nota;
    }

    function setIdUsuarioCreacion($idUsuarioCreacion) {
        $this->idUsuarioCreacion = $idUsuarioCreacion;
    }

    function setIdUsuarioModificacion($idUsuarioModificacion) {
        $this->idUsuarioModificacion = $idUsuarioModificacion;
    }

    function setFechaModificacion($fechaModificacion) {
        $this->fechaModificacion = $fechaModificacion;
    }

    function setFechaCreacion($fechaCreacion) {
        $this->fechaCreacion = $fechaCreacion;
    }


}

?>