<?php
require_once '../entidad/Impuesto.php';
require_once '../modelo/Impuesto.php';

$retorno = array('exito'=>1,'mensaje'=>'', 'data'=>null, 'numeroRegistros'=>0);

try{
    $impuestoM = new \modelo\Impuesto(new \entidad\Impuesto());    
    $retorno['data'] = $impuestoM->consultar();
    $retorno['numeroRegistros'] = $impuestoM->conexion->obtenerNumeroRegistros();
}  catch (Exception $e){
   $retorno['exito'] = 0;
   $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>