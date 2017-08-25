<?php
require_once '../entidad/Transaccion.php';
require_once '../modelo/Transaccion.php';
$retorno = array("exito" => 1, "mensaje"=> "", "data" => null, "numeroRegistros" => 0);
try{
    
    $fechaInicial = $_POST["fechaInicial"];
    $fechaFinal = $_POST["fechaFinal"];
    $idConcepto = $_POST["idConcepto"];
    $idTercero = $_POST["idTercero"];
    
    //Creamos un array para enviarlo por parametro de entrada
    $arrParametros[0] = $fechaInicial;
    $arrParametros[1] = $fechaFinal;
    $arrParametros[2] = $idConcepto;
    $arrParametros[3] = $idTercero;
    
    $transaccionE = new \entidad\Transaccion();
    $transaccionM = new \modelo\Transaccion($transaccionE);
    $retorno["data"] = $transaccionM -> consuPagoAsoci($arrParametros);
    $retorno["numeroRegistros"] = $transaccionM -> conexion -> obtenerNumeroRegistros();
    
} catch (Exception $ex) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $ex -> getMessage();
}
echo json_encode($retorno);
?>