<?php
session_start();

require_once '../entidad/Cliente.php';
require_once '../entidad/Transaccion.php';
require_once '../modelo/Transaccion.php';
require_once '../../Seguridad/entidad/Tercero.php';

$retorno = array('exito'=>1, 'mensaje'=>'', 'data'=>null, 'numeroRegistros'=>0);

try {
    $numeroFactura = $_POST['numeroFactura'];
    $idTipoDocumento = $_POST['idTipoDocumento'];
    $fecha['inicio'] = $_POST['fechaInicio'];
    $fecha['fin'] = $_POST['fechaFin'];
    $idTercero = $_POST['idTercero'];
    //$idCliente = $_POST['idCliente'];
    //$idCaja = $_POST['idCaja'];
    $idFormaPago = $_POST['idFormaPago'];
    $saldo = $_POST['saldo'];
    $tipo = $_POST['tipo'];
    $idTransaccionEstado = $_POST['idTransaccionEstado'];
    $tipoClienteProveedor = $_POST['tipoClienteProveedor'];
    
    $terceroE = new \entidad\Tercero();
    $terceroE->setIdTercero($idTercero);
    
    $transaccionE = new \entidad\Transaccion();
    $transaccionE->setIdTipoDocumento($idTipoDocumento);
    $transaccionE->setNumeroTipoDocumento($numeroFactura);
    $transaccionE->setFecha($fecha);
    $transaccionE->setTercero($terceroE);
    $transaccionE->setIdTransaccionEstado($idTransaccionEstado);
    $transaccionE->setSaldo($saldo);
    $parametros['idCaja'] = $idCaja;
    $parametros['idCliente'] = $idCliente;
    $parametros['idFormaPago'] = $idFormaPago;
    $parametros['tipo'] = $tipo;
    $parametros['tipoClienteProveedor'] = $tipoClienteProveedor;
    
    $transaccionM = new \modelo\Transaccion($transaccionE);
    $retorno['data'] = $transaccionM->consultarFacturacion($parametros);
    $retorno['numeroRegistros'] = $transaccionM->conexion->obtenerNumeroRegistros();
} catch (Exception $e) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $e->getMessage();
}

echo json_encode($retorno);

?>