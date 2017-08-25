<?php

session_start();
set_time_limit(0);

require_once '../entidad/Transaccion.php';
require_once '../modelo/Transaccion.php';
require_once '../entidad/TransaccionCliente.php';
require_once '../modelo/TransaccionCliente.php';
require_once '../entidad/TransaccionCruce.php';
require_once '../modelo/TransaccionCruce.php';
require_once '../entidad/TipoDocumento.php';
require_once '../modelo/TipoDocumento.php';
require_once '../entidad/TransaccionFormaPago.php';
require_once '../modelo/TransaccionFormaPago.php';
require_once '../entidad/Concepto.php';
require_once '../modelo/Concepto.php';
require_once '../entidad/TransaccionConcepto.php';
require_once '../modelo/TransaccionConcepto.php';
require_once '../../Seguridad/entidad/Tercero.php';

require_once '../entidad/TransaccionProducto.php';
require_once '../modelo/TransaccionProducto.php';
require_once '../entidad/TransaccionProductoImpuesto.php';
require_once '../modelo/TransaccionProductoImpuesto.php';
require_once '../entidad/TransaccionImpuesto.php';
require_once '../modelo/TransaccionImpuesto.php';
require_once '../../Seguridad/entidad/ParametroAplicacion.php';
require_once '../../Seguridad/modelo/ParametroAplicacion.php';

require_once '../entorno/Conexion.php';

$retorno = array('exito' => 1, 'mensaje' => 'La facturacion se guardó correctamente.', 'idTransaccion' => '');

try {
    
    $conexion = new Conexion();
    $conexion->iniciarTransaccion();
    
    //-------TRANSACCION---------
    $codigoTipoNaturaleza = "VE"; //VENTAS
    $idTransaccionEstado = "1"; //ACTIVO
    $idTercero = $_POST["idTercero"];
    $idCliente = $_POST["idCliente"];
    $nota = $_POST["nota"];
    $fecha = "NOW()";
    $fechaVencimiento = $_POST["fechaVencimiento"];
    $idFormatoImpresion = $_POST["idFormatoImpresion"];
    $idCaja = $_POST["idCaja"];
    $prefijo = $_POST["prefijo"];
    $saldo = $_POST["valorTotalPagar"];
    $dataFormasPago = json_decode($_POST["dataFormasPago"]);
    $idUsuario = $_SESSION["idUsuario"];

    //-------TRANSACCION PRODUCTO---------
    $dataProductos = json_decode($_POST["dataProductos"], true);
    $idBodega = $_POST["idBodega"];
    
    
    //--------------------CONSULTO EL TIPO DE DOCUMENTO PARA FACTURACION----------------------
    $tipoDocumentoM = new \modelo\TipoDocumento(new \entidad\TipoDocumento(), $conexion);
    $idTiposDocumentos = $tipoDocumentoM->obtenTipoDocumTienePermi();

    if ($idTiposDocumentos == "") {
        throw new Exception("No tiene permiso a ningún tipo de documento");
    }

    $data = $tipoDocumentoM->consultarTipoDocumento($idTiposDocumentos, $idTipoNaturaleza, $codigoTipoNaturaleza);
    $numeroRegistros = $tipoDocumentoM->conexion->obtenerNumeroRegistros();
    if ($numeroRegistros == 0) {
        throw new Exception("No tiene permiso al tipo de documento VENTAS - INVENTARIO");
    }
    if ($numeroRegistros > 1) {
        throw new Exception("Existen más de un tipo de documento con naturaleza: VENTAS - INVENTARIO");
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
    $transaccionE->setIdFormatoImpresion($idFormatoImpresion);    
    $transaccionE->setIdTransaccionAfecta(null);
    $transaccionE->setIdUsuarioCreacion($idUsuario);
    $transaccionE->setIdUsuarioModificacion($idUsuario);

    $transaccionM = new \modelo\Transaccion($transaccionE, $conexion);
    $transaccionM->adicionar();
    $retorno['idTransaccion'] = $transaccionM->obtenerMaximo();

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
    //------------------------------------------------------------------------
    //---------------INSERTO EN TRANSACCION CONCEPTO--------------------------
    $conceptoE = new \entidad\Concepto();
    $conceptoE->setIdTipoDocumento($idTipoDocumento);
    $conceptoM = new \modelo\Concepto($conceptoE, $conexion);
    $arrConcepto = $conceptoM->obtenerIdConcepto();//OBTENGO EL ID CONCEPTO A PARTIR DEL ID TIPO DE DOCUMENTO DE FACTURACIÓN
    if ($arrConcepto["exito"] == 0) {
        throw new Exception("ERROR -> No puede haber más de un concepto con un mismo tipo de documento -> id: " . $idTipoDocumento);
    }
    $idConcepto = $arrConcepto["idConcepto"];

    $conceptoE = new \entidad\Concepto();
    $conceptoE->setIdConcepto($idConcepto);

    $transaccionE = new \entidad\Transaccion();
    $transaccionE->setIdTransaccion($retorno['idTransaccion']);

    $transaccionConceptoE = new \entidad\TransaccionConcepto();
    $transaccionConceptoE->setConcepto($conceptoE);
    $transaccionConceptoE->setTransaccion($transaccionE);
    $transaccionConceptoE->setValor($saldo);
    $transaccionConceptoE->setSaldo($saldo);
    $transaccionConceptoE->setNota(null);
    $transaccionConceptoE->setIdUsuarioCrecion($idUsuario);
    $transaccionConceptoE->setIdUsuarioModificacion($idUsuario);

    $transaccionConceptoM = new \modelo\TransaccionConcepto($transaccionConceptoE, $conexion);
    $transaccionConceptoM->adicionar();
    $idTransaccionConcepto = $transaccionConceptoM->obtenerMaximo();
    //------------------------------------------------------------------------
    //-----------------ACTUALIZO EL NUMERO TIPO DOCUMENTO-------------------
    $tipoDocumentoE = new \entidad\TipoDocumento();
    $tipoDocumentoE->setIdTipoDocumento($idTipoDocumento);
    $tipoDocumentoE->setNumero($numeroTipoDocumento);

    $tipoDocumentoM = new \modelo\TipoDocumento($tipoDocumentoE, $conexion);
    $tipoDocumentoM->actualizarNumero();
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

        $contador++;
        $secuencia++;
    }
    //------------------------------------------------------------------------
    
    
//--------------------------------------TRANSACCION PRODUCTO---------------------------------------------
    
    //---------Se obtiene el tipo de actualizacion de costo-------------
    $parametroAplicacionE = new \entidad\ParametroAplicacion();
    $parametroAplicacionE->setCodigo('TIPO-COSTO');
    $parametroAplicacionM = new \modelo\ParametroAplicacion($parametroAplicacionE);
    $arrParametro = $parametroAplicacionM->consultar();
    if($parametroAplicacionM->conexion->obtenerNumeroRegistros() == 0){
        throw new Exception("No se encontro ningun parámetro para el código TIPO-COSTO");
    }
    $tipoActualizacionCosto = $arrParametro[0]["valor"];
    //------------------------------------------------------------------
    $idTransaccion = $retorno['idTransaccion'];

    $contador = 0;
    $data = $dataProductos;
    while ($contador < count($data)) {
        
        if(trim($data[$contador]["nota"]) == "" || trim($data[$contador]["nota"]) == null || trim($data[$contador]["nota"]) == "undefined"){
            $data[$contador]["nota"] = "null";
        }else{
            $data[$contador]["nota"] = "'" . $data[$contador]["nota"] . "'";
        }
        
        if($data[$contador]["idUnidadMedidaPresentacion"] == "" || $data[$contador]["idUnidadMedidaPresentacion"] == null || $data[$contador]["idUnidadMedidaPresentacion"] == "undefined"){
            $data[$contador]["idUnidadMedidaPresentacion"] = "null";
        }
        
        if ($data[$contador]["serial"] != null) {
            for ($i = 0; $i < count($data[$contador]["serial"]); $i++) {
                
                $sentenciaSql = "SELECT * FROM facturacion_inventario.guardar_transaccion_producto(
                                                        CAST($idTransaccion AS INTEGER)
                                                        , CAST(".$data[$contador]["idProducto"]." AS SMALLINT)
                                                        , CAST(".$data[$contador]["cantidad"]." AS NUMERIC)
                                                        , CAST(".$data[$contador]["valorUnitarioSalida"]." AS NUMERIC)
                                                        , CAST(".$data[$contador]["valorSalidConImpue"]." AS NUMERIC)
                                                        , CAST(".trim($data[$contador]["nota"])." AS CHARACTER VARYING)
                                                        , CAST(".$data[$contador]["serial"][$i]." AS CHARACTER VARYING)
                                                        , CAST(".$data[$contador]["idUnidadMedidaPresentacion"]." AS SMALLINT)
                                                        , CAST($idBodega AS SMALLINT)
                                                        , CAST($idUsuario AS SMALLINT)
                                                    )";
                
                $conexion->ejecutar($sentenciaSql);
                
            }
        } else {
            
            if ($tipoActualizacionCosto == "PEPS") {
                
                $sentenciaSql = "SELECT * FROM facturacion_inventario.guardar_transaccion_producto(
                                                CAST($idTransaccion AS INTEGER)
                                                , CAST(".$data[$contador]["idProducto"]." AS SMALLINT)
                                                , CAST(".$data[$contador]["cantidad"]." AS NUMERIC)
                                                , CAST(".$data[$contador]["valorUnitarioSalida"]." AS NUMERIC)
                                                , CAST(".$data[$contador]["valorSalidConImpue"]." AS NUMERIC)
                                                , CAST(".trim($data[$contador]["nota"])." AS CHARACTER VARYING)
                                                , CAST(NULL AS CHARACTER VARYING) --SERIAL
                                                , CAST(".$data[$contador]["idUnidadMedidaPresentacion"]." AS SMALLINT)
                                                , CAST($idBodega AS SMALLINT)
                                                , CAST($idUsuario AS SMALLINT)
                                            )";
                
                $conexion->ejecutar($sentenciaSql);
                
            }else{
                throw new Exception("ERROR -> El tipo de actualización de costo parametrizado actualmente es <b>".$tipoActualizacionCosto."</b> y es diferente a PEPS");
            }
        }
        $contador++;
    }
    
    $conexion->confirmarTransaccion();
    
} catch (Exception $e) {
    $conexion->cancelarTransaccion();
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
