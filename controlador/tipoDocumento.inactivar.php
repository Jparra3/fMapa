<?php
require_once '../entidad/TipoDocumento.php';
require_once '../modelo/TipoDocumento.php';

$retorno = array('exito'=>1,'mensaje'=>'El tipo de documento se inactivó correctamente.');

try {    
    $idTipoDocumento = $_POST["idTipoDocumento"];
    
    $tipoDocumentoE = new \entidad\TipoDocumento();
    $tipoDocumentoE->setIdTipoDocumento($idTipoDocumento);
    
    $tipoDocumentoM = new \modelo\TipoDocumento($tipoDocumentoE);
    $tipoDocumentoM->inactivar();
} catch (Exception $e) {
    $retorno['exito'] =0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
