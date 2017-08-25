<?php
session_start();
require_once '../entidad/ProductoCodigoBarras.php';
require_once '../modelo/ProductoCodigoBarras.php';

$retorno = array('exito'=>1, 'mensaje'=>'Los códigos de barra se guardaron correctamente.');

try {
    $idProducto = $_POST["idProducto"];
    $data = $_POST["data"];
    $idUsuario = $_SESSION["idUsuario"];
    $contador = 0;
    while ($contador < count($data)) {
        
        $idProductoCodigoBarras = $data[$contador]["idProductoCodigoBarra"];
        
        $productoCodigoBarrasE = new \entidad\ProductoCodigoBarras();
        $productoCodigoBarrasE->setIdProductoCodigoBarras($idProductoCodigoBarras);
        $productoCodigoBarrasE->setIdProducto($idProducto);
        $productoCodigoBarrasE->setCodigoBarras($data[$contador]["codigoBarra"]);
        $productoCodigoBarrasE->setIdUsuarioCreacion($idUsuario);
        $productoCodigoBarrasE->setIdUsuarioModificacion($idUsuario);
        
        $productoCodigoBarrasM = new \modelo\ProductoCodigoBarras($productoCodigoBarrasE);
        if($idProductoCodigoBarras == "" || $idProductoCodigoBarras == null || $idProductoCodigoBarras == "null"){
            $productoCodigoBarrasM->adicionar();
        }else{
            $productoCodigoBarrasM->modificar();
        }        
        $contador++;
    }
} catch (Exception $e) {
    $retorno['exito'] =0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
