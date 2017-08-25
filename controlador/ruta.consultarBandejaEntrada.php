<?php
session_start();
require_once '../entidad/Ruta.php';
require_once '../modelo/Ruta.php';

$retorno = array("exito"=>1, "mensaje"=>"", "data"=>null, "numeroRegistros"=>null);

try{
    $idZona = $_POST['idZona'];
    $fecha['inicio'] = $_POST['fechaInicio'];
    $fecha['fin'] = $_POST['fechaFin'];
    
    $rutaE = new \entidad\Ruta();
    $rutaE->setIdZona($idZona);
    $rutaE->setFechaHora($fecha);
    
    $rutaM = new \modelo\Ruta($rutaE);
    $retorno["data"] = $rutaM->consultarBandejaEntrada();
    $retorno["numeroRegistros"] = $rutaM->conexion->obtenerNumeroRegistros();
    
} catch (Exception $ex) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $ex -> getMessage();
}

echo json_encode($retorno);
?>