<?php
session_start();
require_once '../entidad/Producto.php';
require_once '../modelo/Producto.php';
require_once '../entidad/ProductoExistencia.php';
require_once '../modelo/ProductoExistencia.php';

$retorno = array('exito'=>1, 'mensaje'=>'', 'data'=>null, 'numeroRegistros'=>0);

try {
    $idProducto = $_POST["idProducto"];
    $idBodega = $_POST["idBodega"];
    $idOficina = $_POST["idOficina"];
    $idLineaProducto = $_POST["idLineaProducto"];
    $bodega = $_POST["bodega"];
    $serial = $_POST["serial"];

    $productoE = new \entidad\Producto();
    $productoE->setIdProducto($idProducto);
    $productoE->setIdLineaProducto($idLineaProducto);
    
    $productoExitenciaE = new \entidad\ProductoExistencia();
    $productoExitenciaE->setIdBodega($idBodega);
    $productoExitenciaE->setProducto($productoE);
    $productoExitenciaE->setSerial($serial);
    
    $productoExitenciaM = new \modelo\ProductoExistencia($productoExitenciaE);
    $retorno['data'] = $productoExitenciaM->consultarExistencia();
    $retorno['numeroRegistros'] = $productoExitenciaM->conexion->obtenerNumeroRegistros();
} catch (Exception $e) {
    $retorno['exito'] =0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
