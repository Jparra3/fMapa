<?php

require_once '../entidad/Tecnico.php';
require_once '../modelo/Tecnico.php';
require_once '../entidad/Empleado.php';
require_once '../modelo/Empleado.php';

$retorno = array('exito' => 1, 'mensaje' => '', 'data' => null, 'numeroRegistros' => '');

try {

    $idPersona = $_POST['idPersona'];
    $idTecnico = $_POST['idTecnico'];
    $estado = $_POST['estado'];

    $personaE = new \entidad\Persona();
    $personaE->setIdPersona($idPersona);

    $empleadoE = new \entidad\Empleado();
    $empleadoE->setPersona($personaE);

    $empleadoM = new \modelo\Empleado($empleadoE);
    $arrEmpleado = $empleadoM->consultar();
    $idEmpleado = $arrEmpleado[0]['idEmpleado'];
    if ($idEmpleado != null && $idEmpleado != 'null' && $idEmpleado != "") {

        $empleadoE->setIdEmpleado($arrEmpleado[0]['idEmpleado']);

        $tecnicoE = new \entidad\Tecnico();
        $tecnicoE->setEmpleado($empleadoE);
        $tecnicoE->setIdTecnico($idTecnico);
        $tecnicoE->setEstado($estado);

        $tecnicoM = new \modelo\Tecnico($tecnicoE);
        $retorno['data'] = $tecnicoM->consultar();
        $retorno['numeroRegistros'] = $tecnicoM->conexion->obtenerNumeroRegistros();
    }
} catch (Exception $e) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>