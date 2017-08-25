<?php
namespace modelo;
session_start();
require_once($_SESSION['ruta'].'entorno/Conexion.php');
class ProductoCodigoBarras {
    public $conexion = null;
    
    private $idProductoCodigoBarras;
    private $idProducto;
    private $estado;
    private $codigoBarras;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    
    public function __construct(\entidad\ProductoCodigoBarras $productoCodigoBarras) {
        $this->idProductoCodigoBarras = $productoCodigoBarras->getIdProductoCodigoBarras();
        $this->idProducto = $productoCodigoBarras->getIdProducto();
        $this->estado = $productoCodigoBarras->getEstado();
        $this->codigoBarras = $productoCodigoBarras->getCodigoBarras();
        $this->idUsuarioCreacion = $productoCodigoBarras->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $productoCodigoBarras->getIdUsuarioModificacion();
        $this->fechaCreacion = $productoCodigoBarras->getFechaCreacion();
        $this->fechaModificacion = $productoCodigoBarras->getFechaModificacion();
        
        $this->conexion = new \Conexion();
    }
    public function adicionar() {
        $sentenciaSql = "
                        INSERT INTO
                            facturacion_inventario.producto_codigo_barras
                        (
                            id_producto
                            , estado
                            , codigo_barras
                            , id_usuario_creacion
                            , id_usuario_modificacion
                            , fecha_creacion
                            , fecha_modificacion
                        )
                        VALUES
                        (
                            $this->idProducto
                            , TRUE
                            , '$this->codigoBarras'
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
                            facturacion_inventario.producto_codigo_barras
                        SET
                            codigo_barras = '$this->codigoBarras'
                            , fecha_modificacion = NOW()
                            , id_usuario_modificacion = $this->idUsuarioModificacion
                        WHERE
                            id_producto_codigo_barras = $this->idProductoCodigoBarras
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    
    public function consultar() {
        $sentenciaSql = "
                        SELECT
                            id_producto_codigo_barras
                            , codigo_barras
                        FROM
                            facturacion_inventario.producto_codigo_barras
                        WHERE
                            id_producto = $this->idProducto
                            AND  estado = TRUE
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["idProductoCodigoBarras"] = $fila->id_producto_codigo_barras;
            $retorno[$contador]["codigoBarras"] = $fila->codigo_barras;
            $contador++;
        }
        return $retorno;
    }
    
    public function eliminar() {
        $sentenciaSql = "
                        DELETE FROM
                            facturacion_inventario.producto_codigo_barras
                        WHERE
                            id_producto_codigo_barras = $this->idProductoCodigoBarras
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
}
