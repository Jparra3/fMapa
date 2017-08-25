<?php
require_once '../entidad/FormaPago.php';
require_once '../modelo/FormaPago.php';

$retorno = array("exito"=>1, "mensaje"=>"", "data"=>null, "numeroRegistros"=>0);

try {
    
    $codigoTipoNaturaleza = $_POST["codigoTipoNaturaleza"];
    
    $formaPagoM = new \modelo\FormaPago(new \entidad\FormaPago());
    $retorno["data"] = $formaPagoM->consultar($codigoTipoNaturaleza);
    $retorno["numeroRegistros"] = $formaPagoM->conexion->obtenerNumeroRegistros();
} catch (Exception $exc) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $exc->getMessage();
}
echo json_encode($retorno);