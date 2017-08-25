<?php
require_once '../entidad/Transaccion.php';
require_once '../modelo/Transaccion.php';
require_once '../entidad/TransaccionProducto.php';
require_once '../modelo/TransaccionProducto.php';

$retorno = array('exito'=> 1, 'mensaje'=>'', 'numeroRegistros'=>null, 'data'=>'');

try {
    $idTransaccionEntrada = $_POST['idTransaccionEntrada'];
    $idTransaccionSalida = $_POST['idTransaccionSalida'];
    
    /* TRANSACCIÓN ENTRADA*/
    $transaccionE = new \entidad\Transaccion();
    $transaccionE->setIdTransaccion($idTransaccionEntrada);
    
    $transaccionM = new \modelo\Transaccion($transaccionE);
    $retorno['dataEntrada'] = $transaccionM->consultarDetalleTraslado();
    
    $transaccionProductoE = new \entidad\TransaccionProducto();
    $transaccionProductoE->setTransaccion($transaccionE);
    
    $transaccionProducto = new \modelo\TransaccionProducto($transaccionProductoE);
    $retorno['dataDetalleEntrada'] = $transaccionProducto->consultar();
    $retorno['numeroRegistrosEntrada'] = $transaccionProducto->conexion->obtenerNumeroRegistros();
    
    /**************************************************/
    /* TRANSACCIÓN SALIDA*/
    
    $transaccionE = new \entidad\Transaccion();
    $transaccionE->setIdTransaccion($idTransaccionSalida);
    
    $transaccionM = new \modelo\Transaccion($transaccionE);
    $retorno['dataSalida'] = $transaccionM->consultarDetalleTraslado();
    
    $transaccionProductoE = new \entidad\TransaccionProducto();
    $transaccionProductoE->setTransaccion($transaccionE);
    
    $transaccionProducto = new \modelo\TransaccionProducto($transaccionProductoE);
    $retorno['dataDetalleSalida'] = $transaccionProducto->consultar();
    $retorno['numeroRegistrosSalida'] = $transaccionProducto->conexion->obtenerNumeroRegistros();
    
} catch (Exception $e) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $e->getMessage();
}

echo json_encode($retorno);
?>