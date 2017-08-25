<?php
require_once '../entidad/Naturaleza.php';
require_once '../modelo/Naturaleza.php';

$retorno = array('exito'=>1, 'mensaje'=>'', 'data'=>null, 'numeroRegistros'=>0);

try {
    
    $naturalezaE = new \entidad\Naturaleza();
    
    $naturalezaM = new \modelo\Naturaleza($naturalezaE);
    $retorno["data"] = $naturalezaM->consultar();
    $retorno["numeroRegistros"] = $naturalezaM->conexion->obtenerNumeroRegistros();
} catch (Exception $e) {
    $retorno['exito'] =0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
