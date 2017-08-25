<?php
require_once '../entidad/Cliente.php';
require_once '../modelo/Cliente.php';
$retorno = array("exito"=>1, "mensaje"=>"", "data"=>null, "numeroRegistros"=>0);
try{
    $nit = $_POST["nit"];
    $clienteE =  new \entidad\Cliente();
    $clienteM = new \modelo\Cliente($clienteE);
    $retorno["data"] = $clienteM ->validarExistenciaCliente($nit);
    $retorno["numeroRegistros"] = $clienteM -> conexion -> obtenerNumeroRegistros();
} catch (Exception $ex){
    $retorno["exito"]=0;
    $retorno["mensaje"] = $ex ->getMessage();
}
echo json_encode($retorno);