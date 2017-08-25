<?php
require_once '../entidad/Producto.php';
require_once '../modelo/Producto.php';

$productoE = new \entidad\Producto();

$productoM = new \modelo\Producto($productoE);
$producto = $_GET['term'];
$idProductos = $_GET['idProductos'];
$compuesto = $_GET['compuesto'];
$servicio = $_GET['servicio'];
echo json_encode($productoM->buscarProducto($producto, $idProductos, $compuesto,$servicio));
?>