<?php
session_start();
require_once '../entidad/EstadoPedido.php';
require_once '../modelo/EstadoPedido.php';

$retorno = array('exito'=>1, 'mensaje'=>'', 'data'=>null);

try {
    $idEstadoPedido = $_POST['idEstadoPedido'];
    $estadoPedido = $_POST['estadoPedido'];
    $estado = $_POST['estado'];
    
    $estadoPedidoE = new \entidad\EstadoPedido();
    $estadoPedidoE->setIdEstadoPedido($idEstadoPedido);
    $estadoPedidoE->setEstado($estado);
    $estadoPedidoE->setEstadoPedido($estadoPedido);
    
    $estadoPedidoM = new \modelo\EstadoPedido($estadoPedidoE);
    $retorno['data'] = $estadoPedidoM->consultar();
    
} catch (Exception $exc) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $exc->getMessage();
}
echo json_encode($retorno);

?>