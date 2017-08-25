<?php
require_once '../entidad/Vehiculo.php';
require_once '../modelo/Vehiculo.php';

$retorno = array ('exito'=>1, 'mensaje'=> '', 'data'=>null, 'numeroRegistros'=>'');

try {
    $vehiculoE = new \entidad\Vehiculo();
    $vehiculoE->setEstado("TRUE");
    
    $vehiculoM = new \modelo\Vehiculo($vehiculoE);
    $retorno['data'] = $vehiculoM->consultar();
    $retorno['numeroRegistros']= $vehiculoM->conexion->obtenerNumeroRegistros();
} catch (Exception $e) {
    $retorno['exito']=0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
