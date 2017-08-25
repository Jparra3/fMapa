<?php
require_once '../../Seguridad/entidad/EmpreUnidaNegoc.php';
require_once '../../Seguridad/modelo/EmpreUnidaNegoc.php';

$retorno = array ('exito'=>1, 'mensaje'=> '', 'data'=>null, 'numeroRegistros'=>'');

try {
    $empreUnidaNegocM = new \modelo\EmpreUnidaNegoc(new \entidad\EmpreUnidaNegoc());
    $retorno['data'] = $empreUnidaNegocM->obtenEmpreUnidaNegocTienePermi();
    $retorno['numeroRegistros']= $empreUnidaNegocM->conexion->obtenerNumeroRegistros();
} catch (Exception $e) {
    $retorno['exito']=0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>