<?php
require_once '../entidad/TipoDocumento.php';
require_once '../modelo/TipoDocumento.php';

$retorno = array("exito"=>1, "mensaje"=>"", "idTipoDocumento"=>"");

try {
    $codigo = $_POST["codigo"]; //Es el codigo del parametro para la forma de pago por defecto
    $tipoDocumentoM = new \modelo\TipoDocumento(new \entidad\TipoDocumento());
    $retorno["idTipoDocumento"] = $tipoDocumentoM->obtenerIdFormaPagoDefecto($codigo);
} catch (Exception $exc) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $exc->getMessage();
}
echo json_encode($retorno);