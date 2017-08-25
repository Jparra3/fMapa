<?php
session_start();
require_once '../entidad/MovimientoContable.php';
require_once '../modelo/MovimientoContable.php';


$retorno = array('exito'=>1, 'mensaje'=>'', 'data'=>null, 'numeroRegistros'=>0);

try {
    $idMovimientoContable = $_POST["idMovimientoContable"];
    $fecha["inicio"] = $_POST["fechaInicio"];
    $fecha["fin"] = $_POST["fechaFin"];
    $numeroTipoDocumento = $_POST["numeroTipoDocumento"];
    $estado = $_POST["estado"];
    $idTipoDocumento = $_POST["idTipoDocumento"];
    
    $movimientoContableE = new \entidad\MovimientoContable();
    $movimientoContableE->setIdMovimientoContable($idMovimientoContable);
    $movimientoContableE->setFecha($fecha);
    $movimientoContableE->setNumeroTipoDocumento($numeroTipoDocumento);
    $movimientoContableE->setEstado($estado);
    $movimientoContableE->setIdTipoDocumento($idTipoDocumento);
    
    $movimientoContableM = new \modelo\MovimientoContable($movimientoContableE);
    $retorno["data"] = $movimientoContableM->consultar();
    $retorno["numeroRegistros"] = $movimientoContableM->conexion->obtenerNumeroRegistros();
} catch (Exception $e) {
    $retorno['exito'] =0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
