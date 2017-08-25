<?php
session_start();
require_once '../entidad/MovimientoContableDetalle.php';
require_once '../modelo/MovimientoContableDetalle.php';

$retorno = array('exito'=>1, 'mensaje'=>'El movimiento contable se eliminó correctamente.');

try {
    $idEliminar = $_POST["arrIdEliminar"];
    $contador = 0;
    while ($contador < count($idEliminar)) {
        
        $movimientoContableDetalleE = new \entidad\MovimientoContableDetalle();
        $movimientoContableDetalleE->setIdMovimientoContableDetalle($idEliminar[$contador]);
        
        $movimientoContableDetalleM = new \modelo\MovimientoContableDetalle($movimientoContableDetalleE);
        $movimientoContableDetalleM->eliminar();
        $contador++;
    }
} catch (Exception $e) {
    $retorno['exito'] =0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
