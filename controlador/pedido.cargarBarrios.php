<?php
require_once '../entidad/Pedido.php';
require_once '../modelo/Pedido.php';

$retorno = array("exito"=>1, "mensaje"=>"", "data"=>null, "numeroRegistros"=>0);

try {
    $pedidoM = new \modelo\Pedido(new \entidad\Pedido());
    $retorno["data"] = $pedidoM->consultarBarrios();
    $retorno["numeroRegistros"] = $pedidoM->conexion->obtenerNumeroRegistros();
    
} catch (Exception $exc) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $exc->getMessage();
}
echo json_encode($retorno);