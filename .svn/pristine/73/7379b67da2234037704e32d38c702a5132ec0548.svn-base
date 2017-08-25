<?php
session_start();
require_once '../entidad/Transaccion.php';
require_once '../modelo/Transaccion.php';

$retorno = array('exito'=>1, 'mensaje'=>'', 'data'=>null, 'numeroRegistros'=>0);

try {
    $codigoTipoDocumento = "FB-EN";
    $fecha["inicio"] = $_POST["fechaInicio"];
    $fecha["fin"] = $_POST["fechaFin"];
    $idProducto = $_POST["idProducto"];
    $idTransaccionEstado = $_POST["idTransaccionEstado"];
    
    $transaccionE = new \entidad\Transaccion();
    $transaccionE->setFecha($fecha);
    $transaccionE->setIdTransaccionEstado($idTransaccionEstado);
    $parametros["idProducto"] = $idProducto;
    $parametros["codigoTipoDocumento"] = $codigoTipoDocumento;
    
    $transaccionM = new \modelo\Transaccion($transaccionE);
    $retorno["data"] = $transaccionM->transaccionConsultarProductoCompuesto($parametros);
    $retorno['numeroRegistros'] = $transaccionM->conexion->obtenerNumeroRegistros();
} catch (Exception $e) {
    $retorno['exito'] =0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
