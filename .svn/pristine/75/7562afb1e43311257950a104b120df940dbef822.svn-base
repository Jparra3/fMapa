<?php
session_start();
require_once '../entidad/Producto.php';
require_once '../modelo/Producto.php';
require_once '../entidad/Transaccion.php';
require_once '../modelo/Transaccion.php';
require_once '../entidad/TransaccionProducto.php';
require_once '../modelo/TransaccionProducto.php';
require_once '../entidad/TransaccionConcepto.php';
require_once '../modelo/TransaccionConcepto.php';
require_once '../entidad/Concepto.php';
require_once '../modelo/Concepto.php';
require_once '../entidad/Cliente.php';
require_once '../modelo/Cliente.php';
require_once '../entidad/TipoDocumento.php';
require_once '../modelo/TipoDocumento.php';
require_once '../../Seguridad/entidad/Tercero.php';


$retorno = array('exito'=>1, 'mensaje'=>'La transacción se adicionó correctamente.', 'idTransaccion'=>'');

try {
    
    $fechaActual = date("Y-m-d");
    $idCliente = $_POST["idCliente"];
    $idTransaccionEstado = 1;// ACTIVO
    $nota = "";
    $fecha = $fechaActual;
    $fechaVencimiento = $fechaActual;
    $idUsuario = $_SESSION["idUsuario"];
    
    
    //Se obtiene la información del cliente
    $clienteE = new \entidad\Cliente();
    $clienteE->setIdCliente($idCliente);
    
    $clienteM = new \modelo\Cliente($clienteE);
    $arrInforClien = $clienteM->consultar();
    
    foreach($arrInforClien as $fila){
        $idTercero = $fila["idTercero"];
    }
    
    /*Obtener el tipo de documento válido para realizar la eliminación de la transacción*/
    $idParametroAplicacion = 17;
    $transaccionE = new \entidad\Transaccion();
    $transaccionM = new \modelo\Transaccion($transaccionE);
    $idTipoDocumento = $transaccionM->obtenerParametroAplicacion($idParametroAplicacion);
    
    
    //Se obtiene la información vinculada al tipo de documento
    $tipoDocumentoECons = new \entidad\TipoDocumento();
    $tipoDocumentoECons->setIdTipoDocumento($idTipoDocumento);
    
    $tipoDocumentoMCons =  new \modelo\TipoDocumento($tipoDocumentoECons);
    $arrTipoDocum = $tipoDocumentoMCons->consultar();
    
    $numeroTipoDocumento = $tipoDocumentoMCons->obtenerNumero();
    
    //Se obtiene la información del tipo de documento para guardar la transacción
    if(count($arrTipoDocum) == 0){
        throw new Exception("El tipo de documento enviado no existe");
    }else{
        foreach($arrTipoDocum as $fila){
            $idNaturaleza = $fila["idNaturaleza"];
            $idOficina = $fila["idOficina"];
        }
    }
    
        
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
    $transaccionE->setValor(0);
    $transaccionE->setIdUsuarioCreacion($idUsuario);
    $transaccionE->setIdUsuarioModificacion($idUsuario);
    
    $transaccionM = new \modelo\Transaccion($transaccionE);
    $transaccionM->adicionar();
    $idTransaccion = $transaccionM->obtenerMaximo();
    $retorno['idTransaccion'] = $idTransaccion;
    
    
    /*----------------------------------------TRANSACCIÓN CONCEPTO--------------------------------------*/
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
    $transaccionConceptoE->setValor(0);
    $transaccionConceptoE->setSaldo(0);
    $transaccionConceptoE->setNota(null);
    $transaccionConceptoE->setIdUsuarioCrecion($idUsuario);
    $transaccionConceptoE->setIdUsuarioModificacion($idUsuario);

    $transaccionConceptoM = new \modelo\TransaccionConcepto($transaccionConceptoE, $conexion);
    $transaccionConceptoM->adicionar();
    $idTransaccionConcepto = $transaccionConceptoM->obtenerMaximo();
    
    
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
