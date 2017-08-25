<?php

namespace modelo;
session_start();
require_once($_SESSION['ruta'].'entorno/Conexion.php');

class EstadoClienteServicio {
    private $idEstadoClienteServicio;
    private $estadoClienteServicio;
    private $estado;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    
    public $conexion;
    public $condicion;
    public $whereAnd;
    
    function __construct(\entidad\EstadoClienteServicio $estadoClienteServicio) {
        $this->idEstadoClienteServicio = $estadoClienteServicio->getIdEstadoClienteServicio();
        $this->estadoClienteServicio = $estadoClienteServicio->getEstadoClienteServicio();
        $this->estado = $estadoClienteServicio->getEstado();
        $this->idUsuarioCreacion = $estadoClienteServicio->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $estadoClienteServicio->getIdUsuarioModificacion();
        
        $this->conexion = new \Conexion();
    }
    public function consultar() {
        $this->obtenerCondicion();
        $sentenciaSql = "SELECT
                            id_estado_cliente_servicio
                            , estado_cliente_servicio
                            , estado
                        FROM
                            publics_services.estado_cliente_servicio
                            
                        $this->condicion
                ";
        
        $this->conexion->ejecutar($sentenciaSql);
        
        $contador = 0;
        $retorno = array();
        
        while($fila = $this->conexion->obtenerObjeto()){
            $retorno[$contador]['idEstadoClienteServicio'] = $fila->id_estado_cliente_servicio;
            $retorno[$contador]['estadoClienteServicio'] = $fila->estado_cliente_servicio;
            $retorno[$contador]['estado'] = $fila->estado;
            $contador++;
        }
        
        return $retorno;
    }
    function obtenerCondicion(){
        $this->condicion = '';
        $this->whereAnd = ' WHERE ';
        
        if($this->estado != '' && $this->estado != 'null' && $this->estado != null ){
            $this->condicion .= $this->whereAnd . " estado = ".$this->estado;
            $this->whereAnd = ' AND ';
        }
    }
}

