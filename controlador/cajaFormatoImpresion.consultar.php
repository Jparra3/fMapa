<?php
session_start();
require_once '../entidad/CajaFormatoImpresion.php';
require_once '../entidad/Caja.php';
require_once '../entidad/FormatoImpresion.php';
$retorno = array("exito"=>1, "mesanje"=>"", "data"=>null, "numeroRegistros"=>0);
try{
    $idTipoNaturaleza = $_POST['idTipoNaturaleza'];
    $codigoTipoNaturaleza = $_POST['codigoTipoNaturaleza'];
    $idCajaFormatoImpresion = $_POST['idCajaFormatoImpresion'];
    $idCaja = $_POST['idCaja'];
    $estado = $_POST['estado'];
    
    $formatoImpresionE = new \entidad\FormatoImpresion();
    $formatoImpresionE->setIdTipoNaturaleza($idTipoNaturaleza);
    $formatoImpresionE->setCodigoTipoNaturaleza($codigoTipoNaturaleza);
    
    $cajaE = new \entidad\Caja();
    $cajaE ->setIdCaja($idCaja);
    
    $cajaFormatoImpresionE = new \entidad\CajaFormatoImpresion();
    $cajaFormatoImpresionE ->setIdCajaFormatoImpresion($idCajaFormatoImpresion);
    $cajaFormatoImpresionE ->setCaja($cajaE);
    $cajaFormatoImpresionE ->setEstado($estado);
    $cajaFormatoImpresionE ->setFormatoImpresion($formatoImpresionE);
    
    $cajaFormatoImpresionM = new \modelo\CajaFormatoImpresion($cajaFormatoImpresionE);
    $retorno['data'] = $cajaFormatoImpresionM ->consultar();
    $retorno['numeroRegistros'] = $cajaFormatoImpresionM -> conexion -> obtenerNumeroRegistros();
    
} catch (Exception $ex) {
    $retorno['exito'] = 0;
    $retorno['mesanje'] = $ex -> getMessage();
}
echo json_encode($retorno);
?>