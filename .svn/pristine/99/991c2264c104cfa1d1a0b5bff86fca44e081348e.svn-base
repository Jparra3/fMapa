<?php
require_once '../entidad/TipoNaturaleza.php';
require_once '../modelo/TipoNaturaleza.php';

$retorno = array("exito"=>1, "mensaje"=>"", "data"=>null, "numeroRegistros"=>0);

try {
    $codigo = $_POST["codigo"];
    $estado = $_POST["estado"];
    
    $tipoNaturalezaE = new \entidad\TipoNaturaleza();
    $tipoNaturalezaE->setCodigo($codigo);
    $tipoNaturalezaE->setEstado($estado);
    
    $tipoNaturalezaM = new \modelo\TipoNaturaleza($tipoNaturalezaE);
    $retorno["data"] = $tipoNaturalezaM->consultar();
    $retorno["numeroRegistros"] = $tipoNaturalezaM->conexion->obtenerNumeroRegistros();
} catch (Exception $exc) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $exc->getMessage();
}
echo json_encode($retorno);