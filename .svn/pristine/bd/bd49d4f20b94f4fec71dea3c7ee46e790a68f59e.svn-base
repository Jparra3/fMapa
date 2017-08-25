<?php
/*
 * Autor: Alexis Mauricio Plaza Vargas
 * Fecha : 12 Mayo 2016
 * Nota : Se modificó la consulta para filtrar la información de tipo naturaleza con id = 4  -- VENTAS - FORMAS DE PAGO
 */
require_once '../entidad/TransaccionCruce.php';
require_once '../modelo/TransaccionCruce.php';

$retorno = array ('exito'=>1, 'mensaje'=> '', 'data'=>null, 'numeroRegistros'=>'');

try {
    $idUsuario = $_POST["idUsuario"];
    $fecha = $_POST["fecha"];
    
    $transaccionCruceE = new \entidad\TransaccionCruce();
    $transaccionCruceE->setIdUsuarioCreacion($idUsuario);
    $transaccionCruceE->setFechaCreacion($fecha);
    
    $transaccionCruceM = new \modelo\TransaccionCruce($transaccionCruceE);
    $retorno['data'] = $transaccionCruceM->consultarEstadoCaja();
    $retorno['numeroRegistros']= $transaccionCruceM->conexion->obtenerNumeroRegistros();
} catch (Exception $e) {
    $retorno['exito']= 0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
