<?php
namespace modelo;
session_start();
require_once($_SESSION['ruta'].'entorno/Conexion.php');
class ProductoExistencia{
    
    public $conexion=null;
    private $condicion=null;
    private $whereAnd=null;
   
    private $idProductoExistencia;
    private $producto;
    private $minimo;
    private $maximo;
    private $idBodega;
    private $cantidad;
    private $idProductoUnidadNegocio;
    private $estado;
    private $serial;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    
    function __construct(\entidad\ProductoExistencia $productoExistencia) {
        $this->idProductoExistencia = $productoExistencia->getIdProductoExistencia();
        $this->producto = $productoExistencia->getProducto() != "" ? $productoExistencia->getProducto(): new \entidad\Producto();
        $this->minimo = $productoExistencia->getMinimo();
        $this->maximo = $productoExistencia->getMaximo();
        $this->idBodega = $productoExistencia->getIdBodega();
        $this->cantidad = $productoExistencia->getCantidad();
        $this->serial = $productoExistencia->getSerial();
        $this->idProductoUnidadNegocio = $productoExistencia->getIdProductoUnidadNegocio();
        $this->estado = $productoExistencia->getEstado();
        $this->idUsuarioCreacion = $productoExistencia->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $productoExistencia->getIdUsuarioModificacion();
        
        $this->conexion = new \Conexion();
    }
    
    public function adicionar() {
        $sentenciaSql = "
                        INSERT INTO
                            facturacion_inventario.producto_existencia
                        (
                            id_producto
                            , maximo
                            , minimo
                            , id_bodega
                        )
                        VALUES
                        (
                            ".$this->producto->getIdProducto()."
                            , $this->maximo
                            , $this->minimo
                            , $this->idBodega
                        )
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    
    public function modificar() {
        $sentenciaSql = "
                        UPDATE
                            facturacion_inventario.producto_existencia
                        SET
                            maximo = $this->maximo
                            , minimo = $this->minimo
                            , id_bodega = $this->idBodega
                        WHERE
                            id_producto_existencia = $this->idProductoExistencia
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    
    function consultar(){
        $this->obtenerCondicion();
        $sentenciaSql = "
                            SELECT 
                                p.id_producto
                                ,p.producto
                                ,SUM(pe.cantidad) AS cantidad
                                ,b.bodega
                                , p.maneja_inventario
                            FROM 
                                facturacion_inventario.producto_existencia AS pe
                                INNER JOIN facturacion_inventario.producto AS p ON p.id_producto = pe.id_producto
                                INNER JOIN facturacion_inventario.bodega AS b ON b.id_bodega = pe.id_bodega
                            $this->condicion    
                            GROUP BY 
				p.id_producto,b.bodega    
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        $retorno = array();
        while($fila = $this->conexion->obtenerObjeto()){
            $retorno[$contador]["idProducto"] = $fila->id_producto;
            $retorno[$contador]["producto"] = $fila->producto;
            $retorno[$contador]["cantidad"] = $fila->cantidad;
            $retorno[$contador]["bodega"] = $fila->bodega;
            $retorno[$contador]["manejaInventario"] = $fila->maneja_inventario;
            $contador++;
        }
        return $retorno;
    }
    function obtenerCondicion(){
        $this->condicion = '';
        $this->whereAnd = ' WHERE ';
        
        if($this->producto->getIdProducto() != '' && $this->producto->getIdProducto() != 'null' && $this->producto->getIdProducto() != null ){
            //$this->condicion .= $this->whereAnd . ' pe.id_producto = ' . $this->producto->getIdProducto();
            $this->condicion .= $this->whereAnd . ' pe.id_producto IN (' . $this->producto->getIdProducto().')';
            $this->whereAnd = ' AND ';
        }
        if($this->producto->getIdLineaProducto() != '' && $this->producto->getIdLineaProducto() != 'null' && $this->producto->getIdLineaProducto() != null ){
            $this->condicion .= $this->whereAnd . ' p.id_linea_producto = ' . $this->producto->getIdLineaProducto();
            $this->whereAnd = ' AND ';
        }
        
        if($this->producto->getManejaInventario() != '' && $this->producto->getManejaInventario() != 'null' && $this->producto->getManejaInventario() != null ){
            $this->condicion .= $this->whereAnd . ' p.maneja_inventario = ' . $this->producto->getManejaInventario();
            $this->whereAnd = ' AND ';
        }
        
        
        if($this->estado != '' && $this->estado != 'null' && $this->estado != null ){
            $this->condicion .= $this->whereAnd . ' pe.estado = '.$this->estado;
            $this->whereAnd = ' AND ';
        }
        
        if($this->serial != '' && $this->serial != 'null' && $this->serial != null ){
            $this->condicion .= $this->whereAnd . " pe.serial = '".$this->serial."'";
            $this->whereAnd = ' AND ';
        }
        
        if($this->idBodega != '' && $this->idBodega != 'null' && $this->idBodega != null ){
            $this->condicion .= $this->whereAnd . ' pe.id_bodega = '.$this->idBodega;
            $this->whereAnd = ' AND ';
        }
    }
    public function consultarExistencia(){
        $this->obtenerCondicion();
        $sentenciaSql = "SELECT DISTINCT
                            lp.linea_producto
                            , COALESCE(pe.serial,'') as serial
                            , p.codigo
                            , p.id_producto
                            , p.producto
                            , pe.cantidad
                            , bodega
                            , o.oficina
                            , p.valor_entrada AS valor
                            , pe.serial
                            ,(SELECT tp.serial_interno FROM facturacion_inventario.transaccion_producto AS tp WHERE pe.serial = tp.serial AND tp.serial_interno IS NOT NULL LIMIT 1) AS serial_interno
                            ,(pe.cantidad * p.valor_entrada) AS total
                        FROM
                            facturacion_inventario.producto_existencia AS pe
                            INNER JOIN facturacion_inventario.producto AS p ON p.id_producto = pe.id_producto
                            INNER JOIN facturacion_inventario.linea_producto AS lp ON lp.id_linea_producto = p.id_linea_producto
                            INNER JOIN facturacion_inventario.bodega AS b ON b.id_bodega = pe.id_bodega
                            INNER JOIN contabilidad.oficina AS o ON o.id_oficina = b.id_oficina
                            $this->condicion
                        ORDER BY 
                            p.id_producto
	";
//        echo $sentenciaSql;
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        $retorno = array();
        while($fila = $this->conexion->obtenerObjeto()){
            $retorno[$contador]["idProducto"] = $fila->id_producto;
            $retorno[$contador]["lineaProducto"] = $fila->linea_producto;
            $retorno[$contador]["idProducto"] = $fila->id_producto;
            $retorno[$contador]["producto"] = $fila->producto;
            $retorno[$contador]["codigo"] = $fila->codigo;
            $retorno[$contador]["serial"] = $fila->serial;
            $retorno[$contador]["serialInterno"] = $fila->serial_interno;
            $retorno[$contador]["cantidad"] = $fila->cantidad;
            $retorno[$contador]["bodega"] = $fila->bodega;
            $retorno[$contador]["oficina"] = $fila->oficina;
	    $retorno[$contador]["valor"] = $fila->valor;
            $retorno[$contador]["total"] = $fila->total;
            $contador++;
        }
        return $retorno;
    }
    
    function consultarBodegas(){
        $sentenciaSql = "
                            SELECT 
                                pe.id_producto_existencia
                                , pe.id_bodega
                                , b.bodega
                                , pe.maximo
                                , pe.minimo
                            FROM 
                                facturacion_inventario.producto_existencia AS pe
                                INNER JOIN facturacion_inventario.bodega AS b ON b.id_bodega = pe.id_bodega
                            WHERE
                                pe.id_producto = ".$this->producto->getIdProducto();
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        $retorno = array();
        while($fila = $this->conexion->obtenerObjeto()){
            $retorno[$contador]["idProductoExistencia"] = $fila->id_producto_existencia;
            $retorno[$contador]["idBodega"] = $fila->id_bodega;
            $retorno[$contador]["bodega"] = $fila->bodega;
            $retorno[$contador]["maximo"] = $fila->maximo;
            $retorno[$contador]["minimo"] = $fila->minimo;
	    
            $contador++;
        }
        return $retorno;
    }
    
    public function eliminar() {
        $sentenciaSql = "
                        DELETE FROM facturacion_inventario.producto_existencia WHERE id_producto_existencia = $this->idProductoExistencia
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
}
?>