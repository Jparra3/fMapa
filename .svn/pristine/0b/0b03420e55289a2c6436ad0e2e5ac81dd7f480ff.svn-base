<?php
require_once '../entidad/Caja.php';
require_once '../modelo/Caja.php';

$retorno = array('exito'=>1, 'mensaje'=>'', 'data'=>null, 'numeroRegistros'=>0);

try {
    $direccionIp = $_POST['direccionIp'];
    $cajaE = new \entidad\Caja();
    $cajaE ->setDireccionIp($direccionIp);
    
    $cajaM = new \modelo\Caja($cajaE);
    $retorno["data"] = $cajaM->consultar();
    $retorno["numeroRegistros"] = $cajaM->conexion->obtenerNumeroRegistros();
} catch (Exception $e) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
