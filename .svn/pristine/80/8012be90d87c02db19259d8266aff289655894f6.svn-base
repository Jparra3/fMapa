<?php
session_start();
require_once '../entidad/Tecnico.php';
require_once '../entidad/OrdenTrabajo.php';
require_once '../entidad/OrdenTrabajoTecnico.php';
require_once '../modelo/OrdenTrabajoTecnico.php';

$retorno = array('exito'=>1, 'mensaje'=>'');

try {    
    
    
    $idOrdenTrabajo = $_POST["idOrdenTrabajo"];
    
    $ordenTrabajoE = new \entidad\OrdenTrabajo();
    $ordenTrabajoE->setIdOrdenTrabajo($idOrdenTrabajo);
    
    $ordenTrabajoTecnicoE = new \entidad\OrdenTrabajoTecnico();
    $ordenTrabajoTecnicoE->setOrdenTrabajo($ordenTrabajoE);
    

    $ordenTrabajoTecnicoM = new \modelo\OrdenTrabajoTecnico($ordenTrabajoTecnicoE);
    $ordenTrabajoTecnicoM->eliminar();
    
    
} catch (Exception $exc) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $exc->getMessage();
}
echo json_encode($retorno);

?>