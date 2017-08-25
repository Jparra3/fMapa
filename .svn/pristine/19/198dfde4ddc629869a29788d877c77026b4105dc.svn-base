<?php
session_start();
require '../modelo/ClienteServicio.php';
require '../entidad/ClienteServicioArchivo.php';
require '../modelo/ClienteServicioArchivo.php';

try{
    $retorno = array("exito"=>1,"mensaje"=>"");
    $idClienteServicio = $_POST["idClienteServicio"];
    $idOrdenTrabajoCliente = $_POST["idOrdenTrabajoCliente"];
    $estado = $_POST["estado"];
    
    $ordenTrabajoClienteE = new \entidad\OrdenTrabajoCliente();
    $ordenTrabajoClienteE->setIdOrdenTrabajoCliente($idOrdenTrabajoCliente);
    
    $clienteServicioArchivoE = new \entidad\ClienteServicioArchivo();
    $clienteServicioArchivoE->setOrdenTrabajoCliente($ordenTrabajoClienteE);
    $clienteServicioArchivoE->setEstado("TRUE");
    
    $clienteServicioArchivoM = new \modelo\ClienteServicioArchivo($clienteServicioArchivoE);
    $retorno["data"] = $clienteServicioArchivoM->consultar();
    $retorno["numeroRegistros"] = $clienteServicioArchivoM->conexion->obtenerNumeroRegistros();
            
}catch(Exception $e){
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $e->getMessage();
}
echo json_encode($retorno);