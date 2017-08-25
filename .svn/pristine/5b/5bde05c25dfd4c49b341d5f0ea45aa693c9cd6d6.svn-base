<?php
require_once '../entidad/Producto.php';
require_once '../modelo/Producto.php';

$productoM = new \modelo\Producto(new \entidad\Producto());
$producto = $_GET['term'];
echo json_encode($productoM->buscarProductoCompuesto($producto, 10));
?>