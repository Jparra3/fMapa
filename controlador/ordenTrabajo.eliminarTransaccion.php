<?php
session_start();
require_once '../entidad/OrdenTrabajoCliente.php';
require_once '../modelo/OrdenTrabajoCliente.php';
require_once '../entidad/OrdenTrabajoProducto.php';
require_once '../modelo/OrdenTrabajoProducto.php';
require_once '../entidad/Transaccion.php';
require_once '../modelo/Transaccion.php';
require_once '../entidad/TransaccionProducto.php';
require_once '../modelo/TransaccionProducto.php';
require_once '../entidad/Cliente.php';
require_once '../modelo/Cliente.php';
require_once '../entidad/TipoDocumento.php';
require_once '../modelo/TipoDocumento.php';
require_once '../entidad/Producto.php';
require_once '../modelo/Producto.php';
require_once '../../Seguridad/entidad/Tercero.php';


$retorno = array('exito'=>1, 'mensaje'=>'El inventario se guardó correctamente.', 'idTransaccion'=>'');

try {
    $fechaActual = date("Y-m-d");
    $idUsuario = $_SESSION["idUsuario"];
    $idTransaccionEstado = 1;// ACTIVO
    $nota = "";
    $fecha = $fechaActual;
    $fechaVencimiento = $fechaActual;
    $arrIdTransaccion = array();
    
    $idOrdenTrabajoCliente = $_POST["idOrdenTrabajoCliente"];
    
    /*Obtener el tipo de documento válido para realizar la eliminación de la transacción*/
    $idParametroAplicacion = 17;
    $transaccionE = new \entidad\Transaccion();
    $transaccionM = new \modelo\Transaccion($transaccionE);
    $idTipoDocumento = $transaccionM->obtenerParametroAplicacion($idParametroAplicacion);
    
    
    if(count($idOrdenTrabajoCliente) > 0){
        foreach($idOrdenTrabajoCliente as $idEvaluar){
            
            $ordenTrabajoClienteE = new \entidad\OrdenTrabajoCliente();
            $ordenTrabajoClienteE->setIdOrdenTrabajoCliente($idEvaluar);
            
            $ordenTrabajoClienteM = new \modelo\OrdenTrabajoCliente($ordenTrabajoClienteE);
            $arrInforClien = $ordenTrabajoClienteM->consultar();
            $arrInforClien = $arrInforClien[0];
            
            $idTercero = $arrInforClien["idTercero"];
            
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
            
            array_push($arrIdTransaccion, $idTransaccion);

            $tipoDocumentoE = new \entidad\TipoDocumento();
            $tipoDocumentoE->setIdTipoDocumento($idTipoDocumento);
            $tipoDocumentoE->setNumero($numeroTipoDocumento);

            $tipoDocumentoM = new \modelo\TipoDocumento($tipoDocumentoE);
            $tipoDocumentoM->actualizarNumero();
        
            
            /*Consultar la información de los productos vinculados a la orden trabajo cliente*/
            $ordenTrabajoProductoE =  new \entidad\OrdenTrabajoProducto();
            $ordenTrabajoProductoE->setOrdenTrabajoCliente($ordenTrabajoClienteE);
            $ordenTrabajoProductoE->setEstado("TRUE");
                    
            $ordenTrabajoProductoM = new \modelo\OrdenTrabajoProducto($ordenTrabajoProductoE);
            $arrListadoProducto = $ordenTrabajoProductoM->consultar();
            
            $secuencia = 1;
            if($ordenTrabajoProductoM->conexion->obtenerNumeroRegistros() != 0){
                foreach($arrListadoProducto as $informacionProducto){
                    
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
                    $transaccionProductoE->setValorUnitarioEntrada($informacionProducto["valorUnitario"]);
                    $transaccionProductoE->setValorUnitarioSalida($informacionProducto["valorSalida"]);
                    $transaccionProductoE->setIdBodega($informacionProducto["idBodega"]);
                    $transaccionProductoE->setSecuencia($secuencia);
                    $transaccionProductoE->setNota($informacionProducto["nota"]);
                    $transaccionProductoE->setSerial($informacionProducto["serial"]);
                    $transaccionProductoE->setValorUnitaEntraConImpue($dataProducto["valorEntraConImpue"]);
                    $transaccionProductoE->setValorUnitaSalidConImpue($dataProducto["valorSalidConImpue"]);
                    $transaccionProductoE->setIdUsuarioCreacion($idUsuario);
                    $transaccionProductoE->setIdUsuarioModificacion($idUsuario);

                    $transaccionProductoM = new \modelo\TransaccionProducto($transaccionProductoE);
                    $transaccionProductoM->adicionar();
                    
                    $secuencia++;
                }
            }
        }
    }
    $retorno["idTransaccion"] = $arrIdTransaccion;
} catch (Exception $e) {
    $retorno['exito'] =0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
