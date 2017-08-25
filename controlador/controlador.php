<?php
session_start();
require_once '../../Seguridad/entidad/Sucursal.php';
require_once '../entidad/Cliente.php';
require_once '../entidad/Vendedor.php';
require_once '../entidad/EstadoPedido.php';
require_once '../modelo/EstadoPedido.php';
require_once '../entidad/Pedido.php';
require_once '../modelo/Pedido.php';

$retorno = array('exito'=>1, 'mensaje'=>'');

try {
    $idCliente = $_POST['idCliente'];
    $idVendedor = $_POST['idVendedor'];
    $idZona = $_POST['idZona'];
    $idPedido = $_POST['idPedido'];
    $fecha = $_POST['fecha'];
    $nota = $_POST['nota'];
    $idUsuario = $_SESSION['idUsuario'];
    
    $clienteE = new \entidad\Cliente();
    $clienteE->setIdCliente($idCliente);
    
    $vendedorE = new \entidad\Vendedor();
    $vendedorE->setIdVendedor($idVendedor);
    
    $estadoPedidoM = new \modelo\EstadoPedido(new \entidad\EstadoPedido());
    $idPedidoEstado = $estadoPedidoM->obtenerEstadoInicial();
    
    $pedidoE = new \entidad\Pedido();
    $pedidoE->setIdPedido($idPedido);
    
    $pedidoM = new \modelo\Pedido($pedidoE);
    $validacion = $pedidoM->consultarEstadoFinalizado();
    if($validacion['exito'] == 0 && is_array($validacion)){
        throw new Exception($validacion['mensaje']);
    }
    
    $pedidoE->setCliente($clienteE);
    $pedidoE->setVendedor($vendedorE);
    $pedidoE->setIdZona($idZona);
    $pedidoE->setIdPedidoEstado($idPedidoEstado);
    $pedidoE->setFecha($fecha);
    $pedidoE->setNota($nota);
    $pedidoE->setIdUsuarioModificacion($idUsuario);
    
    $pedidoM = new \modelo\Pedido($pedidoE);
    $pedidoM->modificar();
    
    $retorno['idPedido'] = $idPedido;
} catch (Exception $exc) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $exc->getMessage();
}
echo json_encode($retorno);

?>