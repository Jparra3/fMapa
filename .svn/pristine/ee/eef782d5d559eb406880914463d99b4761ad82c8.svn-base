<?php
require_once '../entidad/Zona.php';
require_once '../modelo/Zona.php';

$zonaE = new \entidad\Zona();

$zonaM = new \modelo\Zona($zonaE);
$zona = $_GET['term'];
echo json_encode($zonaM->buscarZona($zona));
?>