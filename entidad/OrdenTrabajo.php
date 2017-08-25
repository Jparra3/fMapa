<?php
namespace entidad;
require_once '../entidad/Ordenador.php';
require_once '../entidad/ClienteServicio.php';
class OrdenTrabajo {
    private $idOrdenTrabajo;
    private $fecha;
    private $idTipoDocumento;
    private $numero;
    private $ordenador;
    private $idMunicipio;
    private $fechaInicio;
    private $fechaFin;
    private $estado;
    private $idTecnico;
    private $idEstadoOrdenTrabajo;
    private $tipoOrdenTrabajo;
    private $clienteServicio;
    private $observacion;
    private $fechaCreacion;
    private $fechaModificacion;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    
    function getObservacion() {
        return $this->observacion;
    }

    function setObservacion($observacion) {
        $this->observacion = $observacion;
    }
    
    function getClienteServicio() {
        return $this->clienteServicio;
    }

    function setClienteServicio(\entidad\ClienteServicio $clienteServicio) {
        $this->clienteServicio = $clienteServicio;
    }

    function getTipoOrdenTrabajo() {
        return $this->tipoOrdenTrabajo;
    }

    function setTipoOrdenTrabajo($tipoOrdenTrabajo) {
        $this->tipoOrdenTrabajo = $tipoOrdenTrabajo;
    }

        
    function getIdEstadoOrdenTrabajo() {
        return $this->idEstadoOrdenTrabajo;
    }

    function setIdEstadoOrdenTrabajo($idEstadoOrdenTrabajo) {
        $this->idEstadoOrdenTrabajo = $idEstadoOrdenTrabajo;
    }
    
    function getIdTecnico() {
        return $this->idTecnico;
    }

    function setIdTecnico($idTecnico) {
        $this->idTecnico = $idTecnico;
    }

        
    function getIdOrdenTrabajo() {
        return $this->idOrdenTrabajo;
    }

    function getFecha() {
        return $this->fecha;
    }

    function getIdTipoDocumento() {
        return $this->idTipoDocumento;
    }

    function getNumero() {
        return $this->numero;
    }

    function getOrdenador() {
        return $this->ordenador;
    }

    function getIdMunicipio() {
        return $this->idMunicipio;
    }

    function getFechaInicio() {
        return $this->fechaInicio;
    }

    function getFechaFin() {
        return $this->fechaFin;
    }

    function getEstado() {
        return $this->estado;
    }

    function getFechaCreacion() {
        return $this->fechaCreacion;
    }

    function getFechaModificacion() {
        return $this->fechaModificacion;
    }

    function getIdUsuarioCreacion() {
        return $this->idUsuarioCreacion;
    }

    function getIdUsuarioModificacion() {
        return $this->idUsuarioModificacion;
    }

    function setIdOrdenTrabajo($idOrdenTrabajo) {
        $this->idOrdenTrabajo = $idOrdenTrabajo;
    }

    function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    function setIdTipoDocumento($idTipoDocumento) {
        $this->idTipoDocumento = $idTipoDocumento;
    }

    function setNumero($numero) {
        $this->numero = $numero;
    }

    function setOrdenador(\entidad\Ordenador $ordenador) {
        $this->ordenador = $ordenador;
    }

    function setIdMunicipio($idMunicipio) {
        $this->idMunicipio = $idMunicipio;
    }

    function setFechaInicio($fechaInicio) {
        $this->fechaInicio = $fechaInicio;
    }

    function setFechaFin($fechaFin) {
        $this->fechaFin = $fechaFin;
    }

    function setEstado($estado) {
        $this->estado = $estado;
    }

    function setFechaCreacion($fechaCreacion) {
        $this->fechaCreacion = $fechaCreacion;
    }

    function setFechaModificacion($fechaModificacion) {
        $this->fechaModificacion = $fechaModificacion;
    }

    function setIdUsuarioCreacion($idUsuarioCreacion) {
        $this->idUsuarioCreacion = $idUsuarioCreacion;
    }

    function setIdUsuarioModificacion($idUsuarioModificacion) {
        $this->idUsuarioModificacion = $idUsuarioModificacion;
    }

}
?>

