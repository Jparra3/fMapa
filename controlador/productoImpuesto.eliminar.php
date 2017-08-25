<?php
session_start();
require_once '../entidad/ProductoImpuesto.php';
require_once '../modelo/ProductoImpuesto.php';

$retorno = array('exito'=>1, 'mensaje'=>'Los impuestos se eliminaron correctamente.');

try {
    $idEliminar = $_POST["arrIdEliminar"];
    $contador = 0;
    while ($contador < count($idEliminar)) {        
        $productoImpuestoE = new \entidad\ProductoImpuesto();
        $productoImpuestoE->setIdProductoImpuesto($idEliminar[$contador]);
        
        $productoImpuestoM = new \modelo\ProductoImpuesto($productoImpuestoE);
        $productoImpuestoM->eliminar();
        $contador++;
    }
} catch (Exception $e) {
    $retorno['exito'] =0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
