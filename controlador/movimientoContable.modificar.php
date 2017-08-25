<?php
session_start();
require_once '../entidad/MovimientoContable.php';
require_once '../modelo/MovimientoContable.php';
require_once '../entidad/TipoDocumento.php';
require_once '../modelo/TipoDocumento.php';


$retorno = array('exito'=>1, 'mensaje'=>'El movimiento contable se guardó correctamente.', 'idMovimientoContable'=>'');

try {
    $idMovimientoContable = $_POST["idMovimientoContable"];
    $fecha = $_POST["fecha"];
    $nota = $_POST["nota"];
    $idUsuario = $_SESSION["idUsuario"];
    
    $movimientoContableE = new \entidad\MovimientoContable();
    $movimientoContableE->setIdMovimientoContable($idMovimientoContable);
    $movimientoContableE->setFecha($fecha);
    $movimientoContableE->setNota($nota);
    $movimientoContableE->setIdUsuarioModificacion($idUsuario);
    
    $movimientoContableM = new \modelo\MovimientoContable($movimientoContableE);
    $movimientoContableM->modificar();
} catch (Exception $e) {
    $retorno['exito'] =0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
