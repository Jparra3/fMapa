<?php
session_start();
require_once '../entidad/TransaccionEstado.php';
require_once '../modelo/TransaccionEstado.php';
require_once '../entidad/Transaccion.php';
require_once '../modelo/Transaccion.php';
require_once '../entidad/TransaccionProducto.php';
require_once '../modelo/TransaccionProducto.php';
require_once '../entidad/Concepto.php';
require_once '../modelo/Concepto.php';
require_once '../entidad/TransaccionConcepto.php';
require_once '../modelo/TransaccionConcepto.php';
require_once '../entidad/TransaccionProveedor.php';
require_once '../modelo/TransaccionProveedor.php';
require_once '../entidad/TransaccionFormaPago.php';
require_once '../modelo/TransaccionFormaPago.php';

require_once '../entorno/Conexion.php';

$retorno = array('exito'=>1, 'mensaje'=>'El inventario se anuló correctamente.');

try {
    
    $conexion = new Conexion();
    $conexion->iniciarTransaccion();
    
    $idTransaccion = $_POST["idTransaccion"];
    $idUsuario = $_SESSION["idUsuario"];
    $codigoTipoDocumentoEntrada = 'FB-AN-EN';
    $codigoTipoDocumentoSalida = 'FB-AN-SA';
    
//----------------------------------ANULAR PRODUCTO COMPUESTO-----------------------------------------
    //----------------------PROCESO DE ANULACIÓN-----------------------------------------
    $transaccionM = new \modelo\Transaccion(new \entidad\Transaccion(), $conexion);
    $arrInfoTipoDocumento = $transaccionM->obtenerInfoTipoDocumento($codigoTipoDocumentoSalida);    
    
    $transaccionE = new \entidad\Transaccion();
    $transaccionE->setIdTransaccion($idTransaccion);
    
    $transaccionM = new \modelo\Transaccion($transaccionE, $conexion);
    $arrInfoTransaccion = $transaccionM->consultar('false'); //Envio false indicando que tenga en cuenta los tipos de documento que no se visualizan en la consulta
    
    $terceroE = new \entidad\Tercero();
    $terceroE->setIdTercero($arrInfoTransaccion[0]["idTercero"]);
    
    //---------------TRANSACCION----------------------------------------
    $transaccionE = new \entidad\Transaccion();
    $transaccionE->setIdTipoDocumento($arrInfoTipoDocumento[0]["idTipoDocumento"]);
    $transaccionE->setTercero($terceroE);
    $transaccionE->setIdTransaccionEstado(1);
    $transaccionE->setIdOficina($arrInfoTipoDocumento[0]["idOficina"]);
    $transaccionE->setIdNaturaleza($arrInfoTipoDocumento[0]["idNaturaleza"]);
    $transaccionE->setNumeroTipoDocumento($arrInfoTipoDocumento[0]["proximoNumero"]);
    $transaccionE->setNota('ANULACIÓN DE FABRICACIÓN');
    $transaccionE->setFecha($arrInfoTransaccion[0]["fechaHora"]);
    $transaccionE->setFechaVencimiento($arrInfoTransaccion[0]["fechaVencimientoHora"]);
    $transaccionE->setValor($arrInfoTransaccion[0]["valor"]);
    $transaccionE->setSaldo($arrInfoTransaccion[0]["saldo"]);
    $transaccionE->setIdUsuarioCreacion($idUsuario);
    $transaccionE->setIdUsuarioModificacion($idUsuario);
    //Para que no afecte la consulta
    $transaccionE->setIdTransaccionAfecta('NULL');
    
    $transaccionM = new \modelo\Transaccion($transaccionE, $conexion);
    $transaccionM->adicionar();
    $idTransaccionNuevoPadre = $transaccionM->obtenerMaximo();
    
    //---------------TRANSACCION CONCEPTO--------------------------
    $conceptoE = new \entidad\Concepto();
    $conceptoE->setIdTipoDocumento($arrInfoTipoDocumento[0]["idTipoDocumento"]);
    $conceptoM = new \modelo\Concepto($conceptoE, $conexion);
    $arrConcepto = $conceptoM->obtenerIdConcepto();//OBTENGO EL ID CONCEPTO A PARTIR DEL ID TIPO DE DOCUMENTO DE FACTURACIÓN
    if ($arrConcepto["exito"] == 0) {
        throw new Exception("ERROR -> No puede haber más de un concepto con un mismo tipo de documento -> id: " . $arrInfoTipoDocumento[0]["idTipoDocumento"]);
    }
    if($arrConcepto["idConcepto"] == null || $arrConcepto["idConcepto"] == "null" || $arrConcepto["idConcepto"] == ""){
        throw new Exception("ERROR -> No se encontró un concepto con el tipo de documento de id: ".$arrInfoTipoDocumento[0]["idTipoDocumento"]);
    }
    $idConcepto = $arrConcepto["idConcepto"];

    $conceptoE = new \entidad\Concepto();
    $conceptoE->setIdConcepto($idConcepto);

    $transaccionE = new \entidad\Transaccion();
    $transaccionE->setIdTransaccion($idTransaccionNuevoPadre);

    $transaccionConceptoE = new \entidad\TransaccionConcepto();
    $transaccionConceptoE->setConcepto($conceptoE);
    $transaccionConceptoE->setTransaccion($transaccionE);
    $transaccionConceptoE->setValor($arrInfoTransaccion[0]["valor"]);
    $transaccionConceptoE->setSaldo($arrInfoTransaccion[0]["saldo"]);
    $transaccionConceptoE->setNota(null);
    $transaccionConceptoE->setIdUsuarioCrecion($idUsuario);
    $transaccionConceptoE->setIdUsuarioModificacion($idUsuario);

    $transaccionConceptoM = new \modelo\TransaccionConcepto($transaccionConceptoE, $conexion);
    $transaccionConceptoM->adicionar();
    $idTransaccionConcepto = $transaccionConceptoM->obtenerMaximo();
    //------------------------------------------------------------------------
    
    //-----------------------TRANSACCION PRODUCTO-------------------------------
    $transaccionE = new \entidad\Transaccion();
    $transaccionE->setIdTransaccion($idTransaccion);
    
    $transaccionProductoE = new \entidad\TransaccionProducto();
    $transaccionProductoE->setTransaccion($transaccionE);
    $transaccionProductoE->setSaldoCantidadProducto(' > 0 ');
    
    $transaccionProductoM = new \modelo\TransaccionProducto($transaccionProductoE, $conexion);
    $arrInfoProductos = $transaccionProductoM->consultar();
    
    if($arrInfoProductos == null){
        throw new Exception('NO SE PUDO ANULAR -> No se encontraron productos con saldo en cantidad para realizar la anulación.');
    }
    
    $transaccionE = new \entidad\Transaccion();
    $transaccionE->setIdTransaccion($idTransaccionNuevoPadre);
    $contador = 0;
    $secuencia = 1;
    while ($contador < count($arrInfoProductos)) {
        $transaccionProductoE = new \entidad\TransaccionProducto();
        $transaccionProductoE->setTransaccion($transaccionE);
        $transaccionProductoE->setIdProducto($arrInfoProductos[$contador]["idProducto"]);
        $transaccionProductoE->setCantidad($arrInfoProductos[$contador]["saldoCantidadProducto"]);
        $transaccionProductoE->setValorUnitarioEntrada($arrInfoProductos[$contador]["valorUnitarioEntrada"]);
        $transaccionProductoE->setValorUnitarioSalida($arrInfoProductos[$contador]["valorUnitarioSalida"]);
        $transaccionProductoE->setValorUnitaEntraConImpue($arrInfoProductos[$contador]["valorUnitaEntraConImpue"]);
        $transaccionProductoE->setValorUnitaSalidConImpue($arrInfoProductos[$contador]["valorUnitaSalidConImpue"]);
        $transaccionProductoE->setIdBodega($arrInfoProductos[$contador]["idBodega"]);
        $transaccionProductoE->setSecuencia($secuencia);
        $transaccionProductoE->setNota('ANULACIÓN DE FABRICACIÓN '.$arrInfoProductos[$contador]["nota"]);
        $transaccionProductoE->setSerial($arrInfoProductos[$contador]["serial"]);
        $transaccionProductoE->setIdUsuarioCreacion($idUsuario);
        $transaccionProductoE->setIdUsuarioModificacion($idUsuario);
        $transaccionProductoE->setIdUnidadMedidaPresentacion(null);

        $transaccionProductoM = new \modelo\TransaccionProducto($transaccionProductoE, $conexion);
        $transaccionProductoM->adicionar();
        $contador++;
        $secuencia++;
    }
    //--------------------------------------------------------------------------------------
    
    //----------------------SE CAMBIA EL ESTADO DE LA TRANSACCION--------------------------
    $transaccionEstadoM = new \modelo\TransaccionEstado(new \entidad\TransaccionEstado(), $conexion);
    $numeroEstadosInactivos = $transaccionEstadoM->obtenCantiEstadInact();
    
    if($numeroEstadosInactivos > 1){
        throw new Exception ("Error: No fue posible anular el movimiento, ya que existe más de un estado para inactivar.");
    }
    
    $transaccionE = new \entidad\Transaccion();
    $transaccionE->setIdTransaccion($idTransaccion);
    $transaccionE->setIdUsuarioModificacion($idUsuario);
    
    $transaccionM = new \modelo\Transaccion($transaccionE, $conexion);
    $transaccionM->inactivar();
    
//------------------------------------ANULAR LOS COMPONENTES-----------------------------------------
    //----------------------PROCESO DE ANULACIÓN-----------------------------------------
    $transaccionM = new \modelo\Transaccion(new \entidad\Transaccion(), $conexion);
    $arrInfoTipoDocumento = $transaccionM->obtenerInfoTipoDocumento($codigoTipoDocumentoEntrada);    
    $idTransaccionHijo = $transaccionM->obtenerIdTransaccionHijo($idTransaccion);
    
    $transaccionE = new \entidad\Transaccion();
    $transaccionE->setIdTransaccion($idTransaccionHijo);
    
    $transaccionM = new \modelo\Transaccion($transaccionE, $conexion);
    $arrInfoTransaccion = $transaccionM->consultar('false'); //Envio false indicando que tenga en cuenta los tipos de documento que no se visualizan en la consulta
    
    $terceroE = new \entidad\Tercero();
    $terceroE->setIdTercero($arrInfoTransaccion[0]["idTercero"]);
    
    //---------------TRANSACCION----------------------------------------
    $transaccionE = new \entidad\Transaccion();
    $transaccionE->setIdTipoDocumento($arrInfoTipoDocumento[0]["idTipoDocumento"]);
    $transaccionE->setTercero($terceroE);
    $transaccionE->setIdTransaccionEstado(1);
    $transaccionE->setIdOficina($arrInfoTipoDocumento[0]["idOficina"]);
    $transaccionE->setIdNaturaleza($arrInfoTipoDocumento[0]["idNaturaleza"]);
    $transaccionE->setNumeroTipoDocumento($arrInfoTipoDocumento[0]["proximoNumero"]);
    $transaccionE->setNota('ANULACIÓN DE FABRICACIÓN');
    $transaccionE->setFecha($arrInfoTransaccion[0]["fechaHora"]);
    $transaccionE->setFechaVencimiento($arrInfoTransaccion[0]["fechaVencimientoHora"]);
    $transaccionE->setValor($arrInfoTransaccion[0]["valor"]);
    $transaccionE->setSaldo($arrInfoTransaccion[0]["saldo"]);
    $transaccionE->setIdUsuarioCreacion($idUsuario);
    $transaccionE->setIdUsuarioModificacion($idUsuario);
    $transaccionE->setIdTransaccionPadre($idTransaccionNuevoPadre);
    //Para que no afecte la consulta
    $transaccionE->setIdTransaccionAfecta('NULL');
    
    $transaccionM = new \modelo\Transaccion($transaccionE, $conexion);
    $transaccionM->adicionar();
    $idTransaccionNuevoHijo = $transaccionM->obtenerMaximo();
    
    //---------------TRANSACCION CONCEPTO--------------------------
    $conceptoE = new \entidad\Concepto();
    $conceptoE->setIdTipoDocumento($arrInfoTipoDocumento[0]["idTipoDocumento"]);
    $conceptoM = new \modelo\Concepto($conceptoE, $conexion);
    $arrConcepto = $conceptoM->obtenerIdConcepto();//OBTENGO EL ID CONCEPTO A PARTIR DEL ID TIPO DE DOCUMENTO DE FACTURACIÓN
    if ($arrConcepto["exito"] == 0) {
        throw new Exception("ERROR -> No puede haber más de un concepto con un mismo tipo de documento -> id: " . $arrInfoTipoDocumento[0]["idTipoDocumento"]);
    }
    if($arrConcepto["idConcepto"] == null || $arrConcepto["idConcepto"] == "null" || $arrConcepto["idConcepto"] == ""){
        throw new Exception("ERROR -> No se encontró un concepto con el tipo de documento de id: ".$arrInfoTipoDocumento[0]["idTipoDocumento"]);
    }
    $idConcepto = $arrConcepto["idConcepto"];

    $conceptoE = new \entidad\Concepto();
    $conceptoE->setIdConcepto($idConcepto);

    $transaccionE = new \entidad\Transaccion();
    $transaccionE->setIdTransaccion($idTransaccionNuevoHijo);

    $transaccionConceptoE = new \entidad\TransaccionConcepto();
    $transaccionConceptoE->setConcepto($conceptoE);
    $transaccionConceptoE->setTransaccion($transaccionE);
    $transaccionConceptoE->setValor($arrInfoTransaccion[0]["valor"]);
    $transaccionConceptoE->setSaldo($arrInfoTransaccion[0]["saldo"]);
    $transaccionConceptoE->setNota(null);
    $transaccionConceptoE->setIdUsuarioCrecion($idUsuario);
    $transaccionConceptoE->setIdUsuarioModificacion($idUsuario);

    $transaccionConceptoM = new \modelo\TransaccionConcepto($transaccionConceptoE, $conexion);
    $transaccionConceptoM->adicionar();
    $idTransaccionConcepto = $transaccionConceptoM->obtenerMaximo();
    //------------------------------------------------------------------------
    
    //-----------------------TRANSACCION PRODUCTO-------------------------------
    $transaccionE = new \entidad\Transaccion();
    $transaccionE->setIdTransaccion($idTransaccionHijo);
    
    $transaccionProductoE = new \entidad\TransaccionProducto();
    $transaccionProductoE->setTransaccion($transaccionE);
    
    $transaccionProductoM = new \modelo\TransaccionProducto($transaccionProductoE, $conexion);
    $arrInfoProductos = $transaccionProductoM->consultar();
    
    if($arrInfoProductos == null){
        throw new Exception('NO SE PUDO ANULAR -> No se encontraron productos con saldo en cantidad para realizar la anulación.');
    }
    
    $transaccionE = new \entidad\Transaccion();
    $transaccionE->setIdTransaccion($idTransaccionNuevoHijo);
    $contador = 0;
    $secuencia = 1;
    while ($contador < count($arrInfoProductos)) {
        $transaccionProductoE = new \entidad\TransaccionProducto();
        $transaccionProductoE->setTransaccion($transaccionE);
        $transaccionProductoE->setIdProducto($arrInfoProductos[$contador]["idProducto"]);
        $transaccionProductoE->setCantidad($arrInfoProductos[$contador]["cantidad"]);
        $transaccionProductoE->setValorUnitarioEntrada($arrInfoProductos[$contador]["valorUnitarioEntrada"]);
        $transaccionProductoE->setValorUnitarioSalida($arrInfoProductos[$contador]["valorUnitarioSalida"]);
        $transaccionProductoE->setValorUnitaEntraConImpue($arrInfoProductos[$contador]["valorUnitaEntraConImpue"]);
        $transaccionProductoE->setValorUnitaSalidConImpue($arrInfoProductos[$contador]["valorUnitaSalidConImpue"]);
        $transaccionProductoE->setIdBodega($arrInfoProductos[$contador]["idBodega"]);
        $transaccionProductoE->setSecuencia($secuencia);
        $transaccionProductoE->setNota('ANULACIÓN DE FABRICACIÓN '.$arrInfoProductos[$contador]["nota"]);
        $transaccionProductoE->setSerial($arrInfoProductos[$contador]["serial"]);
        $transaccionProductoE->setIdUsuarioCreacion($idUsuario);
        $transaccionProductoE->setIdUsuarioModificacion($idUsuario);
        $transaccionProductoE->setIdUnidadMedidaPresentacion(null);
        $transaccionProductoE->setSaldoCantidadProducto($arrInfoProductos[$contador]["cantidad"]);

        $transaccionProductoM = new \modelo\TransaccionProducto($transaccionProductoE, $conexion);
        $transaccionProductoM->adicionar();
        $contador++;
        $secuencia++;
    }
    //--------------------------------------------------------------------------------------
    
    //----------------------SE CAMBIA EL ESTADO DE LA TRANSACCION--------------------------
    $transaccionEstadoM = new \modelo\TransaccionEstado(new \entidad\TransaccionEstado(), $conexion);
    $numeroEstadosInactivos = $transaccionEstadoM->obtenCantiEstadInact();
    
    if($numeroEstadosInactivos > 1){
        throw new Exception ("Error: No fue posible anular el movimiento, ya que existe más de un estado para inactivar.");
    }
    
    $transaccionE = new \entidad\Transaccion();
    $transaccionE->setIdTransaccion($idTransaccionHijo);
    $transaccionE->setIdUsuarioModificacion($idUsuario);
    
    $transaccionM = new \modelo\Transaccion($transaccionE, $conexion);
    $transaccionM->inactivar();
    
    $conexion->confirmarTransaccion();
    
} catch (Exception $e) {
    $conexion->cancelarTransaccion();
    $retorno['exito'] =0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
