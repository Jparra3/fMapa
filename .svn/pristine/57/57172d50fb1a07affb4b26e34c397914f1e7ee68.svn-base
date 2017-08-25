<?php
session_start();

require_once '../entidad/OrdenTrabajoProducto.php';
require_once '../modelo/OrdenTrabajoProducto.php';

$retorno = array('exito'=>1, 'mensaje'=>'', 'data'=>null);

try {
    $idProducto = $_POST['idProducto'];
    
    $ordenTrabajoProductoE = new \entidad\OrdenTrabajoProducto();
    
    $ordenTrabajoProductoM = new \modelo\OrdenTrabajoProducto($ordenTrabajoProductoE);
    $retorno['data'] = $ordenTrabajoProductoM->consultarProductosSerial($idProducto,$idBodega);
    $retorno['numeroRegistros'] = $ordenTrabajoProductoM->conexion->obtenerNumeroRegistros();
    
} catch (Exception $exc) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $exc->getMessage();
}
echo json_encode($retorno);

?>