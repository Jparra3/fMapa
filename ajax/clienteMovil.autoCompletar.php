<?php
require_once '../entidad/Cliente__.php';
require_once '../modelo/Cliente__.php';

$clienteE = new \entidad\Cliente();

$clienteM = new \modelo\Cliente($clienteE);
$cliente = $_GET['term'];
echo json_encode($clienteM->buscarCliente($cliente));
?>