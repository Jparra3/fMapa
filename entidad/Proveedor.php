<?php
namespace entidad;
class Proveedor{
    private $idProveedor;
    private $sucursal;
    private $idProveedorEstado;
    private $fechaCreacion;
    private $fechaModificacion;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    
    public function getIdProveedorEstado() {
        return $this->idProveedorEstado;
    }

    public function setIdProveedorEstado($idProveedorEstado) {
        $this->idProveedorEstado = $idProveedorEstado;
    }

    public function getIdProveedor() {
        return $this->idProveedor;
    }

    public function getSucursal() {
        return $this->sucursal;
    }

    public function getFechaCreacion() {
        return $this->fechaCreacion;
    }

    public function getFechaModificacion() {
        return $this->fechaModificacion;
    }

    public function getIdUsuarioCreacion() {
        return $this->idUsuarioCreacion;
    }

    public function getIdUsuarioModificacion() {
        return $this->idUsuarioModificacion;
    }

    public function setIdProveedor($idProveedor) {
        $this->idProveedor = $idProveedor;
    }

    public function setSucursal(\entidad\Sucursal $sucursalE) {
        $this->sucursal = $sucursalE;
    }

    public function setFechaCreacion($fechaCreacion) {
        $this->fechaCreacion = $fechaCreacion;
    }

    public function setFechaModificacion($fechaModificacion) {
        $this->fechaModificacion = $fechaModificacion;
    }

    public function setIdUsuarioCreacion($idUsuarioCreacion) {
        $this->idUsuarioCreacion = $idUsuarioCreacion;
    }

    public function setIdUsuarioModificacion($idUsuarioModificacion) {
        $this->idUsuarioModificacion = $idUsuarioModificacion;
    }


}
?>
