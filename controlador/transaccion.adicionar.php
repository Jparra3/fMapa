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
require_once '../entidad/TransaccionProducto.php';
require_once '../modelo/TransaccionProducto.php';
require_once '../entidad/ProductoImpuesto.php';
require_once '../modelo/ProductoImpuesto.php';
require_once '../entidad/Producto.php';
require_once '../modelo/Producto.php';

require_once '../entorno/Conexion.php';

$retorno = array('exito' => 1, 'mensaje' => 'El inventario se guardó correctamente.', 'idTransaccion' => '');

try {
    
    $conexion = new \Conexion();
    $conexion->iniciarTransaccion();
    
    $codigoTipoNaturaleza = "CO"; //COMPRAS
    $idConcepto = $_POST["idConcepto"];
    $idTercero = $_POST["idTercero"];
    $idProveedor = $_POST["idProveedor"];
    $idTransaccionEstado = $_POST["idTransaccionEstado"];
    $nota = $_POST["nota"];
    $fecha = $_POST["fecha"];
    $fechaVencimiento = $_POST["fechaVencimiento"];
    $idUsuario = $_SESSION["idUsuario"];
    $documentoExterno = $_POST['documentoExterno'];
    $totalCompra = $_POST['totalCompra'];
    $dataFormasPago = json_decode($_POST["dataFormasPago"]);
    $dataProductos = json_decode($_POST["dataProductos"], true);
    $fecha = "NOW()";

    //--------------------CONSULTO EL TIPO DE DOCUMENTO PARA FACTURACION----------------------
    $tipoDocumentoM = new \modelo\TipoDocumento(new \entidad\TipoDocumento(), $conexion);
    $idTiposDocumentos = $tipoDocumentoM->obtenTipoDocumTienePermi();

    if ($idTiposDocumentos == "") {
        throw new Exception("No tiene permiso a ningún tipo de documento");
    }

    $data = $tipoDocumentoM->consultarTipoDocumento($idTiposDocumentos, $idTipoNaturaleza, $codigoTipoNaturaleza);
    $numeroRegistros = $tipoDocumentoM->conexion->obtenerNumeroRegistros();
    if ($numeroRegistros == 0) {
        throw new Exception("No tiene permiso al tipo de documento COMPRAS");
    }
    if ($numeroRegistros > 1) {
        throw new Exception("Existen más de un tipo de documento con naturaleza: COMPRAS");
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
    $transaccionE->setValor($totalCompra);
    $transaccionE->setSaldo($totalCompra);
    $transaccionE->setIdUsuarioCreacion($idUsuario);
    $transaccionE->setIdUsuarioModificacion($idUsuario);
    $transaccionE->setIdTransaccionAfecta("NULL");

    $transaccionM = new \modelo\Transaccion($transaccionE, $conexion);
    $transaccionM->adicionar();
    $retorno['idTransaccion'] = $transaccionM->obtenerMaximo();

    /* --------------------INSERTO EN TRANSACCION CONCEPTO---------------------- */
    $conceptoE = new \entidad\Concepto();
    $conceptoE->setIdConcepto($idConcepto);

    $transaccionE = new \entidad\Transaccion();
    $transaccionE->setIdTransaccion($retorno['idTransaccion']);

    $transaccionConceptoE = new \entidad\TransaccionConcepto();
    $transaccionConceptoE->setConcepto($conceptoE);
    $transaccionConceptoE->setTransaccion($transaccionE);
    $transaccionConceptoE->setValor($totalCompra);
    $transaccionConceptoE->setSaldo($totalCompra);
    $transaccionConceptoE->setNota(null);
    $transaccionConceptoE->setIdUsuarioCrecion($idUsuario);
    $transaccionConceptoE->setIdUsuarioModificacion($idUsuario);

    $transaccionConceptoM = new \modelo\TransaccionConcepto($transaccionConceptoE, $conexion);
    $transaccionConceptoM->adicionar();
    $idTransaccionConcepto = $transaccionConceptoM->obtenerMaximo();

    //---------------------INSERTO EN TRANSACCION PROVEEDOR-------------------
    if ($idTercero != 0) {
        $transaccionProveedorE = new \entidad\TransaccionProveedor();
        $transaccionProveedorE->setIdTransaccion($retorno['idTransaccion']);
        $transaccionProveedorE->setIdProveedor($idProveedor);
        $transaccionProveedorE->setIdUsuarioCreacion($idUsuario);
        $transaccionProveedorE->setIdUsuarioModicacion($idUsuario);
        if ($documentoExterno != "" && $documentoExterno != "null" && $documentoExterno != null) {
            $transaccionProveedorE->setDocumentoExterno($documentoExterno);
        }
        $transaccionProveedorM = new \modelo\TransaccionProveedor($transaccionProveedorE, $conexion);
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
        $secuencia++;

        $contador++;
    }
    //------------------------------------------------------------------------
    
    //-----------------------TRANSACCION PRODUCTO------------------------------
    //-----OBTENGO EL CODIGO DE LA NATURALEZA DEL CONCEPTO PARA DETERMINAR SI ES UNA ENTRADA O SALIDA DE INVENTARIO--------
    $conceptoE = new \entidad\Concepto();
    $conceptoE->setIdConcepto($idConcepto);
    $conceptoM = new \modelo\Concepto($conceptoE, $conexion);
    $arrInfoConcepto = $conceptoM->consultar($parametros);
    $codigoNaturaleza = $arrInfoConcepto[0]["codigoNaturaleza"];
    
    $transaccionE = new \entidad\Transaccion();
    $transaccionE->setIdTransaccion($retorno['idTransaccion']);

    /* -------OBTENGO LOS PARAMETROS DEL CONCEPTO---------- */
    $transaccionConceptoE = new \entidad\TransaccionConcepto();
    $transaccionConceptoE->setTransaccion($transaccionE);
    $transaccionConceptoM = new \modelo\TransaccionConcepto($transaccionConceptoE, $conexion);
    $arrInfoConcepto = $transaccionConceptoM->consultar();
    $modificaValorEntrada = $arrInfoConcepto[0]["modificaValorEntrada"];
    $modificaValorSalida = $arrInfoConcepto[0]["modificaValorSalida"];
    /* -------------------------------------------------- */

    $contador = 0;
    $secuencia = 1;
    while ($contador < count($dataProductos)) {

        $idTransaccionProducto = $dataProductos[$contador]["idTransaccionProducto"];

        $dataProductos[$contador]["valorUnitarioEntrada"] = str_replace('.', '', $dataProductos[$contador]["valorUnitarioEntrada"]);
        $dataProductos[$contador]["valorUnitarioEntrada"] = str_replace(',', '.', $dataProductos[$contador]["valorUnitarioEntrada"]);

        $dataProductos[$contador]["valorUnitarioSalida"] = str_replace('.', '', $dataProductos[$contador]["valorUnitarioSalida"]);
        $dataProductos[$contador]["valorUnitarioSalida"] = str_replace(',', '.', $dataProductos[$contador]["valorUnitarioSalida"]);

        //-----------------------OBTENGO LOS VALORES DE ENTRADA Y SALIDA CON IMPUESTO-------------------------------
        $productoImpuestoE = new \entidad\ProductoImpuesto();
        $productoImpuestoE->setIdProducto($dataProductos[$contador]["idProducto"]);

        $productoImpuestoM = new \modelo\ProductoImpuesto($productoImpuestoE, $conexion);
        $arrImpuestos = $productoImpuestoM->obtenerTotalImpuestos($dataProductos[$contador]["valorUnitarioEntrada"], $dataProductos[$contador]["valorUnitarioSalida"]);
        //------------------------------------------------------------------------------------------------------------
        //---------------------------ACTUALIZO COSTOS EN LA TABLA PRODUCTO-------------------------------------
        $productoE = new \entidad\Producto();
        $productoE->setIdProducto($dataProductos[$contador]["idProducto"]);
        $productoE->setValorEntrada($arrImpuestos["valorEntrada"]);
        $productoE->setValorSalida($arrImpuestos["valorSalida"]);
        $productoE->setValorEntraConImpue($arrImpuestos["valorEntraConImpue"]);
        $productoE->setValorSalidConImpue($arrImpuestos["valorSalidConImpue"]);

        $productoM = new \modelo\Producto($productoE, $conexion);
        if ($modificaValorEntrada == true)
            $productoM->modificarValorEntrada();
        if ($modificaValorSalida == true)
            $productoM->modificarValorSalida();
        //-----------------------------------------------------------------------------------------------------

        if ($dataProductos[$contador]["serial"] != null) {
            for ($i = 0; $i < count($dataProductos[$contador]["serial"]); $i++) {
                $transaccionProductoE = new \entidad\TransaccionProducto();
                $transaccionProductoE->setTransaccion($transaccionE);
                $transaccionProductoE->setIdProducto($dataProductos[$contador]["idProducto"]);
                $transaccionProductoE->setCantidad(1);
                $transaccionProductoE->setValorUnitarioEntrada($arrImpuestos["valorEntrada"]);
                $transaccionProductoE->setValorUnitarioSalida($arrImpuestos["valorSalida"]);
                $transaccionProductoE->setValorUnitaEntraConImpue($arrImpuestos["valorEntraConImpue"]);
                $transaccionProductoE->setValorUnitaSalidConImpue($arrImpuestos["valorSalidConImpue"]);
                $transaccionProductoE->setIdBodega($dataProductos[$contador]["idBodega"]);
                $transaccionProductoE->setSecuencia($secuencia);
                $transaccionProductoE->setNota($dataProductos[$contador]["nota"]);
                $transaccionProductoE->setSerial($dataProductos[$contador]["serial"][$i]);
                $transaccionProductoE->setSerialInterno($dataProductos[$contador]["serialInterno"][$i]);
                $transaccionProductoE->setIdUsuarioCreacion($idUsuario);
                $transaccionProductoE->setIdUsuarioModificacion($idUsuario);
                $transaccionProductoE->setIdUnidadMedidaPresentacion(null);

                $transaccionProductoM = new \modelo\TransaccionProducto($transaccionProductoE, $conexion);
                $transaccionProductoM->adicionar();

                $secuencia++;
            }
        } else {
            
            $dataProductos[$contador]["cantidad"] = str_replace(',', '.', $dataProductos[$contador]["cantidad"]);
            
            $transaccionProductoM = new \modelo\TransaccionProducto(new \entidad\TransaccionProducto(), $conexion);
            $secuencia = $transaccionProductoM->obtenerSecuencia($retorno['idTransaccion']);
            
            //SI EL CONCEPTO ES UNA SALIDA DEBO RESTAR EN INVENTARIO 
            if ($codigoNaturaleza == "CO-IN-SA") {
                $idProducto = $dataProductos[$contador]["idProducto"];
                $saldoCantidadVenta = $dataProductos[$contador]["cantidad"];

                //OBTENGO LOS VALORES DE ENTRADA QUE HAYAN SIDO INVENTARIADAS
                $transaccionProductoE = new \entidad\TransaccionProducto();
                $transaccionProductoE->setIdProducto($idProducto);
                $transaccionProductoM = new \modelo\TransaccionProducto($transaccionProductoE, $conexion);
                $arrRetorno = $transaccionProductoM->obtenerValorEntradaProductoPEPS();
                $contador2 = 0;

                //VALIDO QUE EXISTAN CANTIDADES SUFICIENTES PARA VENDER
                if ($saldoCantidadVenta > $arrRetorno["cantidadTotal"]) {
                    throw new Exception("<b>NO SE PUDO REALIZAR LA TRANSACCIÓN</b> <br><br> La cantidad inventariada es insuficiente para el producto <b>" . $dataProductos[$contador]["producto"] . "</b> <br><br> cantidad faltante -> " . ( $saldoCantidadVenta - $arrRetorno["cantidadTotal"]));
                }
                
                while ($contador2 < count($arrRetorno)) {
                    $idTransaccionProductoAfecta = $arrRetorno[$contador2]["idTransaccionProducto"];
                    $saldoCantidadProducto = $arrRetorno[$contador2]["saldoCantidadProducto"];
                    
                    if ($saldoCantidadVenta >= $saldoCantidadProducto) {//SI LA CANTIDAD VENDIDA SUPERA LA CANTIDAD INVENTARIADA
                        $cantidad = $saldoCantidadProducto;
                        $saldoCantidadVenta = $saldoCantidadVenta - $cantidad;
                        $saldoCantidadProducto = 0;
                    } else {//SI LA CANTIDAD VENDIDA ES MENOR A LA CANTIDAD INVENTARIADA
                        $cantidad = $saldoCantidadVenta;
                        $saldoCantidadVenta = 0;
                        $saldoCantidadProducto = $saldoCantidadProducto - $cantidad;
                    }

                    $transaccionProductoE = new \entidad\TransaccionProducto();
                    $transaccionProductoE->setTransaccion($transaccionE);
                    $transaccionProductoE->setIdProducto($dataProductos[$contador]["idProducto"]);
                    $transaccionProductoE->setCantidad($cantidad);
                    $transaccionProductoE->setValorUnitarioEntrada($arrImpuestos["valorEntrada"]);
                    $transaccionProductoE->setValorUnitarioSalida($arrImpuestos["valorSalida"]);
                    $transaccionProductoE->setValorUnitaEntraConImpue($arrImpuestos["valorEntraConImpue"]);
                    $transaccionProductoE->setValorUnitaSalidConImpue($arrImpuestos["valorSalidConImpue"]);
                    $transaccionProductoE->setIdBodega($dataProductos[$contador]["idBodega"]);
                    $transaccionProductoE->setSecuencia($secuencia);
                    $transaccionProductoE->setNota($dataProductos[$contador]["nota"]);
                    $transaccionProductoE->setSerial($dataProductos[$contador]["serial"]);
                    $transaccionProductoE->setSerialInterno($dataProductos[$contador]["serialInterno"]);
                    $transaccionProductoE->setIdUsuarioCreacion($idUsuario);
                    $transaccionProductoE->setIdUsuarioModificacion($idUsuario);
                    $transaccionProductoE->setSaldoCantidadProducto($cantidad);
                    $transaccionProductoE->setIdTransaccionProductoAfecta($idTransaccionProductoAfecta);
                    $transaccionProductoE->setIdUnidadMedidaPresentacion(null);

                    $transaccionProductoM = new \modelo\TransaccionProducto($transaccionProductoE, $conexion);
                    $transaccionProductoM->adicionar();

                    $contador2++;

                    if ($saldoCantidadVenta > 0) {
                        $transaccionProductoM = new \modelo\TransaccionProducto(new \entidad\TransaccionProducto(), $conexion);
                        $secuencia = $transaccionProductoM->obtenerSecuencia($retorno['idTransaccion']);
                        continue;
                    } else {
                        break;
                    }
                }
            //SI EL CONCEPTO ES UNA ENTRADA DEBO SUMAR EN INVENTARIO 
            }else{            
                $transaccionProductoE = new \entidad\TransaccionProducto();
                $transaccionProductoE->setTransaccion($transaccionE);
                $transaccionProductoE->setIdProducto($dataProductos[$contador]["idProducto"]);
                $transaccionProductoE->setCantidad($dataProductos[$contador]["cantidad"]);
                $transaccionProductoE->setValorUnitarioEntrada($arrImpuestos["valorEntrada"]);
                $transaccionProductoE->setValorUnitarioSalida($arrImpuestos["valorSalida"]);
                $transaccionProductoE->setValorUnitaEntraConImpue($arrImpuestos["valorEntraConImpue"]);
                $transaccionProductoE->setValorUnitaSalidConImpue($arrImpuestos["valorSalidConImpue"]);
                $transaccionProductoE->setIdBodega($dataProductos[$contador]["idBodega"]);
                $transaccionProductoE->setSecuencia($secuencia);
                $transaccionProductoE->setNota($dataProductos[$contador]["nota"]);
                $transaccionProductoE->setSerial($dataProductos[$contador]["serial"]);
                $transaccionProductoE->setSerialInterno($dataProductos[$contador]["serialInterno"]);
                $transaccionProductoE->setIdUsuarioCreacion($idUsuario);
                $transaccionProductoE->setIdUsuarioModificacion($idUsuario);
                $transaccionProductoE->setIdUnidadMedidaPresentacion(null);

                $transaccionProductoM = new \modelo\TransaccionProducto($transaccionProductoE, $conexion);
                $transaccionProductoM->adicionar();
            }
        }
        $contador++;
        $secuencia++;
    }
    
    //---------ACTUALIZO EL NUMERO DEL TIPO DE DOCUMENTO-----------
    $tipoDocumentoE = new \entidad\TipoDocumento();
    $tipoDocumentoE->setIdTipoDocumento($idTipoDocumento);
    $tipoDocumentoE->setNumero($numeroTipoDocumento);

    $tipoDocumentoM = new \modelo\TipoDocumento($tipoDocumentoE, $conexion);
    $tipoDocumentoM->actualizarNumero();
    
    $conexion->confirmarTransaccion();
    
} catch (Exception $e) {
    $conexion->cancelarTransaccion();
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>