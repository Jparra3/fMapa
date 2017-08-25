<?php
session_start();
require_once '../entidad/ClienteServicio.php';
require_once '../modelo/ClienteServicio.php';

$retorno = array('exito'=>1, 'mensaje'=>'El servicio se ha adicionado correctamente',"idClienteServicio"=>null);

try {
    
    $idCliente = $_POST["idCliente"];
    $valor = $_POST["valor"];
    $fechaInicial = $_POST["fechaInicial"];
    $idProductoComposicion = $_POST["idProductoComposicion"];
    $idOrdenTrabajoCliente = $_POST["idOrdenTrabajoCliente"];
    $idUsuario = $_SESSION["idUsuario"];
    
    $clienteE = new \entidad\Cliente();
    $clienteE->setIdCliente($idCliente);
    
    $ordenTrabajoClienteE = new \entidad\OrdenTrabajoCliente();
    $ordenTrabajoClienteE->setIdOrdenTrabajoCliente($idOrdenTrabajoCliente);
    
    $clienteServicioE = new \entidad\ClienteServicio();
    $clienteServicioE->setCliente($clienteE);
    $clienteServicioE->setValor($valor);
    $clienteServicioE->setOrdenTrabajoCliente($ordenTrabajoClienteE);
    $clienteServicioE->setIdProductoComposicion($idProductoComposicion);
    $clienteServicioE->setFechaInicial($fechaInicial);
    $clienteServicioE->setFechaFinal("");
    $clienteServicioE->setIdTransaccion($idTransaccion);
    $clienteServicioE->setIdEstadoClienteServicio($idEstadoClienteServicio);
    $clienteServicioE->setEstado("TRUE");
    $clienteServicioE->setIdUsuarioCreacion($idUsuario);
    $clienteServicioE->setIdUsuarioModificacion($idUsuario);
    
    $clienteServicioM = new \modelo\ClientServicio($clienteServicioE);
    $clienteServicioM->adicionar();
    
    $retorno["idClienteServicio"] = $clienteServicioM->obtenerMaximo();
    
} catch (Exception $exc) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $exc->getMessage();
}
echo json_encode($retorno);

?>