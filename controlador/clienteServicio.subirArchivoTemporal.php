<?php
session_start();
require_once '../entidad/ClienteServicio.php';
require_once '../modelo/ClienteServicio.php';
try{
    
    $retorno = array("exito"=>1,"mensaje"=>"El archivo se subio correctamente","rutaArchivo"=>"");
    
    $archivo = $_FILES;
    $usuario = $_SESSION["usuario"];
    
    
    $clienteServicioE = new \entidad\ClienteServicio();
    $clienteServicioM = new \modelo\ClientServicio($clienteServicioE);
    $arrRetorno = $clienteServicioM->subirArchivo($archivo, $usuario);
    
    if($arrRetorno["exito"] == 0){
        throw new Exception($arrRetorno["mensaje"]);
    }
    
    $retorno["rutaArchivo"] = $arrRetorno["ruta"];
}  catch (Exception $e){
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $e->getMessage();
}
echo json_encode($retorno);