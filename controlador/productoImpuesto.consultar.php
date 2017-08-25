<?php
session_start();
require_once '../entidad/ProductoImpuesto.php';
require_once '../modelo/ProductoImpuesto.php';

$retorno = array('exito'=>1, 'mensaje'=>'', 'data'=>null, 'numeroRegistros'=>0);

try {
    $idProducto = $_POST["idProducto"];
    
    $productoImpuestoE = new \entidad\ProductoImpuesto();
    $productoImpuestoE->setIdProducto($idProducto);
    
    $productoImpuestoM = new \modelo\ProductoImpuesto($productoImpuestoE);
    $retorno["data"] = $productoImpuestoM->consultar();
    $retorno["numeroRegistros"] = $productoImpuestoM->conexion->obtenerNumeroRegistros();
} catch (Exception $e) {
    $retorno['exito'] =0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
