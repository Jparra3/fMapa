<?php
require_once '../entidad/Transaccion.php';
require_once '../modelo/Transaccion.php';

$transaccionE = new \entidad\Transaccion();

$transaccionM = new \modelo\Transaccion($transaccionE);
$tercero = $_GET['term'];
echo json_encode($transaccionM->buscarTercero($tercero, 10));
?>