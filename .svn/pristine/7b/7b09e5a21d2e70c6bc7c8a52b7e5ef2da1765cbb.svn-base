<?php
session_start();
require_once '../entidad/Transaccion.php';
require_once '../modelo/Transaccion.php';

$retorno = array('exito'=>1, 'mensaje'=>'', 'data'=>null, 'numeroRegistros'=>0);

try {
    $parametros["numeroReciboAfecta"] = $_POST["numeroRecibo"];
    $parametros["idCaja"] = $_POST["idCaja"];
    $parametros["idOficina"] = $_POST["idOficina"];
    $parametros["fecha"]["inicio"] = $_POST["fechaInicio"];
    $parametros["fecha"]["fin"] = $_POST["fechaFin"];
    $parametros["idCliente"] = $_POST["idCliente"];
    $parametros["idEstadoTransaccion"] = $_POST["idEstadoTransaccion"];
    
    $transaccionProductoM = new \modelo\Transaccion(new \entidad\Transaccion());
    $retorno["data"] = $transaccionProductoM->consultarFacturas($parametros);
    $retorno['numeroRegistros'] = $transaccionProductoM->conexion->obtenerNumeroRegistros();
} catch (Exception $e) {
    $retorno['exito'] =0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
