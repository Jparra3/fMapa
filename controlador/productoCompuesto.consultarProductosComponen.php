<?php
session_start();
require_once '../entidad/ProductoComposicion.php';
require_once '../modelo/ProductoComposicion.php';

$retorno = array('exito'=>1, 'mensaje'=>'', 'data'=>null, 'numeroRegistros'=>0);

try {
    $idProducto = $_POST["idProducto"];
    
    $productoComposicionE = new \entidad\ProductoComposicion();
    $productoComposicionE->setIdProductoCompuesto($idProducto);
    
    $productoComposicionM = new \modelo\ProductoComposicion($productoComposicionE);
    $retorno["data"] = $productoComposicionM->consultarProductosComponen();
    $retorno["numeroRegistros"] = $productoComposicionM->conexion->obtenerNumeroRegistros();
} catch (Exception $e) {
    $retorno['exito'] =0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
