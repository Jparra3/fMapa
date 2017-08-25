<?php
session_start();
require_once '../entidad/MovimientoContable.php';
require_once '../modelo/MovimientoContable.php';
require_once '../entidad/TipoDocumento.php';
require_once '../modelo/TipoDocumento.php';


$retorno = array('exito'=>1, 'mensaje'=>'El movimiento contable se guardó correctamente.', 'idMovimientoContable'=>'');

try {
    $idTipoDocumento = $_POST["idTipoDocumento"];
    $fecha = $_POST["fecha"];
    $nota = $_POST["nota"];
    $idUsuario = $_SESSION["idUsuario"];
    
    $tipoDocumentoE = new \entidad\TipoDocumento();
    $tipoDocumentoE->setIdTipoDocumento($idTipoDocumento);
    
    $tipoDocumentoM = new \modelo\TipoDocumento($tipoDocumentoE);
    $numeroTipoDocumento = $tipoDocumentoM->obtenerNumero();
    
    $movimientoContableE = new \entidad\MovimientoContable();
    $movimientoContableE->setIdTipoDocumento($idTipoDocumento);
    $movimientoContableE->setNumeroTipoDocumento($numeroTipoDocumento);
    $movimientoContableE->setFecha($fecha);
    $movimientoContableE->setNota($nota);
    $movimientoContableE->setIdUsuarioCreacion($idUsuario);
    $movimientoContableE->setIdUsuarioModificacion($idUsuario);
    
    $movimientoContableM = new \modelo\MovimientoContable($movimientoContableE);
    $movimientoContableM->adicionar();
    $retorno['idMovimientoContable'] = $movimientoContableM->obtenerMaximo();
    
    $tipoDocumentoE = new \entidad\TipoDocumento();
    $tipoDocumentoE->setIdTipoDocumento($idTipoDocumento);
    $tipoDocumentoE->setNumero($numeroTipoDocumento);
    
    $tipoDocumentoM = new \modelo\TipoDocumento($tipoDocumentoE);
    $tipoDocumentoM->actualizarNumero();
} catch (Exception $e) {
    $retorno['exito'] =0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
