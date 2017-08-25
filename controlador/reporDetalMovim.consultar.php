<?php
session_start();
require_once '../entidad/MovimientoContable.php';
require_once '../entidad/MovimientoContableDetalle.php';
require_once '../modelo/MovimientoContableDetalle.php';

$retorno = array('exito'=>1, 'mensaje'=>'', 'data'=>null, 'numeroRegistros'=>0);

try {
    $fecha["inicio"] = $_POST["fechaInicio"];
    $fecha["fin"] = $_POST["fechaFin"];
    
    $movimientoContableE = new \entidad\MovimientoContable();
    $movimientoContableE->setFecha($fecha);
    
    $movimientoContableDetalleE = new \entidad\MovimientoContableDetalle();
    $movimientoContableDetalleE->setMovimientoContable($movimientoContableE);
    
    $movimientoContableDetalleM = new \modelo\MovimientoContableDetalle($movimientoContableDetalleE);
    $retorno["data"] = $movimientoContableDetalleM->consultarReporte();
    $retorno["numeroRegistros"] = $movimientoContableDetalleM->conexion->obtenerNumeroRegistros();
} catch (Exception $e) {
    $retorno['exito'] =0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
