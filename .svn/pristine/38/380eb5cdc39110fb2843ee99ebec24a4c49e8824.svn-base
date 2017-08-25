<?php

namespace modelo;
session_start();
require_once($_SESSION['ruta'].'entorno/Conexion.php');

class EstadoFactura {
    private $idEstadoFactura;
    private $estadoFactura;
    private $finalizado;
    private $inicial;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    
    public $conexion;
    private $whereAnd;
    private $condicion;
    
    function __construct(\entidad\EstadoFactura $estadoFactura) {
        $this->idEstadoFactura = $estadoFactura->getIdEstadoFactura();
        $this->estadoFactura = $estadoFactura->getEstadoFactura();
        $this->finalizado = $estadoFactura->getFinalizado();
        $this->inicial = $estadoFactura->getInicial();
        $this->idUsuarioCreacion = $estadoFactura->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $estadoFactura->getIdUsuarioModificacion();
        $this->fechaCreacion = $estadoFactura->getFechaCreacion();
        $this->fechaModificacion = $estadoFactura->getFechaModificacion();
        
        $this->conexion = new \Conexion();
    }
    public function consutlar() {
        $this->obtenerCondicion();
        $sentenciaSql = "SELECT
                            ef.id_estado_factura
                            , ef.estado_factura
                            , ef.finalizado
                            , ef.inicial
                        FROM
                            publics_services.estado_factura ef
                        $this->condicion";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        $retorno=array();
        while ($fila = $this->conexion->obtenerObjeto()){
            $retorno[$contador]['idEstadoFactura']=  $fila->id_estado_factura;
            $retorno[$contador]['estadoFactura']=  $fila->estado_factura;
            $retorno[$contador]['finzalizado']=  $fila->finzalizado;
            $retorno[$contador]['inicial']=  $fila->inicial;
            $contador++;
        }
        
        return $retorno;
    }
    public function obtenerEstado() {
        $this->obtenerCondicion();
        $sentenciaSql = "SELECT
                            ef.id_estado_factura
                            , ef.estado_factura
                        FROM
                            publics_services.estado_factura ef
                        $this->condicion";
        $this->conexion->ejecutar($sentenciaSql);
        $numeroRegistros = $this->conexion->obtenerNumeroRegistros();
        
        if($numeroRegistros == 0){
            $retorno['exito'] = 0;
            if($this->finalizado != '' && $this->finalizado != 'null')
                $retorno['mensaje'] = 'No hay estados de tipo finalizado';
            else if($this->inicial != '' && $this->inicial != 'null')
                $retorno['mensaje'] = 'No hay estados de tipo inicial';
        
            return $retorno;
        }else if($numeroRegistros > 1){
            $retorno['exito'] = 0;
            if($this->finalizado != '' && $this->finalizado != 'null')
                $retorno['mensaje'] = 'Hay mas de un estado de tipo finalizado';
            else if($this->inicial != '' && $this->inicial != 'null')
                $retorno['mensaje'] = 'Hay mas de un estado de tipo inicial';
            
            return $retorno;
        }
        
        $retorno=array();
        $fila = $this->conexion->obtenerObjeto();
        $retorno['idEstadoFactura']=  $fila->id_estado_factura;
        $retorno['estadoFactura']=  $fila->estado_factura;
        $retorno['exito']=  1;
        
        return $retorno;
    }
    function obtenerCondicion() {
        $this->condicion = '';
        $this->whereAnd = ' WHERE ';
        
        if($this->finalizado != '' && $this->finalizado != 'null'){
            $this->condicion = $this->condicion.$this->whereAnd. ' ef.finalizado = '.$this->finalizado;
            $this->whereAnd = ' AND ';
        }
        if($this->inicial != '' && $this->inicial != 'null'){
            $this->condicion = $this->condicion.$this->whereAnd. ' ef.inicial = '.$this->inicial;
            $this->whereAnd = ' AND ';
        }
    }
}
