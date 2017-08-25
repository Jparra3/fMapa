<?php
require_once '../entidad/Caja.php';
require_once '../modelo/Caja.php';

$retorno = array('exito'=>1, 'mensaje'=>'', 'data'=>null, 'numeroRegistros'=>0);

try {
    $idCaja = $_POST['idCaja'];
    $estado = $_POST['estado'];
//    $idUsuario = $_POST['idUsuario'];
    $direccionIp = $_POST['direccionIp'];
//    $idFormatoImpresion = $_POST['idFormatoImpresion'];
    
    $cajaE = new \entidad\Caja();
    $cajaE->setIdCaja($idCaja);
    $cajaE->setEstado($estado);
    $cajaE->setDireccionIp($direccionIp);
    
    $cajaM = new \modelo\Caja($cajaE);
    $retorno["data"] = $cajaM->cajaConsultaMasiva($idUsuario, $idFormatoImpresion);
    $retorno["numeroRegistros"] = $cajaM->conexion->obtenerNumeroRegistros();
} catch (Exception $e) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
