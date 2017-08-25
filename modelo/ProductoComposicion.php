<?php
namespace modelo;
session_start();
require_once($_SESSION['ruta'].'entorno/Conexion.php');
class ProductoComposicion {
    public $conexion;
    
    private $idProductoComposicion;
    private $idProductoCompuesto;
    private $cantidad;
    private $idProductoCompone;
    private $estado;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    
    public function __construct(\entidad\ProductoComposicion $productoComposicion) {
        $this->idProductoComposicion = $productoComposicion->getIdProductoComposicion();
        $this->idProductoCompuesto = $productoComposicion->getIdProductoCompuesto();
        $this->cantidad = $productoComposicion->getCantidad();
        $this->idProductoCompone = $productoComposicion->getIdProductoCompone();
        $this->estado = $productoComposicion->getEstado();
        $this->idUsuarioCreacion = $productoComposicion->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $productoComposicion->getIdUsuarioModificacion();
        $this->fechaCreacion = $productoComposicion->getFechaCreacion();
        $this->fechaModificacion = $productoComposicion->getFechaModificacion();
        
        $this->conexion = new \Conexion();
    }
    
    public function adicionar() {
        $sentenciaSql = "
                        INSERT INTO
                            facturacion_inventario.producto_composicion
                        (
                            id_producto_compuesto
                            , cantidad
                            , id_producto_compone
                            , estado
                            , id_usuario_creacion
                            , id_usuario_modificacion
                            , fecha_creacion
                            , fecha_modificacion
                        )
                        VALUES
                        (
                            $this->idProductoCompuesto
                            , $this->cantidad
                            , $this->idProductoCompone
                            , TRUE
                            , $this->idUsuarioCreacion
                            , $this->idUsuarioModificacion
                            , NOW()
                            , NOW()
                        )
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    public function modificar() {
        $sentenciaSql = "
                        UPDATE
                            facturacion_inventario.producto_composicion
                        SET
                            id_producto_compuesto = $this->idProductoCompuesto
                            , cantidad = $this->cantidad
                            , id_producto_compone = $this->idProductoCompone
                            , id_usuario_modificacion = $this->idUsuarioModificacion
                            , fecha_modificacion = NOW()
                        WHERE
                            id_producto_composicion = $this->idProductoComposicion
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    public function eliminar() {
        $sentenciaSql = "
                        DELETE FROM
                            facturacion_inventario.producto_composicion
                        WHERE
                            id_producto_composicion = $this->idProductoComposicion
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    public function consultar() {
        $sentenciaSql = "
                        SELECT
                            pc.id_producto_composicion
                            , pc.id_producto_compone
                            , p.codigo
                            , p.producto
                            , um.unidad_medida
                            , pc.cantidad AS cantidad
                        FROM
                            facturacion_inventario.producto_composicion AS pc
                            INNER JOIN facturacion_inventario.producto AS p ON p.id_producto = pc.id_producto_compone
                            INNER JOIN facturacion_inventario.unidad_medida AS um ON um.id_unidad_medida = p.id_unidad_medida
                        WHERE
                            pc.id_producto_compuesto = ".$this->idProductoCompuesto;
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["idProductoComposicion"] = $fila->id_producto_composicion;
            $retorno[$contador]["idProductoCompone"] = $fila->id_producto_compone;
            $retorno[$contador]["codigo"] = $fila->codigo;
            $retorno[$contador]["producto"] = $fila->producto;
            $retorno[$contador]["unidadMedida"] = $fila->unidad_medida;
            $retorno[$contador]["cantidad"] = $fila->cantidad;
            $contador++;
        }
        return $retorno;
    }
    public function consultarProductosComponen() {
        $sentenciaSql = "
                        SELECT
                            pc.id_producto_compone
                            , p.codigo
                            , p.producto
                            , pc.cantidad AS cantidad
                            , um.unidad_medida
                        FROM
                            facturacion_inventario.producto_composicion AS pc
                            INNER JOIN facturacion_inventario.producto AS p ON p.id_producto = pc.id_producto_compone
                            INNER JOIN facturacion_inventario.unidad_medida AS um ON um.id_unidad_medida = p.id_unidad_medida
                        WHERE
                            pc.id_producto_compuesto = ".$this->idProductoCompuesto."
                            AND p.estado = TRUE";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["idProductoCompone"] = $fila->id_producto_compone;
            $retorno[$contador]["codigo"] = $fila->codigo;
            $retorno[$contador]["producto"] = $fila->producto;
            $retorno[$contador]["cantidad"] = $fila->cantidad;
            $retorno[$contador]["unidadMedida"] = $fila->unidad_medida;
            $contador++;
        }
        return $retorno;
    }
    public function consultarProductoCompuestoInventariado($idTransaccionPadre) {
        $sentenciaSql = "
                        SELECT
                            p.codigo
                            , p.producto
                            , um.unidad_medida
                            , lp.linea_producto
                            , SUM(tp.cantidad) AS cantidad
                        FROM
                            facturacion_inventario.transaccion_producto AS tp
                            INNER JOIN facturacion_inventario.transaccion AS t ON t.id_transaccion = tp.id_transaccion
                            INNER JOIN facturacion_inventario.producto AS p ON p.id_producto = tp.id_producto
                            INNER JOIN facturacion_inventario.unidad_medida AS um ON um.id_unidad_medida = p.id_unidad_medida
                            INNER JOIN facturacion_inventario.linea_producto AS lp ON lp.id_linea_producto = p.id_linea_producto
                        WHERE
                            t.id_transaccion_padre = $idTransaccionPadre
                        GROUP BY
                            p.codigo
                            , p.producto
                            , um.unidad_medida
                            , lp.linea_producto";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["codigo"] = $fila->codigo;
            $retorno[$contador]["producto"] = $fila->producto;
            $retorno[$contador]["unidadMedida"] = $fila->unidad_medida;
            $retorno[$contador]["lineaProducto"] = $fila->linea_producto;
            $retorno[$contador]["cantidad"] = $fila->cantidad;
            $contador++;
        }
        return $retorno;
    }
    
}
