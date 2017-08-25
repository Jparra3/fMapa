<?php

namespace modelo;
session_start();
require_once($_SESSION['ruta'].'entorno/Conexion.php');
class EstadoPedido {
    private $idEstadoPedido;
    private $estadoPedido;
    private $estado;
    private $finalizado;
    private $activo;
    private $inicial;
    
    public $conexion;
    private $condicion;
    private $whereAnd;
    
    function __construct(\entidad\EstadoPedido $estadoPedido) {
        $this->idEstadoPedido = $estadoPedido->getIdEstadoPedido();
        $this->estadoPedido = $estadoPedido->getEstadoPedido();
        $this->estado = $estadoPedido->getEstado();
        $this->finalizado = $estadoPedido->getFinalizado();
        $this->activo = $estadoPedido->getActivo();
        $this->inicial = $estadoPedido->getInicial();
        
        $this->conexion = new \Conexion();
    }
    
    public function consultar() {
        $this->obtenerCondicion();
        $sentenciaSql = "SELECT
                            id_estado_pedido
                            , estado_pedido
                            , estado
                        FROM
                            facturacion_inventario.estado_pedido
                            
                        $this->condicion
                ";
        
        $this->conexion->ejecutar($sentenciaSql);
        
        $contador = 0;
        $retorno = array();
        
        while($fila = $this->conexion->obtenerObjeto()){
            $retorno[$contador]['idEstadoPedido'] = $fila->id_estado_pedido;
            $retorno[$contador]['estadoPedido'] = $fila->estado_pedido;
            $retorno[$contador]['estado'] = $fila->estado;
            
            $contador++;
        }
        
        return $retorno;
    }
    public function obtenerEstadoFinalizado() {
        $sentenciaSql = "SELECT
                            id_estado_pedido 
                        FROM
                            facturacion_inventario.estado_pedido
                        WHERE
                            finzalida = TRUE
                 ";
        $this->conexion->ejecutar($sentenciaSql);
        
        if($this->conexion->obtenerNumeroRegistros() > 1){
            $retorno['exito'] = 0;
            $retorno['mensaje'] = 'Hay mas de un estado de tipo finalizado.';
            return $retorno;
        }
        
        $fila = $this->conexion->obtenerObjeto();
        return $fila->id_estado_pedido;
    }
    public function obtenerEstadoInicial() {
        $sentenciaSql = "SELECT
                            id_estado_pedido 
                        FROM
                            facturacion_inventario.estado_pedido
                        WHERE
                            inicial = TRUE
                 ";
        $this->conexion->ejecutar($sentenciaSql);
        
        if($this->conexion->obtenerNumeroRegistros() > 1){
            $retorno['exito'] = 0;
            $retorno['mensaje'] = 'Hay mas de un estado de tipo inicial.';
            return $retorno;
        }
        
        $fila = $this->conexion->obtenerObjeto();
        return $fila->id_estado_pedido;
    }
    
    function obtenerCondicion(){
        $this->condicion = '';
        $this->whereAnd = ' WHERE ';
    }
}
