<?php
session_start();
require_once '../entidad/Transaccion.php';
require_once '../modelo/Transaccion.php';
require_once '../entidad/TipoDocumento.php';
require_once '../modelo/TipoDocumento.php';
require_once '../../Seguridad/entidad/Tercero.php';
require_once '../entidad/Concepto.php';
require_once '../modelo/Concepto.php';
require_once '../entidad/TransaccionConcepto.php';
require_once '../modelo/TransaccionConcepto.php';
require_once '../entidad/TransaccionProducto.php';
require_once '../modelo/TransaccionProducto.php';
require_once '../entidad/ProductoImpuesto.php';
require_once '../modelo/ProductoImpuesto.php';
require_once '../entidad/Producto.php';
require_once '../modelo/Producto.php';
require_once '../entidad/ProductoComposicion.php';
require_once '../modelo/ProductoComposicion.php';

require_once '../entorno/Conexion.php';

$retorno = array('exito' => 1, 'mensaje' => 'La información del producto se guardó correctamente.');

try {

    $conexion = new \Conexion();
    $conexion->iniciarTransaccion();

    $codigoTipoDocumentoEntrada = "FB-EN"; //FABRICACIÓN - ENTRADA
    $codigoTipoDocumentoSalida = "FB-SA"; //FABRICACIÓN - SALIDA
    $idProducto = $_POST["idProducto"];
    $cantidad = str_replace(',', '.', $_POST["cantidad"]);
    $idTercero = 0; //SIN TERCERO
    $idTransaccionEstado = 1; //ACTIVO
    $fecha = "NOW()";
    $fechaVencimiento = "NOW()";
    $idUsuario = $_SESSION["idUsuario"];

//-------------------------------------REALIZO LA ENTRADA DEL PRODUCTO COMPUESTO------------------------------------------
    //--------------------CONSULTO EL TIPO DE DOCUMENTO PARA FABRICACIÓN ENTRADA----------------------
    $transaccionM = new \modelo\Transaccion(new \entidad\Transaccion(), $conexion);
    $arrInfoTipoDocumento = $transaccionM->obtenerInfoTipoDocumento($codigoTipoDocumentoEntrada);
    $idTipoDocumento = $arrInfoTipoDocumento[0]["idTipoDocumento"];
    $idOficina = $arrInfoTipoDocumento[0]["idOficina"];
    $idNaturaleza = $arrInfoTipoDocumento[0]["idNaturaleza"];
    //--------------CONSULTO EL CONCEPTO A PARTIR DEL TIPO DE DOCUMENTO-----------------------
    $conceptoE = new \entidad\Concepto();
    $conceptoE->setIdTipoDocumento($idTipoDocumento);
    $conceptoM = new \modelo\Concepto($conceptoE, $conexion);
    $arrConcepto = $conceptoM->obtenerIdConcepto(); //OBTENGO EL ID CONCEPTO A PARTIR DEL ID TIPO DE DOCUMENTO DE FABRICACIÓN
    if ($arrConcepto["exito"] == 0) {
        throw new \Exception("ERROR -> No puede haber más de un concepto con un mismo tipo de documento -> id: " . $idTipoDocumento);
    }
    if ($arrConcepto["idConcepto"] == null || $arrConcepto["idConcepto"] == "null" || $arrConcepto["idConcepto"] == "") {
        throw new \Exception("ERROR -> No se encontró un concepto con el tipo de documento de id: " . $idTipoDocumento);
    }
    $idConcepto = $arrConcepto["idConcepto"];
    //----------------------------------------------------------------------------------------
    //----------------------INSERTO EN TRANSACCION---------------------------------------------
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
    $transaccionE->setNota(null);
    $transaccionE->setFecha($fecha);
    $transaccionE->setFechaVencimiento($fechaVencimiento);
    $transaccionE->setValor(0);
    $transaccionE->setSaldo(0);
    $transaccionE->setIdUsuarioCreacion($idUsuario);
    $transaccionE->setIdUsuarioModificacion($idUsuario);
    $transaccionE->setIdTransaccionAfecta("NULL");

    $transaccionM = new \modelo\Transaccion($transaccionE, $conexion);
    $transaccionM->adicionar();
    $idTransaccionEntrada = $transaccionM->obtenerMaximo();

    //--------------------INSERTO EN TRANSACCION CONCEPTO----------------------
    $conceptoE = new \entidad\Concepto();
    $conceptoE->setIdConcepto($idConcepto);

    $transaccionE = new \entidad\Transaccion();
    $transaccionE->setIdTransaccion($idTransaccionEntrada);

    $transaccionConceptoE = new \entidad\TransaccionConcepto();
    $transaccionConceptoE->setConcepto($conceptoE);
    $transaccionConceptoE->setTransaccion($transaccionE);
    $transaccionConceptoE->setValor(0);
    $transaccionConceptoE->setSaldo(0);
    $transaccionConceptoE->setNota(null);
    $transaccionConceptoE->setIdUsuarioCrecion($idUsuario);
    $transaccionConceptoE->setIdUsuarioModificacion($idUsuario);

    $transaccionConceptoM = new \modelo\TransaccionConcepto($transaccionConceptoE, $conexion);
    $transaccionConceptoM->adicionar();


    //--------------------------------------TRANSACCION PRODUCTO--------------------------------
    //---------Se obtiene el tipo de actualizacion de costo-------------
    $parametroAplicacionE = new \entidad\ParametroAplicacion();
    $parametroAplicacionE->setCodigo('TIPO-COSTO');
    $parametroAplicacionM = new \modelo\ParametroAplicacion($parametroAplicacionE);
    $arrParametro = $parametroAplicacionM->consultar();
    if ($parametroAplicacionM->conexion->obtenerNumeroRegistros() == 0) {
        throw new Exception("No se encontro ningun parámetro para el código TIPO-COSTO");
    }
    $tipoActualizacionCosto = $arrParametro[0]["valor"];
    //------------------------------------------------------------------
    
    $productoE = new \entidad\Producto();
    $productoE->setIdProducto($idProducto);
    $productoM = new \modelo\Producto($productoE, $conexion);
    $dataProducto = $productoM->consultar();
    $valorEntrada = $dataProducto[0]["valorEntrada"];
    $valorEntradaConImpue = $dataProducto[0]["valorEntraConImpue"];
    $valorSalida = $dataProducto[0]["valorSalida"];
    $valorSalidConImpue = $dataProducto[0]["valorSalidConImpue"];
    
    $idBodega = 1;
    
    $transaccionProductoE = new \entidad\TransaccionProducto();
    $transaccionProductoE->setTransaccion($transaccionE);
    $transaccionProductoE->setIdProducto($idProducto);
    $transaccionProductoE->setCantidad($cantidad);
    $transaccionProductoE->setValorUnitarioEntrada($valorEntrada);
    $transaccionProductoE->setValorUnitarioSalida($valorSalida);
    $transaccionProductoE->setValorUnitaEntraConImpue($valorEntradaConImpue);
    $transaccionProductoE->setValorUnitaSalidConImpue($valorSalidConImpue);
    $transaccionProductoE->setIdBodega($idBodega);
    $transaccionProductoE->setSecuencia(1);
    $transaccionProductoE->setNota(null);
    $transaccionProductoE->setSerial(null);
    $transaccionProductoE->setIdUsuarioCreacion($idUsuario);
    $transaccionProductoE->setIdUsuarioModificacion($idUsuario);
    $transaccionProductoE->setIdUnidadMedidaPresentacion(null);

    $transaccionProductoM = new \modelo\TransaccionProducto($transaccionProductoE, $conexion);
    $transaccionProductoM->adicionar();
    
//----------------------------------------------------------------------------------------------------------------------------
    
    
//-------------------------------------REALIZO LA SALIDA DE LOS COMPONENTES DE INVENTARIO------------------------------------------
    //--------------------CONSULTO EL TIPO DE DOCUMENTO PARA FABRICACIÓN SALIDA----------------------
    $transaccionM = new \modelo\Transaccion(new \entidad\Transaccion(), $conexion);
    $arrInfoTipoDocumento = $transaccionM->obtenerInfoTipoDocumento($codigoTipoDocumentoSalida);
    $idTipoDocumento = $arrInfoTipoDocumento[0]["idTipoDocumento"];
    $idOficina = $arrInfoTipoDocumento[0]["idOficina"];
    $idNaturaleza = $arrInfoTipoDocumento[0]["idNaturaleza"];
    //--------------CONSULTO EL CONCEPTO A PARTIR DEL TIPO DE DOCUMENTO-----------------------
    $conceptoE = new \entidad\Concepto();
    $conceptoE->setIdTipoDocumento($idTipoDocumento);
    $conceptoM = new \modelo\Concepto($conceptoE, $conexion);
    $arrConcepto = $conceptoM->obtenerIdConcepto(); //OBTENGO EL ID CONCEPTO A PARTIR DEL ID TIPO DE DOCUMENTO DE FABRICACIÓN
    if ($arrConcepto["exito"] == 0) {
        throw new \Exception("ERROR -> No puede haber más de un concepto con un mismo tipo de documento -> id: " . $idTipoDocumento);
    }
    if ($arrConcepto["idConcepto"] == null || $arrConcepto["idConcepto"] == "null" || $arrConcepto["idConcepto"] == "") {
        throw new \Exception("ERROR -> No se encontró un concepto con el tipo de documento de id: " . $idTipoDocumento);
    }
    $idConcepto = $arrConcepto["idConcepto"];
    //----------------------------------------------------------------------------------------
    //----------------------INSERTO EN TRANSACCION---------------------------------------------
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
    $transaccionE->setNota(null);
    $transaccionE->setFecha($fecha);
    $transaccionE->setFechaVencimiento($fechaVencimiento);
    $transaccionE->setValor(0);
    $transaccionE->setSaldo(0);
    $transaccionE->setIdUsuarioCreacion($idUsuario);
    $transaccionE->setIdUsuarioModificacion($idUsuario);
    $transaccionE->setIdTransaccionAfecta("NULL");
    $transaccionE->setIdTransaccionPadre($idTransaccionEntrada);

    $transaccionM = new \modelo\Transaccion($transaccionE, $conexion);
    $transaccionM->adicionar();
    $idTransaccionSalida = $transaccionM->obtenerMaximo();

    //--------------------INSERTO EN TRANSACCION CONCEPTO----------------------
    $conceptoE = new \entidad\Concepto();
    $conceptoE->setIdConcepto($idConcepto);

    $transaccionE = new \entidad\Transaccion();
    $transaccionE->setIdTransaccion($idTransaccionSalida);

    $transaccionConceptoE = new \entidad\TransaccionConcepto();
    $transaccionConceptoE->setConcepto($conceptoE);
    $transaccionConceptoE->setTransaccion($transaccionE);
    $transaccionConceptoE->setValor(0);
    $transaccionConceptoE->setSaldo(0);
    $transaccionConceptoE->setNota(null);
    $transaccionConceptoE->setIdUsuarioCrecion($idUsuario);
    $transaccionConceptoE->setIdUsuarioModificacion($idUsuario);

    $transaccionConceptoM = new \modelo\TransaccionConcepto($transaccionConceptoE, $conexion);
    $transaccionConceptoM->adicionar();


    //--------------------------------------TRANSACCION PRODUCTO--------------------------------
    //---------Se obtiene el tipo de actualizacion de costo-------------
    $parametroAplicacionE = new \entidad\ParametroAplicacion();
    $parametroAplicacionE->setCodigo('TIPO-COSTO');
    $parametroAplicacionM = new \modelo\ParametroAplicacion($parametroAplicacionE);
    $arrParametro = $parametroAplicacionM->consultar();
    if ($parametroAplicacionM->conexion->obtenerNumeroRegistros() == 0) {
        throw new Exception("No se encontro ningun parámetro para el código TIPO-COSTO");
    }
    $tipoActualizacionCosto = $arrParametro[0]["valor"];
    //------------------------------------------------------------------

    $productoComposicionE = new \entidad\ProductoComposicion();
    $productoComposicionE->setIdProductoCompuesto($idProducto);

    $productoComposicionM = new \modelo\ProductoComposicion($productoComposicionE);
    $data = $productoComposicionM->consultarProductosComponen();

    $contador = 0;
    while ($contador < count($data)) {

        $idProductoCompone = $data[$contador]["idProductoCompone"];
        $cantidadTotal = ($data[$contador]["cantidad"] * $cantidad);
        
        $productoE = new \entidad\Producto();
        $productoE->setIdProducto($idProductoCompone);
        $productoM = new \modelo\Producto($productoE, $conexion);
        $dataProducto = $productoM->consultar();
        $valorUnitarioSalida = $dataProducto[0]["valorSalida"];
        $valorSalidConImpue = $dataProducto[0]["valorSalidConImpue"];
        $idBodega = 1;

        if ($tipoActualizacionCosto == "PEPS") {

            $sentenciaSql = "SELECT * FROM facturacion_inventario.guardar_transaccion_producto(
                                            CAST($idTransaccionSalida AS INTEGER)
                                            , CAST(" . $idProductoCompone . " AS SMALLINT)
                                            , CAST(" . $cantidadTotal . " AS NUMERIC)
                                            , CAST(" . $valorUnitarioSalida . " AS NUMERIC)
                                            , CAST(" . $valorSalidConImpue . " AS NUMERIC)
                                            , CAST(NULL AS CHARACTER VARYING) --NOTA
                                            , CAST(NULL AS CHARACTER VARYING) --SERIAL
                                            , CAST(NULL AS SMALLINT) --UNIDAD DE MEDIDA PRESENTACION
                                            , CAST($idBodega AS SMALLINT)
                                            , CAST($idUsuario AS SMALLINT)
                                        )";

            $conexion->ejecutar($sentenciaSql);
        } else {
            throw new Exception("ERROR -> El tipo de actualización de costo parametrizado actualmente es <b>" . $tipoActualizacionCosto . "</b> y es diferente a PEPS");
        }

        $contador++;
    }
//----------------------------------------------------------------------------------------------------------------------------
    
    $conexion->confirmarTransaccion();
    
} catch (Exception $e) {
    $conexion->cancelarTransaccion();
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
