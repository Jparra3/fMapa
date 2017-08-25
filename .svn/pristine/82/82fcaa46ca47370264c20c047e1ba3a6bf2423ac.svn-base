<?php
session_start();
require_once '../entidad/EstadoClienteServicio.php';
require_once '../modelo/EstadoClienteServicio.php';

$retorno = array('exito'=>1, 'mensaje'=>'', 'data'=>null);

try {
    $idEstadoClienteServicio = $_POST['idEstadoClienteServicio'];
    $estadoClienteServicio = $_POST['estadoClienteServicio'];
    $estado = $_POST['estado'];
    
    $estadoClienteServicioE = new \entidad\EstadoClienteServicio();
    $estadoClienteServicioE->setIdEstadoClienteServicio($idEstadoClienteServicio);
    $estadoClienteServicioE->setEstadoClienteServicio($estadoClienteServicio);
    $estadoClienteServicioE->setEstado($estado);
    
    $estadoClienteServicioM = new \modelo\EstadoClienteServicio($estadoClienteServicioE);
    $retorno['data'] = $estadoClienteServicioM->consultar();
    
} catch (Exception $exc) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $exc->getMessage();
}
echo json_encode($retorno);

?>