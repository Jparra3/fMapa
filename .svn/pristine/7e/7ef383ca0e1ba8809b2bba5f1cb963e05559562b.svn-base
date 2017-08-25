<?php
session_start();
require_once '../entidad/Concepto.php';
require_once '../modelo/Concepto.php';
require_once '../entidad/OrdenTrabajoProducto.php';
require_once '../modelo/OrdenTrabajoProducto.php';
require_once '../entidad/Transaccion.php';
require_once '../modelo/Transaccion.php';
require_once '../entidad/TransaccionProducto.php';
require_once '../modelo/TransaccionProducto.php';
require_once '../entidad/TransaccionConcepto.php';
require_once '../modelo/TransaccionConcepto.php';
require_once '../entidad/Cliente.php';
require_once '../modelo/Cliente.php';
require_once '../entidad/TipoDocumento.php';
require_once '../modelo/TipoDocumento.php';
require_once '../../Seguridad/entidad/Tercero.php';
require_once '../entidad/ClienteTransaccionProducto.php';
require_once '../modelo/ClienteTransaccionProducto.php';


$retorno = array('exito'=>1, 'mensaje'=>'El inventario se guardó correctamente.', 'idTransaccion'=>'');

try {
    $fechaActual = date("Y-m-d");
    $idUsuario = $_SESSION["idUsuario"];
    $idTransaccionEstado = 1;// ACTIVO
    $arrTransaccionCrea = array();
    
    $idOrdenTrabajoProducto = $_POST["idOrdenTrabajoProducto"];
    $idTipoDocumentoTransaccion = $_POST["idTipoDocumento"];
    
    /*Obtener el tipo de documento válido para realizar la eliminación de la transacción*/
    $idParametroAplicacion = 17;
    $transaccionE = new \entidad\Transaccion();
    $transaccionM = new \modelo\Transaccion($transaccionE);
    $idTipoDocumento = $transaccionM->obtenerParametroAplicacion($idParametroAplicacion);
    
    $secuencia = 1;
    if(count($idOrdenTrabajoProducto) > 0){
        foreach($idOrdenTrabajoProducto as $idEvaluar){
        
            $nota = "";
            $fecha = $fechaActual;
            $fechaVencimiento = $fechaActual;


            //Se consulta la información del producto a reversar
            $ordenTrabajoProductoE = new \entidad\OrdenTrabajoProducto();
            $ordenTrabajoProductoE->setIdOrdenTrabajoProducto($idEvaluar);

            $ordenTrabajoProductoM = new \modelo\OrdenTrabajoProducto($ordenTrabajoProductoE);
            $arrInfor = $ordenTrabajoProductoM->consultar();
            $informacionProducto = $arrInfor[0];
            
            $clienteE = new \entidad\Cliente();
            $clienteE->setIdCliente($informacionProducto["idCliente"]);
            
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
            
            
            foreach($arrTipoDocum as $fila){
                
                //Se valida si ya se ha adicionado una transacción con ese tercero
                $existencia = false;
                
                foreach($arrTransaccionCrea as $filaEvaluar){
                    if($informacionProducto["idTercero"] == $filaEvaluar["idTercero"]){
                        $idTransaccion = $filaEvaluar["idTransaccion"];
                        $existencia = true;
                        break;
                    }
                }
                
                if($existencia == false){
                    $terceroE = new \entidad\Tercero();
                    $terceroE->setIdTercero($informacionProducto["idTercero"]);

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

                    $tipoDocumentoE = new \entidad\TipoDocumento();
                    $tipoDocumentoE->setIdTipoDocumento($idTipoDocumento);
                    $tipoDocumentoE->setNumero($numeroTipoDocumento);

                    $tipoDocumentoM = new \modelo\TipoDocumento($tipoDocumentoE);
                    $tipoDocumentoM->actualizarNumero();
                    
                    $arrTransa = array("idTercero"=>$informacionProducto["idTercero"],"idTransaccion"=>$idTransaccion);
                    array_push($arrTransaccionCrea, $arrTransa);
                }
                
                if($informacionProducto["serial"] == "" || $informacionProducto["serial"] == null || $informacionProducto["serial"] == "null"){
                    $informacionProducto["serial"] = "";
                }
                
                $transaccionE = new \entidad\Transaccion();
                $transaccionE->setIdTransaccion($idTransaccion);
                
                
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
                $transaccionE->setIdTransaccion($idTransaccion);

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
                
                
                
                $transaccionProductoE = new \entidad\TransaccionProducto();
                $transaccionProductoE->setTransaccion($transaccionE);
                $transaccionProductoE->setIdProducto($informacionProducto["idProducto"]);
                $transaccionProductoE->setCantidad($informacionProducto["cantidad"]);
                $transaccionProductoE->setValorUnitarioEntrada($informacionProducto["valorUnitario"]);
                $transaccionProductoE->setValorUnitarioSalida($informacionProducto["valorSalida"]);
                $transaccionProductoE->setIdBodega($informacionProducto["idBodega"]);
                $transaccionProductoE->setSecuencia($secuencia);
                $transaccionProductoE->setNota($informacionProducto["nota"]);
                $transaccionProductoE->setSerial($informacionProducto["serial"]);
                $transaccionProductoE->setSerialInterno("");
                $transaccionProductoE->setValorUnitaEntraConImpue($informacionProducto["valorEntraConImpue"]);
                $transaccionProductoE->setValorUnitaSalidConImpue($informacionProducto["valorSalidConImpue"]);
                $transaccionProductoE->setIdUsuarioCreacion($idUsuario);
                $transaccionProductoE->setIdUsuarioModificacion($idUsuario);

                $transaccionProductoM = new \modelo\TransaccionProducto($transaccionProductoE);
                $transaccionProductoM->adicionar();
                $secuencia++;
                
                $idTransaccionProducto = $transaccionProductoM->obtenerMaximo();
                
                
                
                
                
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
                /*---------------------------------------------------------------------------------------------*/
            }
        }
    }
    $retorno["idTransaccion"] = $arrTransaccionCrea;
} catch (Exception $e) {
    $retorno['exito'] =0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
