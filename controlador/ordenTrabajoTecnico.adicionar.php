<?php
session_start();
require_once '../entidad/Tecnico.php';
require_once '../entidad/OrdenTrabajo.php';
require_once '../entidad/OrdenTrabajoTecnico.php';
require_once '../modelo/OrdenTrabajoTecnico.php';

$retorno = array('exito'=>1, 'mensaje'=>'');

try {
    $ordenTrabajoTecnico = $_POST['ordenTrabajoTecnico'];
    $idUsuario = $_SESSION["idUsuario"];
    
    foreach( $ordenTrabajoTecnico as $fila){
        $idOrdenTrabajo = $fila['idOrdenTrabajo'];
        $idTecnico = $fila['idTecnico'];
        $principal = $fila['principal'];
        
        $ordenTrabajoE = new \entidad\OrdenTrabajo();
        $ordenTrabajoE->setIdOrdenTrabajo($idOrdenTrabajo);
        
        $tecnicoE =  new \entidad\Tecnico();
        $tecnicoE->setIdTecnico($idTecnico);
        
        $ordenTrabajoTecnicoE = new \entidad\OrdenTrabajoTecnico();
        $ordenTrabajoTecnicoE->setOrdenTrabajo($ordenTrabajoE);
        $ordenTrabajoTecnicoE->setTecnico($tecnicoE);
        $ordenTrabajoTecnicoE->setPrincipal($principal);
        $ordenTrabajoTecnicoE->setEstado("TRUE");
        $ordenTrabajoTecnicoE->setIdUsuarioCreacion($idUsuario);
        $ordenTrabajoTecnicoE->setIdUsuarioModificacion($idUsuario);
        
        $ordenTrabajoTecnicoM = new \modelo\OrdenTrabajoTecnico($ordenTrabajoTecnicoE);
        $ordenTrabajoTecnicoM->adicionar();
    }
    
} catch (Exception $exc) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $exc->getMessage();
}
echo json_encode($retorno);

?>