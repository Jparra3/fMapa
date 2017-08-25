<?php
session_start();
require_once '../entidad/ProductoExistencia.php';
require_once '../entidad/Producto.php';
require_once '../modelo/ProductoExistencia.php';

$retorno = array('exito'=>1, 'mensaje'=>'', 'data'=>null, 'numeroRegistros'=>0);

try {
    $idProducto = $_POST["idProducto"];
    
    $productoE = new \entidad\Producto();
    $productoE->setIdProducto($idProducto);
    
    $productoExistenciaE = new \entidad\ProductoExistencia();
    $productoExistenciaE->setProducto($productoE);
    
    $productoExistenciaM = new \modelo\ProductoExistencia($productoExistenciaE);
    $retorno["data"] = $productoExistenciaM->consultarBodegas();
    $retorno["numeroRegistros"] = $productoExistenciaM->conexion->obtenerNumeroRegistros();
} catch (Exception $e) {
    $retorno['exito'] =0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
