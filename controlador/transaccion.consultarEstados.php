<?php
require_once '../entidad/TransaccionEstado.php';
require_once '../modelo/TransaccionEstado.php';

$retorno = array("exito"=>1, "mensaje"=>"", "data"=>null, "numeroRegistros"=>0);

try {
    $transaccionEstadoM = new \modelo\TransaccionEstado(new \entidad\TransaccionEstado());    
    $retorno["data"] = $transaccionEstadoM->consultar();
    $retorno["numeroRegistros"] = $transaccionEstadoM->conexion->obtenerNumeroRegistros();
} catch (Exception $exc) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $exc->getMessage();
}
echo json_encode($retorno);