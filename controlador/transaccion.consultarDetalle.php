<?php
session_start();
require_once '../entidad/Transaccion.php';
require_once '../modelo/Transaccion.php';
require_once '../entidad/TipoDocumento.php';
require_once '../modelo/TipoDocumento.php';
require_once '../../Seguridad/entidad/Tercero.php';

$retorno = array('exito'=>1, 'mensaje'=>'', 'data'=>null, 'numeroRegistros'=>0);

try {
    $fecha["inicio"] = $_POST["fechaInventarioInicio"];
    $fecha["fin"] = $_POST["fechaInventarioFin"];
    $idTipoDocumento = $_POST["idTipoDocumento"];
    $idProducto = $_POST["idProducto"];
    $idBodega = $_POST["idBodega"];
    $idOficina = $_POST["idOficina"];
    $idProveedor = $_POST["idProveedor"];
    $idLineaProducto = $_POST["idLineaProducto"];
    $bodega = $_POST["bodega"];
    $idTransaccionEstado = $_POST["idTransaccionEstado"];
    
    $parametros['idProveedor'] = $idProveedor;
    $parametros['idLineaProducto'] = $idLineaProducto;
    $parametros['idBodega'] = $idBodega;
    $parametros['idProducto'] = $idProducto;
    $parametros['bodega'] = $bodega;

    $transaccionE = new \entidad\Transaccion();
    $transaccionE->setIdTipoDocumento($idTipoDocumento);
    $transaccionE->setTercero(new \entidad\Tercero());
    $transaccionE->setIdTransaccionEstado($idTransaccionEstado);
    $transaccionE->setIdOficina($idOficina);
    $transaccionE->setFecha($fecha);
    $transaccionE->setFechaVencimiento($fechaVencimiento);
    
    $transaccionM = new \modelo\Transaccion($transaccionE);
    $retorno["data"] = $transaccionM->consultarDetalleTransaccion($parametros);
    $retorno['numeroRegistros'] = $transaccionM->conexion->obtenerNumeroRegistros();
} catch (Exception $e) {
    $retorno['exito'] =0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
