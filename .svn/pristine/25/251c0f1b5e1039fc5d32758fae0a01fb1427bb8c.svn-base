<?php
session_start();
require_once '../entidad/MovimientoContable.php';
require_once '../modelo/MovimientoContable.php';

$retorno = array('exito'=>1, 'mensaje'=>'El movimiento contable se inactivó correctamente.');

try {
    $idMovimientoContable = $_POST["idMovimientoContable"];
    
    $movimientoContableE = new \entidad\MovimientoContable();
    $movimientoContableE->setIdMovimientoContable($idMovimientoContable);
    
    $movimientoContableM = new \modelo\MovimientoContable($movimientoContableE);
    $movimientoContableM->inactivar();
} catch (Exception $e) {
    $retorno['exito'] =0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
