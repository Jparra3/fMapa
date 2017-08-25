<?php
session_start();
require_once '../entidad/Ruta.php';
require_once '../modelo/Ruta.php';
require_once '../entidad/Cliente.php';
require_once '../modelo/Cliente.php';

$retorno = array("exito"=>1, "mensaje"=>"", "data"=>null, "numeroRegistros"=>null);

try{
    $idZona = $_POST['idZona'];
    $idRegional = $_POST['idRegional'];
    $idPeriodicidadVisita = $_POST['idPeriodicidadVisita'];
    $fechaFin = $_POST['fechaFin'];
    $modulo = $_POST['modulo'];
    
    $clienteE = new \entidad\Cliente();
    $clienteE->setIdZona($idZona);
    $clienteE->setIdPeriodicidadVisita($idPeriodicidadVisita);
    
    $rutaE = new \entidad\Ruta();
    $rutaE->setCliente($clienteE);
    $rutaE->setFechaFin($fechaFin);
    
    $rutaM = new \modelo\Ruta($rutaE);
    $retorno["data"] = $rutaM->consultar();
    
    if($rutaM->numeroRegistros > 0)
        $retorno["data"] = $rutaM->organizarArreglo($retorno["data"]);
    
    $retorno["numeroRegistros"] = $rutaM->numeroRegistros;
    
    //----------SE ALMACENA EN UNA SESION PARA REALIZAR LUEGO EL PROCESO DE DESCARGA Y ALMACENAMIENTO EN LA BD----------------
    $retorno["infoRuta"]["idZona"] = $idZona;
    $retorno["infoRuta"]["idRegional"] = $idRegional;
    $retorno["infoRuta"]["modulo"] = $modulo;
    $_SESSION['arregloEvaluar'] = $retorno;
    //-----------------------------------------------------------------------------------------------------------------------
    
} catch (Exception $ex) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $ex -> getMessage();
}

echo json_encode($retorno);
?>