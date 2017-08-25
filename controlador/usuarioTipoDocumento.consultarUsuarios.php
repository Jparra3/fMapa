<?php session_start();
require_once '../entidad/UsuarioTipoDocumento.php';
require_once '../modelo/UsuarioTipoDocumento.php';

$retorno = array ('exito'=>1,'mensaje'=> '', 'data'=>null, 'numeroRegistros'=>'');

try {
    $usuarioTipoDocumentoM = new \modelo\UsuarioTipoDocumento(new \entidad\UsuarioTipoDocumento());
    $retorno['data'] = $usuarioTipoDocumentoM->consultarUsuarios();
    $retorno['numeroRegistros']= $usuarioTipoDocumentoM->conexion->obtenerNumeroRegistros();
} catch (Exception $e) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
