<?php
namespace entidad;
require_once '../entidad/Producto.php';
class ProductoExistencia{
    private $idProductoExistencia;
    private $producto;
    private $minimo;
    private $maximo;
    private $idBodega;
    private $cantidad;
    private $idProductoUnidadNegocio;
    private $serial;
    private $estado;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    
    function getSerial() {
        return $this->serial;
    }

    function setSerial($serial) {
        $this->serial = $serial;
    }
    
    function getIdProductoExistencia() {
        return $this->idProductoExistencia;
    }

    function getProducto() {
        return $this->producto;
    }

    function getMinimo() {
        return $this->minimo;
    }

    function getMaximo() {
        return $this->maximo;
    }

    function getIdBodega() {
        return $this->idBodega;
    }

    function getCantidad() {
        return $this->cantidad;
    }

    function getIdProductoUnidadNegocio() {
        return $this->idProductoUnidadNegocio;
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

    function setIdProductoExistencia($idProductoExistencia) {
        $this->idProductoExistencia = $idProductoExistencia;
    }

    function setProducto(\entidad\Producto $producto) {
        $this->producto = $producto;
    }

    function setMinimo($minimo) {
        $this->minimo = $minimo;
    }

    function setMaximo($maximo) {
        $this->maximo = $maximo;
    }

    function setIdBodega($idBodega) {
        $this->idBodega = $idBodega;
    }

    function setCantidad($cantidad) {
        $this->cantidad = $cantidad;
    }

    function setIdProductoUnidadNegocio($idProductoUnidadNegocio) {
        $this->idProductoUnidadNegocio = $idProductoUnidadNegocio;
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

