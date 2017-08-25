<?php
session_start();
require '../modelo/ClienteServicio.php';
require '../entidad/ClienteServicioArchivo.php';
require '../modelo/ClienteServicioArchivo.php';

try{
    $retorno = array("exito"=>1,"mensaje"=>"");
    $informacionArchivosEliminar = $_POST["informacionEliminarArchivos"];
    $idUsuario = $_SESSION["idUsuario"];
    
    if($informacionArchivosEliminar != "" && $informacionArchivosEliminar != null){
        
        foreach($informacionArchivosEliminar as $idClienteServicioArchivoEliminar){
            
            $clienteServicioArchivoE = new \entidad\ClienteServicioArchivo();
            $clienteServicioArchivoE->setIdClienteServicioArchivo($idClienteServicioArchivoEliminar);
            
            $clienteServicioArchivoM = new \modelo\ClienteServicioArchivo($clienteServicioArchivoE);
            $clienteServicioArchivoM->inactivar();       
        }
    }
}catch(Exception $e){
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $e->getMessage();
}
echo json_encode($retorno);