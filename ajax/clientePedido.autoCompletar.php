<?php
require_once '../entidad/Cliente.php';
require_once '../modelo/Cliente.php';

$clienteE = new \entidad\Cliente();

$clienteM = new \modelo\Cliente($clienteE);
$cliente = $_GET['term'];
echo json_encode($clienteM->buscarClientePedido($cliente));
?>