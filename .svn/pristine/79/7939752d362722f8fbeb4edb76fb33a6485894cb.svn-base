<?php

require_once '../entidad/Ordenador.php';
require_once '../modelo/Ordenador.php';
require_once '../entidad/Empleado.php';
require_once '../modelo/Empleado.php';

$retorno = array('exito' => 1, 'mensaje' => '', 'data' => null, 'numeroRegistros' => '');

try {

    $idPersona = $_POST['idPersona'];
    $idOrdenador = $_POST['idOrdenador'];
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

        $ordenadorE = new \entidad\Ordenador();
        $ordenadorE->setEmpleado($empleadoE);
        $ordenadorE->setIdOrdenador($idOrdenador);
        $ordenadorE->setEstado($estado);

        $ordenadorM = new \modelo\Ordenador($ordenadorE);
        $retorno['data'] = $ordenadorM->consultar();
        $retorno['numeroRegistros'] = $ordenadorM->conexion->obtenerNumeroRegistros();
    }
} catch (Exception $e) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>