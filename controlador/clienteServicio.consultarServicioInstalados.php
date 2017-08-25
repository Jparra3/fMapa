<?php
session_start();
require_once '../entidad/Cliente.php';
require_once '../entidad/OrdenTrabajo.php';
require_once '../entidad/OrdenTrabajoCliente.php';
require_once '../entidad/ClienteServicio.php';
require_once '../modelo/ClienteServicio.php';

$retorno = array('exito'=>1, 'mensaje'=>'', 'data'=>null);

try {
    $idProducto = $_POST["idServicio"];
    $fechaInicial = $_POST['fechaInicial'];
    $fechaFinal = $_POST['fechaFinal'];
    $idCliente = $_POST['idCliente'];
    $tipoOrdenTrabajo = $_POST['tipoOrden'];
    $idMunicipio = $_POST['idMunicipio'];
    $idVendedor = $_POST["idVendedor"];
    
    //ORDEN DE TRABAJO
    $ordenTrabajoE = new \entidad\OrdenTrabajo();
    $ordenTrabajoE->setIdMunicipio($idMunicipio);
    $ordenTrabajoE->setTipoOrdenTrabajo($tipoOrdenTrabajo);
    
    //CLIENTE
    $clienteE = new \entidad\Cliente();
    $clienteE->setIdCliente($idCliente);
    
    //ORDEN DE TRABAJO CLIENTE
    $ordenTrabajoClienteE = new \entidad\OrdenTrabajoCliente();
    $ordenTrabajoClienteE->setOrdenTrabajo($ordenTrabajoE);
    
    //CLIENTE SERVICIO
    $clienteServicioE = new \entidad\ClienteServicio();
    $clienteServicioE->setIdClienteServicio($idClienteServicio);
    $clienteServicioE->setOrdenTrabajoCliente($ordenTrabajoClienteE);
    $clienteServicioE->setCliente($clienteE);
    $clienteServicioE->setIdEstadoClienteServicio($idEstadoClienteServicio);
    $clienteServicioE->setFechaInicial($fechaInicial);
    $clienteServicioE->setFechaFinal($fechaFinal);
    
    $ordenTrabajoClienteM = new \modelo\ClientServicio($clienteServicioE);
    $retorno['data'] = $ordenTrabajoClienteM->reporteServiciosInstalados($idVendedor, $idProducto);
    $retorno['numeroRegistros'] = $ordenTrabajoClienteM->conexion->obtenerNumeroRegistros();
    
} catch (Exception $exc) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $exc->getMessage();
}
echo json_encode($retorno);

?>