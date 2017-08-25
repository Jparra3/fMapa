<?php
session_start();
require_once '../entidad/Eps.php';
$retorno = array("exito"=>1, "mensaje"=>"", "data"=>null, "numeroRegistros"=>null);

try{
    
    $idEps = $_POST['idEps'];
    $estado = $_POST['estado'];
    
    $epsE = new \entidad\Eps();
    $epsE -> setIdEps($idEps);
    $epsE -> setEstado($estado);
    
    $epsM = new \modelo\Eps($epsE);
    $retorno["data"] = $epsM -> consultar();
    $retorno["numeroRegistros"] = $epsM -> conexion -> obtenerNumeroRegistros();
    
} catch (Exception $ex) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $ex -> getMessage();
}
echo json_encode($retorno);
?>