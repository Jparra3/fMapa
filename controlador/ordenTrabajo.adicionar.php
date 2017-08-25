<?php
session_start();
require_once '../entidad/ClienteServicio.php';
require_once '../entidad/Ordenador.php';
require_once '../entidad/TipoDocumento.php';
require_once '../modelo/TipoDocumento.php';
require_once '../entidad/OrdenTrabajo.php';
require_once '../modelo/OrdenTrabajo.php';

$retorno = array('exito'=>1, 'mensaje'=>'','idOrdenTrabajo'=>null);

try {
    //$fecha = $_POST['fecha'];
    $idClienteServicio = $_POST['idClienteServicio'];
    $idTipoDocumento = $_POST['idTipoDocumento'];
    $idOrdenador = $_POST['idOrdenador'];
    $idMunicipio = $_POST['idMunicipio'];
    $fechaInicio = $_POST['fechaInicio'];
    $fechaFin = $_POST['fechaFin'];
    $observacion = $_POST['observacion'];
    $tipoOrdenTrabajo = $_POST['tipoOrdenTrabajo'];
    $idEstadoOrdenTrabajo = $_POST['idEstadoOrdenTrabajo'];
    $idUsuario = $_SESSION['idUsuario'];
    $idClienteServicio = $_POST["idClienteServicio"];
    
    if($idClienteServicio == "" || $idClienteServicio == null){
        $idClienteServicio = "NULL";
    }
    
    $tipoDocumentoE = new \entidad\TipoDocumento();
    $tipoDocumentoE->setIdTipoDocumento($idTipoDocumento);
    
    $tipoDocumentoM = new \modelo\TipoDocumento($tipoDocumentoE);
    $numeroTipoDocumento = $tipoDocumentoM->obtenerNumeroOrdenTrabajo();
    
    
    $ordenadorE =  new \entidad\Ordenador();
    $ordenadorE->setIdOrdenador($idOrdenador);
    
    $clienteServicioE = new \entidad\ClienteServicio();
    $clienteServicioE->setIdClienteServicio($idClienteServicio);
    
    $ordenTrabajoE = new \entidad\OrdenTrabajo();
    $ordenTrabajoE->setClienteServicio($clienteServicioE);
    //$ordenTrabajoE->setFecha($fecha);
    $ordenTrabajoE->setIdTipoDocumento($idTipoDocumento);
    $ordenTrabajoE->setNumero($numeroTipoDocumento);
    $ordenTrabajoE->setEstado("TRUE");
    $ordenTrabajoE->setObservacion($observacion);
    $ordenTrabajoE->setOrdenador($ordenadorE);
    $ordenTrabajoE->setIdMunicipio($idMunicipio);
    $ordenTrabajoE->setFechaInicio($fechaInicio);
    $ordenTrabajoE->setFechaFin($fechaFin);
    $ordenTrabajoE->setTipoOrdenTrabajo($tipoOrdenTrabajo);
    $ordenTrabajoE->setIdEstadoOrdenTrabajo($idEstadoOrdenTrabajo);
    $ordenTrabajoE->setIdUsuarioCreacion($idUsuario);
    $ordenTrabajoE->setIdUsuarioModificacion($idUsuario);
    
    
    $ordenTrabajoM = new \modelo\OrdenTrabajo($ordenTrabajoE);
    $ordenTrabajoM->adicionar();
    
    /*
    $tipoDocumentoE = new \entidad\TipoDocumento();
    $tipoDocumentoE->setIdTipoDocumento($idTipoDocumento);
    $tipoDocumentoE->setNumero($numeroTipoDocumento);
    
    $tipoDocumentoM = new \modelo\TipoDocumento($tipoDocumentoE);
    $tipoDocumentoM->actualizarNumero();
     * 
     */
    
    $retorno["idOrdenTrabajo"] = $ordenTrabajoM->obtenerMaximo();

} catch (Exception $exc) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $exc->getMessage();
}
echo json_encode($retorno);

?>