<?php
session_start();
require_once '../entidad/ProductoCodigoBarras.php';
require_once '../modelo/ProductoCodigoBarras.php';

$retorno = array('exito'=>1, 'mensaje'=>'Los códigos de barra se eliminaron correctamente.');

try {
    $idEliminar = $_POST["arrIdEliminar"];
    $contador = 0;
    while ($contador < count($idEliminar)) {        
        $productoCodigoBarrasE = new \entidad\ProductoCodigoBarras();
        $productoCodigoBarrasE->setIdProductoCodigoBarras($idEliminar[$contador]);
        
        $productoCodigoBarrasM = new \modelo\ProductoCodigoBarras($productoCodigoBarrasE);
        $productoCodigoBarrasM->eliminar();
        $contador++;
    }
} catch (Exception $e) {
    $retorno['exito'] =0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
