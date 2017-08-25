<?php
require_once '../entidad/Cajero.php';
require_once '../modelo/Cajero.php';

$retorno = array ('exito'=>1, 'mensaje'=> '', 'data'=>null, 'numeroRegistros'=>'');

try {
    $cajeroE = new \entidad\Cajero();
    $cajeroM = new \modelo\Cajero($cajeroE);
    $retorno['data'] = $cajeroM->consultarCajeros();
    $retorno['numeroRegistros']= $cajeroM->conexion->obtenerNumeroRegistros();
} catch (Exception $e) {
    $retorno['exito']= 0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
