<?php
session_start();
set_time_limit(0);

require_once '../entidad/Transaccion.php';
require_once '../modelo/Transaccion.php';
require_once '../entidad/TransaccionCliente.php';
require_once '../modelo/TransaccionCliente.php';
require_once '../entidad/TransaccionProveedor.php';
require_once '../modelo/TransaccionProveedor.php';
require_once '../entidad/TransaccionCruce.php';
require_once '../modelo/TransaccionCruce.php';
require_once '../entidad/TipoDocumento.php';
require_once '../modelo/TipoDocumento.php';
require_once '../../Seguridad/entidad/Tercero.php';
require_once '../entidad/TransaccionConcepto.php';
require_once '../modelo/TransaccionConcepto.php';
require_once '../entidad/Concepto.php';
require_once '../entidad/TransaccionFormaPago.php';
require_once '../modelo/TransaccionFormaPago.php';

require_once '../entorno/Conexion.php';

$retorno = array('exito' => 1, 'mensaje' => 'La información se registró correctamente.', 'idTransaccion' => '');

try {
    
    $conexion = new \Conexion();
    $conexion->iniciarTransaccion();
    
    $codigoTipoNaturaleza = "CA"; //CAJA
    $idTercero = $_POST["idTercero"];
    $idCliente = $_POST["idClienteProveedor"];
    $idTransaccionEstado = "1"; //ACTIVO
    $nota = $_POST["nota"];
    $fecha = "NOW()";
    $fechaVencimiento = $_POST["fechaVencimiento"];
    $idCaja = $_POST["idCaja"];
    $prefijo = $_POST["prefijo"];
    $saldo = $_POST["valorTotalPagar"];
    $tipoClienteProveedor = $_POST["tipoClienteProveedor"];
    $permiteMultipleConceptos = $_POST["permiteMultipleConceptos"];
    $dataFormasPago = json_decode($_POST["dataFormasPago"]);
    $idUsuario = $_SESSION["idUsuario"];
    $arrConceptoPago = json_decode($_POST["arrConceptoPago"]);

    //--------------------CONSULTO EL TIPO DE DOCUMENTO PARA CAJA----------------------
    $tipoDocumentoM = new \modelo\TipoDocumento(new \entidad\TipoDocumento(), $conexion);
    $idTiposDocumentos = $tipoDocumentoM->obtenTipoDocumTienePermi();

    if ($idTiposDocumentos == "") {
        throw new Exception("No tiene permiso a ningún tipo de documento");
    }

    $data = $tipoDocumentoM->consultarTipoDocumento($idTiposDocumentos, $idTipoNaturaleza, $codigoTipoNaturaleza);
    $numeroRegistros = $tipoDocumentoM->conexion->obtenerNumeroRegistros();
    if ($numeroRegistros == 0) {
        throw new Exception("No tiene permiso al tipo de documento CAJA VENTAS");
    }
    if ($numeroRegistros > 1) {
        throw new Exception("Existen más de un tipo de documento con naturaleza: CAJA VENTAS");
    }
    $idTipoDocumento = $data[0]["idTipoDocumento"];
    $idOficina = $data[0]["idOficina"];
    $idNaturaleza = $data[0]["idNaturaleza"];
    //----------------------------------------------------------------------------------------

    $tipoDocumentoE = new \entidad\TipoDocumento();
    $tipoDocumentoE->setIdTipoDocumento($idTipoDocumento);

    $tipoDocumentoM = new \modelo\TipoDocumento($tipoDocumentoE, $conexion);
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
    $transaccionE->setValor($saldo);
    $transaccionE->setSaldo($saldo);
    $transaccionE->setIdUsuarioCreacion($idUsuario);
    $transaccionE->setIdUsuarioModificacion($idUsuario);

    $transaccionM = new \modelo\Transaccion($transaccionE, $conexion);
    $transaccionM->adicionar();
    $retorno['idTransaccion'] = $transaccionM->obtenerMaximo();
    
    if($tipoClienteProveedor == 'P'){
        //---------------------INSERTO EN TRANSACCION PROVEEDOR-------------------
        $transaccionProveedorE = new \entidad\TransaccionProveedor();
        $transaccionProveedorE->setIdTransaccion($retorno['idTransaccion']);
        $transaccionProveedorE->setIdProveedor($idCliente);
        $transaccionProveedorE->setIdUsuarioCreacion($idUsuario);
        $transaccionProveedorE->setIdUsuarioModicacion($idUsuario);
        
        $transaccionProveedorM = new \modelo\TransaccionProveedor($transaccionProveedorE, $conexion);
        $transaccionProveedorM->adicionar();
        //----------------------------------------------------------------------
    }else{
        //---------------------INSERTO EN TRANSACCION CLIENTE-------------------
        $transaccionClienteE = new \entidad\TransaccionCliente();
        $transaccionClienteE->setIdTransaccion($retorno['idTransaccion']);
        $transaccionClienteE->setIdCliente($idCliente);
        $transaccionClienteE->setIdUsuarioCreacion($idUsuario);
        $transaccionClienteE->setIdUsuarioModicacion($idUsuario);
        $transaccionClienteE->setIdCaja($idCaja);
        $transaccionClienteE->setPrefijo($prefijo);

        $transaccionClienteM = new \modelo\TransaccionCliente($transaccionClienteE, $conexion);
        $transaccionClienteM->adicionar();
        //----------------------------------------------------------------------
    }
    
    //-----------------ACTUALIZO EL NUMERO TIPO DOCUMENTO-------------------
    $tipoDocumentoE = new \entidad\TipoDocumento();
    $tipoDocumentoE->setIdTipoDocumento($idTipoDocumento);
    $tipoDocumentoE->setNumero($numeroTipoDocumento);

    $tipoDocumentoM = new \modelo\TipoDocumento($tipoDocumentoE, $conexion);
    $tipoDocumentoM->actualizarNumero();
    //------------------------------------------------------------------------
    //---------------INSERTO EN TRANSACCIÓN CONCEPTO--------------------------
    if($permiteMultipleConceptos != "TRUE" && $permiteMultipleConceptos != true){
        if (count($arrConceptoPago) > 1)
            throw new Exception("ERROR -> No puede haber más de un concepto.");
    }
    
    $contador = 0;
    while ($contador < count($arrConceptoPago)) {
        $conceptoE = new \entidad\Concepto();
        $conceptoE->setIdConcepto($arrConceptoPago[$contador]->idConcepto);

        $transaccionE = new \entidad\Transaccion();
        $transaccionE->setIdTransaccion($retorno['idTransaccion']);

        $valor = str_replace(".", "", $arrConceptoPago[$contador]->valorPago);
        
        $transaccionConceptoE = new \entidad\TransaccionConcepto();
        $transaccionConceptoE->setConcepto($conceptoE);
        $transaccionConceptoE->setTransaccion($transaccionE);
        $transaccionConceptoE->setValor($valor);
        $transaccionConceptoE->setSaldo($valor);
        $transaccionConceptoE->setNota($arrConceptoPago[$contador]->nota);
        $transaccionConceptoE->setIdUsuarioCrecion($idUsuario);
        $transaccionConceptoE->setIdUsuarioModificacion($idUsuario);

        $transaccionConceptoM = new \modelo\TransaccionConcepto($transaccionConceptoE, $conexion);
        $transaccionConceptoM->adicionar();
        $idTransaccionConcepto = $transaccionConceptoM->obtenerMaximo();

        $arrDataParametros = json_decode($arrConceptoPago[$contador]->parametros);
        if ($arrConceptoPago[$contador]->archivo != "" && $arrConceptoPago[$contador]->archivo != "" && $arrConceptoPago[$contador]->archivo != " ") {
            include $arrConceptoPago[$contador]->archivo;
        }

        $contador++;
    }
    //-----------------------------------------------------------------------
    //----------------------INSERTO LAS FORMAS DE PAGO------------------------
    $contador = 0;
    $secuencia = 1;
    while ($contador < count($dataFormasPago)) {

        //---------------INSERTO EN TRANSACCION FORMA DE PAGO-----------------------------
        $transaccionFormaPagoE = new \entidad\TransaccionFormaPago();
        $transaccionFormaPagoE->setIdFormaPago($dataFormasPago[$contador]->idFormaPago);
        $transaccionFormaPagoE->setIdTransaccion($retorno['idTransaccion']);
        $transaccionFormaPagoE->setValor($dataFormasPago[$contador]->valor);
        $transaccionFormaPagoE->setNota("");
        $transaccionFormaPagoE->setEstado("true");
        $transaccionFormaPagoE->setIdUsuarioCreacion($idUsuario);
        $transaccionFormaPagoE->setIdUsuarioModificacion($idUsuario);

        $transaccionFormaPagoM = new \modelo\TransaccionFormaPago($transaccionFormaPagoE, $conexion);
        $transaccionFormaPagoM->adicionar();
        $idTransaccionFormaPago = $transaccionFormaPagoM->obtenerMaximo();

        
        if($permiteMultipleConceptos !== "TRUE" && $permiteMultipleConceptos !== true){
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

            $transaccionCruceM = new \modelo\TransaccionCruce($transaccionCruceE, $conexion);
            $transaccionCruceM->adicionar();
            $secuencia++;
        }
        
        $contador++;
        
    }
    //------------------------------------------------------------------------
    
    $conexion->confirmarTransaccion();
    
} catch (Exception $e) {
    $conexion->cancelarTransaccion();
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
