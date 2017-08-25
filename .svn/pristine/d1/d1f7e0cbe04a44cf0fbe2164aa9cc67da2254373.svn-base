<?php
require_once '../entidad/Producto.php';
require_once '../modelo/Producto.php';
$retorno = array('exito'=>1,'mensaje'=>'El producto se inactivó correctamente');

try {
    $idProducto = $_POST['idProducto'];
    
    $productoE = new entidad\Producto();
    $productoE->setIdProducto($idProducto);
    
    $productoM = new modelo\Producto($productoE);
    $productoM->inactivar();
} catch (Exception $e) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
