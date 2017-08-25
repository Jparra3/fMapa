<?php
session_start();
require_once '../entidad/Cliente.php';
require_once '../entidad/OrdenTrabajo.php';
require_once '../entidad/OrdenTrabajoCliente.php';
require_once '../modelo/OrdenTrabajoCliente.php';

$retorno = array('exito'=>1, 'mensaje'=>'','idOrdenTrabajoCliente'=>null);

try {
    $idOrdenTrabajo = $_POST['idOrdenTrabajo'];
    $idCliente = $_POST['idCliente'];
    $idProductoComposicion = $_POST['idProductoComposicion'];
    $idPedido = $_POST['idPedido'];
    $idUsuario = $_SESSION['idUsuario'];
    
    if($idPedido == "" || $idPedido == null){
        $idPedido = "NULL";
    }
    
    $ordenTrabajoE = new \entidad\OrdenTrabajo();
    $ordenTrabajoE->setIdOrdenTrabajo($idOrdenTrabajo);

    $clienteE =  new \entidad\Cliente();
    $clienteE->setIdCliente($idCliente);
    
    $pedidoE =  new \entidad\Pedido();
    $pedidoE->setIdPedido($idPedido);

    $ordenTrabajoClienteE = new \entidad\OrdenTrabajoCliente();
    $ordenTrabajoClienteE->setOrdenTrabajo($ordenTrabajoE);
    $ordenTrabajoClienteE->setCliente($clienteE);
    $ordenTrabajoClienteE->setIdProductoComposicion($idProductoComposicion);
    $ordenTrabajoClienteE->setPedido($pedidoE);
    $ordenTrabajoClienteE->setEstado("TRUE");
    $ordenTrabajoClienteE->setIdUsuarioCreacion($idUsuario);
    $ordenTrabajoClienteE->setIdUsuarioModificacion($idUsuario);

    $ordenTrabajoClienteM = new \modelo\OrdenTrabajoCliente($ordenTrabajoClienteE);
    $ordenTrabajoClienteM->adicionar();
    
    $retorno["idOrdenTrabajoCliente"] = $ordenTrabajoClienteM->obtenerMaximo();
    
} catch (Exception $exc) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $exc->getMessage();
}
echo json_encode($retorno);

?>