<?php
require_once '../entidad/Cliente.php';
require_once '../modelo/Cliente.php';

$retorno = array ('exito'=>1, 'mensaje'=> '', 'data'=>null, 'numeroRegistros'=>'');

try {
    $idZona = $_POST['idZona'];
    $idPeriodicidadVisita = $_POST['idPeriodicidadVisita'];
    
    $clienteE = new \entidad\Cliente();
    $clienteE->setIdZona($idZona);
    $clienteE->setIdPeriodicidadVisita($idPeriodicidadVisita);
    
    $clienteM = new \modelo\Cliente($clienteE);
    $retorno['data'] = $clienteM->consultarOrdenVisita();
    $retorno['numeroRegistros']= $clienteM->conexion->obtenerNumeroRegistros();
    
} catch (Exception $e) {
    $retorno['exito']=0;
    $retorno['mensaje'] = $e->getMessage();
}

echo json_encode($retorno);
?>
