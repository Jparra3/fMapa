<?php
session_start();
require_once '../entidad/OrdenTrabajo.php';
require_once '../modelo/OrdenTrabajo.php';

$retorno = array('exito'=>1, 'mensaje'=>'');

try {
    $idOrdenTrabajo = $_POST['idOrdenTrabajo'];
    $idEstadoOrdenTrabajo = $_POST['idEstadoOrdenTrabajo'];
    
    $ordenTrabajoE = new \entidad\OrdenTrabajo();
    $ordenTrabajoE->setIdOrdenTrabajo($idOrdenTrabajo);
    $ordenTrabajoE->setIdEstadoOrdenTrabajo($idEstadoOrdenTrabajo);
    
    $ordenTrabajoM = new \modelo\OrdenTrabajo($ordenTrabajoE);
    $ordenTrabajoM->actualizarEstado();
    
    
} catch (Exception $exc) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $exc->getMessage();
}
echo json_encode($retorno);

?>
