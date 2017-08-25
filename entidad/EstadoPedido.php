<?php

namespace entidad;
class EstadoPedido {
    private $idEstadoPedido;
    private $estadoPedido;
    private $estado;
    private $finalizado;
    private $activo;
    private $inicial;
    
    function getIdEstadoPedido() {
        return $this->idEstadoPedido;
    }

    function getEstadoPedido() {
        return $this->estadoPedido;
    }

    function getEstado() {
        return $this->estado;
    }

    function getFinalizado() {
        return $this->finalizado;
    }

    function getActivo() {
        return $this->activo;
    }

    function getInicial() {
        return $this->inicial;
    }

    function setIdEstadoPedido($idEstadoPedido) {
        $this->idEstadoPedido = $idEstadoPedido;
    }

    function setEstadoPedido($estadoPedido) {
        $this->estadoPedido = $estadoPedido;
    }

    function setEstado($estado) {
        $this->estado = $estado;
    }

    function setFinalizado($finalizado) {
        $this->finalizado = $finalizado;
    }

    function setActivo($activo) {
        $this->activo = $activo;
    }

    function setInicial($inicial) {
        $this->inicial = $inicial;
    }


}
