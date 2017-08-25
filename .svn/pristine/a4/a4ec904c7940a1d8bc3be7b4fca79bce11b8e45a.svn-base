<?php
require_once '../entidad/ProductoUnidadMedida.php';
require_once '../modelo/ProductoUnidadMedida.php';

$retorno = array("exito"=>1, "mensaje"=>"", "data"=>null, "numeroRegistros"=>0);

try {
    
    $idProducto = $_POST["idProducto"];
    
    $productoUnidadMedidaE = new \entidad\ProductoUnidadMedida();
    $productoUnidadMedidaE->setIdProducto($idProducto);
    
    $productoUnidadMedidaM = new \modelo\ProductoUnidadMedida($productoUnidadMedidaE);
    $retorno["data"] = $productoUnidadMedidaM->consultar();
    $retorno["numeroRegistros"] = $productoUnidadMedidaM->conexion->obtenerNumeroRegistros();
    
} catch (Exception $exc) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $exc->getMessage();
}
echo json_encode($retorno);