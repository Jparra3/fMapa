<?php
session_start();
require_once '../entidad/OrdenTrabajoVehiculo.php';
require_once '../modelo/OrdenTrabajoVehiculo.php';

$retorno = array('exito'=>1, 'mensaje'=>'');

try {
    $idOrdenTrabajo = $_POST['idOrdenTrabajo'];
    
    $ordenTrabajoE = new \entidad\OrdenTrabajo();
    $ordenTrabajoE->setIdOrdenTrabajo($idOrdenTrabajo);
    
    $ordenTrabajoVehiculoE = new \entidad\OrdenTrabajoVehiculo();
    $ordenTrabajoVehiculoE->setOrdenTrabajo($ordenTrabajoE);

    $ordenTrabajoVehiculoM = new \modelo\OrdenTrabajoVehiculo($ordenTrabajoVehiculoE);
    $ordenTrabajoVehiculoM->eliminar();
    
    
} catch (Exception $exc) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $exc->getMessage();
}
echo json_encode($retorno);

?>