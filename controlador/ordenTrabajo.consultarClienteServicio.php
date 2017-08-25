<?php
session_start();
require_once '../entidad/Ordenador.php';
require_once '../entidad/Tecnico.php';
require_once '../entidad/OrdenTrabajoCliente.php';
require_once '../entidad/ClienteServicio.php';
require_once '../entidad/OrdenTrabajo.php';
require_once '../modelo/OrdenTrabajo.php';

$retorno = array('exito'=>1, 'mensaje'=>'', 'data'=>null);

try {
    $idClienteServicio = $_POST['idClienteServicio'];
    $idProducto = $_POST['idProducto'];
    $idEstadoClienteServicio = $_POST['idEstadoClienteServicio'];
    $numero = $_POST['numero'];
    $fechaInicial = $_POST['fechaInicio'];
    $fechaFinal = $_POST['fechaFin'];
    $idTecnico = $_POST['idTecnico'];
    $idMunicipio = $_POST['idMunicipio'];
    $idEstadoClienteServicio = $_POST['idEstadoClienteServicio'];
    $idCliente = $_POST['idCliente'];
    
    $clienteE = new \entidad\Cliente();
    $clienteE->setIdCliente($idCliente);
            
    $clienteServicioE = new \entidad\ClienteServicio();
    $clienteServicioE->setIdClienteServicio($idClienteServicio);
    $clienteServicioE->setIdProductoComposicion($idProducto);
    $clienteServicioE->setIdEstadoClienteServicio($idEstadoClienteServicio);
    $clienteServicioE->setCliente($clienteE);
    $clienteServicioE->setFechaInicial($fechaInicial);
    $clienteServicioE->setFechaFinal($fechaFinal);
    
    $ordenTrabajoE = new \entidad\OrdenTrabajo();
    $ordenTrabajoE->setNumero($numero);
    $ordenTrabajoE->setIdTecnico($idTecnico);
    $ordenTrabajoE->setIdMunicipio($idMunicipio);
    $ordenTrabajoE->setClienteServicio($clienteServicioE);
    
    $ordenTrabajoM = new \modelo\OrdenTrabajo($ordenTrabajoE);
    $retorno['data'] = $ordenTrabajoM->consultarClienteServicio();
    $retorno['numeroRegistros'] = $ordenTrabajoM->conexion->obtenerNumeroRegistros();
    
} catch (Exception $exc) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $exc->getMessage();
}
echo json_encode($retorno);

?>
