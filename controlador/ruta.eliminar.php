<?php
session_start();
require_once '../entidad/Ruta.php';
require_once '../modelo/Ruta.php';

$retorno = array("exito"=>1, "mensaje"=>"La ruta se eliminó correctamente.");

try{
    $idRuta = $_POST['idRuta'];
    $archivo = $_POST['archivo'];
    
    @unlink($archivo);
    
    $rutaE = new \entidad\Ruta();
    $rutaE->setIdRuta($idRuta);
    
    $rutaM = new \modelo\Ruta($rutaE);
    $rutaM->eliminar();
    
} catch (Exception $ex) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $ex -> getMessage();
}

echo json_encode($retorno);
?>