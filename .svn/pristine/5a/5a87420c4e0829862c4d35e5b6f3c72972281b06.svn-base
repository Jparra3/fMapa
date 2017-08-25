<?php
require_once '../../Seguridad/entidad/Oficina.php';
require_once '../../Seguridad/modelo/Oficina.php';
require_once '../entidad/Bodega.php';
require_once '../modelo/Bodega.php';

$retorno = array("exito"=>1, "mensaje"=>"", "data"=>null, "numeroRegistros"=>0);

try {
    
    $oficinaM = new \modelo\Oficina(new \entidad\Oficina());
    $idOficinas = $oficinaM->consuOficiTienePermi();
    
    $bodegaE = new \entidad\Bodega();
    $bodegaE->setIdOficina($idOficinas);
    
    $bodegaM = new \modelo\Bodega($bodegaE);
    $retorno["data"] = $bodegaM->consultar();
    $retorno["numeroRegistros"] = $bodegaM->conexion->obtenerNumeroRegistros();
} catch (Exception $exc) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $exc->getMessage();
}
echo json_encode($retorno);