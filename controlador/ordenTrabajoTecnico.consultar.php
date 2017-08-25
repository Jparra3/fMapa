<?php
require_once '../entidad/OrdenTrabajo.php';
require_once '../entidad/OrdenTrabajoTecnico.php';
require_once '../modelo/OrdenTrabajoTecnico.php';

$retorno = array("exito"=>1, "mensaje"=>"", "data"=>null, "numeroRegistros"=>0);

try {
    $estado = $_POST["estado"];
    $idOrdenTrabajo = $_POST["idOrdenTrabajo"];
    
    $ordenTrabajoE = new \entidad\OrdenTrabajo();
    $ordenTrabajoE->setIdOrdenTrabajo($idOrdenTrabajo);
    
    $ordenTrabajoTecnicoE = new \entidad\OrdenTrabajoTecnico();
    $ordenTrabajoTecnicoE->setEstado($estado);
    $ordenTrabajoTecnicoE->setOrdenTrabajo($ordenTrabajoE);
    
    $ordenTrabajoTecnicoM = new \modelo\OrdenTrabajoTecnico($ordenTrabajoTecnicoE);

    $retorno["data"] = $ordenTrabajoTecnicoM->consultar();
    $retorno["numeroRegistros"] = $ordenTrabajoTecnicoM->conexion->obtenerNumeroRegistros();
} catch (Exception $exc) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $exc->getMessage();
}
echo json_encode($retorno);