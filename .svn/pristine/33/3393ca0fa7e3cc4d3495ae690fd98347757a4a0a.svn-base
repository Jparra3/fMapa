<?php
session_start();

require_once '../entidad/Transaccion.php';
require_once '../modelo/Transaccion.php';

$retorno = array('exito'=>1, 'mensaje'=>'', 'data'=>null, 'numeroRegistros'=>0);

try {
    $idTransaccionConcepto = $_POST['idTransaccionConcepto'];
    
    $transaccionM = new \modelo\Transaccion(new \entidad\Transaccion());
    $retorno['data'] = $transaccionM->consultarRecibosCaja($idTransaccionConcepto);
    $retorno['numeroRegistros'] = $transaccionM->conexion->obtenerNumeroRegistros();
} catch (Exception $e) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $e->getMessage();
}

echo json_encode($retorno);

?>