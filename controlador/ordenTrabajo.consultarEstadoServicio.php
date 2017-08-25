<?php
require_once '../entidad/EstadoClienteServicio.php';
require_once '../modelo/EstadoClienteServicio.php';

$retorno = array("exito"=>1, "mensaje"=>"", "data"=>null, "numeroRegistros"=>0);

try {
    $estado = $_POST["estado"];
    
    $estadoClienteServicioE = new \entidad\EstadoClienteServicio();
    $estadoClienteServicioE->setEstado($estado);
    
    $estadoClienteServicioM = new \modelo\EstadoClienteServicio($estadoClienteServicioE);
    $retorno["data"] = $estadoClienteServicioM->consultar();
    $retorno["numeroRegistros"] = $estadoClienteServicioM->conexion->obtenerNumeroRegistros();
} catch (Exception $exc) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $exc->getMessage();
}
echo json_encode($retorno);