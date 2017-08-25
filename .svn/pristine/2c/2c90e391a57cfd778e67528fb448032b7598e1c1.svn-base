<?php
/*
 * Autor : Alexis Mauricio Plaza Vargas
 * Fecha  : 12 Mayo 2016
 * Nota : Se modificó el controlador para actualizar el número del tipo de documento para las devoluciones
 */
session_start();
require_once '../entidad/Transaccion.php';
require_once '../modelo/Transaccion.php';
require_once '../entidad/TransaccionProducto.php';
require_once '../modelo/TransaccionProducto.php';
require_once '../entidad/TransaccionCruce.php';
require_once '../modelo/TransaccionCruce.php';
require_once '../entidad/TransaccionCliente.php';
require_once '../modelo/TransaccionCliente.php';


$retorno = array('exito'=>1, 'mensaje'=> 'La devolución del recibo se realizó correctamente.');
try {
    $idTransaccionAfectada = $_POST['idTransaccion'];
    $idCaja = $_POST['idCaja'];
    $idCliente = $_POST['idCliente'];
    $dataProductos = $_POST['dataProductos'];
    $fecha = date('Y-m-d h:i:s A');
    $fechaVencimiento = $fecha;
    $idUsuario = $_SESSION["idUsuario"];
    $idTercero = $_POST["idTercero"];
    $idTipoDocumentoDinero = 17;
    $idTipoDocumentoProducto = 15;
    
    /*----OBTENER VALORES TIPO DOCUMENTO Y ESTADO INICIAL TRANSACCIÓN----*/
    /*--DINERO--*/
    $tipoDocumentoE = new \entidad\TipoDocumento();
    $tipoDocumentoE->setIdTipoDocumento($idTipoDocumentoDinero);
    
    $tipoDocumentoM = new \modelo\TipoDocumento($tipoDocumentoE);
    $numeroTipoDocumentoDinero = $tipoDocumentoM->obtenerNumero();
    $datosTipoDocumento = $tipoDocumentoM->consultar();
    
    $idOficinaDinero = $datosTipoDocumento[0]["idOficina"];
    $idNaturalezaDinero = $datosTipoDocumento[0]["idNaturaleza"];
    
    
    /*--PRODUCTOS--*/
    $tipoDocumentoE = new \entidad\TipoDocumento();
    $tipoDocumentoE->setIdTipoDocumento($idTipoDocumentoProducto);
    
    $tipoDocumentoM = new \modelo\TipoDocumento($tipoDocumentoE);
    $numeroTipoDocumentoProducto = $tipoDocumentoM->obtenerNumero();
    $datosTipoDocumento = $tipoDocumentoM->consultar();
    
    $idOficinaProducto = $datosTipoDocumento[0]["idOficina"];
    $idNaturalezaProducto = $datosTipoDocumento[0]["idNaturaleza"];
    
    $transaccionM = new \modelo\Transaccion(new \entidad\Transaccion());
    $idTransaccionEstado = $transaccionM->obtenerEstadoInicial();
    
    $terceroE = new \entidad\Tercero();
    $terceroE->setIdTercero($idTercero);
    
    /*---------------------------TRANSACCIÓN DEVOLUCIÓN PRODUCTO---------------------------*/
    $nota = 'DEVOLUCIÓN DE PRODUCTOS';
    
    $transaccionE = new \entidad\Transaccion();
    $transaccionE->setIdTipoDocumento($idTipoDocumentoProducto);
    $transaccionE->setTercero($terceroE);
    $transaccionE->setIdTransaccionEstado($idTransaccionEstado);
    $transaccionE->setIdOficina($idOficinaProducto);
    $transaccionE->setIdNaturaleza($idNaturalezaProducto);
    $transaccionE->setNumeroTipoDocumento($numeroTipoDocumentoProducto);
    $transaccionE->setNota($nota);
    $transaccionE->setFecha($fecha);
    $transaccionE->setIdTransaccionAfecta($idTransaccionAfectada);
    $transaccionE->setFechaVencimiento($fechaVencimiento);
    $transaccionE->setValor(0);
    $transaccionE->setIdUsuarioCreacion($idUsuario);
    $transaccionE->setIdUsuarioModificacion($idUsuario);
    
    $transaccionM = new \modelo\Transaccion($transaccionE);
    $transaccionM->adicionar();
    $idTransaccion = $transaccionM->obtenerMaximo();
    
    $valorTotal = 0;
    $secuencia = 1;
    foreach ($dataProductos as $producto){
        $transaccionE = new \entidad\Transaccion();
        $transaccionE->setIdTransaccion($idTransaccion);
        
        $idTransaccionProductoAfecta = $producto['idTransaccionProducto'];
        $idProducto = $producto['idProducto'];
        $serial = $producto['serial'];
        $valorUnitario = $producto['valorUnitario'];
        $cantidad = $producto['cantidadDevolver'];
        $idBodega = $producto['idBodega'];
        
        if($serial == ''){
            $serial = "null";
        }
        
        /* RESTAR EL PRODUCTO EN LA BODEGA ORIGEN */
        $transaccionProductoE = new \entidad\TransaccionProducto();
        $transaccionProductoE->setIdProducto($idProducto);
        $transaccionProductoE->setIdBodega($idBodega);
        $transaccionProductoE->setTransaccion($transaccionE);
        $transaccionProductoE->setSecuencia($secuencia);
        $transaccionProductoE->setSerial($serial);
        $transaccionProductoE->setNota($nota);
        $transaccionProductoE->setCantidad($cantidad);
        $transaccionProductoE->setIdUsuarioCreacion($idUsuario);
        $transaccionProductoE->setIdUsuarioModificacion($idUsuario);
        $transaccionProductoE->setIdTransaccionProductoAfecta($idTransaccionProductoAfecta);
        
        $transaccionProductoM = new \modelo\TransaccionProducto($transaccionProductoE);
        $transaccionProductoM->adicionarProductoDevuelto();
        $secuencia++;
        
        $valorTotal += $valorUnitario * $cantidad;
    }
    
    //---------------------------TRANSACCIÓN DEVOLUCIÓN DINERO---------------------------
    $nota = 'SALIDA DINERO POR DEVOLUCIÓN DE PRODUCTOS';
    
    //---------------INSERCIÓN CRUCE---------------
    $transaccionCruceE = new \entidad\TransaccionCruce();
    $transaccionCruceE->setIdTransaccionAfectado($idTransaccionAfectada); //Id de la factura que viene desde el frm
    $transaccionCruceE->setIdTransaccionAfecta($idTransaccion); //Id que se inserto anteriormente (para la devolucion)
    $transaccionCruceE->setValor($valorTotal);
    $transaccionCruceE->setSecuencia(1);
    $transaccionCruceE->setFecha('NOW()');
    $transaccionCruceE->setObservacion($nota);
    $transaccionCruceE->setIdUsuarioCreacion($idUsuario);
    $transaccionCruceE->setIdUsuarioModificacion($idUsuario);

    $transaccionCruceM = new \modelo\TransaccionCruce($transaccionCruceE);
    $transaccionCruceM->adicionar();
    
    //---------------------INSERTO EN TRANSACCION CLIENTE-------------------    
    $transaccionClienteE = new \entidad\TransaccionCliente();
    $transaccionClienteE->setIdTransaccion($idTransaccion);//Id que se inserto anteriormente (para la devolucion)
    $transaccionClienteE->setIdCliente($idCliente);
    $transaccionClienteE->setIdUsuarioCreacion($idUsuario);
    $transaccionClienteE->setIdUsuarioModicacion($idUsuario);
    $transaccionClienteE->setIdCaja($idCaja);
    
    $transaccionClienteM = new \modelo\TransaccionCliente($transaccionClienteE);
    $transaccionClienteM->adicionar();
    
    
    //------------------------------------------------------------------------
    //Actualizar el número de la devolución tipo de documento de dinero
    $tipoDocumentoE = new \entidad\TipoDocumento();
    $tipoDocumentoE->setIdTipoDocumento($idTipoDocumentoDinero);
    $tipoDocumentoE->setNumero($numeroTipoDocumentoDinero);
    
    $tipoDocumentoM = new \modelo\TipoDocumento($tipoDocumentoE);
    $tipoDocumentoM->actualizarNumero();
    
    //Actualizar el número de la devolución tipo de documento de productos
    $tipoDocumentoE = new \entidad\TipoDocumento();
    $tipoDocumentoE->setIdTipoDocumento($idTipoDocumentoProducto);
    $tipoDocumentoE->setNumero($numeroTipoDocumentoProducto);
    
    $tipoDocumentoM = new \modelo\TipoDocumento($tipoDocumentoE);
    $tipoDocumentoM->actualizarNumero();
    
} catch (Exception $e) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $e->getMessage();
}

echo json_encode($retorno);
?>