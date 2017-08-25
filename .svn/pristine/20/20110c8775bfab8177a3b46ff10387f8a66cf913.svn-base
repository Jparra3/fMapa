<?php
session_start();
require_once '../../Seguridad/entidad/Persona.php';
require_once '../entidad/Vendedor.php';
require_once '../modelo/Vendedor.php';
$retorno = array("exito"=>1, "mensaje"=>"Se adicionó la información correctamente.");
try{
    
    $idPersona = $_POST['idPersona'];
    $idZona = $_POST['idZona'];
    $estado = $_POST['estado'];
    $idUsuario = $_SESSION['idUsuario'];
    
    $personaE = new \entidad\Persona();
    $personaE->setNumeroIdentificacion($numeroIdentificacion);
    $personaE->setIdPersona($idPersona);
    
    $vendedorE = new \entidad\Vendedor();
    $vendedorE->setIdVendedor($idVendedor);
    $vendedorE->setPersona($personaE);
    $vendedorE->setIdZona($idZona);
    $vendedorE->setEstado($estado);
    $vendedorE->setIdUsuarioCreacion($idUsuario);
    $vendedorE->setIdUsuarioModificacion($idUsuario);
    
    $vendedorM = new \modelo\Vendedor($vendedorE);
    $vendedorM -> adicionar();
    
} catch (Exception $ex) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $ex -> getMessage();
}
echo json_encode($retorno);
?>