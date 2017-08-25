<?php
session_start();
require_once '../entidad/ProductoComposicion.php';
require_once '../modelo/ProductoComposicion.php';

$retorno = array('exito'=>1, 'mensaje'=>'', 'data'=>null, 'numeroRegistros'=>0);

try {
    $idProducto = $_POST["idProducto"];
    $cantidad = str_replace(',', '.', $_POST["cantidad"]);
    
    $productoComposicionE = new \entidad\ProductoComposicion();
    $productoComposicionE->setIdProductoCompuesto($idProducto);
    
    $productoComposicionM = new \modelo\ProductoComposicion($productoComposicionE);
    $productosComponen = $productoComposicionM->consultarProductosComponen();
    $retorno["numeroRegistros"] = $productoComposicionM->conexion->obtenerNumeroRegistros();
    
    $contador = 0;
    while ($contador < count($productosComponen)){
        $retorno["data"][$contador]["codigo"] = $productosComponen[$contador]["codigo"];
        $retorno["data"][$contador]["producto"] = $productosComponen[$contador]["producto"];
        $retorno["data"][$contador]["cantidad"] = ($productosComponen[$contador]["cantidad"] * $cantidad);
        $retorno["data"][$contador]["unidadMedida"] = $productosComponen[$contador]["unidadMedida"];
        $contador++;
    }
    
} catch (Exception $e) {
    $retorno['exito'] =0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
