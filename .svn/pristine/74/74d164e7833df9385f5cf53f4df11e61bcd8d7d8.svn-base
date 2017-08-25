<?php
session_start();
require_once '../entidad/EstadoFactura.php';
require_once '../modelo/EstadoFactura.php';

$retorno = array('exito'=> 1, 'mensaje'=> '', 'numeroRegistros'=>null, 'data'=>null);

try {
    $estadoFacturaE = new \entidad\EstadoFactura();
    
    $estadoFacturaM = new \modelo\EstadoFactura($estadoFacturaE);
    $retorno['data'] = $estadoFacturaM->consutlar();
    $retorno['numeroRegistros'] = $estadoFacturaM->conexion->obtenerNumeroRegistros();
} catch (Exception $e) {
    $retorno['exito'] = 0;
    $retorno['exito'] = $e->getMessage();
}
echo json_encode($retorno);
?>