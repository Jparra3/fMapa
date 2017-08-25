<?php
require_once '../entidad/UnidadMedida.php';
require_once '../modelo/UnidadMedida.php';

$retorno = array('exito'=>1,'mensaje'=>'Se ha consultado satisfactoriamente', 'data'=>null, 'numeroRegistros'=>0);

try{
    $unidadMedidaE = new \entidad\UnidadMedida();
    $unidadMedidaE->setEstado("true");
    
    $unidadMedidaM = new \modelo\UnidadMedida($unidadMedidaE);
    $retorno['data'] = $unidadMedidaM->consultar();
    $retorno['numeroRegistros'] = $unidadMedidaM->conexion->obtenerNumeroRegistros();
}  catch (Exception $e){
   $retorno['exito'] = 0;
   $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);

?>
