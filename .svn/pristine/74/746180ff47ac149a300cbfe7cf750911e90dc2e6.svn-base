<?php
require_once '../../Seguridad/entidad/Oficina.php';
require_once '../../Seguridad/modelo/Oficina.php';
require_once '../entidad/Transaccion.php';
require_once '../modelo/Transaccion.php';

$retorno = array("exito"=>1, "mensaje"=>"", "data"=>null, "numeroRegistros"=>0);

try {
    
    $oficinaM = new \modelo\Oficina(new \entidad\Oficina());
    $idOficinas = $oficinaM->consuOficiTienePermi();
    
    $transaccionE = new \entidad\Transaccion();
    $transaccionE->setIdOficina($idOficinas);
    
    $transaccionM = new \modelo\Transaccion($transaccionE);
    $retorno["data"] = $transaccionM->consultarOficinas();
    $retorno["numeroRegistros"] = $transaccionM->conexion->obtenerNumeroRegistros();
} catch (Exception $exc) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $exc->getMessage();
}
echo json_encode($retorno);