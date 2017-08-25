<?php
session_start();
require_once '../entidad/Transaccion.php';
require_once '../modelo/Transaccion.php';
require_once '../../Seguridad/entidad/Tercero.php';

$retorno = array('exito'=>1, 'mensaje'=>'', 'data'=>null, 'numeroRegistros'=>0);

try {
    $nit = $_POST["nit"];
    
    $terceroE = new \entidad\Tercero();
    $terceroE->setNit($nit);
    
    $transaccionE = new \entidad\Transaccion();
    $transaccionE->setTercero($terceroE);
    
    $transaccionM = new \modelo\Transaccion($transaccionE);
    $retorno["data"] = $transaccionM->consultarTerceroSucursal();
    $retorno['numeroRegistros'] = $transaccionM->conexion->obtenerNumeroRegistros();
} catch (Exception $e) {
    $retorno['exito'] =0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
