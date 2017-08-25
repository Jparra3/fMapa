<?php
session_start();
require_once '../entidad/ProductoCodigoBarras.php';
require_once '../modelo/ProductoCodigoBarras.php';

$retorno = array('exito'=>1, 'mensaje'=>'', 'data'=>null, 'numeroRegistros'=>0);

try {
    $idProducto = $_POST["idProducto"];
    
    $productoCodigoBarrasE = new \entidad\ProductoCodigoBarras();
    $productoCodigoBarrasE->setIdProducto($idProducto);
    
    $productoCodigoBarrasM = new \modelo\ProductoCodigoBarras($productoCodigoBarrasE);
    $retorno["data"] = $productoCodigoBarrasM->consultar();
    $retorno["numeroRegistros"] = $productoCodigoBarrasM->conexion->obtenerNumeroRegistros();
} catch (Exception $e) {
    $retorno['exito'] =0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
