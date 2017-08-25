<?php
require_once '../entidad/Zona.php';
require_once '../modelo/Zona.php';

$retorno = array ('exito'=>1, 'mensaje'=> '', 'data'=>null, 'numeroRegistros'=>'');

try {
    $idZona = $_POST['idZona'];
    $zonaE = new \entidad\Zona();
    $zonaE->setIdZona($idZona);
    
    $zonaM = new \modelo\Zona($zonaE);
    $retorno['data'] = $zonaM->consultar();
    $retorno['numeroRegistros']= $zonaM->conexion->obtenerNumeroRegistros();
} catch (Exception $e) {
    $retorno['exito']=0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
