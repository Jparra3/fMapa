<?php
require_once '../entidad/PedidoProducto.php';
require_once '../modelo/PedidoProducto.php';

$retorno = array("exito"=>1, "mensaje"=>"", "data"=>null, "numeroRegistros"=>0);

try {
    $pedidoProductoM = new \modelo\PedidoProducto(new \entidad\PedidoProducto());
    $retorno["data"] = $pedidoProductoM->consultarProductos();
    $retorno["numeroRegistros"] = $pedidoProductoM->conexion->obtenerNumeroRegistros();
    
} catch (Exception $exc) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $exc->getMessage();
}
echo json_encode($retorno);