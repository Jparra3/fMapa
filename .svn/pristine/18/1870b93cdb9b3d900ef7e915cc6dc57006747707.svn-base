<?php
session_start();
require_once '../entidad/ClienteServicio.php';
require_once '../modelo/ClienteServicio.php';

$retorno = array('exito'=>1, 'mensaje'=>'El servicio de canceló correctamente');

try {
    $idClienteServicio = $_POST['idClienteServicio'];
    $idEstadoClienteServicio = $_POST['idEstadoClienteServicio'];
    
    $clienteServicioE = new \entidad\ClienteServicio();
    $clienteServicioE->setIdClienteServicio($idClienteServicio);
    $clienteServicioE->setIdEstadoClienteServicio($idEstadoClienteServicio);
    
    $clienteServicioM = new \modelo\ClientServicio($clienteServicioE);
    $clienteServicioM->actualizarEstado();
} catch (Exception $exc) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $exc->getMessage();
}
echo json_encode($retorno);

?>