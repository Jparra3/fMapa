<?php
session_start();
require_once '../entidad/ProductoComposicion.php';
require_once '../modelo/ProductoComposicion.php';

$retorno = array('exito'=>1, 'mensaje'=>'El producto se eliminó correctamente.');

try {
    $idEliminar = $_POST["arrIdEliminar"];
    $contador = 0;
    while ($contador < count($idEliminar)) {        
        $productoComposicionE = new \entidad\ProductoComposicion();
        $productoComposicionE->setIdProductoComposicion($idEliminar[$contador]);
        
        $productoComposicionM = new \modelo\ProductoComposicion($productoComposicionE);
        $productoComposicionM->eliminar();
        $contador++;
    }
} catch (Exception $e) {
    $retorno['exito'] =0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
