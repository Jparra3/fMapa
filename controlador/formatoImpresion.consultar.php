<?php
require_once '../entidad/FormatoImpresion.php';
$retorno = array("exito"=>1, "mensaje"=>"", "data"=>null, "numeroRegistros"=>0);
try{
    $idFormatoImpresion = $_POST['idFormatoImpresion'];
    $estado = $_POST['estado'];
    
    $formatoImpresionE = new \entidad\FormatoImpresion();
    $formatoImpresionE ->setIdFormatoImpresion($idFormatoImpresion);
    $formatoImpresionE ->setEstado($estado);
    
    $formatoImpresionM = new \modelo\FormatoImpresion($formatoImpresionE);
    $retorno["data"] = $formatoImpresionM->consultar();
    $retorno["numeroRegistros"] = $formatoImpresionM->conexion->obtenerNumeroRegistros();
} catch (Exception $ex) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $ex->getMessage();
}
echo json_encode($retorno);
?>