<?php
require_once '../entidad/Tecnico.php';
require_once '../modelo/Tecnico.php';

$retorno = array("exito"=>1, "mensaje"=>"", "data"=>null, "numeroRegistros"=>0);

try {
    $estado = $_POST["estado"];
    
    $tecnicoE = new \entidad\Tecnico();
    $tecnicoE->setEstado($estado);
    
    $tecnicoM = new \modelo\Tecnico($tecnicoE);

    $retorno["data"] = $tecnicoM->consultar();
    $retorno["numeroRegistros"] = $tecnicoM->conexion->obtenerNumeroRegistros();
} catch (Exception $exc) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $exc->getMessage();
}
echo json_encode($retorno);