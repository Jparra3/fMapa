<?php
require_once '../entidad/Cliente.php';
require_once '../entidad/Pedido.php';
require_once '../entidad/PedidoProducto.php';
require_once '../modelo/PedidoProducto.php';
require_once '../entidad/Producto.php';

$retorno = array("exito"=>1, "mensaje"=>"", "data"=>null, "numeroRegistros"=>0);

try {
    $idPedido = $_POST["idPedido"];
    $fecha["inicio"] = $_POST["fechaInicio"];
    $fecha["fin"] = $_POST["fechaFin"];
    $idCliente = $_POST["idCliente"];
    $idProducto = $_POST["idProducto"];
    $idBarrio = $_POST["idBarrio"];
    $idPedidoEstado = $_POST["idPedidoEstado"];
    
    $pedidoE = new \entidad\Pedido();
    $pedidoE->setIdPedido($idPedido);
    $pedidoE->setFecha($fecha);
    $pedidoE->setIdBarrio($idBarrio);
    $pedidoE->setIdPedidoEstado($idPedidoEstado);    
    $clienteE = new \entidad\Cliente();
    $clienteE->setIdCliente($idCliente);
    $pedidoE->setCliente($clienteE);
    
    
    $pedidoProductoE = new \entidad\PedidoProducto();
    $pedidoProductoE->setPedido($pedidoE);    
    $productoE = new \entidad\Producto();
    $productoE->setIdProducto($idProducto);
    $pedidoProductoE->setProducto($productoE);
    
    $pedidoProductoM = new \modelo\PedidoProducto($pedidoProductoE);
    $retorno["data"] = $pedidoProductoM->consultarPedidoMovil();
    $retorno["numeroRegistros"] = $pedidoProductoM->conexion->obtenerNumeroRegistros();
} catch (Exception $exc) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $exc->getMessage();
}
echo json_encode($retorno);