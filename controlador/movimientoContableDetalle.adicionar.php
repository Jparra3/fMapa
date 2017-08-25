<?php
session_start();
require_once '../entidad/MovimientoContable.php';
require_once '../entidad/MovimientoContableDetalle.php';
require_once '../modelo/MovimientoContableDetalle.php';
require_once '../../Seguridad/entidad/Tercero.php';

$retorno = array('exito'=>1, 'mensaje'=>'El movimiento contable se guardó correctamente.');

try {
    $idMovimientoContable = $_POST["idMovimientoContable"];
    $data = $_POST["data"];
    $idUsuario = $_SESSION["idUsuario"];
    $contador = 0;
    while ($contador < count($data)) {
        $terceroE = new \entidad\Tercero();
        $terceroE->setIdTercero($data[$contador]["idTercero"]);
        
        $idMovimientoContableDetalle = $data[$contador]["idMovimientoDetalle"];
        
        $movimientoContableE = new \entidad\MovimientoContable();
        $movimientoContableE->setIdMovimientoContable($idMovimientoContable);
        
        $movimientoContableDetalleE = new \entidad\MovimientoContableDetalle();
        $movimientoContableDetalleE->setIdCentroCosto(1);//Sin definir
        $movimientoContableDetalleE->setIdCuentaContable(1);//Sin definir
        $movimientoContableDetalleE->setIdMovimientoContableDetalle($idMovimientoContableDetalle);
        $movimientoContableDetalleE->setMovimientoContable($movimientoContableE);
        $movimientoContableDetalleE->setTercero($terceroE);
        $movimientoContableDetalleE->setValor($data[$contador]["valor"]);
        $movimientoContableDetalleE->setNota($data[$contador]["nota"]);
        $movimientoContableDetalleE->setIdUsuarioCreacion($idUsuario);
        $movimientoContableDetalleE->setIdUsuarioModificacion($idUsuario);
        
        $movimientoContableDetalleM = new \modelo\MovimientoContableDetalle($movimientoContableDetalleE);
        if($idMovimientoContableDetalle == "" || $idMovimientoContableDetalle == null || $idMovimientoContableDetalle == "null"){
            $movimientoContableDetalleM->adicionar();
        }else{
            $movimientoContableDetalleM->modificar();
        }        
        $contador++;
    }
} catch (Exception $e) {
    $retorno['exito'] =0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
