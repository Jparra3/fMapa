<?php
session_start();
require_once '../entidad/Producto.php';
require_once '../entidad/ProductoExistencia.php';
require_once '../modelo/ProductoExistencia.php';

$retorno = array('exito'=>1, 'mensaje'=>'', 'data'=>null , 'numeroRegistros'=>0);

try {
    
    $idProducto = $_POST['idProducto'];
    $idBodega = $_POST['idBodega'];
    
    $productoE = new \entidad\Producto();
    $productoE->setIdProducto($idProducto);
    //$productoE->setManejaInventario("TRUE");
    
    $productoExistenciaE =  new \entidad\ProductoExistencia();
    $productoExistenciaE->setProducto($productoE);
    $productoExistenciaE->setIdBodega($idBodega);
        
    $productoExistenciaM = new \modelo\ProductoExistencia($productoExistenciaE);
    $retorno['data'] = $productoExistenciaM->consultar();
    $retorno['numeroRegistros'] = $productoExistenciaM->conexion->obtenerNumeroRegistros();
    
} catch (Exception $exc) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $exc->getMessage();
}
echo json_encode($retorno);

?>