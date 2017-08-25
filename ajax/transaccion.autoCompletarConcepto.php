<?php
require_once '../entidad/Concepto.php';
require_once '../modelo/Concepto.php';

$codigoTipoNaturaleza = $_GET["codigoTipoNaturaleza"];

$conceptoE = new \entidad\Concepto();

$conceptoM = new \modelo\Concepto($conceptoE);
$concepto = $_GET['term'];
echo json_encode($conceptoM->buscarConcepto($concepto, $codigoTipoNaturaleza));
?>