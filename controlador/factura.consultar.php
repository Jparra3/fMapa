<?php
session_start();
require_once '../entidad/ClienteServicio.php';
require_once '../entidad/Cliente.php';
require_once '../entidad/Factura.php';
require_once '../modelo/Factura.php';

$retorno = array('exito'=>1, 'mensaje'=>'', 'data'=>NULL);

try {
    $idCliente = $_POST['idCliente'];
    $idEstadoFactura = $_POST['idEstadoFactura'];
    $fechaInicial['inicio'] = $_POST['fechaInicialInicio'];
    $fechaInicial['fin'] = $_POST['fechaInicialFin'];
    $fechaFinal['inicio'] = $_POST['fechaFinalInicio'];
    $fechaFinal['fin'] = $_POST['fechaFinalFin'];
    
    $clienteE =  new \entidad\Cliente();
    $clienteE->setIdCliente($idCliente);
    
    $clienteServicioE =  new \entidad\ClienteServicio();
    $clienteServicioE->setCliente($clienteE);
    
    $facturaE = new \entidad\Factura();
    $facturaE->setIdFactura($idFactura);
    $facturaE->setClienteServicio($clienteServicioE);
    $facturaE->setIdEstadoFactura($idEstadoFactura);
    $facturaE->setFechaInicial($fechaInicial);
    $facturaE->setFechaFinal($fechaFinal);
    
    $facturaM = new \modelo\Factura($facturaE);
    $retorno['data'] = $facturaM->consultar();
    $retorno['numeroRegistros'] = $facturaM->conexion->obtenerNumeroRegistros();
} catch (Exception $e) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $e->getMessage();
}

echo json_encode($retorno);

?>