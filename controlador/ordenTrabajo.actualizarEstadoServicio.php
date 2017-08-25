<?php
session_start();
require_once '../entidad/OrdenTrabajo.php';
require_once '../modelo/OrdenTrabajo.php';

$retorno = array('exito'=>1, 'mensaje'=>'');

try {
    $idClienteServicio = $_POST['idClienteServicio'];
    $idEstadoOrdenTrabajo = $_POST['idEstadoOrdenTrabajo'];
    
    $clienteServicioE = new \entidad\ClienteServicio();
    $clienteServicioE->setIdClienteServicio($idClienteServicio);
    
    $ordenTrabajoE = new \entidad\OrdenTrabajo();
    $ordenTrabajoE->setIdEstadoOrdenTrabajo($idEstadoOrdenTrabajo);
    $ordenTrabajoE->setClienteServicio($clienteServicioE);
    
    $ordenTrabajoM = new \modelo\OrdenTrabajo($ordenTrabajoE);
    $ordenTrabajoM->actualizarEstadoServicio();
    
    
} catch (Exception $exc) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $exc->getMessage();
}
echo json_encode($retorno);

?>
