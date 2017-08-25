<?php

namespace modelo;
session_start();
require_once($_SESSION['ruta'].'entorno/Conexion.php');

class Factura{
    private $idFactura;
    private $clienteServicio;
    private $fechaInicial;
    private $fechaFinal;
    private $idEstadoFactura;
    private $valor;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    
    public $conexion;
    private $whereAnd;
    private $condicion;
    
    function __construct(\entidad\Factura $estadoFactura) {
        $this->idFactura = $estadoFactura->getIdFactura();
        $this->clienteServicio = $estadoFactura->getClienteServicio();
        $this->fechaInicial = $estadoFactura->getFechaInicial();
        $this->fechaFinal = $estadoFactura->getFechaFinal();
        $this->idEstadoFactura = $estadoFactura->getIdEstadoFactura();
        $this->valor = $estadoFactura->getValor();
        $this->idUsuarioCreacion = $estadoFactura->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $estadoFactura->getIdUsuarioModificacion();
        $this->fechaCreacion = $estadoFactura->getFechaCreacion();
        $this->fechaModificacion = $estadoFactura->getFechaModificacion();
        
        $this->conexion = new \Conexion();
    }
    
    public function consultar() {
        $this->obtenerCondicion();
        $sentenciaSql = "
                        SELECT
                            f.id_factura
                            , s.sucursal
                            , c.id_cliente
                            , f.fecha_inicial
                            , f.fecha_final
                            , p.producto
                            , cs.valor
                            , ef.estado_factura
                            , ef.finalizado
                            , t.tercero
                        FROM
                            publics_services.factura f
                            INNER JOIN publics_services.estado_factura ef ON ef.id_estado_factura = f.id_estado_factura
                            INNER JOIN publics_services.cliente_servicio cs ON cs.id_cliente_servicio = f.id_cliente_servicio
                            INNER JOIN facturacion_inventario.cliente c ON c.id_cliente = cs.id_cliente
                            INNER JOIN contabilidad.sucursal s ON s.id_sucursal = c.id_sucursal
                            INNER JOIN contabilidad.tercero t ON t.id_tercero = s.id_tercero
                            INNER JOIN facturacion_inventario.producto p ON p.id_producto = cs.id_producto_composicion
                            $this->condicion
                ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        $retorno = array();
        
        while($fila = $this->conexion->obtenerObjeto()){
            $retorno[$contador]['idFactura'] = $fila->id_factura;
            $retorno[$contador]['sucursal'] = $fila->sucursal;
            $retorno[$contador]['tercero'] = $fila->tercero;
            $retorno[$contador]['nombreCliente'] = "$fila->tercero - $fila->sucursal";
            $retorno[$contador]['idCliente'] = $fila->id_cliente;
            $retorno[$contador]['fechaInicial'] = $fila->fecha_inicial;
            $retorno[$contador]['fechaFinal'] = $fila->fecha_final;
            $retorno[$contador]['valor'] = $fila->valor;
            $retorno[$contador]['producto'] = $fila->producto;
            $retorno[$contador]['estadoFactura'] = $fila->estado_factura;
            $retorno[$contador]['finalizado'] = $fila->finalizado;
            
            $contador++;
        }
        return $retorno;
    }
    
    public function cambiarEstado() {
        $sentenciaSql = "
                        UPDATE
                            publics_services.factura
                        SET
                            id_estado_factura = $this->idEstadoFactura
                            , id_usuario_modificacion = ".$_SESSION['idUsuario']." 
                        WHERE
                            id_factura = $this->idFactura
                ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    function obtenerCondicion() {
        $this->condicion = '';
        $this->whereAnd = ' WHERE ';
        
        if($this->clienteServicio->getCliente()->getIdCliente() != '' && $this->clienteServicio->getCliente()->getIdCliente() != 'null' && $this->clienteServicio->getCliente()->getIdCliente() != null){
            $this->condicion = $this->condicion.$this->whereAnd. " cs.id_cliente = ".$this->clienteServicio->getCliente()->getIdCliente();
            $this->whereAnd = ' AND ';
        }
        
        if($this->idEstadoFactura != '' && $this->idEstadoFactura != 'null'){
            $this->condicion = $this->condicion.$this->whereAnd. " f.id_estado_factura IN($this->idEstadoFactura)";
            $this->whereAnd = ' AND ';
        }
        
        if($this->fechaInicial != '' && $this->fechaInicial != 'null'){
            if($this->fechaInicial['inicio'] != '' && $this->fechaInicial['fin'] != ''){
                $this->condicion = $this->condicion.$this->whereAnd. " f.fecha_inicial BETWEEN '".$this->fechaInicial['inicio']."' AND '".$this->fechaInicial['fin']."'";
                $this->whereAnd = ' AND ';
            }
            if($this->fechaInicial['inicio'] != '' && $this->fechaInicial['fin'] == ''){
                $this->condicion = $this->condicion.$this->whereAnd. " f.fecha_inicial >= '".$this->fechaInicial['inicio']."'";
                $this->whereAnd = ' AND ';
            }
            if($this->fechaInicial['inicio'] == '' && $this->fechaInicial['fin'] != ''){
                $this->condicion = $this->condicion.$this->whereAnd. " f.fecha_inicial <= '".$this->fechaInicial['fin']."'";
                $this->whereAnd = ' AND ';
            }
        }
        if($this->fechaFinal != '' && $this->fechaFinal != 'null'){
            if($this->fechaFinal['inicio'] != '' && $this->fechaFinal['fin'] != ''){
                $this->condicion = $this->condicion.$this->whereAnd. " f.fecha_final BETWEEN '".$this->fechaFinal['inicio']."' AND '".$this->fechaFinal['fin']."'";
                $this->whereAnd = ' AND ';
            }
            if($this->fechaFinal['inicio'] != '' && $this->fechaFinal['fin'] == ''){
                $this->condicion = $this->condicion.$this->whereAnd. " f.fecha_final >= '".$this->fechaFinal['inicio']."'";
                $this->whereAnd = ' AND ';
            }
            if($this->fechaFinal['inicio'] == '' && $this->fechaFinal['fin'] != ''){
                $this->condicion = $this->condicion.$this->whereAnd. " f.fecha_final <= '".$this->fechaFinal['fin']."'";
                $this->whereAnd = ' AND ';
            }
        }
    }
}
