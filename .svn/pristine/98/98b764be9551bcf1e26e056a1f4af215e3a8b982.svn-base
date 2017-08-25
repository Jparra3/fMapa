<?php
require_once '../entidad/EstadoOrdenTrabajo.php';
require_once '../modelo/EstadoOrdenTrabajo.php';

$retorno = array("exito"=>1, "mensaje"=>"", "data"=>null, "numeroRegistros"=>0);

try {
    $estado = $_POST["estado"];
    
    $estadoOrdenTrabajoE = new \entidad\EstadoOrdenTrabajo();
    $estadoOrdenTrabajoE->setEstado($estado);
    
    $estadoOrdenTrabajoM = new \modelo\EstadoOrdenTrabajo($estadoOrdenTrabajoE);

    $retorno["data"] = $estadoOrdenTrabajoM->consultar();
    $retorno["numeroRegistros"] = $estadoOrdenTrabajoM->conexion->obtenerNumeroRegistros();
} catch (Exception $exc) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $exc->getMessage();
}
echo json_encode($retorno);