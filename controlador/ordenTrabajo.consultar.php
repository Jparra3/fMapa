<?php
session_start();
require_once '../entidad/Ordenador.php';
require_once '../entidad/Tecnico.php';
require_once '../entidad/OrdenTrabajo.php';
require_once '../modelo/OrdenTrabajo.php';
require_once '../entidad/ClienteServicio.php';
require_once '../entidad/Cliente.php';

$retorno = array('exito'=>1, 'mensaje'=>'', 'data'=>null);

try {
    $idOrdenTrabajo = $_POST['idOrdenTrabajo'];
    $idTipoDocumento = $_POST['idTipoDocumento'];
    $numeroOrden = $_POST['numeroOrden'];
    $fechaInicio = $_POST['fechaInicio'];
    $fechaFin = $_POST['fechaFin'];
    $idOrdenador = $_POST['idOrdenador'];
    $idTecnico = $_POST['idTecnico'];
    $idMunicipio = $_POST['idMunicipio'];
    $tipoOrdenTrabajo = $_POST['tipoOrdenTrabajo'];
    $idEstadoOrdenTrabajo = $_POST['idEstadoOrdenTrabajo'];
    $idCliente = $_POST['idCliente'];
    
    $ordenadorE = new \entidad\Ordenador();
    $ordenadorE->setIdOrdenador($idOrdenador);
    
    $clienteE = new \entidad\Cliente();
    $clienteE->setIdCliente($idCliente);
    
    $clienteServicioE = new entidad\ClienteServicio();
    $clienteServicioE ->setCliente($clienteE);
    
    $ordenTrabajoE = new \entidad\OrdenTrabajo();
    $ordenTrabajoE->setIdOrdenTrabajo($idOrdenTrabajo);
    $ordenTrabajoE->setIdTipoDocumento($idTipoDocumento);
    $ordenTrabajoE->setNumero($numeroOrden);
    $ordenTrabajoE->setFechaInicio($fechaInicio);
    $ordenTrabajoE->setFechaFin($fechaFin);
    $ordenTrabajoE->setOrdenador($ordenadorE);
    $ordenTrabajoE->setIdTecnico($idTecnico);
    $ordenTrabajoE->setIdMunicipio($idMunicipio);
    $ordenTrabajoE->setTipoOrdenTrabajo($tipoOrdenTrabajo);
    $ordenTrabajoE->setIdEstadoOrdenTrabajo($idEstadoOrdenTrabajo);
    $ordenTrabajoE->setClienteServicio($clienteServicioE);
    
    
    $ordenTrabajoM = new \modelo\OrdenTrabajo($ordenTrabajoE);
    $retorno['data'] = $ordenTrabajoM->consultar();
    $retorno['numeroRegistros'] = $ordenTrabajoM->conexion->obtenerNumeroRegistros();
    
} catch (Exception $exc) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $exc->getMessage();
}
echo json_encode($retorno);

?>
