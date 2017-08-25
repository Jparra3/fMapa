<?php
session_start();
require_once '../entidad/Cliente.php';
require_once '../modelo/Cliente.php';
require_once '../../Seguridad/entidad/Tercero.php';
require_once '../../Seguridad/modelo/Tercero.php';

$retorno = array('exito'=>1, 'mensaje'=>'', 'data'=>null, 'numeroRegistros'=>0);

try {
    
    $nit = $_POST["nit"];
    
    $clienteE = new \entidad\Cliente();
    $clienteE ->setNit($nit);
    
    $clienteM = new \modelo\Cliente($clienteE);
    $retorno["data"] = $clienteM->consultar();
    $retorno['numeroRegistros'] = $clienteM->conexion->obtenerNumeroRegistros();
} catch (Exception $e) {
    $retorno['exito'] =0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
