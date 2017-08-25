<?php
namespace entidad;
class TransaccionProveedor {
    private $idTransaccionProveedor;
    private $idTransaccion;
    private $idProveedor;
    private $idUsuarioCreacion;
    private $idUsuarioModicacion;
    private $fechaCreacion;
    private $fechaModificacion;
    private $documentoExterno;
    
    public function getDocumentoExterno() {
        return $this->documentoExterno;
    }

    public function setDocumentoExterno($documentoExterno) {
        $this->documentoExterno = $documentoExterno;
    }
    
    function getIdTransaccionProveedor() {
        return $this->idTransaccionProveedor;
    }

    function getIdTransaccion() {
        return $this->idTransaccion;
    }

    function getIdProveedor() {
        return $this->idProveedor;
    }

    function getIdUsuarioCreacion() {
        return $this->idUsuarioCreacion;
    }

    function getIdUsuarioModicacion() {
        return $this->idUsuarioModicacion;
    }

    function getFechaCreacion() {
        return $this->fechaCreacion;
    }

    function getFechaModificacion() {
        return $this->fechaModificacion;
    }

    function setIdTransaccionProveedor($idTransaccionProveedor) {
        $this->idTransaccionProveedor = $idTransaccionProveedor;
    }

    function setIdTransaccion($idTransaccion) {
        $this->idTransaccion = $idTransaccion;
    }

    function setIdProveedor($idProveedor) {
        $this->idProveedor = $idProveedor;
    }

    function setIdUsuarioCreacion($idUsuarioCreacion) {
        $this->idUsuarioCreacion = $idUsuarioCreacion;
    }

    function setIdUsuarioModicacion($idUsuarioModicacion) {
        $this->idUsuarioModicacion = $idUsuarioModicacion;
    }

    function setFechaCreacion($fechaCreacion) {
        $this->fechaCreacion = $fechaCreacion;
    }

    function setFechaModificacion($fechaModificacion) {
        $this->fechaModificacion = $fechaModificacion;
    }


}
