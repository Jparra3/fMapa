<?php
session_start();
require_once '../entidad/ProductoComposicion.php';
require_once '../modelo/ProductoComposicion.php';

$retorno = array('exito'=>1, 'mensaje'=>'La información del producto se guardó correctamente.');

try {
    $idProducto = $_POST["idProducto"];
    $data = $_POST["data"];
    $idUsuario = $_SESSION["idUsuario"];
    $contador = 0;
    while ($contador < count($data)) {
        $idProductoComposicion = $data[$contador]["idProductoComposicion"];
        
        $productoComposicionE = new \entidad\ProductoComposicion();
        $productoComposicionE->setIdProductoComposicion($idProductoComposicion);
        $productoComposicionE->setIdProductoCompuesto($idProducto);
        $productoComposicionE->setIdProductoCompone($data[$contador]["idProducto"]);
        $productoComposicionE->setCantidad($data[$contador]["cantidad"]);
        $productoComposicionE->setEstado("TRUE");
        $productoComposicionE->setIdUsuarioCreacion($idUsuario);
        $productoComposicionE->setIdUsuarioModificacion($idUsuario);
        
        $productoComposicionM = new \modelo\ProductoComposicion($productoComposicionE);
        if($idProductoComposicion == "" || $idProductoComposicion == null || $idProductoComposicion == "null"){
            $productoComposicionM->adicionar();
        }else{
            $productoComposicionM->modificar();
        }        
        $contador++;
    }
} catch (Exception $e) {
    $retorno['exito'] =0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
