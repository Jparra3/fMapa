<?php
session_start();
require_once '../entidad/PedidoProducto.php';
require_once '../modelo/PedidoProducto.php';

$retorno = array('exito'=>1, 'mensaje'=>'', 'data'=>null);

try {
    $idPedido = $_POST['idPedido'];
    
    $pedidoE = new \entidad\PedidoProducto();
    $pedidoE->setIdPedido($idPedido);
    
    $pedidoM = new \modelo\PedidoProducto($pedidoE);
    $retorno['data'] = $pedidoM->consultar();
    
} catch (Exception $exc) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $exc->getMessage();
}
echo json_encode($retorno);

?>