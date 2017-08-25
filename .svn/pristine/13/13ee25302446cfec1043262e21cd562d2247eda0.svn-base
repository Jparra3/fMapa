<?php
require_once '../../Seguridad/entidad/Municipio.php';
require_once '../../Seguridad/modelo/Municipio.php';

$municipioE = new \entidad\Municipio();

$municipioM = new \modelo\Municipio($municipioE);
$municipio = $_GET['term'];

echo json_encode($municipioM->buscarMunicipioDepartamento($municipio));
?>