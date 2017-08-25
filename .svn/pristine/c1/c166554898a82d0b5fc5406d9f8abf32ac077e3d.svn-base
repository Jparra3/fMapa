<?php
session_start();
require_once '../entidad/OrdenTrabajoCliente.php';
require_once '../modelo/OrdenTrabajoCliente.php';

$retorno = array('exito'=>1, 'mensaje'=>'','idOrdenTrabajoCliente'=>null);

try {
    $idOrdenTrabajoCliente = $_POST['idOrdenTrabajoCliente'];
    
    if(count($idOrdenTrabajoCliente) > 0){
        foreach($idOrdenTrabajoCliente as $idEliminar){
            $ordenTrabajoClienteE = new \entidad\OrdenTrabajoCliente();
            $ordenTrabajoClienteE->setIdOrdenTrabajoCliente($idEliminar);

            $ordenTrabajoClienteM = new \modelo\OrdenTrabajoCliente($ordenTrabajoClienteE);
            $ordenTrabajoClienteM->inactivar();
        }
    }
} catch (Exception $exc) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $exc->getMessage();
}
echo json_encode($retorno);

?>