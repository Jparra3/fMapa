<?php
require_once '../Servidor.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $body = json_decode(file_get_contents("php://input"), true);
}
$parametros = $body;
$servidor = new Servidor();
$retorno = $servidor->registrarCliente($parametros);
echo json_encode($retorno);