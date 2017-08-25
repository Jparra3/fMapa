<?php
namespace modelo;
session_start();
require_once($_SESSION['ruta'].'entorno/Conexion.php');

class EstadoOrdenTrabajo {
    
    public $conexion;
    private $condicion;
    private $whereAnd;
    
    private $idEstadoOrdenTrabajo;
    private $estadoOrdenTrabajo;
    private $estado;
    private $principal;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    
    function __construct(\entidad\EstadoOrdenTrabajo $estadoOrdenTrabajo) {
        $this->idEstadoOrdenTrabajo = $estadoOrdenTrabajo->getIdEstadoOrdenTrabajo();
        $this->estadoOrdenTrabajo = $estadoOrdenTrabajo->getEstadoOrdenTrabajo();
        $this->principal = $estadoOrdenTrabajo->getPrincipal();
        $this->estado = $estadoOrdenTrabajo->getEstado();
        $this->idUsuarioCreacion = $estadoOrdenTrabajo->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $estadoOrdenTrabajo->getIdUsuarioModificacion();
        
        $this->conexion = new \Conexion();
    }
    function consultar(){
        $this->obtenerCondicion();
        $sentenciaSql = "
                            SELECT 
                                id_estado_orden_trabajo
                                ,estado_orden_trabajo
                                ,estado
                                ,principal
                            FROM
                                publics_services.estado_orden_trabajo
                                $this->condicion
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while($fila = $this->conexion->obtenerObjeto()){
            $retorno[$contador]["idEstadoOrdenTrabajo"] = $fila->id_estado_orden_trabajo;
            $retorno[$contador]["estadoOrdenTrabajo"] = $fila->estado_orden_trabajo;
            $retorno[$contador]["estado"] = $fila->estado;
            $retorno[$contador]["principal"] = $fila->principal;
            $contador++;
        }
        return $retorno;
    }
    function obtenerCondicion(){
        $this->condicion = '';
        $this->whereAnd = ' WHERE ';
        
        if($this->estado != '' && $this->estado != 'null' && $this->estado != null ){
            $this->condicion .= $this->whereAnd . ' estado = ' . $this->estado;
            $this->whereAnd = ' AND ';
        }
    }    
}

