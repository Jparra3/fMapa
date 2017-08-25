<?php
require_once '../entidad/TipoDocumento.php';
require_once '../modelo/TipoDocumento.php';
$retorno = array('exito'=>1,'mensaje'=>'', 'existe'=>'');

try {
    $codigo = $_POST['codigo'];
    
    $tipoDocumentoE = new \entidad\TipoDocumento();
    $tipoDocumentoE->setCodigo($codigo);
    
    $tipoDocumentoM = new \modelo\TipoDocumento($tipoDocumentoE);
    $retorno["existe"] = $tipoDocumentoM->validarExistenciaCodigo();
} catch (Exception $e) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
