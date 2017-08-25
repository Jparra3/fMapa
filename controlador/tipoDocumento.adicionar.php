<?php
session_start();
require_once '../entidad/TipoDocumento.php';
require_once '../modelo/TipoDocumento.php';

$retorno = array('exito'=>1,'mensaje'=>'El tipo de documento se guardó correctamente.');

try {    
    $tipoDocumento = $_POST["tipoDocumento"];
    $idNaturaleza = $_POST["idNaturaleza"];
    $idOficina = $_POST["idOficina"];
    $estado = $_POST["estado"];
    $codigo = $_POST["codigo"];
    $idUsuario = $_SESSION["idUsuario"];
    
    $tipoDocumentoE = new \entidad\TipoDocumento();
    $tipoDocumentoE->setCodigo($codigo);
    $tipoDocumentoE->setTipoDocumento($tipoDocumento);
    $tipoDocumentoE->setIdNaturaleza($idNaturaleza);
    $tipoDocumentoE->setIdOficina($idOficina);
    $tipoDocumentoE->setEstado($estado);
    $tipoDocumentoE->setIdUsuarioCreacion($idUsuario);
    $tipoDocumentoE->setIdUsuarioModificacion($idUsuario);
            
    $tipoDocumentoM = new \modelo\TipoDocumento($tipoDocumentoE);
    $tipoDocumentoM->adicionar();
} catch (Exception $e) {
    $retorno['exito'] =0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
