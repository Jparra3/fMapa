<?php
session_start();
require_once '../entidad/ProductoExistencia.php';
require_once '../modelo/ProductoExistencia.php';

$retorno = array('exito'=>1, 'mensaje'=>'Las bodegas se eliminaron correctamente.');

try {
    $idEliminar = $_POST["arrIdEliminar"];
    $contador = 0;
    while ($contador < count($idEliminar)) {
        
        $productoExistenciaE = new \entidad\ProductoExistencia();
        $productoExistenciaE->setIdProductoExistencia($idEliminar[$contador]);
        
        $productoExistenciaM = new \modelo\ProductoExistencia($productoExistenciaE);
        $productoExistenciaM->eliminar();
        $contador++;
    }
} catch (Exception $e) {
    $retorno['exito'] =0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
