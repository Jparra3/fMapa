<?php
namespace entidad;
require_once '../entidad/ClienteServicio.php';
require_once '../entidad/Producto.php';
class ClienteServicioProducto{
    
    private $idClienteServicioProducto;
    private $clienteServicio;
    private $producto;
    private $nota;
    private $secuencia;
    private $cantidad;
    private $valorUnitaConImpue;
    private $serial;
    private $idBodega;
    private $estado;
    private $idEstaClieServProd;
    private $idOrdenTrabajo;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    
    function getIdEstaClieServProd() {
        return $this->idEstaClieServProd;
    }

    function getIdOrdenTrabajo() {
        return $this->idOrdenTrabajo;
    }

    function setIdEstaClieServProd($idEstaClieServProd) {
        $this->idEstaClieServProd = $idEstaClieServProd;
    }


    function setIdOrdenTrabajo($idOrdenTrabajo) {
        $this->idOrdenTrabajo = $idOrdenTrabajo;
    }

    function getIdClienteServicioProducto() {
        return $this->idClienteServicioProducto;
    }

    function getClienteServicio() {
        return $this->clienteServicio;
    }

    function getProducto() {
        return $this->producto;
    }

    function getNota() {
        return $this->nota;
    }

    function getSecuencia() {
        return $this->secuencia;
    }

    function getCantidad() {
        return $this->cantidad;
    }

    function getValorUnitaConImpue() {
        return $this->valorUnitaConImpue;
    }

    function getSerial() {
        return $this->serial;
    }

    function getIdBodega() {
        return $this->idBodega;
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

    function setIdClienteServicioProducto($idClienteServicioProducto) {
        $this->idClienteServicioProducto = $idClienteServicioProducto;
    }

    function setClienteServicio(\entidad\ClienteServicio $clienteServicio) {
        $this->clienteServicio = $clienteServicio;
    }

    function setProducto(\entidad\Producto $producto) {
        $this->producto = $producto;
    }

    function setNota($nota) {
        
        if($nota == "" || $nota == null){
            $this->nota = "NULL";
        }else{
            $this->nota = "'".$nota."'";
        }
    }

    function setSecuencia($secuencia) {
        $this->secuencia = $secuencia;
    }

    function setCantidad($cantidad) {
        $this->cantidad = $cantidad;
    }

    function setValorUnitaConImpue($valorUnitaConImpue) {
        $this->valorUnitaConImpue = $valorUnitaConImpue;
    }

    function setSerial($serial) {
        if($serial == "" || $serial == null || $serial == "null"){
            $this->serial = "NULL";
        }else{
            $this->serial = "'".$serial."'";
        }
    }
    
    function setIdBodega($idBodega) {
        $this->idBodega = $idBodega;
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
