<?php
session_start();
require_once '../entidad/Ordenador.php';
require_once '../entidad/OrdenTrabajo.php';
require_once '../modelo/OrdenTrabajo.php';

$retorno = array('exito'=>1, 'mensaje'=>'');

try {
    $idOrdenTrabajo = $_POST['idOrdenTrabajo'];
    //$fecha = $_POST['fecha'];
    $idTipoDocumento = $_POST['idTipoDocumento'];
    $numeroOrden = $_POST['numeroOrden'];
    $idOrdenador = $_POST['idOrdenador'];
    $idMunicipio = $_POST['idMunicipio'];
    $fechaInicio = $_POST['fechaInicio'];
    $fechaFin = $_POST['fechaFin'];
    $observacion = $_POST['observacion'];
    $tipoOrdenTrabajo = $_POST['tipoOrdenTrabajo'];
    $idEstadoOrdenTrabajo = $_POST['idEstadoOrdenTrabajo'];
    $idUsuario = $_SESSION['idUsuario'];
    $idClienteServicio = $_POST['idClienteServicio'];
    
    if($idClienteServicio == "" || $idClienteServicio == null || $idClienteServicio == "null"){
        $idClienteServicio = "NULL";
    }
    
    $ordenadorE =  new \entidad\Ordenador();
    $ordenadorE->setIdOrdenador($idOrdenador);
    
    $clienteServicioE = new \entidad\ClienteServicio();
    $clienteServicioE->setIdClienteServicio($idClienteServicio);
    
    $ordenTrabajoE = new \entidad\OrdenTrabajo();
    $ordenTrabajoE->setClienteServicio($clienteServicioE);
    $ordenTrabajoE->setIdOrdenTrabajo($idOrdenTrabajo);
    //$ordenTrabajoE->setFecha($fecha);
    $ordenTrabajoE->setIdTipoDocumento($idTipoDocumento);
    $ordenTrabajoE->setNumero($numeroOrden);
    $ordenTrabajoE->setTipoOrdenTrabajo($tipoOrdenTrabajo);
    $ordenTrabajoE->setIdEstadoOrdenTrabajo($idEstadoOrdenTrabajo);
    $ordenTrabajoE->setObservacion($observacion);
    $ordenTrabajoE->setEstado("TRUE");
    $ordenTrabajoE->setOrdenador($ordenadorE);
    $ordenTrabajoE->setIdMunicipio($idMunicipio);
    $ordenTrabajoE->setFechaInicio($fechaInicio);
    $ordenTrabajoE->setFechaFin($fechaFin);
    $ordenTrabajoE->setIdUsuarioCreacion($idUsuario);
    $ordenTrabajoE->setIdUsuarioModificacion($idUsuario);
    
    $ordenTrabajoM = new \modelo\OrdenTrabajo($ordenTrabajoE);
    $ordenTrabajoM->modificar();
    
} catch (Exception $exc) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $exc->getMessage();
}
echo json_encode($retorno);

?>