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
    $tipoPedido = $_POST['tipoPedido'];
    $fecha = $_POST['fecha'];
    $nota = $_POST['nota'];
    $idClienteServicio = $_POST['idClienteServicio'];
    $idUsuario = $_SESSION['idUsuario'];
    
    $clienteE = new \entidad\Cliente();
    $clienteE->setIdCliente($idCliente);
    
    $vendedorE = new \entidad\Vendedor();
    $vendedorE->setIdVendedor($idVendedor);
    
    $estadoPedidoM = new \modelo\EstadoPedido(new \entidad\EstadoPedido());
    
    $validacion = $estadoPedidoM->obtenerEstadoInicial();
    if($validacion['exito'] == 0 && is_array($validacion)){
        throw new Exception($validacion['mensaje']);
    }else{
        $idPedidoEstado = $validacion;
    }
    
    $pedidoE = new \entidad\Pedido();
    $pedidoE->setCliente($clienteE);
    $pedidoE->setIdPedidoEstado($idPedidoEstado);
    $pedidoE->setVendedor($vendedorE);
    $pedidoE->setIdZona($idZona);
    $pedidoE->setTipoPedido($tipoPedido);
    $pedidoE->setIdPedidoEstado($idPedidoEstado);
    $pedidoE->setFecha($fecha);
    $pedidoE->setNota($nota);
    $pedidoE->setIdClienteServicio($idClienteServicio);
    $pedidoE->setIdUsuarioCreacion($idUsuario);
    $pedidoE->setIdUsuarioModificacion($idUsuario);
    
    $pedidoM = new \modelo\Pedido($pedidoE);
    $pedidoM->adicionar();
    $retorno['idPedido'] = $pedidoM->obtenerMaximo();
    
} catch (Exception $exc) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $exc->getMessage();
}
echo json_encode($retorno);

?>