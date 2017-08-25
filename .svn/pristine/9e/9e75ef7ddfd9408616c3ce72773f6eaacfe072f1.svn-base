<?php

namespace modelo;
session_start();
require_once($_SESSION['ruta'].'entorno/Conexion.php');
class EstadoPedidoProducto {
    private $idEstadoPedidoProducto;
    private $estadoPedidoProducto;
    private $estado;
    private $inicial;
    
    public $conexion;
    private $condicion;
    private $whereAnd;
    
    function __construct(\entidad\EstadoPedidoProducto $estadoPedido) {
        $this->idEstadoPedidoProducto = $estadoPedido->getIdEstadoPedidoProducto();
        $this->estadoPedidoProducto = $estadoPedido->getEstadoPedidoProducto();
        $this->estado = $estadoPedido->getEstado();
        $this->inicial = $estadoPedido->getInicial();
        
        $this->conexion = new \Conexion();
    }
    
    public function consultar() {
        $this->obtenerCondicion();
        $sentenciaSql = "SELECT
                            id_estado_pedido_producto
                            , estado_pedido_producto
                            , estado
                        FROM
                            facturacion_inventario.estado_pedido_producto
                            
                        $this->condicion
                ";
        
        $this->conexion->ejecutar($sentenciaSql);
        
        $contador = 0;
        $retorno = array();
        
        while($fila = $this->conexion->obtenerObjeto()){
            $retorno[$contador]['idEstadoPedido'] = $fila->id_estado_pedido_producto;
            $retorno[$contador]['estadoPedido'] = $fila->estado_pedido_producto;
            $retorno[$contador]['estado'] = $fila->estado;
            
            $contador++;
        }
        
        return $retorno;
    }
    public function obtenerEstadoInicial() {
        $sentenciaSql = "SELECT
                            id_estado_pedido_producto
                        FROM
                            facturacion_inventario.estado_pedido_producto
                        WHERE
                            inicial = TRUE
                 ";
        $this->conexion->ejecutar($sentenciaSql);
        
        if($this->conexion->obtenerNumeroRegistros() == 0){
            $retorno['exito'] = 0;
            $retorno['mensaje'] = 'No hay ningún estado de producto del pedido asignado.';
            return $retorno;
        }
        
        if($this->conexion->obtenerNumeroRegistros() > 1){
            $retorno['exito'] = 0;
            $retorno['mensaje'] = 'Hay mas de un estado de tipo finalizado.';
            return $retorno;
        }
        
        $fila = $this->conexion->obtenerObjeto();
        return $fila->id_estado_pedido_producto;
    }
    
    function obtenerCondicion(){
        $this->condicion = '';
        $this->whereAnd = ' WHERE ';
    }
}
