<?php
require_once '../entidad/Producto.php';
require_once '../modelo/Producto.php';

$productoE = new \entidad\Producto();

$productoM = new \modelo\Producto($productoE);
$producto = $_GET['term'];
echo json_encode($productoM->buscarProductoCodigo($producto));
?>