<?php
namespace modelo;
session_start();
require_once($_SESSION['ruta'].'entorno/Conexion.php');
require_once('../entidad/ClienteServicio.php');
class ClienteServicioProducto {
    public $conexion;
    private $condicion;
    private $whereAnd;
    
    private $idClienteServicioProducto;
    private $clienteServicio;
    private $producto;
    private $nota;
    private $secuencia;
    private $cantidad;
    private $valorUnitaConImpue;
    private $serial;
    private $idBodega;
    private $idEstaClieServProd;
    private $idOrdenTrabajo;
    private $estado;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    
    public function __construct(\entidad\ClienteServicioProducto $clienteServicioProducto) {
        $this->idClienteServicioProducto = $clienteServicioProducto->getIdClienteServicioProducto();
        $this->clienteServicio = $clienteServicioProducto->getClienteServicio() != "" ? $clienteServicioProducto->getClienteServicio(): new \entidad\ClienteServicio();
        $this->producto = $clienteServicioProducto->getProducto() != "" ? $clienteServicioProducto->getProducto(): new \entidad\Producto();
        $this->nota = $clienteServicioProducto->getNota();
        $this->idBodega = $clienteServicioProducto->getIdBodega();
        $this->secuencia = $clienteServicioProducto->getSecuencia();
        $this->cantidad = $clienteServicioProducto->getCantidad();
        $this->valorUnitaConImpue = $clienteServicioProducto->getValorUnitaConImpue();
        $this->serial = $clienteServicioProducto->getSerial();
        $this->idEstaClieServProd = $clienteServicioProducto->getIdEstaClieServProd();
        $this->idOrdenTrabajo = $clienteServicioProducto->getIdOrdenTrabajo();
        $this->estado = $clienteServicioProducto->getEstado();
        $this->idUsuarioCreacion = $clienteServicioProducto->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $clienteServicioProducto->getIdUsuarioModificacion();
        
        $this->conexion = new \Conexion();
    }
    function adicionar(){
        $sentenciaSql = "
                            INSERT INTO
                                publics_services.cliente_servicio_producto
                            (
                                id_cliente_servicio
                                ,id_producto
                                ,nota
                                ,secuencia
                                ,cantidad
                                ,valor_unita_con_impue
                                ,serial
                                ,id_bodega
                                ,id_esta_clie_serv_prod
                                ,id_orden_trabajo
                                ,estado
                                ,id_usuario_creacion
                                ,id_usuario_modificacion
                                ,fecha_creacion
                                ,fecha_modificacion
                            )
                            VALUES
                            (
                                ".$this->clienteServicio->getIdClienteServicio()."
                                ,".$this->producto->getIdProducto()."
                                , $this->nota
                                , $this->secuencia
                                , $this->cantidad
                                , $this->valorUnitaConImpue
                                , $this->serial
                                , $this->idBodega
                                , $this->idEstaClieServProd
                                , $this->idOrdenTrabajo
                                , ".$this->estado."
                                , $this->idUsuarioCreacion
                                , $this->idUsuarioModificacion
                                , NOW()
                                , NOW()
                            )
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    function modificar(){
        $sentenciaSql = "
                            UPDATE
                                publics_services.cliente_servicio_producto
                            SET
                                id_cliente_servicio = ".$this->clienteServicio->getIdClienteServicio()."
                                ,id_producto = ".$this->producto->getIdProducto()."
                                ,nota = $this->nota
                                ,secuencia = $this->secuencia
                                ,cantidad = $this->cantidad
                                ,valor_unita_con_impue = $this->valorUnitaConImpue
                                ,serial = $this->serial
                                ,id_bodega = $this->idBodega
                                ,id_esta_clie_serv_prod = $this->idEstaClieServProd
                                ,id_orden_trabajo = $this->idOrdenTrabajo
                                ,estado = ".$this->estado."
                                ,id_usuario_modificacion = $this->idUsuarioModificacion
                                ,fecha_modificacion = NOW()
                            WHERE
                                id_orden_trabajo_producto = $this->idOrdenTrabajoProducto
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    
    public function actualizarEstado(){
        $sentenciaSql = "
                            UPDATE
                                publics_services.cliente_servicio_producto
                            SET
                                id_esta_clie_serv_prod = $this->idEstaClieServProd
                            WHERE
                                id_cliente_servicio_producto = $this->idClienteServicioProducto

                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    
    public function consultar(){
        $this->obtenerCondicion();
        $sentenciaSql = "
                        SELECT DISTINCT
                            csp.id_cliente_servicio_producto
                            , csp.id_cliente_servicio
                            , otc.id_orden_trabajo_cliente
                            , csp.id_producto
                            , csp.nota
                            , csp.secuencia
                            , csp.cantidad
                            , csp.valor_unita_con_impue
                            , csp.serial
                            , csp.estado
                            , csp.id_bodega
                            , b.bodega
                            , p.producto_serial
                            , p.valor_entrada
                            , p.valor_salida
                            , p.valor_entra_con_impue
                            , p.valor_salid_con_impue
                            , p.codigo as codigo_producto_compone
                            , p.producto as producto_compone
                            , COUNT(prc.id_producto) as cantidad_producto_compone
                            , cs.id_producto_composicion
                            , prc.codigo as codigo_producto_composicion
                            , prc.producto as producto_composicion
                            , um.unidad_medida
                            , s.id_tercero
                            , csp.id_esta_clie_serv_prod
                            , csp.id_orden_trabajo
                            ,cs.id_cliente
                        FROM
                            publics_services.cliente_servicio_producto csp
                            INNER JOIN facturacion_inventario.bodega AS b ON b.id_bodega = csp.id_bodega
                            INNER JOIN publics_services.cliente_servicio cs ON cs.id_cliente_servicio = csp.id_cliente_servicio
                            INNER JOIN facturacion_inventario.cliente AS c ON c.id_cliente = cs.id_cliente
                            INNER JOIN contabilidad.sucursal AS s ON s.id_sucursal = c.id_sucursal
                            INNER JOIN publics_services.orden_trabajo_cliente AS otc ON otc.id_orden_trabajo_cliente = cs.id_orden_trabajo_cliente
                            INNER JOIN publics_services.orden_trabajo ot ON ot.id_orden_trabajo = otc.id_orden_trabajo
                            INNER JOIN facturacion_inventario.producto p ON p.id_producto = csp.id_producto
                            INNER JOIN publics_services.cliente_servicio_producto cspc ON cspc.id_cliente_servicio = cs.id_cliente_servicio
                            INNER JOIN facturacion_inventario.producto prc ON prc.id_producto = cs.id_producto_composicion
                            INNER JOIN facturacion_inventario.unidad_medida um ON um.id_unidad_medida = p.id_unidad_medida
                            INNER JOIN publics_services.ordenador o ON o.id_ordenador = ot.id_ordenador
                            INNER JOIN general.municipio m ON m.id_municipio = ot.id_municipio
                        $this->condicion    
                        GROUP BY 
                               csp.id_cliente_servicio_producto, csp.id_cliente_servicio, csp.id_producto
                                , csp.nota, csp.secuencia, csp.cantidad, csp.valor_unita_con_impue, csp.serial, csp.estado
                                , p.codigo, p.producto, cs.id_producto_composicion, prc.codigo, prc.producto
                                ,otc.id_orden_trabajo_cliente,p.producto_serial,um.unidad_medida,b.bodega,s.id_tercero,p.valor_salida
                                ,p.valor_entra_con_impue,p.valor_salid_con_impue,p.valor_entrada,csp.id_esta_clie_serv_prod,
                                csp.id_transaccion,csp.id_orden_trabajo,cs.id_cliente
                        ORDER BY csp.id_cliente_servicio_producto
                ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        $retorno = array();
        
        while ($fila = $this->conexion->obtenerObjeto()){
            $retorno[$contador]['idClienteServicioProducto'] = $fila->id_cliente_servicio_producto;
            $retorno[$contador]['idClienteServicio'] = $fila->id_cliente_servicio;
            $retorno[$contador]['idOrdenTrabajoCliente'] = $fila->id_orden_trabajo_cliente;
            $retorno[$contador]['idProducto'] = $fila->id_producto;
            $retorno[$contador]['productoSerial'] = $fila->producto_serial;
            $retorno[$contador]['nota'] = $fila->nota;
            $retorno[$contador]['secuencia'] = $fila->secuencia;
            $retorno[$contador]['cantidad'] = $fila->cantidad;
            $retorno[$contador]['valorUnitario'] = $fila->valor_unita_con_impue;
            $retorno[$contador]['serial'] = $fila->serial;
            $retorno[$contador]['estado'] = $fila->estado;
            $retorno[$contador]['idBodega'] = $fila->id_bodega;
            $retorno[$contador]['bodega'] = $fila->bodega;
            $retorno[$contador]['unidadMedida'] = $fila->unidad_medida;
            $retorno[$contador]['productoSerial'] = $fila->producto_serial;
            $retorno[$contador]['valorSalida'] = $fila->valor_salida;
            $retorno[$contador]['valorEntrada'] = $fila->valor_entrada;
            $retorno[$contador]['valorEntraConImpue'] = $fila->valor_entra_con_impue;
            $retorno[$contador]['valorSalidConImpue'] = $fila->valor_salid_con_impue;
            $retorno[$contador]['codigoProductoCompone'] = $fila->codigo_producto_compone;
            $retorno[$contador]['productoCompone'] = $fila->producto_compone;
            $retorno[$contador]['idProductoComposicion'] = $fila->id_producto_composicion;
            $retorno[$contador]['codigoProductoComposicion'] = $fila->codigo_producto_composicion;
            $retorno[$contador]['productoComposicion'] = $fila->producto_composicion;
            $retorno[$contador]['cantidadProductoCompone'] = $fila->cantidad_producto_compone;
            $retorno[$contador]['idTercero'] = $fila->id_tercero;
            $retorno[$contador]['idEstaClieServProd'] = $fila->id_esta_clie_serv_prod;
            $retorno[$contador]['idOrdenTrabajo'] = $fila->id_orden_trabajo;
            $retorno[$contador]['idCliente'] = $fila->id_cliente;
            $contador++;
        }
        return $retorno;
    }
    function obtenerCondicion(){
        $this->condicion = '';
        $this->whereAnd = ' WHERE ';
        
        if($this->clienteServicio != '' && $this->clienteServicio != 'null' && $this->clienteServicio != null ){
            if($this->clienteServicio->getIdClienteServicio() != '' && $this->clienteServicio->getIdClienteServicio() != 'null' && $this->clienteServicio->getIdClienteServicio() != null ){
                $this->condicion .= $this->whereAnd . ' cs.id_cliente_servicio IN(' .$this->clienteServicio->getIdClienteServicio().')';
                $this->whereAnd = ' AND ';
            }
        }
        
        if($this->idClienteServicioProducto != '' && $this->idClienteServicioProducto != 'null' && $this->idClienteServicioProducto != null ){
            $this->condicion .= $this->whereAnd . ' csp.id_cliente_servicio_producto = '.$this->idClienteServicioProducto;
            $this->whereAnd = ' AND ';
        }
        
        if($this->estado != '' && $this->estado != 'null' && $this->estado != null ){
            $this->condicion .= $this->whereAnd . ' csp.estado = '.$this->estado;
            $this->whereAnd = ' AND ';
        }
        
    }
    function actualizarCantidadProducto(){
        $sentenciaSql = "   
                            UPDATE
                                publics_services.cliente_servicio_producto
                            SET
                                cantidad = ".$this->cantidad."
                                ,estado  = ".$this->estado."
                                ,nota = ".$this->nota."
                            WHERE
                                id_cliente_servicio_producto = ".$this->idClienteServicioProducto;
        $this->conexion->ejecutar($sentenciaSql);
    }
}
