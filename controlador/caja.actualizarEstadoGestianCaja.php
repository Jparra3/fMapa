<?php
require_once '../entidad/Caja.php';
require_once '../modelo/Caja.php';
require_once '../entidad/CajaFormatoImpresion.php';
$retorno = array("exito"=>1, "mensaje"=>"Se adicionó la informacion correctamente.", "idCaja"=>null);
try{
    //POST
    $idCaja = $_POST['idCaja'];
    $estado = $_POST['estado'];
    
    $cajaE = new \entidad\Caja();
    $cajaE ->setIdCaja($idCaja);
    $cajaE ->setEstado($estado);
    
    $cajaM = new \modelo\Caja($cajaE);
    $cajaM->actualizarEstado();
    
} catch (Exception $ex) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $ex->getMessage();
}
echo json_encode($retorno);
?>