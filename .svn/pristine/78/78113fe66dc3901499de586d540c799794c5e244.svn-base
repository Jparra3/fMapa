<?php
session_start();
require_once '../entidad/Cliente.php';
require_once '../modelo/Cliente.php';

$retorno = array('exito'=>1, 'mensaje'=>'', 'data'=>null, 'numeroRegistros'=>0);

try {
    $nit = $_POST["nit"];
    $tipoClienteProveedor = $_POST["tipo"];
    
    $clienteE = new \entidad\Cliente();
    $clienteE->setNit($nit);
    $clienteE->setTipoClienteProveedor($tipoClienteProveedor);
    
    $clienteM = new \modelo\Cliente($clienteE);
    $retorno["data"] = $clienteM->consultarCliente();
    $retorno['numeroRegistros'] = $clienteM->conexion->obtenerNumeroRegistros();
} catch (Exception $e) {
    $retorno['exito'] =0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
