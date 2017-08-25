<?php
session_start();
require_once '../entidad/Vendedor.php';
require_once '../modelo/Vendedor.php';
$retorno = array("exito"=>1, "mensaje"=>"Se modificó la información correctamente.");
try{
    
    $idVendedor = $_POST['idVendedor'];
    $idZona = $_POST['idZona'];
    $estado = $_POST['estado'];
    $idUsuario = $_SESSION['idUsuario'];
    
    $vendedorE = new \entidad\Vendedor();
    $vendedorE->setIdVendedor($idVendedor);
    $vendedorE->setIdZona($idZona);
    $vendedorE->setEstado($estado);
    $vendedorE->setIdUsuarioCreacion($idUsuario);
    $vendedorE->setIdUsuarioModificacion($idUsuario);
    
    $vendedorM = new \modelo\Vendedor($vendedorE);
    $vendedorM -> modificar();
    
} catch (Exception $ex) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $ex -> getMessage();
}
echo json_encode($retorno);
?>