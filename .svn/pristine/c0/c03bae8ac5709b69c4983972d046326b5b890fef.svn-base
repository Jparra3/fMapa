<?php
require_once '../entidad/Caja.php';
require_once '../modelo/Caja.php';

$prefijo = $_GET['term'];

$cajaE = new \entidad\Caja();
$cajaM = new \modelo\Caja($cajaE);
echo json_encode($cajaM->consultarCaja($prefijo));
?>