<?php
session_start();
require_once '../entidad/Cargo.php';
$retorno = array("exito"=>1, "mensaje"=>"", "data"=>null, "numeroRegistros"=>null);

try{
    
    $idCargo = $_POST['idCargo'];
    $estado = $_POST['estado'];
    
    $cargoE = new \entidad\Cargo();
    $cargoE ->setIdCargo($idEps);
    $cargoE -> setEstado($estado);
    
    $cargoM = new \modelo\Cargo($cargoE);
    $retorno["data"] = $cargoM -> consultar();
    $retorno["numeroRegistros"] = $cargoM -> conexion -> obtenerNumeroRegistros();
    
} catch (Exception $ex) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $ex -> getMessage();
}
echo json_encode($retorno);
?>