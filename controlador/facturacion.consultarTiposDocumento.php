<?php
session_start();

require_once '../entidad/Cliente.php';
require_once '../entidad/Transaccion.php';
require_once '../modelo/Transaccion.php';
require_once '../../Seguridad/entidad/Tercero.php';

$retorno = array('exito'=>1, 'mensaje'=>'', 'data'=>null, 'numeroRegistros'=>0);

try {
    $idCliente = $_POST['idCliente'];
    $idTipoDocumentoOmitir = $_POST['idTipoDocumentoOmitir'];
    $tipoClienteProveedor = $_POST['tipoClienteProveedor'];
    
    $parametros['idCliente'] = $idCliente;
    $parametros['idTipoDocumentoOmitir'] = $idTipoDocumentoOmitir;
    $parametros['tipoClienteProveedor'] = $tipoClienteProveedor;
    
    $transaccionM = new \modelo\Transaccion(new \entidad\Transaccion());
    $retorno['data'] = $transaccionM->consultarTiposDocumento($parametros);
    $retorno['numeroRegistros'] = $transaccionM->conexion->obtenerNumeroRegistros();
} catch (Exception $e) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $e->getMessage();
}

echo json_encode($retorno);

?>