<?php
session_start();
require_once '../entidad/UsuarioTipoDocumento.php';
require_once '../modelo/UsuarioTipoDocumento.php';

$retorno = array('exito'=>1, 'mensaje'=>'', 'data'=>null, 'numeroRegistros'=>0);

try {
    $idUsuario = $_POST["idUsuario"];
    
    $usuarioTipoDocumentoE = new \entidad\UsuarioTipoDocumento();
    $usuarioTipoDocumentoE->setIdUsuario($idUsuario);
    
    $usuarioTipoDocumentoM = new \modelo\UsuarioTipoDocumento($usuarioTipoDocumentoE);
    $retorno["data"] = $usuarioTipoDocumentoM->consultar();
    $retorno['numeroRegistros'] = $usuarioTipoDocumentoM->conexion->obtenerNumeroRegistros();
    
} catch (Exception $e) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
