<?php
session_start();
require_once '../entidad/OrdenTrabajoVehiculo.php';
require_once '../modelo/OrdenTrabajoVehiculo.php';

$retorno = array('exito'=>1, 'mensaje'=>'');

try {
    $idOrdenTrabajo = $_POST['idOrdenTrabajo'];
    $ordenTrabajoVehiculo = $_POST['ordenTrabajoVehiculo'];
    $idUsuario = $_SESSION["idUsuario"];
    
    foreach( $ordenTrabajoVehiculo as $fila){
        $idVehiculo = $fila['idVehiculo'];
        $estado = $fila['estado'];
        
        $ordenTrabajoE = new \entidad\OrdenTrabajo();
        $ordenTrabajoE->setIdOrdenTrabajo($idOrdenTrabajo);
        
        $vehiculoE =  new \entidad\Vehiculo();
        $vehiculoE->setIdVehiculo($idVehiculo);
        
        $ordenTrabajoVehiculoE = new \entidad\OrdenTrabajoVehiculo();
        $ordenTrabajoVehiculoE->setOrdenTrabajo($ordenTrabajoE);
        $ordenTrabajoVehiculoE->setVehiculo($vehiculoE);
        $ordenTrabajoVehiculoE->setEstado("TRUE");
        $ordenTrabajoVehiculoE->setIdUsuarioCreacion($idUsuario);
        $ordenTrabajoVehiculoE->setIdUsuarioModificacion($idUsuario);
        
        $ordenTrabajoVehiculoM = new \modelo\OrdenTrabajoVehiculo($ordenTrabajoVehiculoE);
        $ordenTrabajoVehiculoM->adicionar();
    }
    
} catch (Exception $exc) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $exc->getMessage();
}
echo json_encode($retorno);

?>