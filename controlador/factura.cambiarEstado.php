<?php
session_start();
require_once '../entidad/EstadoFactura.php';
require_once '../modelo/EstadoFactura.php';
require_once '../entidad/Factura.php';
require_once '../modelo/Factura.php';

$retorno = array('exito'=>1, 'mensaje'=>'', 'data'=>NULL);

try {
    $idFactura = $_POST['idFactura'];
    $estado = $_POST['estado'];

    $estadoFacturaE = new \entidad\EstadoFactura();
    if($estado == 'true')
        $estadoFacturaE->setFinalizado('true');
    else
        $estadoFacturaE->setInicial('true');
    
    $estadoFacturaM = new \modelo\EstadoFactura($estadoFacturaE);
    $estadoFactura = $estadoFacturaM->obtenerEstado();
    if($estadoFactura['exito'] == 0){
        throw new Exception($estadoFactura['mensaje']);
    }
    
    $idEstadoFactura = $estadoFactura['idEstadoFactura'];
    
    $facturaE = new \entidad\Factura();
    $facturaE->setIdFactura($idFactura);
    $facturaE->setIdEstadoFactura($idEstadoFactura);
    
    $facturaM = new \modelo\Factura($facturaE);
    $facturaM->cambiarEstado();
} catch (Exception $e) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $e->getMessage();
}

echo json_encode($retorno);

?>