<?php

session_start();
set_time_limit(0);

require_once '../entidad/Transaccion.php';
require_once '../modelo/Transaccion.php';
require_once '../entidad/TransaccionProveedor.php';
require_once '../modelo/TransaccionProveedor.php';
require_once '../entidad/TipoDocumento.php';
require_once '../modelo/TipoDocumento.php';
require_once '../../Seguridad/entidad/Tercero.php';
require_once '../entidad/TransaccionFormaPago.php';
require_once '../modelo/TransaccionFormaPago.php';
require_once '../entidad/Concepto.php';
require_once '../modelo/Concepto.php';
require_once '../entidad/TransaccionCruce.php';
require_once '../modelo/TransaccionCruce.php';
require_once '../entidad/TransaccionConcepto.php';
require_once '../modelo/TransaccionConcepto.php';

$arrRetorno = array('exito' => 1, 'mensaje' => 'El inventario se guardó correctamente.', 'idTransaccion' => '');

try {
    $tipoDocumentoE = new \entidad\TipoDocumento();
    $tipoDocumentoE->setIdTipoDocumento($idTipoDocumento);

    $tipoDocumentoM = new \modelo\TipoDocumento($tipoDocumentoE);
    $numeroTipoDocumento = $tipoDocumentoM->obtenerNumero();

    $terceroE = new \entidad\Tercero();
    $terceroE->setIdTercero($idTercero);

    $transaccionE = new \entidad\Transaccion();
    $transaccionE->setIdTipoDocumento($idTipoDocumento);
    $transaccionE->setTercero($terceroE);
    $transaccionE->setIdTransaccionEstado($idTransaccionEstado);
    $transaccionE->setIdOficina($idOficina);
    $transaccionE->setIdNaturaleza($idNaturaleza);
    $transaccionE->setNumeroTipoDocumento($numeroTipoDocumento);
    $transaccionE->setNota($nota);
    $transaccionE->setFecha($fecha);
    $transaccionE->setFechaVencimiento($fechaVencimiento);
    $transaccionE->setValor($totalCompra);
    $transaccionE->setSaldo($totalCompra);
    $transaccionE->setIdUsuarioCreacion($idUsuario);
    $transaccionE->setIdUsuarioModificacion($idUsuario);
    $transaccionE->setIdTransaccionAfecta("NULL");

    $transaccionM = new \modelo\Transaccion($transaccionE);
    $transaccionM->adicionar();
    $arrRetorno['idTransaccion'] = $transaccionM->obtenerMaximo();

    /* --------------------INSERTO EN TRANSACCION CONCEPTO---------------------- */
    $conceptoE = new \entidad\Concepto();
    $conceptoE->setIdConcepto($idConcepto);

    $transaccionE = new \entidad\Transaccion();
    $transaccionE->setIdTransaccion($arrRetorno['idTransaccion']);

    $transaccionConceptoE = new \entidad\TransaccionConcepto();
    $transaccionConceptoE->setConcepto($conceptoE);
    $transaccionConceptoE->setTransaccion($transaccionE);
    $transaccionConceptoE->setValor($totalCompra);
    $transaccionConceptoE->setSaldo($totalCompra);
    $transaccionConceptoE->setNota(null);
    $transaccionConceptoE->setIdUsuarioCrecion($idUsuario);
    $transaccionConceptoE->setIdUsuarioModificacion($idUsuario);

    $transaccionConceptoM = new \modelo\TransaccionConcepto($transaccionConceptoE);
    $transaccionConceptoM->adicionar();
    $idTransaccionConcepto = $transaccionConceptoM->obtenerMaximo();

    //---------------------INSERTO EN TRANSACCION PROVEEDOR-------------------
    if ($idTercero != 0) {
        $transaccionProveedorE = new \entidad\TransaccionProveedor();
        $transaccionProveedorE->setIdTransaccion($arrRetorno['idTransaccion']);
        $transaccionProveedorE->setIdProveedor($idProveedor);
        $transaccionProveedorE->setIdUsuarioCreacion($idUsuario);
        $transaccionProveedorE->setIdUsuarioModicacion($idUsuario);
        if ($documentoExterno != "" && $documentoExterno != "null" && $documentoExterno != null) {
            $transaccionProveedorE->setDocumentoExterno($documentoExterno);
        }
        $transaccionProveedorM = new \modelo\TransaccionProveedor($transaccionProveedorE);
        $transaccionProveedorM->adicionar();
    }
    //------------------------------------------------------------------------
    //----------------------INSERTO LAS FORMAS DE PAGO------------------------
    $contador = 0;
    $secuencia = 1;
    while ($contador < count($dataFormasPago)) {

        //---------------INSERTO EN TRANSACCION FORMA DE PAGO-----------------------------
        $transaccionFormaPagoE = new \entidad\TransaccionFormaPago();
        $transaccionFormaPagoE->setIdFormaPago($dataFormasPago[$contador]->idFormaPago);
        $transaccionFormaPagoE->setIdTransaccion($arrRetorno['idTransaccion']);
        $transaccionFormaPagoE->setValor($dataFormasPago[$contador]->valor);
        $transaccionFormaPagoE->setNota("");
        $transaccionFormaPagoE->setEstado("true");
        $transaccionFormaPagoE->setIdUsuarioCreacion($idUsuario);
        $transaccionFormaPagoE->setIdUsuarioModificacion($idUsuario);

        $transaccionFormaPagoM = new \modelo\TransaccionFormaPago($transaccionFormaPagoE);
        $transaccionFormaPagoM->adicionar();
        $idTransaccionFormaPago = $transaccionFormaPagoM->obtenerMaximo();

        //-------INSERTO EN TRANSACCION CRUCE-----------------------
        $transaccionCruceE = new \entidad\TransaccionCruce();
        $transaccionCruceE->setIdTransaccionConceptoAfectado($idTransaccionConcepto);
        $transaccionCruceE->setIdTransaccionFormaPago($idTransaccionFormaPago);
        $transaccionCruceE->setValor($dataFormasPago[$contador]->valor);
        $transaccionCruceE->setSecuencia($secuencia);
        $transaccionCruceE->setFecha($fecha);
        $transaccionCruceE->setObservacion("");
        $transaccionCruceE->setIdUsuarioCreacion($idUsuario);
        $transaccionCruceE->setIdUsuarioModificacion($idUsuario);
        $transaccionCruceE->setIdTransaccionConceptoOrigen("null");

        $transaccionCruceM = new \modelo\TransaccionCruce($transaccionCruceE);
        $transaccionCruceM->adicionar();
        $secuencia++;

        $contador++;
    }
    //------------------------------------------------------------------------

    $tipoDocumentoE = new \entidad\TipoDocumento();
    $tipoDocumentoE->setIdTipoDocumento($idTipoDocumento);
    $tipoDocumentoE->setNumero($numeroTipoDocumento);

    $tipoDocumentoM = new \modelo\TipoDocumento($tipoDocumentoE);
    $tipoDocumentoM->actualizarNumero();
} catch (Exception $e) {
    $arrRetorno['exito'] = 0;
    $arrRetorno['mensaje'] = $e->getMessage();
}
?>
