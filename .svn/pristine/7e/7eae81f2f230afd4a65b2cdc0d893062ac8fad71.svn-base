<?php
session_start();
require_once '../entidad/Transaccion.php';
require_once '../entidad/TransaccionProducto.php';
require_once '../modelo/TransaccionProducto.php';

$retorno = array('exito'=>1, 'mensaje'=>'', 'data'=>null, 'numeroRegistros'=>0);

try {
    $idTransaccion = $_POST["idTransaccion"];
    
    $transaccionE = new \entidad\Transaccion();
    $transaccionE->setIdTransaccion($idTransaccion);
    
    $transaccionProductoE = new \entidad\TransaccionProducto();
    $transaccionProductoE->setTransaccion($transaccionE);
    
    $transaccionProductoM = new \modelo\TransaccionProducto($transaccionProductoE);
    $retorno["data"] = $transaccionProductoM->consultar();
    $retorno['numeroRegistros'] = $transaccionProductoM->conexion->obtenerNumeroRegistros();
    
} catch (Exception $e) {
    $retorno['exito'] =0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
