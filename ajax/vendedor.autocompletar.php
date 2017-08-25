<?php
require_once '../entidad/Vendedor.php';
require_once '../modelo/Vendedor.php';

$vendedorE = new \entidad\Vendedor();

$vendedorM = new \modelo\Vendedor($vendedorE);
$vendedor = $_GET['term'];
echo json_encode($vendedorM->buscarVendedor($vendedor));
?>