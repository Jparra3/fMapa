<?php

namespace modelo;
session_start();
require_once($_SESSION['ruta'].'entorno/Conexion.php');
require_once '../entidad/Producto.php';

class PedidoProducto {
    private $idPedidoProducto;
    private $producto;
    private $idPedidoProductoEstado;
    private $nota;
    private $secuencia;
    private $cantidad;
    private $valorUnitaConImpue;
    private $idPedido;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    
    public $conexion;
    private $condicion;
    private $whereAnd;
    
    function __construct(\entidad\PedidoProducto $pedidoProducto) {
        $this->idPedidoProducto = $pedidoProducto->getIdPedidoProducto();
        $this->producto = $pedidoProducto->getProducto() != "" ? $pedidoProducto->getProducto(): new \entidad\Producto();
        $this->idPedidoProductoEstado = $pedidoProducto->getIdPedidoProductoEstado();
        $this->nota = $pedidoProducto->getNota();
        $this->cantidad = $pedidoProducto->getCantidad();
        $this->secuencia = $pedidoProducto->getSecuencia();
        $this->valorUnitaConImpue = $pedidoProducto->getValorUnitaConImpue();
        $this->idPedido = $pedidoProducto->getIdPedido();
        $this->idUsuarioCreacion = $pedidoProducto->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $pedidoProducto->getIdUsuarioModificacion();
        $this->fechaCreacion = $pedidoProducto->getFechaCreacion();
        $this->fechaModificacion = $pedidoProducto->getFechaModificacion();
        
        $this->conexion = new \Conexion();
    }
    public function adicionar() {
        $sentenciaSql = "INSERT INTO
                            facturacion_inventario.pedido_producto(
                                id_producto
                                , id_pedido
                                , id_pedido_producto_estado
                                , nota
                                , cantidad
                                , secuencia
                                , valor_unita_con_impue
                                , id_usuario_creacion
                                , id_usuario_modificacion
                                , fecha_creacion
                                , fecha_modificacion
                            )
                        VALUES(
                                ".$this->producto->getIdProducto()."
                                , $this->idPedido
                                , $this->idPedidoProductoEstado
                                , '$this->nota'
                                , $this->cantidad
                                , $this->secuencia
                                , $this->valorUnitaConImpue
                                , $this->idUsuarioCreacion
                                , $this->idUsuarioModificacion
                                , NOW()
                                , NOW()
                        )
                ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    public function consultar() {
        $this->obtenerCondicion();
        $sentenciaSql = "SELECT
                            p.id_pedido
                            , p.id_cliente
                            , p.fecha
                            , p.nota
                            , p.id_pedido_estado
                            , pp.id_pedido_producto
                            , pp.nota as nota_pedido_producto
                            , pp.secuencia
                            , pp.cantidad
                            , pp.valor_unita_con_impue
                            , pp.id_pedido_producto_estado
                            , epp.estado_pedido_producto
                            , pr.id_producto
                            , pr.producto
                            , pr.codigo
                            , pp.cantidad * pp.valor_unita_con_impue as valor_total
                        FROM
                            facturacion_inventario.pedido p
                            INNER JOIN facturacion_inventario.pedido_producto pp ON pp.id_pedido = p.id_pedido
                            INNER JOIN facturacion_inventario.producto pr ON pr.id_producto = pp.id_producto
                            INNER JOIN facturacion_inventario.estado_pedido_producto epp ON epp.id_estado_pedido_producto = pp.id_pedido_producto_estado
                            
                        $this->condicion
                        ORDER BY id_pedido_producto
                ";
        
        $this->conexion->ejecutar($sentenciaSql);
        
        $contador = 0;
        $retorno = array();
        
        while($fila = $this->conexion->obtenerObjeto()){
            $retorno[$contador]['idPedido'] = $fila->id_pedido;
            $retorno[$contador]['idCliente'] = $fila->id_cliente;
            $retorno[$contador]['fecha'] = $fila->fecha;
            $retorno[$contador]['nota_pedido'] = $fila->nota;
            $retorno[$contador]['idPedidoProducto'] = $fila->id_pedido_producto;
            $retorno[$contador]['notaPedidoProducto'] = $fila->nota_pedido_producto;
            $retorno[$contador]['secuencia'] = $fila->secuencia;
            $retorno[$contador]['cantidad'] = $fila->cantidad;
            $retorno[$contador]['valorUnitario'] = $fila->valor_unita_con_impue;
            $retorno[$contador]['idPedidoProductoEstado'] = $fila->id_pedido_producto_estado;
            $retorno[$contador]['pedidoProductoEstado'] = $fila->estado_pedido_producto;
            $retorno[$contador]['idProducto'] = $fila->id_producto;
            $retorno[$contador]['producto'] = $fila->producto;
            $retorno[$contador]['codigo'] = $fila->codigo;
            $retorno[$contador]['valorTotal'] = $fila->valor_total;
            
            $contador++;
        }
        
        return $retorno;
    }
    
    public function eliminar() {
        $sentenciaSql = "DELETE FROM 
                            facturacion_inventario.pedido_producto
                        WHERE
                            id_pedido = $this->idPedido
                ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    
    function obtenMaximSecuePedid() {
        $sentenciaSql = "SELECT
                            COALESCE(secuencia, 0) AS maximo
                        FROM 
                            facturacion_inventario.pedido_producto
                        WHERE
                            id_pedido = $this->idPedido
                ";
        $this->conexion->ejecutar($sentenciaSql);
        $fila = $this->conexion->obtenerObjeto();
        return $fila->maximo;
    }
    public function actualizarSecuencia(){
        $data = $this->consultar();
        $sentenciaSql = "
                        UPDATE
                            facturacion_inventario.pedido_producto
                        SET
                            secuencia = 0
                        WHERE
                            id_pedido = $this->idPedido
            ";
        $this->conexion->ejecutar($sentenciaSql);
        
        foreach ($data as $registro){
            $secuencia = $this->obtenMaximSecuePedid();
            $sentenciaSql = "
                        UPDATE
                            facturacion_inventario.pedido_producto
                        SET
                            secuencia = $secuencia
                        WHERE
                            id_pedido_producto = ".$registro['idPedidoProducto'];
            ;
            $this->conexion->ejecutar($sentenciaSql);
        }
    }
    function obtenerCondicion(){
        $this->condicion = '';
        $this->whereAnd = ' WHERE ';
        
        if($this->idPedido != '' && $this->idPedido != 'null' && $this->idPedido != null){
            $this->condicion .= $this->whereAnd . ' p.id_pedido = ' .$this->idPedido;
            $this->whereAnd = ' AND ';
        }
    }
}
