<?php
require_once '../entidad/TipoDocumento.php';
require_once '../modelo/TipoDocumento.php';

$retorno = array("exito"=>1, "mensaje"=>"", "data"=>null, "numeroRegistros"=>0);

try {
    $idTipoNaturaleza = $_POST["idTipoNaturaleza"];
    $codigoTipoNaturaleza = $_POST["codigoTipoNaturaleza"];
    
    $tipoDocumentoM = new \modelo\TipoDocumento(new \entidad\TipoDocumento());
    $idTiposDocumentos = $tipoDocumentoM->obtenTipoDocumTienePermi();
    
    if($idTiposDocumentos == ""){
        throw new Exception("No tiene permiso a ningún tipo de documento");
    }
    
    $retorno["data"] = $tipoDocumentoM->consultarTipoDocumento($idTiposDocumentos, $idTipoNaturaleza, $codigoTipoNaturaleza);
    $retorno["numeroRegistros"] = $tipoDocumentoM->conexion->obtenerNumeroRegistros();
} catch (Exception $exc) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $exc->getMessage();
}
echo json_encode($retorno);