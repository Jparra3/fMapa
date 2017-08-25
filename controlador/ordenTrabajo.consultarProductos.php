<?php
require_once '../entidad/Producto.php';
require_once '../modelo/Producto.php';

$retorno = array("exito"=>1, "mensaje"=>"", "data"=>null, "numeroRegistros"=>0);

try {
    $idProducto = $_POST["idProducto"];
    $codigoProducto = $_POST["codigoProducto"];
    $compuesto = $_POST["compuesto"];
    
    $productoE = new \entidad\Producto();
    $productoE->setIdProducto($idProducto);
    $productoE->setCodigo($codigoProducto);
    $productoE->setProductoComposicion($compuesto);
    
    $productoM = new \modelo\Producto($productoE);
    $retorno["data"] = $productoM->consultarProductos();
    $retorno["numeroRegistros"] = $productoM->conexion->obtenerNumeroRegistros();
} catch (Exception $exc) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $exc->getMessage();
}
echo json_encode($retorno);