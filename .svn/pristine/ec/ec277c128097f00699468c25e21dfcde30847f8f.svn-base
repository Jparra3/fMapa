<?php
require_once '../entidad/Ordenador.php';
require_once '../modelo/Ordenador.php';

$retorno = array("exito"=>1, "mensaje"=>"", "data"=>null, "numeroRegistros"=>0);

try {
    $estado = $_POST["estado"];
    
    $ordenadorE = new \entidad\Ordenador();
    $ordenadorE->setEstado($estado);
    
    $ordenadorM = new \modelo\Ordenador($ordenadorE);

    $retorno["data"] = $ordenadorM->consultar();
    $retorno["numeroRegistros"] = $ordenadorM->conexion->obtenerNumeroRegistros();
} catch (Exception $exc) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $exc->getMessage();
}
echo json_encode($retorno);