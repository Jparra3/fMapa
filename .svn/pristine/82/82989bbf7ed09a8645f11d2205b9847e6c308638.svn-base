<?php
require_once '../entidad/Transaccion.php';
require_once '../entidad/ProductoComposicion.php';
require_once '../modelo/ProductoComposicion.php';

$retorno = array("exito"=>1, "mensaje"=>"", "data"=>null, "numeroRegistros"=>0);

try {
    $idTransaccion = $_POST["idTransaccion"];
    
    $productoComposicionM = new \modelo\ProductoComposicion(new \entidad\ProductoComposicion());
    $retorno["data"] = $productoComposicionM->consultarProductoCompuestoInventariado($idTransaccion);
    $retorno["numeroRegistros"] = $productoComposicionM->conexion->obtenerNumeroRegistros();
} catch (Exception $exc) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $exc->getMessage();
}
echo json_encode($retorno);