<?php
session_start();
require_once '../entidad/OrdenTrabajoCliente.php';
require_once '../modelo/OrdenTrabajoCliente.php';

$retorno = array('exito'=>1, 'mensaje'=>'');

try {
    $idOrdenTrabajoCliente = $_POST['idOrdenTrabajoCliente'];
    $idEstaOrdeTrabClie = $_POST['idEstaOrdeTrabClie'];
    
    $ordenTrabajoClienteE = new \entidad\OrdenTrabajoCliente();
    $ordenTrabajoClienteE->setIdOrdenTrabajoCliente($idOrdenTrabajoCliente);
    $ordenTrabajoClienteE->setIdEstaOrdeTrabClie($idEstaOrdeTrabClie);
    
    $ordenTrabajoClienteM = new \modelo\OrdenTrabajoCliente($ordenTrabajoClienteE);
    $ordenTrabajoClienteM->actualizarEstado();
    
} catch (Exception $exc) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $exc->getMessage();
}
echo json_encode($retorno);
?>