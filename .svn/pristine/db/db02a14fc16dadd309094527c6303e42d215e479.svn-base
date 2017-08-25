<?php
require_once '../entidad/Concepto.php';
require_once '../modelo/Concepto.php';

$retorno = array("exito"=>1, "mensaje"=>"", "data"=>null, "numeroRegistros"=>0);

try {
    
    $codigo = $_POST["idConcepto"];
    $codigoTipoNaturaleza = $_POST["codigoTipoNaturaleza"];
    
    $conceptoE = new \entidad\Concepto();
    
    $conceptoM = new \modelo\Concepto($conceptoE);
    $retorno["data"] = $conceptoM ->consultarConcepto($codigo, $codigoTipoNaturaleza);
    $retorno["numeroRegistros"] = $conceptoM->conexion->obtenerNumeroRegistros();
} catch (Exception $exc) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $exc->getMessage();
}
echo json_encode($retorno);