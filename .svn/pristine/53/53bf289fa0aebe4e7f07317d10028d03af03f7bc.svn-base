<?php
require_once '../entidad/Producto.php';
require_once '../modelo/Producto.php';

$retorno = array("exito"=>1, "mensaje"=>"", "data"=>null, "numeroRegistros"=>0);

try {
    $idProducto = $_POST["idProducto"];
    $codigo = $_POST["codigoProducto"];
    $producto = $_POST["producto"];
    $idUnidadMedida = $_POST["idUnidadMedida"];
    $idLineaProducto = $_POST["idLineaProducto"];
    $parametros['idBodega'] = $_POST["idBodega"];
    $parametros['mostrarSaldosNegativos'] = $_POST["mostrarSaldosNegativos"];
    $parametros['maximoMinimo'] = $_POST["maximoMinimo"];
    
    $productoE = new \entidad\Producto();
    $productoE->setIdProducto($idProducto);
    $productoE->setCodigo($codigo);
    $productoE->setProducto($producto);
    $productoE->setIdUnidadMedida($idUnidadMedida);
    $productoE->setIdLineaProducto($idLineaProducto);
    
    $productoM = new \modelo\Producto($productoE);
    $retorno["data"] = $productoM->consultarSaldosMinimos($parametros);
    $retorno["numeroRegistros"] = $productoM->conexion->obtenerNumeroRegistros();
} catch (Exception $exc) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $exc->getMessage();
}
echo json_encode($retorno);