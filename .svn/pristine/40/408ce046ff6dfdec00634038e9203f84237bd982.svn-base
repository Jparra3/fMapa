<?php
session_start();
require_once '../entidad/Arl.php';
$retorno = array("exito"=>1, "mensaje"=>"", "data"=>null, "numeroRegistros"=>null);

try{
    
    $idArl = $_POST['idArl'];
    $estado = $_POST['estado'];
    
    $arlE = new \entidad\Arl();
    $arlE -> setIdArl($idArl);
    $arlE -> setEstado($estado);
    
    $arlM = new \modelo\Arl($arlE);
    $retorno["data"] = $arlM -> consultar();
    $retorno["numeroRegistros"] = $arlM -> conexion -> obtenerNumeroRegistros();
    
} catch (Exception $ex) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $ex -> getMessage();
}
echo json_encode($retorno);
?>
