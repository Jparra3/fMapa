<?php
session_start();
require_once '../entidad/Producto.php';
require_once '../entidad/ProductoExistencia.php';
require_once '../modelo/ProductoExistencia.php';

$retorno = array('exito'=>1, 'mensaje'=>'Las bodegas se guardaron correctamente.');

try {
    $idProducto = $_POST["idProducto"];
    $data = $_POST["data"];
    $idUsuario = $_SESSION["idUsuario"];
    $contador = 0;
    while ($contador < count($data)) {
        
        $idProductoExistencia = $data[$contador]["idProductoExistencia"];
        
        $productoE = new \entidad\Producto();
        $productoE->setIdProducto($idProducto);
        
        $productoExistenciaE = new \entidad\ProductoExistencia();
        $productoExistenciaE->setIdProductoExistencia($idProductoExistencia);
        $productoExistenciaE->setProducto($productoE);
        $productoExistenciaE->setIdBodega($data[$contador]["idBodega"]);
        $productoExistenciaE->setMaximo($data[$contador]["maximo"]);
        $productoExistenciaE->setMinimo($data[$contador]["minimo"]);
        $productoExistenciaE->setIdUsuarioCreacion($idUsuario);
        $productoExistenciaE->setIdUsuarioModificacion($idUsuario);
        
        $productoExistenciaM = new \modelo\ProductoExistencia($productoExistenciaE);
        if($idProductoExistencia == "" || $idProductoExistencia == null || $idProductoExistencia == "null"){
            $productoExistenciaM->adicionar();
        }else{
            $productoExistenciaM->modificar();
        }        
        $contador++;
    }
} catch (Exception $e) {
    $retorno['exito'] =0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
