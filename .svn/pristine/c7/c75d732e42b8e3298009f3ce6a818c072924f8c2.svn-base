<?php
require_once '../entidad/PedidoProducto.php';
require_once '../modelo/PedidoProducto.php';

$retorno = array("exito"=>1, "mensaje"=>"El producto cambió de estado correctamente.");

try {
    $idPedidoProducto = $_POST["idPedidoProducto"];
    $idPedidoProductoEstado = $_POST["idPedidoProductoEstado"];
    
    $pedidoProductoE = new \entidad\PedidoProducto();
    $pedidoProductoE->setIdPedidoProducto($idPedidoProducto);
    $pedidoProductoE->setIdPedidoProductoEstado($idPedidoProductoEstado);
    
    $pedidoProductoM = new \modelo\PedidoProducto($pedidoProductoE);
    $pedidoProductoM->cambiarEstado();
} catch (Exception $exc) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $exc->getMessage();
}
echo json_encode($retorno);