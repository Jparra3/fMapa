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
require_once '../entidad/OrdenTrabajoProducto.php';
require_once '../modelo/OrdenTrabajoProducto.php';
require_once '../../Seguridad/entidad/Tercero.php';
require_once '../entidad/ClienteTransaccionProducto.php';
require_once '../modelo/ClienteTransaccionProducto.php';


$retorno = array('exito'=>1, 'mensaje'=>'La transacción se adicionó correctamente.', 'idTransaccion'=>'' , 'idTransaccionProducto'=>'');

try {
    
    $fechaActual = date("Y-m-d");
    
    $idTipoDocumento = $_POST["idTipoDocumento"];
    $idCliente = $_POST["idCliente"];
    $productos = $_POST["productos"];
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
    
    
    /*----------------------------Transaccion Producto ----------------------------------------*/
    $secuencia = 1;
    if(count($productos) > 0){
        foreach($productos as $informacionProducto){
            
            if($informacionProducto["idTransaccion"] != "" && $informacionProducto["idTransaccion"] != null && $informacionProducto["idTransaccion"] != "null"){
                continue;
            }
        
            $productoE = new \entidad\Producto();
            $productoE->setIdProducto($informacionProducto["idProducto"]);

            $productoM = new \modelo\Producto($productoE);
            $arrRetorno = $productoM->consultar();
            $dataProducto = $arrRetorno[0];

            $transaccionE = new \entidad\Transaccion();
            $transaccionE->setIdTransaccion($idTransaccion);

            $transaccionProductoE = new \entidad\TransaccionProducto();
            $transaccionProductoE->setTransaccion($transaccionE);
            $transaccionProductoE->setIdProducto($informacionProducto["idProducto"]);
            $transaccionProductoE->setCantidad($informacionProducto["cantidad"]);
            $transaccionProductoE->setValorUnitarioEntrada($dataProducto["valorEntrada"]);
            $transaccionProductoE->setValorUnitarioSalida($dataProducto["valorSalida"]);
            $transaccionProductoE->setIdBodega($informacionProducto["idBodega"]);
            $transaccionProductoE->setSecuencia($secuencia);
            $transaccionProductoE->setNota($informacionProducto["nota"]);
            $transaccionProductoE->setSerial($informacionProducto["serial"]);
            $transaccionProductoE->setSerialInterno("");
            $transaccionProductoE->setValorUnitaEntraConImpue($dataProducto["valorEntraConImpue"]);
            $transaccionProductoE->setValorUnitaSalidConImpue($dataProducto["valorSalidConImpue"]);
            $transaccionProductoE->setIdUsuarioCreacion($idUsuario);
            $transaccionProductoE->setIdUsuarioModificacion($idUsuario);

            $transaccionProductoM = new \modelo\TransaccionProducto($transaccionProductoE);
            $transaccionProductoM->adicionar();
            
            $idTransaccionProducto = $transaccionProductoM->obtenerMaximo();
            
            if($informacionProducto["idOrdenTrabajoProducto"] != "" && $informacionProducto["idOrdenTrabajoProducto"] != null && $informacionProducto["idOrdenTrabajoProducto"] != "null"){
                /*-------------------------------------Cliente Transaccion Producto-------------------------*/
                $transaccionProductoE = new \entidad\TransaccionProducto();
                $transaccionProductoE->setIdTransaccionProducto($idTransaccionProducto);

                $clienteTransaccionProductoE = new \entidad\clienteTransaccionProducto();
                $clienteTransaccionProductoE->setCliente($clienteE);
                $clienteTransaccionProductoE->setTransaccionProducto($transaccionProductoE);
                $clienteTransaccionProductoE->setTabla("orden_trabajo_producto");
                $clienteTransaccionProductoE->setIdTabla($informacionProducto["idOrdenTrabajoProducto"]);
                $clienteTransaccionProductoE->setEstado("TRUE");
                $clienteTransaccionProductoE->setIdUsuarioCreacion($idUsuario);
                $clienteTransaccionProductoE->setIdUsuarioModificacion($idUsuario);

                $clienteTransaccionProductoM = new \modelo\ClienteTransaccionProducto($clienteTransaccionProductoE);
                $clienteTransaccionProductoM->adicionar();
            }
            
            /*---------------------------------------------------------------------------------------------*/
            
            if($informacionProducto["idOrdenTrabajoProducto"] != "" && $informacionProducto["idOrdenTrabajoProducto"] != null){
                $ordenTrabajoProductoE = new \entidad\OrdenTrabajoProducto();
                $ordenTrabajoProductoE->setIdOrdenTrabajoProducto($informacionProducto["idOrdenTrabajoProducto"]);
                $ordenTrabajoProductoE->setIdEstaOrdeTrabProdu(2);//Por Instalar
                
                $ordenTrabajoProductoM = new \modelo\OrdenTrabajoProducto($ordenTrabajoProductoE);
                $ordenTrabajoProductoM->actualizarEstado();
            }

            $secuencia++;
        }
    }
} catch (Exception $e) {
    $retorno['exito'] =0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
