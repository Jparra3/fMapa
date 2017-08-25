<?php
require_once '../entidad/Producto.php';
require_once '../modelo/Producto.php';
$retorno = array('exito'=>1,'mensaje'=>'', 'existe'=>'');

try {
    $codigo = $_POST['codigo'];
    
    $productoE = new \entidad\Producto();
    $productoE->setCodigo($codigo);
    
    $productoM = new \modelo\Producto($productoE);
    $retorno["existe"] = $productoM->validarExistenciaCodigo();
} catch (Exception $e) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
