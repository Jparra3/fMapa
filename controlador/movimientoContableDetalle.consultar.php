<?php
session_start();
require_once '../entidad/MovimientoContable.php';
require_once '../entidad/MovimientoContableDetalle.php';
require_once '../modelo/MovimientoContableDetalle.php';

$retorno = array('exito'=>1, 'mensaje'=>'', 'data'=>null, 'numeroRegistros'=>0);

try {
    $idMovimientoContable = $_POST["idMovimientoContable"];
    
    $movimientoContableE = new \entidad\MovimientoContable();
    $movimientoContableE->setIdMovimientoContable($idMovimientoContable);
    
    $movimientoContableDetalleE = new \entidad\MovimientoContableDetalle();
    $movimientoContableDetalleE->setMovimientoContable($movimientoContableE);
    
    $movimientoContableDetalleM = new \modelo\MovimientoContableDetalle($movimientoContableDetalleE);
    $retorno["data"] = $movimientoContableDetalleM->consultar();
    $retorno["numeroRegistros"] = $movimientoContableDetalleM->conexion->obtenerNumeroRegistros();
} catch (Exception $e) {
    $retorno['exito'] =0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
