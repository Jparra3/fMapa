<?php
session_start();
require_once '../entidad/Empleado.php';
require_once '../modelo/Empleado.php';
require_once '../entidad/Ordenador.php';
require_once '../modelo/Ordenador.php';
$retorno = array("exito"=>1, "mensaje"=> "Se adicionó la información correctamente.");
try{
    
    $idOrdenador = $_POST['idOrdenador'];
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
    
    $ordenadorE = new \entidad\Ordenador();
    $ordenadorE ->setIdOrdenador($idOrdenador);
    $ordenadorE ->setEstado($estado);
    $ordenadorE ->setIdUsuarioModificacion($idUsuario);
    
    $ordenadorM = new \modelo\Ordenador($ordenadorE);
    $ordenadorM -> modificar();
    
} catch (Exception $ex) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $ex ->getMessage();
}
echo json_encode($retorno);
?>