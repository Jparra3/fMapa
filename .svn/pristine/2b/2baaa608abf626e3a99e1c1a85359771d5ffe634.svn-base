<?php
session_start();
require_once '../entidad/UsuarioTipoDocumento.php';
require_once '../modelo/UsuarioTipoDocumento.php';

$retorno = array('exito'=>1, 'mensaje'=>'La información se guardó correctamente.');

try {
    $idUsuario = $_POST["idUsuario"];
    $dataTipoDocumento = $_POST["dataTipoDocumento"];
    $idUsuarioCreacion = $_SESSION["idUsuario"];
    
    $usuarioTipoDocumentoE = new \entidad\UsuarioTipoDocumento();
    $usuarioTipoDocumentoE->setIdUsuario($idUsuario);
    
    $usuarioTipoDocumentoM = new \modelo\UsuarioTipoDocumento($usuarioTipoDocumentoE);
    $usuarioTipoDocumentoM->eliminar();
    
    $contador = 0;
    while ($contador < count($dataTipoDocumento)) {
        $usuarioTipoDocumentoE = new \entidad\UsuarioTipoDocumento();
        $usuarioTipoDocumentoE->setIdUsuario($idUsuario);
        $usuarioTipoDocumentoE->setIdTipoDocumento($dataTipoDocumento[$contador]["idTipoDocumento"]);
        $usuarioTipoDocumentoE->setIdUsuarioCreacion($idUsuarioCreacion);
        $usuarioTipoDocumentoE->setIdUsuarioModificacion($idUsuarioCreacion);
        
        $usuarioTipoDocumentoM = new \modelo\UsuarioTipoDocumento($usuarioTipoDocumentoE);
        $usuarioTipoDocumentoM->adicionar(); 
        $contador++;
    }
} catch (Exception $e) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
