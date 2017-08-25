<?php
session_start();
require_once '../entidad/TipoDocumento.php';
require_once '../modelo/TipoDocumento.php';
require_once '../entidad/Transaccion.php';
require_once '../modelo/Transaccion.php';
require_once '../entidad/TransaccionProducto.php';
require_once '../modelo/TransaccionProducto.php';

$retorno = array('exito'=>1, 'mensaje'=> 'Los productos se trasladaron correctamente.');

try {
    $productosTrasladados = $_POST['productosTrasladados'];
    $idUsuario = $_SESSION['idUsuario'];
    
    /* CREAR TRANSACCIÓN */
    $idTipoDocumentoEntrada = $_POST["idTipoDocumentoEntrada"];
    $idTipoDocumentoSalida = $_POST["idTipoDocumentoSalida"];
    $idBodegaOrigen = $_POST["bodegaOrigen"];
    $idBodegaDestino = $_POST["bodegaDestino"];
    
    $idTercero = $_SESSION["idTercero"];
    
    $nota = 'null';
    $fecha = date('Y-m-d');
    $fechaVencimiento = $fecha;
    $idUsuario = $_SESSION["idUsuario"];
    
    
    /* INSERTAR TRANSACCIÓN DE ENTRADA */
    $tipoDocumentoE = new \entidad\TipoDocumento();
    $tipoDocumentoE->setIdTipoDocumento($idTipoDocumentoEntrada);
    
    $tipoDocumentoM = new \modelo\TipoDocumento($tipoDocumentoE);
    $numeroTipoDocumento = $tipoDocumentoM->obtenerNumero();
    $datosTipoDocumentoEntrada = $tipoDocumentoM->consultar();
    
    foreach($datosTipoDocumentoEntrada as $dato){
        $idOficina = $dato["idOficina"];
        $idNaturaleza = $dato["idNaturaleza"];
    }
    
    $transaccionE = new \entidad\Transaccion();
    $transaccionM = new \modelo\Transaccion($transaccionE);
    $idTransaccionEstado = $transaccionM->obtenerEstadoInicial();
    
    $terceroE = new \entidad\Tercero();
    $terceroE->setIdTercero($idTercero);
    
    $transaccionE = new \entidad\Transaccion();
    $transaccionE->setIdTipoDocumento($idTipoDocumentoEntrada);
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
    $idTransaccionEntrada = $transaccionM->obtenerMaximo();
    
    /* ACTUALIZAR NUMERO TIPO DOCUMENTO */
    
    $tipoDocumentoE = new \entidad\TipoDocumento();
    $tipoDocumentoE->setIdTipoDocumento($idTipoDocumentoEntrada);
    $tipoDocumentoE->setNumero($numeroTipoDocumento);
    
    $tipoDocumentoM = new \modelo\TipoDocumento($tipoDocumentoE);
    $tipoDocumentoM->actualizarNumero();
    
    /* INSERTAR TRANSACCIÓN DE SALIDA*/
    $tipoDocumentoE = new \entidad\TipoDocumento();
    $tipoDocumentoE->setIdTipoDocumento($idTipoDocumentoSalida);
    
    $tipoDocumentoM = new \modelo\TipoDocumento($tipoDocumentoE);
    $numeroTipoDocumento = $tipoDocumentoM->obtenerNumero();
    $datosTipoDocumentoSalida = $tipoDocumentoM->consultar();
    
    foreach($datosTipoDocumentoSalida as $dato){
        $idOficina = $dato["idOficina"];
        $idNaturaleza = $dato["idNaturaleza"];
    }
    
    $transaccionE = new \entidad\Transaccion();
    $transaccionM = new \modelo\Transaccion($transaccionE);
    $idTransaccionEstado = $transaccionM->obtenerEstadoInicial();
    
    $terceroE = new \entidad\Tercero();
    $terceroE->setIdTercero($idTercero);
    
    $transaccionE = new \entidad\Transaccion();
    $transaccionE->setIdTipoDocumento($idTipoDocumentoSalida);
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
    $idTransaccionSalida = $transaccionM->obtenerMaximo();
    
    /* ACTUALIZAR NUMERO TIPO DOCUMENTO */
    
    $tipoDocumentoE = new \entidad\TipoDocumento();
    $tipoDocumentoE->setIdTipoDocumento($idTipoDocumentoSalida);
    $tipoDocumentoE->setNumero($numeroTipoDocumento);
    
    $tipoDocumentoM = new \modelo\TipoDocumento($tipoDocumentoE);
    $tipoDocumentoM->actualizarNumero();
    
    
    //------------------------------------------------------------------------
    /* GUARDAR PRODUCTOS */
    $transaccionEntrada = new \entidad\Transaccion();
    $transaccionEntrada->setIdTransaccion($idTransaccionEntrada);
    
    $transaccionSalida = new \entidad\Transaccion();
    $transaccionSalida->setIdTransaccion($idTransaccionSalida);
    $secuencia = 1;
    foreach ($productosTrasladados as $producto){
        $idProducto = $producto['idProducto'];
        $serial = $producto['serial'];
        $cantidad = $producto['cantidad'];
        
        if($serial == ''){
            $serial = "null";
        }
        
        /* RESTAR EL PRODUCTO EN LA BODEGA ORIGEN */
        $transaccionProductoE = new \entidad\TransaccionProducto();
        $transaccionProductoE->setIdProducto($idProducto);
        $transaccionProductoE->setIdBodega($idBodegaOrigen);
        $transaccionProductoE->setTransaccion($transaccionSalida);
        $transaccionProductoE->setSecuencia($secuencia);
        $transaccionProductoE->setSerial($serial);
        $transaccionProductoE->setNota($nota);
        $transaccionProductoE->setCantidad($cantidad);
        $transaccionProductoE->setIdUsuarioCreacion($idUsuario);
        $transaccionProductoE->setIdUsuarioModificacion($idUsuario);
        
        $transaccionProductoM = new \modelo\TransaccionProducto($transaccionProductoE);
        $transaccionProductoM->adicionarProductoTraslado('');
        $secuencia++;
        
        /* RESTAR EL PRODUCTO EN LA BODEGA DESTINO */
        $transaccionProductoE = new \entidad\TransaccionProducto();
        $transaccionProductoE->setIdProducto($idProducto);
        $transaccionProductoE->setIdBodega($idBodegaOrigen);
        $transaccionProductoE->setTransaccion($transaccionEntrada);
        $transaccionProductoE->setSecuencia($secuencia);
        $transaccionProductoE->setSerial($serial);
        $transaccionProductoE->setNota($nota);
        $transaccionProductoE->setCantidad($cantidad);
        $transaccionProductoE->setIdUsuarioCreacion($idUsuario);
        $transaccionProductoE->setIdUsuarioModificacion($idUsuario);
        
        $transaccionProductoM = new \modelo\TransaccionProducto($transaccionProductoE);
        $transaccionProductoM->adicionarProductoTraslado($idBodegaDestino);
        $secuencia++;
    }
    $retorno['idTransaccionEntrada'] = $idTransaccionEntrada;
    $retorno['idTransaccionSalida'] = $idTransaccionSalida;
} catch (Exception $e) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $e->getMessage();
}


echo json_encode($retorno);
?>