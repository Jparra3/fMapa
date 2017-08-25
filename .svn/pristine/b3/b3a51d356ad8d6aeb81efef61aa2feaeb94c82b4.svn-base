<?php
session_start();
require_once '../entidad/Pedido.php';
require_once '../modelo/Pedido.php';
require_once '../entidad/EstadoPedido.php';
require_once '../modelo/EstadoPedido.php';

$retorno = array('exito'=>1, 'mensaje'=>'El pedido se inactivó correctamente.');

try {
    $idPedido = $_POST['idPedido'];
    
    /*$estadoPedidoE = new \entidad\EstadoPedido();
    $estadoPedidoE->setIdEstadoPedido(4);
    
    $estadoPedidoM = new \modelo\EstadoPedido($estadoPedidoE);
    
    $validacion = $estadoPedidoM->consultar();
    if($validacion['exito'] == 0 && is_array($validacion)){
        throw new Exception($validacion['mensaje']);
    }else{
        $idPedidoEstado = $validacion;
    }*/
    
    $pedidoE = new \entidad\Pedido();
    $pedidoE->setIdPedido($idPedido);
    $pedidoE->setIdPedidoEstado(4);//CANCELADO
    
    $pedidoM = new \modelo\Pedido($pedidoE);
    $pedidoM->inactivar();
    
} catch (Exception $exc) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $exc->getMessage();
}
echo json_encode($retorno);

?>