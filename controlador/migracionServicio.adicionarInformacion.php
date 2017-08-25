<?php

session_start();
ini_set('max_execution_time', 1200);
require_once '../entidad/Producto.php';
require_once '../modelo/Producto.php';
require_once '../entidad/ProductoImpuesto.php';
require_once '../modelo/ProductoImpuesto.php';
require_once '../entidad/ProductoCodigoBarras.php';
require_once '../modelo/ProductoCodigoBarras.php';
require_once '../entidad/UnidadMedida.php';
require_once '../modelo/UnidadMedida.php';
require_once '../entidad/LineaProducto.php';
require_once '../modelo/LineaProducto.php';
$retorno = array("exito" => 1, "mensaje" => "Se registró la información correctamente", "data" => null);

try {
    $idUsuario = $_SESSION['idUsuario'];
    $arrInformacion = json_decode($_POST['arrInformacion']);
    for ($i = 0; $i < count($arrInformacion); $i++) {
        $productoE = new \entidad\Producto();
        
        $estadoProducto = validarExistenciaProducto($arrInformacion[$i]->codigoServicio, $productoE);
        if($estadoProducto == null){
            $productoE->setProducto($arrInformacion[$i]->nombreServicio);
            $productoE->setCodigo($arrInformacion[$i]->codigoServicio);
            $productoE->setTangible("false");
            $productoE->setEstado("TRUE");
            $productoE->setIdUsuarioCreacion($idUsuario);
            $productoE->setIdUsuarioModificacion($idUsuario);
            $productoE->setIdEmpresaUnidadNegocio(37);//
            $productoE->setImagen("null");
            $productoE->setProductoServicio("true");
            $productoE->setIdUnidadMedida(3);//UNIDAD
            $productoE->setIdLineaProducto(2);//INTERNET BANDA ANCHA
            $productoE->setManejaInventario("true");            
            $productoE->setValorEntrada(0);
            $productoE->setValorSalida(str_replace(".", "", $arrInformacion[$i]->valorServicio));       
            $productoE->setProductoSerial("false");
            $productoE->setProductoComposicion("true");
            
            $productoM = new \modelo\Producto($productoE);
            $idProducto = $productoM->adicionar();
            
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
    $productoM = new \modelo\Producto($productoE);
    $arrInformacionProducto = $productoM->consultar();
    if(count($arrInformacionProducto)>0){
        $idProducto = $arrInformacionProducto[0]['idProducto'];
    }
    return $idProducto;
}
echo json_encode($retorno);
?>

