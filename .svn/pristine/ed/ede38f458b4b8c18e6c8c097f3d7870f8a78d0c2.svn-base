<?php
require_once '../entidad/ClienteServicioArchivo.php';
require_once '../modelo/ClienteServicioArchivo.php';
$retorno = array("exito"=>1, "mensaje"=>"", "data"=>null, "numeroRegistros"=>0);
try{
    
    $idOrdenTrabajoCliente = $_POST['idOrdenTrabajoCliente'];
    
    $ordenTrabajoClienteE = new \entidad\OrdenTrabajoCliente();
    $ordenTrabajoClienteE ->setIdOrdenTrabajoCliente($idOrdenTrabajoCliente);
    
    $clienteServicioArchivoE = new \entidad\ClienteServicioArchivo();
    $clienteServicioArchivoE ->setOrdenTrabajoCliente($ordenTrabajoClienteE);
    
    $clienteServicioArchivoM = new \modelo\ClienteServicioArchivo($clienteServicioArchivoE);
    $retorno["data"] = $clienteServicioArchivoM -> consultar();
    $retorno["numeroRegistros"] = $clienteServicioArchivoM -> conexion -> obtenerNumeroRegistros();
    
} catch (Exception $ex) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $ex -> getMessage();
}
echo json_encode($retorno);
?>