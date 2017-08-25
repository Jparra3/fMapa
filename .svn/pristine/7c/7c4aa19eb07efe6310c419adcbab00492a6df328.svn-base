<?php
require_once '../entidad/Producto.php';
require_once '../modelo/Producto.php';

$retorno = array("exito"=>1, "mensaje"=>"", "data"=>null, "numeroRegistros"=>0);

try {
    $idProducto = $_POST["idProducto"];
    $codigo = $_POST["codigoProducto"];
    $producto = $_POST["producto"];
    $idUnidadMedida = $_POST["idUnidadMedida"];
    $productoComposicion = $_POST["productoComposicion"];
    $idLineaProducto = $_POST["idLineaProducto"];
    $productoServicio = $_POST['productoServicio'];
    $estado = $_POST["estado"];
    
    $productoE = new \entidad\Producto();
    $productoE->setIdProducto($idProducto);
    $productoE->setCodigo($codigo);
    $productoE->setProducto($producto);
    $productoE->setIdUnidadMedida($idUnidadMedida);
    $productoE->setProductoComposicion($productoComposicion);
    $productoE->setIdLineaProducto($idLineaProducto);
    $productoE->setEstado($estado);
    $productoE->setProductoServicio($productoServicio);
    
    $productoM = new \modelo\Producto($productoE);
    $retorno["data"] = $productoM->consultar();
    $retorno["numeroRegistros"] = $productoM->conexion->obtenerNumeroRegistros();
} catch (Exception $exc) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $exc->getMessage();
}
echo json_encode($retorno);