<?php

require_once '../entidad/Concepto.php';
require_once '../modelo/Concepto.php';
require_once '../entidad/FormaPago.php';
require_once '../modelo/FormaPago.php';
require_once '../entidad/TransaccionCruce.php';
require_once '../modelo/TransaccionCruce.php';
require_once '../entidad/TransaccionFormaPago.php';
require_once '../modelo/TransaccionFormaPago.php';

$idTransaccionAfectado = $arrDataParametros[0]->idTransaccion;
$valor = str_replace(".", "", $arrDataParametros[0]->valorConcepto);
$idConcepto = $arrConceptoPago[$contador]->idConcepto;

$conceptoE = new \entidad\Concepto();
$conceptoE->setIdConcepto($idConcepto);

$conceptoM = new \modelo\Concepto($conceptoE);
$arrDataConcepto = $conceptoM->consultar($parametros);
$idFormaPago = $arrDataConcepto[0]["idFormaPago"];

if ($idFormaPago != null && $idFormaPago != "null" && $idFormaPago != "null") {
    $formaPagoE = new \entidad\FormaPago();
    $formaPagoE->setIdFormaPago($idFormaPago);

    $formaPagoM = new \modelo\FormaPago($formaPagoE);
    $arrDataFormaPago = $formaPagoM->obtenerInfoFormaPago();

    if ($arrDataFormaPago["cruza"] == true) {
        if ($arrDataFormaPago["cruzaOtro"] == true) {
            //------------------INSERTO EN TRANSACCION FORMA DE PAGO-----------------------------
            $transaccionFormaPagoE = new \entidad\TransaccionFormaPago();
            $transaccionFormaPagoE->setIdFormaPago($idFormaPago);
            $transaccionFormaPagoE->setIdTransaccion($idTransaccionAfectado);
            $transaccionFormaPagoE->setNota("");
            $transaccionFormaPagoE->setValor($valor);
            $transaccionFormaPagoE->setEstado("true");
            $transaccionFormaPagoE->setIdUsuarioCreacion($idUsuario);
            $transaccionFormaPagoE->setIdUsuarioModificacion($idUsuario);

            $transaccionFormaPagoM = new \modelo\TransaccionFormaPago($transaccionFormaPagoE);
            $transaccionFormaPagoM->adicionar();
            $idTransaccionFormaPago = $transaccionFormaPagoM->obtenerMaximo();

            //--------------OBTENGO LA SECUENCIA DE ESA TRANSACCION-------------------
            $transaccionE = new \entidad\Transaccion();
            $transaccionE->setIdTransaccion($idTransaccionAfectado);
            $transaccionM = new \modelo\Transaccion($transaccionE);
            $arrInfoConcepto = $transaccionM->obtenerInfoConcepto();
            
            $transaccionCruceE = new \entidad\TransaccionCruce();
            $transaccionCruceE->setIdTransaccionConceptoAfectado($arrInfoConcepto["idTransaccionConcepto"]);
            
            $transaccionCruceM =  new \modelo\TransaccionCruce($transaccionCruceE);
            $secuencia = $transaccionCruceM->obtenerSecuencia();
            
            //----------------INSERTO EN TRANSACCION CRUCE-------------------------
            $transaccionCruceE = new \entidad\TransaccionCruce();
            $transaccionCruceE->setIdTransaccionConceptoAfectado($arrInfoConcepto["idTransaccionConcepto"]);
            $transaccionCruceE->setIdTransaccionFormaPago($idTransaccionFormaPago);
            $transaccionCruceE->setValor($valor);
            $transaccionCruceE->setSecuencia($secuencia);
            $transaccionCruceE->setFecha("NOW()");
            $transaccionCruceE->setObservacion("");
            $transaccionCruceE->setIdUsuarioCreacion($idUsuario);
            $transaccionCruceE->setIdUsuarioModificacion($idUsuario);
            $transaccionCruceE->setIdTransaccionConceptoOrigen($idTransaccionConcepto);//Este valor viene del archivo "caja.adicionar.php"

            $transaccionCruceM = new \modelo\TransaccionCruce($transaccionCruceE);
            $transaccionCruceM->adicionar();
        }
    }
}