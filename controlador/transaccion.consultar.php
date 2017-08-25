<?php
session_start();
require_once '../entidad/Transaccion.php';
require_once '../modelo/Transaccion.php';
require_once '../entidad/TipoDocumento.php';
require_once '../modelo/TipoDocumento.php';
require_once '../../Seguridad/entidad/Tercero.php';

$retorno = array('exito'=>1, 'mensaje'=>'', 'data'=>null, 'numeroRegistros'=>0);

try {
    $fecha["inicio"] = $_POST["fechaInicio"];
    $fecha["fin"] = $_POST["fechaFin"];
    $fechaVencimiento["inicio"] = $_POST["fechaVencimientoInicio"];
    $fechaVencimiento["fin"] = $_POST["fechaVencimientoFin"];
    $idTipoDocumento = $_POST["idTipoDocumento"];
    $idConcepto = $_POST["idConcepto"];
    $idTercero = $_POST["idTercero"];
    $idOficina = $_POST["idOficina"];
    $idTransaccionEstado = $_POST["idTransaccionEstado"];
    $documentoExterno = $_POST['documentoExterno'];
    $codigoTipoNaturaleza = $_POST["codigoTipoNaturaleza"];
    
    $terceroE = new \entidad\Tercero();
    $terceroE->setIdTercero($idTercero);
    
    $transaccionE = new \entidad\Transaccion();
    $transaccionE->setIdTipoDocumento($idTipoDocumento);
    $transaccionE->setTercero($terceroE);
    $transaccionE->setIdTransaccionEstado($idTransaccionEstado);
    $transaccionE->setIdOficina($idOficina);
    $transaccionE->setFecha($fecha);
    $transaccionE->setFechaVencimiento($fechaVencimiento);
    
    $transaccionM = new \modelo\Transaccion($transaccionE);
    //$retorno["data"] = $transaccionM->consultar();
    $retorno["data"] = $transaccionM->transaccionConsultar($documentoExterno, $idConcepto, $codigoTipoNaturaleza);
    $retorno['numeroRegistros'] = $transaccionM->conexion->obtenerNumeroRegistros();
} catch (Exception $e) {
    $retorno['exito'] =0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
