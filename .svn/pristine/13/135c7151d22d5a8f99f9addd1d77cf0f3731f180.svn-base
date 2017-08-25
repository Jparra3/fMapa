<?php
require_once '../Servidor.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $body = json_decode(file_get_contents("php://input"), true);
}
$usuario = $body["usuario"];
$contrasenia = $body["contrasenia"];

$parametros = array("usuario"=>$usuario,"contrasenia"=>$contrasenia);
$servidor = new Servidor();
$retorno = $servidor->validarCliente($parametros);
echo json_encode($retorno);
