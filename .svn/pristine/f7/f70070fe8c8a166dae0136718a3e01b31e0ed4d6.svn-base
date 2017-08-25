<?php
require_once '../entidad/Ordenador.php';
require_once '../modelo/Ordenador.php';

$ordenadorE = new \entidad\Ordenador();

$ordenadorM = new \modelo\Ordenador($ordenadorE);
$ordenador = $_GET['term'];
echo json_encode($ordenadorM->buscarOrdenador($ordenador));
?>