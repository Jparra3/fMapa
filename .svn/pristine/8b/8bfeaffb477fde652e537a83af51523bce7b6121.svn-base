<?php
require_once '../entidad/OrdenTrabajoVehiculo.php';
require_once '../modelo/OrdenTrabajoVehiculo.php';

$retorno = array("exito"=>1, "mensaje"=>"", "data"=>null, "numeroRegistros"=>0);

try {
    $estado = $_POST["estado"];
    $idOrdenTrabajo = $_POST["idOrdenTrabajo"];
    
    $ordenTrabajoE = new \entidad\OrdenTrabajo();
    $ordenTrabajoE->setIdOrdenTrabajo($idOrdenTrabajo);
    
    $ordenTrabajoVehiculoE = new \entidad\OrdenTrabajoVehiculo();
    $ordenTrabajoVehiculoE->setEstado($estado);
    $ordenTrabajoVehiculoE->setOrdenTrabajo($ordenTrabajoE);
    
    $ordenTrabajoVehiculoM = new \modelo\OrdenTrabajoVehiculo($ordenTrabajoVehiculoE);

    $retorno["data"] = $ordenTrabajoVehiculoM->consultar();
    $retorno["numeroRegistros"] = $ordenTrabajoVehiculoM->conexion->obtenerNumeroRegistros();
} catch (Exception $exc) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $exc->getMessage();
}
echo json_encode($retorno);