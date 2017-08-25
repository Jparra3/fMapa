<?php
require_once '../entidad/Cliente.php';
require_once '../modelo/Cliente.php';

$clienteE = new \entidad\Cliente();

$tipo = $_GET["tipo"];

$clienteM = new \modelo\Cliente($clienteE);
$cliente = $_GET['term'];
echo json_encode($clienteM->buscarCliente($cliente, 10, $tipo));
?>