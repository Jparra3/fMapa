<?php
namespace entidad;
class MovimientoContable {
    private $idMovimientoContable;
    private $idTipoDocumento;
    private $numeroTipoDocumento;
    private $fecha;
    private $nota;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    private $estado;
    
    public function getIdMovimientoContable() {
        return $this->idMovimientoContable;
    }

    public function getIdTipoDocumento() {
        return $this->idTipoDocumento;
    }

    public function getNumeroTipoDocumento() {
        return $this->numeroTipoDocumento;
    }

    public function getFecha() {
        return $this->fecha;
    }

    public function getNota() {
        return $this->nota;
    }

    public function getIdUsuarioCreacion() {
        return $this->idUsuarioCreacion;
    }

    public function getIdUsuarioModificacion() {
        return $this->idUsuarioModificacion;
    }

    public function getFechaCreacion() {
        return $this->fechaCreacion;
    }

    public function getFechaModificacion() {
        return $this->fechaModificacion;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function setIdMovimientoContable($idMovimientoContable) {
        $this->idMovimientoContable = $idMovimientoContable;
    }

    public function setIdTipoDocumento($idTipoDocumento) {
        $this->idTipoDocumento = $idTipoDocumento;
    }

    public function setNumeroTipoDocumento($numeroTipoDocumento) {
        $this->numeroTipoDocumento = $numeroTipoDocumento;
    }

    public function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    public function setNota($nota) {
        if($nota != "" && $nota != null && $nota != "null"){
            $this->nota = "'".$nota."'";
        }else{
            $this->nota = "null";
        }
    }

    public function setIdUsuarioCreacion($idUsuarioCreacion) {
        $this->idUsuarioCreacion = $idUsuarioCreacion;
    }

    public function setIdUsuarioModificacion($idUsuarioModificacion) {
        $this->idUsuarioModificacion = $idUsuarioModificacion;
    }

    public function setFechaCreacion($fechaCreacion) {
        $this->fechaCreacion = $fechaCreacion;
    }

    public function setFechaModificacion($fechaModificacion) {
        $this->fechaModificacion = $fechaModificacion;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }



}
