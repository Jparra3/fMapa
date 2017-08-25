<?php
session_start();
require_once '../../Seguridad/entidad/Sucursal.php';
require_once '../entidad/Cliente.php';
require_once '../entidad/Vendedor.php';
require_once '../entidad/Pedido.php';
require_once '../modelo/Pedido.php';

$retorno = array('exito'=>1, 'mensaje'=>'', 'data'=>null);

try {
    $idCliente = $_POST['idCliente'];
    $idPedido = $_POST['idPedido'];
    $idZona = $_POST['idZona'];
    $tipoPedido = $_POST['tipoPedido'];
    $idVendedor = $_POST['idVendedor'];
    $fecha['inicio'] = $_POST['fechaInicio'];
    $fecha['fin'] = $_POST['fechaFin'];
    $idPedidoEstado = $_POST['idEstadoPedido'];
    $sucursal = $_POST['sucursal'];
    
    $vendedorE = new \entidad\Vendedor();
    $vendedorE->setIdVendedor($idVendedor);
    
    $clienteE = new \entidad\Cliente();
    $clienteE->setIdCliente($idCliente);
    
    $pedidoE = new \entidad\Pedido();
    $pedidoE->setCliente($clienteE);
    $pedidoE->setIdZona($idZona);
    $pedidoE->setTipoPedido($tipoPedido);
    $pedidoE->setVendedor($vendedorE);
    $pedidoE->setIdPedido($idPedido);
    $pedidoE->setFecha($fecha);
    $pedidoE->setIdPedidoEstado($idPedidoEstado);
    
    $pedidoM = new \modelo\Pedido($pedidoE);
    $retorno['data'] = $pedidoM->consultar();
    
} catch (Exception $exc) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $exc->getMessage();
}
echo json_encode($retorno);

?>