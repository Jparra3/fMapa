<?php
session_start();
require_once '../entidad/Ruta.php';
require_once '../modelo/Ruta.php';
require_once '../entidad/Cliente.php';
require_once '../modelo/Cliente.php';

$retorno = array("exito"=>1, "mensaje"=>"La ruta se adicionó correctamente.", "idRuta"=>"");

try{
    $fechaHora = $_POST['fechaHora'];
    $idRutaEstado = $_POST['idRutaEstado'];
    $idRutaPadre = $_POST['idRutaPadre'];
    $idCliente = $_POST['idCliente'];
    $idZona = $_POST['idZona'];
    $idRegional = $_POST['idRegional'];
    $archivo = $_POST['archivo'];
    $jsonData = $_POST['jsonData'];
    $idUsuario = $_SESSION['idUsuario'];
    
    $clienteE = new \entidad\Cliente();
    $clienteE->setIdCliente($idCliente);
    
    $rutaE = new \entidad\Ruta();
    $rutaE->setCliente($clienteE);
    $rutaE->setFechaHora($fechaHora);
    $rutaE->setEstado("true");
    $rutaE->setIdRutaEstado($idRutaEstado);
    $rutaE->setIdRutaPadre($idRutaPadre);
    $rutaE->setIdZona($idZona);
    $rutaE->setIdRegional($idRegional);
    $rutaE->setArchivo($archivo);
    $rutaE->setJsonData($jsonData);
    $rutaE->setIdUsuarioCreacion($idUsuario);
    $rutaE->setIdUsuarioModificacion($idUsuario);
    
    $rutaM = new \modelo\Ruta($rutaE);
    $rutaM->adicionar();
    $retorno["idRuta"] = $rutaM->obtenerMaximo();
    
} catch (Exception $ex) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $ex -> getMessage();
}

//echo json_encode($retorno);
?>