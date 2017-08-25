<?php
require_once '../entidad/PeriodicidadVisita.php';
require_once '../modelo/PeriodicidadVisita.php';
$retorno = array('exito'=>1, 'mensaje'=>'','data'=>null);
try {
    $periodicidadVisitaE = new \entidad\PeriodicidadVisita();
    
    $periodicidadVisitaM = new \modelo\PeriodicidadVisita($periodicidadVisitaE);
    $retorno['data'] = $periodicidadVisitaM->cargarPeriodicidadVisita();
} catch (Exception $e) {
    $retorno['exito'] = 0;
    $retorno['exito'] = $e->getMessage();
}
echo json_encode($retorno);