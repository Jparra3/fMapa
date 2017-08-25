<?php
session_start();
require_once '../entidad/Cliente.php';
require_once '../modelo/Cliente.php';
$retorno = array("exito"=>1, "mensaje"=>"", "data"=>null, "numeroRegistros"=>0);
try{
    $idCliente = $_POST['idCliente'];
    
    $clienteE = new \entidad\Cliente();
    $clienteE -> setIdCliente($idCliente);
    
    $clienteM = new \modelo\Cliente($clienteE);
    $retorno["data"] = $clienteM ->consultarHistoricoCliente();
    $retorno["numeroRegistros"] = $clienteM -> conexion -> obtenerNumeroRegistros();
} catch (Exception $ex) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $ex -> getMessage();
}
echo json_encode($retorno);
?>
