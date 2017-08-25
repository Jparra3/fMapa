<?php
session_start();
require_once '../entidad/Transaccion.php';
require_once '../modelo/Transaccion.php';

$retorno = array('exito'=>1, 'mensaje'=>'', 'valor'=>null);

try {
    $idParametroAplicacion = $_POST['idParametroAplicacion'];
    
    $transaccionE = new \entidad\Transaccion();
    $transaccionM = new \modelo\Transaccion($transaccionE);
    $retorno['valor'] = $transaccionM->obtenerParametroAplicacion($idParametroAplicacion);
    
} catch (Exception $exc) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $exc->getMessage();
}
echo json_encode($retorno);

?>
