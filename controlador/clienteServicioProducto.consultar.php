<?php
session_start();
require_once '../entidad/ClienteServicio.php';
require_once '../entidad/ClienteServicioProducto.php';
require_once '../modelo/ClienteServicioProducto.php';

$retorno = array('exito'=>1, 'mensaje'=>'', 'data'=>null);

try {
    $idClienteServicio = $_POST['idClienteServicio'];
    $estado = $_POST["estado"];
    
    
    //CLIENTE SERVICIO
    $clienteServicioE = new \entidad\ClienteServicio();
    $clienteServicioE->setIdClienteServicio($idClienteServicio);
    
    
    $clienteServicioProductoE = new \entidad\ClienteServicioProducto();
    $clienteServicioProductoE->setClienteServicio($clienteServicioE);
    $clienteServicioProductoE->setEstado($estado);
    
    
    $clienteServicioProductoM = new \modelo\ClienteServicioProducto($clienteServicioProductoE);
    $retorno['data'] = $clienteServicioProductoM->consultar();
    $retorno['numeroRegistros'] = $clienteServicioProductoM->conexion->obtenerNumeroRegistros();
    
} catch (Exception $exc) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $exc->getMessage();
}
echo json_encode($retorno);

?>