<?php
namespace entidad;
require_once '../entidad/OrdenTrabajoCliente.php';
require_once '../entidad/OrdenTrabajo.php';
require_once '../entidad/Producto.php';
class OrdenTrabajoProducto {
    
    private $idOrdenTrabajoProducto;
    private $ordenTrabajoCliente;
    private $ordenTrabajo;
    private $producto;
    private $nota;
    private $secuencia;
    private $cantidad;
    private $valorUnitaConImpue;
    private $serial;
    private $idBodega;
    private $estado;
    private $idEstaOrdeTrabProdu;
    private $idTransaccion;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    
    function getIdTransaccion() {
        return $this->idTransaccion;
    }

    function setIdTransaccion($idTransaccion) {
        if($idTransaccion == "" || $idTransaccion == null || $idTransaccion == "null"){
            $this->idTransaccion = "NULL";
        }else{
            $this->idTransaccion = $idTransaccion;
        }
    }
    
    function getOrdenTrabajo(){
        return $this->ordenTrabajo;
    }
    
    function setOrdenTrabajo(\entidad\OrdenTrabajo $ordenTrabajo){
        $this->ordenTrabajo=$ordenTrabajo;
    }
    
    function getIdEstaOrdeTrabProdu() {
        return $this->idEstaOrdeTrabProdu;
    }

    function setIdEstaOrdeTrabProdu($idEstaOrdeTrabProdu) {
        $this->idEstaOrdeTrabProdu = $idEstaOrdeTrabProdu;
    }
    
    function getValorUnitaConImpue() {
        return $this->valorUnitaConImpue;
    }

    function setValorUnitaConImpue($valorUnitaConImpue) {
        $this->valorUnitaConImpue = $valorUnitaConImpue;
    }
    
    function getIdBodega() {
        return $this->idBodega;
    }

    function setIdBodega($idBodega) {
        $this->idBodega = $idBodega;
    }

        
    function getIdOrdenTrabajoProducto() {
        return $this->idOrdenTrabajoProducto;
    }

    function getOrdenTrabajoCliente() {
        return $this->ordenTrabajoCliente;
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

    function getSerial() {
        return $this->serial;
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

    function setIdOrdenTrabajoProducto($idOrdenTrabajoProducto) {
        $this->idOrdenTrabajoProducto = $idOrdenTrabajoProducto;
    }

    function setOrdenTrabajoCliente(\entidad\OrdenTrabajoCliente $ordenTrabajoCliente) {
        $this->ordenTrabajoCliente = $ordenTrabajoCliente;
    }

    function setProducto(\entidad\Producto $producto) {
        $this->producto = $producto;
    }

    function setNota($nota) {
        if($nota != "" && $nota != null){
            $this->nota = "'".$nota."'";
        }else{
            $this->nota = "NULL";
        }
        
    }

    function setSecuencia($secuencia) {
        $this->secuencia = $secuencia;
    }

    function setCantidad($cantidad) {
        $this->cantidad = $cantidad;
    }
    
    function setSerial($serial) {
        
        if($serial != "" && $serial != "null"){
            $this->serial = "'".$serial."'";
        }else{
            $this->serial = "NULL";
        }
        
        
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
