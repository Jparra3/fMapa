<?php
require_once '../entidad/Producto.php';
require_once '../modelo/Producto.php';

require_once '../entidad/Cliente.php';
require_once '../modelo/Cliente.php';

$productoE = new \entidad\Producto();

$productoM = new \modelo\Producto($productoE);
$producto = $_GET['term'];
$idProductos = $_GET['idProductos'];
$compuesto = $_GET['compuesto'];
$idCliente = $_GET['idCliente'];

if($idCliente != "" && $idCliente != null && $idCliente != "null"){
    $clienteE = new \entidad\Cliente();
    $clienteE->setIdCliente($idCliente);
    $clienteM = new \modelo\Cliente($clienteE);
    $facturaCliente = $clienteM->validarFacturaRegimen();
}

echo json_encode($productoM->buscarProducto($producto, $idProductos, $compuesto, null, 10 , $facturaCliente));
?>