<?php
/*
 * Autor: Alexis Mauricio Plaza Vargas
 * Fecha : 12 Mayo 2016
 * Nota : Se modifico la consulta para visualizar los valores de cantidad de producto y saldo cantidad producto
 * Adémas adición de algunos campos para que los retorne la consulta : Saldo Cantidad Producto , Línea Producto
 */
session_start();
require_once '../entidad/TransaccionProducto.php';
require_once '../modelo/TransaccionProducto.php';

$retorno = array('exito'=>1, 'mensaje'=>'', 'data'=>null, 'numeroRegistros'=>0);

try {
    $parametros["numeroRecibo"] = $_POST["numeroRecibo"];
    $parametros["idCaja"] = $_POST["idCaja"];
    $parametros["idOficina"] = $_POST["idOficina"];
    $parametros["fecha"]["inicio"] = $_POST["fechaInicio"];
    $parametros["fecha"]["fin"] = $_POST["fechaFin"];
    $parametros["idCliente"] = $_POST["idCliente"];
    $parametros["idLineaProducto"] = $_POST["idLineaProducto"];
    $idProducto = $_POST["idProducto"];
    $idBodega = $_POST["idBodega"];
    
    $transaccionProductoE = new \entidad\TransaccionProducto();
    $transaccionProductoE->setIdProducto($idProducto);
    $transaccionProductoE->setIdBodega($idBodega);
    
    $transaccionProductoM = new \modelo\TransaccionProducto($transaccionProductoE);
    $retorno["data"] = $transaccionProductoM->consultarReporteVenta($parametros);
    $retorno['numeroRegistros'] = $transaccionProductoM->conexion->obtenerNumeroRegistros();
} catch (Exception $e) {
    $retorno['exito'] =0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
