<?php
require_once '../entidad/TipoDocumento.php';
require_once '../modelo/TipoDocumento.php';

$retorno = array("exito"=>1, "mensaje"=>"", "numero"=>null);

try {
    
    $idTipoDocumento = $_POST["idTipoDocumento"];
    
    $tipoDocumentoE = new \entidad\TipoDocumento();
    $tipoDocumentoE->setIdTipoDocumento($idTipoDocumento);
    
    $tipoDocumentoM = new \modelo\TipoDocumento($tipoDocumentoE);
    
    $retorno["numero"] = $tipoDocumentoM->obtenerNumero();
} catch (Exception $exc) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $exc->getMessage();
}
echo json_encode($retorno);