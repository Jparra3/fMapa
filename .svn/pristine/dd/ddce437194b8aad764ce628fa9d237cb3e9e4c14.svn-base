<?php
require_once '../entidad/TipoDocumento.php';
require_once '../modelo/TipoDocumento.php';

$retorno = array("exito"=>1, "mensaje"=>"", "data"=>null, "numeroRegistros"=>0);

try {
    $codigo = $_POST["codigo"];
    $idTipoDocumento = $_POST["idTipoDocumento"];
    $tipoDocumento = $_POST["tipoDocumento"];
    $idNaturaleza = $_POST["idNaturaleza"];
    $validarPermisos = $_POST["validarPermisos"];
    $estado = $_POST["estado"];
    $idTipoNaturaleza = $_POST["idTipoNaturaleza"];
    $codigoTipoNaturaleza = $_POST["codigoTipoNaturaleza"];
    
    if($validarPermisos == true){
        $tipoDocumentoM = new \modelo\TipoDocumento(new \entidad\TipoDocumento());
        $idTipoDocumento = $tipoDocumentoM->obtenTipoDocumTienePermi();
        
        if($idTipoDocumento == ""){
            throw new Exception("No tiene permiso a ningún tipo de documento");
        }
        
    }
    
    $tipoDocumentoE = new \entidad\TipoDocumento();
    $tipoDocumentoE->setCodigo($codigo);
    $tipoDocumentoE->setIdTipoDocumento($idTipoDocumento);
    $tipoDocumentoE->setTipoDocumento($tipoDocumento);
    $tipoDocumentoE->setIdNaturaleza($idNaturaleza);
    $tipoDocumentoE->setEstado($estado);
    
    $tipoDocumentoM = new \modelo\TipoDocumento($tipoDocumentoE);
    $retorno["data"] = $tipoDocumentoM->consultar($idTipoNaturaleza, $codigoTipoNaturaleza);
    $retorno["numeroRegistros"] = $tipoDocumentoM->conexion->obtenerNumeroRegistros();
} catch (Exception $exc) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $exc->getMessage();
}
echo json_encode($retorno);