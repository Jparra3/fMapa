<?php
session_start();
require_once '../../Seguridad/entidad/Persona.php';
require_once '../entidad/Empleado.php';
require_once '../modelo/Empleado.php';
require_once '../entidad/Ordenador.php';
require_once '../modelo/Ordenador.php';
$retorno = array("exito"=>1, "mensaje"=> "Se adicionó la información correctamente.");
try{
    $idPersona = $_POST['idPersona'];
    $idArl = $_POST['idArl'];
    $idEps = $_POST['idEps'];
    $estado = $_POST['estado'];
    $idCargo = $_POST['idCargo'];
    
    $idUsuario = $_SESSION['idUsuario'];
    
    $personaE = new \entidad\Persona();
    $personaE -> setIdPersona($idPersona);
    
    $empleadoE = new \entidad\Empleado();
    $empleadoE ->setPersona($personaE);
    $empleadoE ->setIdEps($idEps);
    $empleadoE ->setIdArl($idArl);
    $empleadoE ->setIdUsuarioCreacion($idUsuario);
    $empleadoE ->setIdUsuarioModificacion($idUsuario);
    $empleadoE ->setIdCargo($idCargo);
    
    $empleadoM = new \modelo\Empleado($empleadoE);
    $idEmpleado = $empleadoM ->adicionar();
    $empleadoE ->setIdEmpleado($idEmpleado);
    
    $ordenadorE = new \entidad\Ordenador();
    $ordenadorE ->setEmpleado($empleadoE);
    $ordenadorE ->setEstado($estado);
    $ordenadorE ->setIdUsuarioCreacion($idUsuario);
    $ordenadorE ->setIdUsuarioModificacion($idUsuario);
    
    $ordenadorM = new \modelo\Ordenador($ordenadorE);
    $ordenadorM -> adicionar();
    
} catch (Exception $ex) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $ex ->getMessage();
}
echo json_encode($retorno);
?>