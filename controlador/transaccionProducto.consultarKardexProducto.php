<?php
session_start();
require_once '../../Seguridad/entidad/Oficina.php';
require_once '../../Seguridad/modelo/Oficina.php';
require_once '../entidad/Transaccion.php';
require_once '../entidad/TransaccionProducto.php';
require_once '../modelo/TransaccionProducto.php';

$retorno = array('exito'=>1, 'mensaje'=>'', 'data'=>null, 'numeroRegistros'=>0);

try {
    $oficinaM = new \modelo\Oficina(new \entidad\Oficina());
    $idOficinas = $oficinaM->consuOficiTienePermi();
    
    $idProducto = $_POST["idProducto"];
    $idBodega = $_POST["idBodega"];
    $fechaInicio = $_POST["fechaInicio"];
    $fechaFin = $_POST["fechaFin"];
    
    $transaccionProductoE = new \entidad\TransaccionProducto();
    $transaccionProductoE->setIdBodega($idBodega);
    $transaccionProductoE->setIdProducto($idProducto);
    $transaccionProductoE->setFechaInicio($fechaInicio);
    $transaccionProductoE->setFechaFin($fechaFin);
    
    
    $transaccionProductoM = new \modelo\TransaccionProducto($transaccionProductoE);
    $retorno["data"] = $transaccionProductoM->consultarKardexProducto($idOficinas);
    $retorno['numeroRegistros'] = $transaccionProductoM->conexion->obtenerNumeroRegistros();
    
} catch (Exception $e) {
    $retorno['exito'] =0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
