<?php
date_default_timezone_set("America/Bogota");
session_start();
require_once '../entidad/Caja.php';
require_once '../modelo/Caja.php';
require_once '../entidad/AsignacionCaja.php';
require_once '../modelo/AsignacionCaja.php';
require_once '../entidad/Cajero.php';
require_once '../modelo/Cajero.php';
require_once '../entidad/Transaccion.php';
require_once '../modelo/Transaccion.php';

$retorno = array('exito'=>1, 'mensaje'=>'El cajero se verificó correctamente.');

try {
    $direccionIp = '186.117.187.146'; //getenv('REMOTE_ADDR');
    
    $cajaE = new \entidad\Caja();
    $cajaE->setDireccionIp($direccionIp);
    
    $cajaM = new \modelo\Caja($cajaE);
    $dataCaja = $cajaM->consultar();
    $numeroRegistros = $cajaM->conexion->obtenerNumeroRegistros();
    
    if($numeroRegistros == 0){
        throw new Exception("No existe una caja con la ip -> ".$direccionIp);
    }
    
    if($numeroRegistros > 1){
        throw new Exception("Existe más de una caja con la ip -> ".$direccionIp);
    }
    
    $asignacionCajaE = new \entidad\AsignacionCaja();
    $asignacionCajaE->setIdCaja($dataCaja[0]["idCaja"]);
    
    $asignacionCajaM = new \modelo\AsignacionCaja($asignacionCajaE);
    $dataAsignacionCaja = $asignacionCajaM->consultar();
    
    if($dataAsignacionCaja == null){
        throw new Exception("No tiene ninguna caja asignada.");
    }
    
    $cajeroE = new \entidad\Cajero();
    $cajeroE->setIdCajero($dataAsignacionCaja[0]["idCajero"]);
    
    $cajeroM = new \modelo\Cajero($cajeroE);
    $dataCajero = $cajeroM->consultar();
    
    if($dataCajero == null){
        throw new Exception("El usuario autenticado no es un cajero.");
    }
    
    $formatoFecha = "Y-m-d H:i:s";
    $fechaInicio = date_create($dataAsignacionCaja[0]["fechaHoraInicial"]);
    $fechaInicio = date_format($fechaInicio, $formatoFecha);
    $fechaFin = date_create($dataAsignacionCaja[0]["fechaHoraFinal"]);
    $fechaFin = date_format($fechaFin, $formatoFecha);
    $fechaActual = date($formatoFecha);
    
    ////////////////////////////////////////////////////
    ///////$fechaInicio = "1900-01-01 00:00:00";///////
    ///////$fechaFin = "1900-01-01 00:00:00";/////////
    /////////////////////////////////////////////////
    
    if($fechaInicio == "1900-01-01 00:00:00" && $fechaFin == "1900-01-01 00:00:00"){
        echo json_encode($retorno);
        exit();
    }else if($fechaActual > $fechaInicio && $fechaActual < $fechaFin){
        echo json_encode($retorno);
        exit();
    }else{
        throw new Exception("No tiene permiso para acceder a caja en este momento.");
    }
} catch (Exception $e) {
    $retorno['exito'] =0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
