<?php
session_start();
require_once '../entidad/Empleado.php';
require_once '../modelo/Empleado.php';
require_once '../entidad/Tecnico.php';
require_once '../modelo/Tecnico.php';
$retorno = array("exito"=>1, "mensaje"=> "Se adicionó la información correctamente.");
try{
    
    $idTecnico = $_POST['idTecnico'];
    $idArl = $_POST['idArl'];
    $idEps = $_POST['idEps'];
    $estado = $_POST['estado'];
    $idCargo = $_POST['idCargo'];
    $idEmpleado = $_POST['idEmpleado'];
    
    $idUsuario = $_SESSION['idUsuario'];
    
    $empleadoE = new \entidad\Empleado();
    $empleadoE ->setIdEmpleado($idEmpleado);
    $empleadoE ->setIdEps($idEps);
    $empleadoE ->setIdArl($idArl);
    $empleadoE ->setIdUsuarioModificacion($idUsuario);
    $empleadoE ->setIdCargo($idCargo);
    
    $empleadoM = new \modelo\Empleado($empleadoE);
    $idEmpleado = $empleadoM ->modificar();
    
    $tecnicoE = new \entidad\Tecnico();
    $tecnicoE ->setIdTecnico($idTecnico);
    $tecnicoE ->setEstado($estado);
    $tecnicoE ->setIdUsuarioModificacion($idUsuario);
    
    $tecnicoM = new \modelo\Tecnico($tecnicoE);
    $tecnicoM -> modificar();
    
} catch (Exception $ex) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $ex ->getMessage();
}
echo json_encode($retorno);
?>