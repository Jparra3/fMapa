<?php
session_start();
require_once '../entidad/Producto.php';
require_once '../modelo/Producto.php';
require_once '../entidad/Transaccion.php';
require_once '../modelo/Transaccion.php';
require_once '../entidad/TransaccionProducto.php';
require_once '../modelo/TransaccionProducto.php';
require_once '../entidad/Cliente.php';
require_once '../modelo/Cliente.php';
require_once '../entidad/ClienteServicioProducto.php';
require_once '../modelo/ClienteServicioProducto.php';
require_once '../entidad/TipoDocumento.php';
require_once '../modelo/TipoDocumento.php';
require_once '../entidad/OrdenTrabajoProducto.php';
require_once '../modelo/OrdenTrabajoProducto.php';
require_once '../entidad/OrdenTrabajoCliente.php';
require_once '../../Seguridad/entidad/Tercero.php';
require_once '../modelo/ClienteServicio.php';

$retorno = array('exito'=>1, 'mensaje'=>'El servicio de canceló correctamente');

try {
    
    $arrIdTransaccion = array();
    
    $idClienteServicio = $_POST['idClienteServicio'];
    $idOrdenTrabajoCliente = $_POST['idOrdenTrabajoCliente'];
    $idCliente = $_POST['idCliente'];
    
    $fechaActual = date("Y-m-d");
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
    
    
    if($idClienteServicio != "" && $idClienteServicio != null && $idClienteServicio != "null"){
        
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
        
        
        $clienteServicioE = new \entidad\ClienteServicio();
        $clienteServicioE->setIdClienteServicio($idClienteServicio);
        
        $clienteServicioProductoE = new \entidad\ClienteServicioProducto();
        $clienteServicioProductoE->setClienteServicio($clienteServicioE);
        $clienteServicioProductoE->setEstado("TRUE");
        
        $clienteServicioProductoM = new \modelo\ClienteServicioProducto($clienteServicioProductoE);
        $arrProductos = $clienteServicioProductoM->consultar();
        
        
        //Actualizar Fecha Final
        $clienteServicioE = new \entidad\ClienteServicio();
        $clienteServicioE->setIdClienteServicio($idClienteServicio);
        
        $clienteServicioM = new \modelo\ClientServicio($clienteServicioE);
        $clienteServicioM->actualizarFechaFinal();
        
        $secuencia = 1;
        foreach($arrProductos as $informacionProducto){
            
            //Actualizar estado desinstalado
            $clienteServicioProductoE = new \entidad\ClienteServicioProducto();
            $clienteServicioProductoE->setIdClienteServicioProducto($informacionProducto["idClienteServicioProducto"]);
            $clienteServicioProductoE->setIdEstaClieServProd("2");//Desinstalado
            
            $clienteServicioProductoM = new \modelo\ClienteServicioProducto($clienteServicioProductoE);
            $clienteServicioProductoM->actualizarEstado();
            
            
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
            
            
            $ordenTrabajoClienteE = new \entidad\OrdenTrabajoCliente();
            $ordenTrabajoClienteE->setIdOrdenTrabajoCliente($informacionProducto["idOrdenTrabajoCliente"]);
        
            
            //Se actualiza el estado del producto a desinstalado
            $ordenTrabajoProductoE = new \entidad\OrdenTrabajoProducto();
            $ordenTrabajoProductoE->setOrdenTrabajoCliente($ordenTrabajoClienteE);

            $ordenTrabajoProductoM = new \modelo\OrdenTrabajoProducto($ordenTrabajoProductoE);
            $ordenTrabajoProductoM->actualizarEstadoProductosCliente(4);//Desinstalado
             
            
            $secuencia++;
        }    
    }
    /*----------------------------Productos pertenecientes al orden de trabajo cliente -----*/
    if($idOrdenTrabajoCliente != "" && $idOrdenTrabajoCliente != null && $idOrdenTrabajoCliente != "null"){
        
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
        
        $ordenTrabajoClienteE = new \entidad\OrdenTrabajoCliente();
        $ordenTrabajoClienteE->setIdOrdenTrabajoCliente($idOrdenTrabajoCliente);
        
        $ordenTrabajoProductoE = new \entidad\OrdenTrabajoProducto();
        $ordenTrabajoProductoE->setOrdenTrabajoCliente($ordenTrabajoClienteE);
        $ordenTrabajoProductoE->setEstado("TRUE");
        
        $ordenTrabajoProductoM = new \modelo\OrdenTrabajoProducto($ordenTrabajoProductoE);
        $arrProductos = $ordenTrabajoProductoM->consultar();
        
        $secuencia = 1;
        foreach($arrProductos as $informacionProducto){
            
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
            
            $secuencia++;
        }
    }
    $retorno["idTransaccion"] = $arrIdTransaccion;
} catch (Exception $exc) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $exc->getMessage();
}
echo json_encode($retorno);

?>