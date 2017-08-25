<?php
require_once '../entidad/Concepto.php';
require_once '../modelo/Concepto.php';
require_once '../entidad/TipoDocumento.php';
require_once '../modelo/TipoDocumento.php';
$retorno = array("exito" => 1, "mensaje" => "", "data" => null, "numeroRegistros" => 0);

try{
    
    $idConcepto = $_POST["idConcepto"];
    $estado = $_POST["estado"];
    $codigoTipoNaturaleza = $_POST["codigoTipoNaturaleza"];
    
    $parametros["codigoTipoNaturaleza"] = $codigoTipoNaturaleza;
    
    $conceptoE = new \entidad\Concepto();
    $conceptoE ->setIdConcepto($idConcepto);
    
    $conceptoM = new \modelo\Concepto($conceptoE);
    $retorno["data"] = $conceptoM -> consultar($parametros);
    $retorno["numeroRegistros"] = $conceptoM -> conexion -> obtenerNumeroRegistros();
    
} catch (Exception $ex) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $ex -> getMessage();
}

echo json_encode($retorno);
?>

