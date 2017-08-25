<?php
namespace modelo;
session_start();
require_once($_SESSION['ruta'].'entorno/Conexion.php');
class ProductoUnidadMedida {
    public $conexion = null;

    private $idProductoUnidadMedida;
    private $idProducto;
    private $idUnidadMedida;
    private $principal;
    
    public function __construct(\entidad\ProductoUnidadMedida $productoUnidadMedida) {
        $this->idProductoUnidadMedida = $productoUnidadMedida->getIdProductoUnidadMedida();
        $this->idProducto = $productoUnidadMedida->getIdProducto();
        $this->idUnidadMedida = $productoUnidadMedida->getIdUnidadMedida();
        $this->principal = $productoUnidadMedida->getPrincipal();
        
        $this->conexion = new \Conexion();
    }
    
    public function consultar() {
        $sentenciaSql = "
                        SELECT
                            pum.id_unidad_medida
                            , um.unidad_medida
                            , pum.principal
                        FROM
                            facturacion_inventario.producto_unidad_medida AS pum
                            INNER JOIN facturacion_inventario.unidad_medida AS um ON um.id_unidad_medida = pum.id_unidad_medida
                        WHERE
                            pum.id_producto = ".$this->idProducto;
        $contador = 0;
        $this->conexion->ejecutar($sentenciaSql);
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["idUnidadMedida"] = $fila->id_unidad_medida;
            $retorno[$contador]["unidadMedida"] = $fila->unidad_medida;
            $retorno[$contador]["principal"] = $fila->principal;
            $contador++;
        }
        return $retorno;
    }
}
