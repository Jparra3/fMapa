<?php
session_start();
require_once '../entidad/Cliente.php';
require_once '../entidad/OrdenTrabajo.php';
require_once '../entidad/OrdenTrabajoCliente.php';
require_once '../entidad/ClienteServicio.php';
require_once '../modelo/ClienteServicio.php';

$retorno = array('exito'=>1, 'mensaje'=>'', 'data'=>null,"numeroRegistros"=>0);

try {
    $idClienteServicio = $_POST['idClienteServicio'];
    $idOrdenTrabajoCliente = $_POST['idOrdenTrabajoCliente'];
    $fechaInicial['inicio'] = $_POST['fechaInicialInicio'];
    $fechaInicial['fin'] = $_POST['fechaInicioFin'];
    $fechaFinal['inicio'] = $_POST['fechaFinalInicio'];
    $fechaFinal['fin'] = $_POST['fechaFinalFin'];
    $idEstadoClienteServicio = $_POST['idEstadoClienteServicio'];
    $idCliente = $_POST['idCliente'];
    $numero = $_POST['numero'];
    $tipoOrdenTrabajo = $_POST['tipoOrden'];
    $idMunicipio = $_POST['idMunicipio'];
    
    //ORDEN DE TRABAJO
    $ordenTrabajoE = new \entidad\OrdenTrabajo();
    $ordenTrabajoE->setIdMunicipio($idMunicipio);
    $ordenTrabajoE->setNumero($numero);
    $ordenTrabajoE->setTipoOrdenTrabajo($tipoOrdenTrabajo);
    
    //CLIENTE
    $clienteE = new \entidad\Cliente();
    $clienteE->setIdCliente($idCliente);
    
    //ORDEN DE TRABAJO CLIENTE
    $ordenTrabajoClienteE = new \entidad\OrdenTrabajoCliente();
    $ordenTrabajoClienteE->setIdOrdenTrabajoCliente($idOrdenTrabajoCliente);
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
    $retorno['data'] = $ordenTrabajoClienteM->consultarServiciosValidos();
    $retorno['numeroRegistros'] = $ordenTrabajoClienteM->conexion->obtenerNumeroRegistros();
    
} catch (Exception $exc) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $exc->getMessage();
}
echo json_encode($retorno);

?>