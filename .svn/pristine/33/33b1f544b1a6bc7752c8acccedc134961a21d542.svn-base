<?php

session_start();
ini_set('max_execution_time', 1200);
require_once '../entidad/Producto.php';
require_once '../modelo/Producto.php';
require_once '../entidad/ProductoComposicion.php';
require_once '../modelo/ProductoComposicion.php';
$retorno = array("exito" => 1, "mensaje" => "Se registró la información correctamente", "data" => null);

try {
    $idUsuario = $_SESSION['idUsuario'];
    $arrInformacion = json_decode($_POST['arrInformacion']);
    for ($i = 0; $i < count($arrInformacion); $i++) {
        $productoE = new \entidad\Producto();
        
        $estadoProducto = validarExistenciaProducto($arrInformacion[$i]->codigoProductoComposicion, $productoE);
        if($estadoProducto != null){
            
            $productoE = new \entidad\Producto();
            $estadoServicio = validarExistenciaProductoServicio($arrInformacion[$i]->nombreServicio, $productoE);
            if($estadoServicio != null){
                $productoComposicionE = new \entidad\ProductoComposicion();
                $productoComposicionE->setIdProductoCompuesto($estadoServicio);
                $productoComposicionE->setIdProductoCompone($estadoProducto);
                $productoComposicionE->setCantidad($arrInformacion[$i]->cantidad);
                $productoComposicionE->setIdUsuarioCreacion($idUsuario);
                $productoComposicionE->setIdUsuarioModificacion($idUsuario);

                $productoComposicionM = new \modelo\ProductoComposicion($productoComposicionE);
                $productoComposicionM -> adicionar();
            }
        }
    }
} catch (Exception $ex) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $ex->getMessage();
}
/***************************Validar existencia producto***********************/
function validarExistenciaProducto($codigo, $productoE){
    $idProducto = null;
    $productoE->setCodigo($codigo);
    $productoE->setProductoComposicion('false');
    $productoM = new \modelo\Producto($productoE);
    $arrInformacionProducto = $productoM->consultar();
    if(count($arrInformacionProducto)>0){
        $idProducto = $arrInformacionProducto[0]['idProducto'];
    }
    return $idProducto;
}

function validarExistenciaProductoServicio($servicio, $productoE){
    $idProducto = null;
    $productoE->setProducto($servicio);
    $productoE->setProductoComposicion('true');
    $productoM = new \modelo\Producto($productoE);
    $arrInformacionProducto = $productoM->consultar();
    if(count($arrInformacionProducto)>0){
        $idProducto = $arrInformacionProducto[0]['idProducto'];
    }
    return $idProducto;
}
echo json_encode($retorno);
?>

