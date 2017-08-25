<?php
require_once '../Servidor.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $body = json_decode(file_get_contents("php://input"), true);
}

$idCliente = $body["idCliente"];
$idZona = $body["idZona"];
$direcciones = $body["direcciones"];
$idFormaPago = $body["idFormaPago"];
$telefono = $body["telefono"];
$idBarrio = $body["idBarrio"];
$direccionEnvio = $body["direccionEnvio"];
$valorDomicilio = $body["valorDomicilio"];
$productos = $body["productos"];

$parametros = array("idCliente"=>$idCliente
                    , "idZona"=>$idZona
                    , "direcciones"=>$direcciones
                    , "idFormaPago"=>$idFormaPago
                    , "telefono"=>$telefono
                    , "idBarrio"=>$idBarrio
                    , "direccionEnvio"=>$direccionEnvio
                    , "valorDomicilio"=>$valorDomicilio
                    , "productos"=>$productos);

$servidor = new Servidor();
$retorno = $servidor->insertarPedido($parametros);
echo json_encode($retorno);