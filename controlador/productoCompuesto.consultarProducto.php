<?php
require_once '../entidad/Producto.php';
require_once '../modelo/Producto.php';

$retorno = array("exito"=>1, "mensaje"=>"", "data"=>null, "numeroRegistros"=>0);

try {
    $codigoProducto = $_POST["codigoProducto"];
    
    $productoE = new \entidad\Producto();
    $productoE->setCodigo($codigoProducto);
    
    $productoM = new \modelo\Producto($productoE);
    $retorno["data"] = $productoM->consultarProductoCompuesto();
    $retorno["numeroRegistros"] = $productoM->conexion->obtenerNumeroRegistros();
} catch (Exception $exc) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $exc->getMessage();
}
echo json_encode($retorno);