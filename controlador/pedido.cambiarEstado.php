<?php
require_once '../entidad/Pedido.php';
require_once '../modelo/Pedido.php';

$retorno = array("exito"=>1, "mensaje"=>"El pedido cambió de estado correctamente.");

try {
    $idPedido = $_POST["idPedido"];
    $idPedidoEstado = $_POST["idPedidoEstado"];
    
    $pedidoE = new \entidad\Pedido();
    $pedidoE->setIdPedido($idPedido);
    $pedidoE->setIdPedidoEstado($idPedidoEstado);
    
    $pedidoM = new \modelo\Pedido($pedidoE);
    $pedidoM->cambiarEstado();    
} catch (Exception $exc) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $exc->getMessage();
}
echo json_encode($retorno);