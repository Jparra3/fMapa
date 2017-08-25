<?php
session_start();
require_once '../entidad/Cliente.php';
require_once '../modelo/Cliente.php';
require_once '../../Seguridad/entidad/Sucursal.php';
require_once '../../Seguridad/modelo/Sucursal.php';

$retorno = array("exito"=>1, "mensaje"=>'Se ha inactivado el cliente correctamente.');
try{
    $idUsuario = $_SESSION['idUsuario'];
    $idSucursal = $_POST['idSucursal'];
    $estado = $_POST['estado'];
    
    $sucursalE = new \entidad\Sucursal();
    $sucursalE -> setIdSucursal($idSucursal);
    $sucursalE -> setEstado($estado);
    $sucursalE -> setIdUsuarioModificacion($idUsuario);
    
    $sucursalM = new \modelo\Sucursal($sucursalE);
    $sucursalM -> inactivar();
    
//    $clienteE = new \entidad\Cliente();
//    $clienteE ->setIdCliente($idCliente);
//    $clienteE ->setIdUsuarioModificacion($idUsuarioModificacion);
//    $clienteE ->setSucursal($sucursalE);
//    
//    $clienteM = new \modelo\Cliente($clienteE);
//    $clienteM->inactivar();
} catch (Exception $ex) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $ex -> getMessage();
}
echo json_encode($retorno);
?>