<?php
session_start();
require_once '../entidad/EstadoPedidoProducto.php';
require_once '../modelo/EstadoPedidoProducto.php';
require_once '../entidad/Pedido.php';
require_once '../entidad/Producto.php';
require_once '../entidad/PedidoProducto.php';
require_once '../modelo/PedidoProducto.php';

$retorno = array('exito'=>1, 'mensaje'=>'');

try {
    $idPedido = $_POST['idPedido'];
    $productos = $_POST['productos'];
    $idUsuario = $_SESSION['idUsuario'];
    
    $estadoPedidoProductoM = new \modelo\EstadoPedidoProducto(new \entidad\EstadoPedidoProducto());
    $validacion = $estadoPedidoProductoM->obtenerEstadoInicial();
    if($validacion['exito'] == 0 && is_array($validacion)){
        throw new Exception($validacion['mensaje']);
    }else{
        $idPedidoProductoEstado = $validacion;
    }
    
    $pedidoE = new \entidad\Pedido();
    $pedidoE->setIdPedido($idPedido);
    
    foreach ($productos as $producto){
        $idProducto = $producto['idProducto'];
        $cantidad = $producto['cantidad'];
        $valorUnitario = $producto['valorUnitario'];
        $nota = $producto['nota'];
        
        $productoE = new \entidad\Producto();
        $productoE->setIdProducto($idProducto);
        
        $pedidoProductoE = new \entidad\PedidoProducto();
        $pedidoProductoE->setIdPedido($idPedido);
        
        $pedidoProductoM = new \modelo\PedidoProducto($pedidoProductoE);
        $secuencia = $pedidoProductoM->obtenMaximSecuePedid() + 1;
        
        $pedidoProductoE->setProducto($productoE);
        $pedidoProductoE->setIdPedidoProductoEstado($idPedidoProductoEstado);
        $pedidoProductoE->setNota($nota);
        $pedidoProductoE->setValorUnitaConImpue($valorUnitario);
        $pedidoProductoE->setCantidad($cantidad);
        $pedidoProductoE->setSecuencia($secuencia);
        $pedidoProductoE->setIdUsuarioCreacion($idUsuario);
        $pedidoProductoE->setIdUsuarioModificacion($idUsuario);
        
        $pedidoProductoM = new \modelo\PedidoProducto($pedidoProductoE);
        $pedidoProductoM->adicionar();
    }
    
} catch (Exception $exc) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $exc->getMessage();
}
echo json_encode($retorno);

?>