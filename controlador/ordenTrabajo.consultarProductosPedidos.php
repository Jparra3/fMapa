<?php
session_start();
require_once '../entidad/OrdenTrabajo.php';
require_once '../modelo/OrdenTrabajo.php';

$retorno = array('exito'=>1, 'mensaje'=>'', 'data'=>null);

try {
    
    $idPedido = $_POST['idPedido'];
    
    $ordenTrabajoE =  new \entidad\OrdenTrabajo();
        
    $ordenTrabajoM = new \modelo\OrdenTrabajo($ordenTrabajoE);
    $retorno['data'] = $ordenTrabajoM->consultarProductosPedidos($idPedido);
    
} catch (Exception $exc) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $exc->getMessage();
}
echo json_encode($retorno);

?>