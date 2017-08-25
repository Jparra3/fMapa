<?php
require_once '../entidad/Tecnico.php';
require_once '../modelo/Tecnico.php';

$tecnicoE = new \entidad\Tecnico();

$tecnicoM = new \modelo\Tecnico($tecnicoE);
$tecnico = $_GET['term'];
echo json_encode($tecnicoM->buscarTecnico($tecnico));
?>