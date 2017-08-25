<?php
require_once '../entidad/Cliente.php';
require_once '../entidad/ClienteServicio.php';
require_once '../modelo/ClienteServicio.php';
try{
    $retorno = array("exito"=>1,"mensaje"=>"","data"=>"","numeroRegistros"=>0);
    
    $idCliente = $_POST["idCliente"];
    
    $clienteE = new \entidad\Cliente();
    $clienteE->setIdCliente($idCliente);
    
    $clienteServicioE = new \entidad\ClienteServicio();
    $clienteServicioE->setCliente($clienteE);
    
    $clienteServicioM = new \modelo\ClientServicio($clienteServicioE);
    $retorno["data"] = $clienteServicioM->consultarInformacionServiciosCliente();
    $retorno["numeroRegistros"] = $clienteServicioM->conexion->obtenerNumeroRegistros();
    
}  catch (Exception $e){
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $e->getMessage();
}
echo json_encode($retorno);
?>