<?php
namespace entidad;
class Ruta {
    private $idRuta;
    private $fechaHora;
    private $estado;
    private $idRutaEstado;
    private $idRutaPadre;
    private $idZona;
    private $idRegional;
    private $archivo;
    private $jsonData;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    
    private $cliente;
    private $fechaFin;
    
    function getJsonData() {
        return $this->jsonData;
    }

    function setJsonData($jsonData) {
        if($jsonData != "" && $jsonData != "null" && $jsonData != null){
            $this->jsonData = "'".$jsonData."'";
        }else{
            $this->jsonData = "null";
        }
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
    
    function getArchivo() {
        return $this->archivo;
    }

    function setArchivo($archivo) {
        if($archivo != "" && $archivo != "null" && $archivo != null){
            $this->archivo = "'".$archivo."'";
        }else{
            $this->archivo = "null";
        }
    }
    
    function getIdRuta() {
        return $this->idRuta;
    }

    function getFechaHora() {
        return $this->fechaHora;
    }

    function getEstado() {
        return $this->estado;
    }

    function getIdRutaEstado() {
        return $this->idRutaEstado;
    }

    function getIdRutaPadre() {
        return $this->idRutaPadre;
    }

    function getIdZona() {
        return $this->idZona;
    }

    function getIdRegional() {
        return $this->idRegional;
    }

    function setIdRuta($idRuta) {
        $this->idRuta = $idRuta;
    }

    function setFechaHora($fechaHora) {
        $this->fechaHora = $fechaHora;
    }

    function setEstado($estado) {
        $this->estado = $estado;
    }

    function setIdRutaEstado($idRutaEstado) {
        $this->idRutaEstado = $idRutaEstado;
    }

    function setIdRutaPadre($idRutaPadre) {
        $this->idRutaPadre = $idRutaPadre;
    }

    function setIdZona($idZona) {
        $this->idZona = $idZona;
    }

    function setIdRegional($idRegional) {
        $this->idRegional = $idRegional;
    }

        
    function getCliente() {
        return $this->cliente;
    }

    function setCliente(\entidad\Cliente $cliente) {
        $this->cliente = $cliente;
    }

    function getFechaFin() {
        return $this->fechaFin;
    }

    function setFechaFin($fechaFin) {
        $this->fechaFin = $fechaFin;
    }    
}