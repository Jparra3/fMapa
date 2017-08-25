<?php
session_start();
require_once '../entidad/OrdenTrabajoCliente.php';
require_once '../modelo/OrdenTrabajoCliente.php';

$retorno = array('exito'=>1, 'mensaje'=>'', 'data'=>null);

try {
    $idOrdenTrabajoCliente = $_POST['idOrdenTrabajoCliente'];
    $idOrdenTrabajo = $_POST['idOrdenTrabajo'];
    $estado = $_POST['estado'];
    
    $ordenTrabajoE = new \entidad\OrdenTrabajo();
    $ordenTrabajoE->setIdOrdenTrabajo($idOrdenTrabajo);
    
    $ordenTrabajoClienteE = new \entidad\OrdenTrabajoCliente();
    $ordenTrabajoClienteE->setOrdenTrabajo($ordenTrabajoE);
    $ordenTrabajoClienteE->setIdOrdenTrabajoCliente($idOrdenTrabajoCliente);
    $ordenTrabajoClienteE->setEstado($estado);
    
    $ordenTrabajoClienteM = new \modelo\OrdenTrabajoCliente($ordenTrabajoClienteE);
    $retorno['data'] = $ordenTrabajoClienteM->consultar();
    $retorno['numeroRegistros'] = $ordenTrabajoClienteM->conexion->obtenerNumeroRegistros();
    
} catch (Exception $exc) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $exc->getMessage();
}
echo json_encode($retorno);

?>