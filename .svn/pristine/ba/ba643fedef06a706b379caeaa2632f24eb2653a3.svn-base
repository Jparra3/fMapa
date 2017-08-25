<?php
session_start();
require_once '../entidad/Producto.php';
require_once '../modelo/Producto.php';

$retorno = array('exito'=>1,'mensaje'=>'La información del producto se guardó correctamente');

try {
    $idProducto = $_POST['idProducto'];
    $imagen = $_POST['imagen'];
    $producto = $_POST['producto'];
    $tangible = $_POST['tangible'];
    $idUnidadMedida = $_POST['idUnidadMedida'];
    $estado = $_POST['estado'];
    $idEmpresaUnidadNegocio = $_POST["idEmpresaUnidadNegocio"];
    $idLineaProducto = $_POST['idLineaProducto'];
    $manejaInventario = $_POST["manejaInventario"];
    
    $valorEntrada =  str_replace ('.', '', $_POST["valorEntrada"]);
    $valorEntrada =  str_replace (',', '.', $valorEntrada);
    
    $valorSalida =  str_replace ('.', '', $_POST["valorSalida"]);
    $valorSalida =  str_replace (',', '.', $valorSalida);
    
    $productoServicio = $_POST["productoServicio"];
    $productoSerial = $_POST["productoSerial"];
    $codigo = $_POST["codigo"];
    $productoComposicion = $_POST["productoComposicion"];
    $maximo = $_POST["maximo"];
    $minimo = $_POST["minimo"];
    $idUsuario = $_SESSION['idUsuario'];
    $modulo = $_POST['modulo'];
    
    $productoE = new \entidad\Producto();
    $productoE->setIdProducto($idProducto);
    $productoE->setImagen($imagen);
    $productoE->setProducto($producto);
    $productoE->setTangible($tangible);
    $productoE->setIdUnidadMedida($idUnidadMedida);
    $productoE->setEstado($estado);
    $productoE->setIdEmpresaUnidadNegocio($idEmpresaUnidadNegocio);
    $productoE->setIdLineaProducto($idLineaProducto);
    $productoE->setManejaInventario($manejaInventario);
    $productoE->setValorEntrada($valorEntrada);
    $productoE->setValorSalida($valorSalida);
    $productoE->setProductoServicio($productoServicio);
    $productoE->setProductoSerial($productoSerial);
    $productoE->setCodigo($codigo);
    $productoE->setProductoComposicion($productoComposicion);
    $productoE->setMaximo($maximo);
    $productoE->setMinimo($minimo);
    $productoE->setIdUsuarioCreacion($idUsuario);
    $productoE->setIdUsuarioModificacion($idUsuario);
    
    $productoM = new \modelo\Producto($productoE);
    $productoM->modificar();
    
    if(strpos($imagen, 'temporal') != false){
        $extension = substr(strrchr($imagen, "."), 1);
        $imagenNueva = "../../archivos/PublicServices/productos/$idProducto.$extension";
        rename($imagen, $imagenNueva);
        $direccionIpServidor = $_SERVER['SERVER_ADDR'];
        $puerto = $_SERVER['SERVER_PORT'];
        $imagenNueva = '/archivos/PublicServices/productos/'.$idProducto.'.'.$extension;

        $productoE = new \entidad\Producto();
        $productoE->setIdProducto($idProducto);
        $productoE->setImagen($imagenNueva);

        $productoM = new \modelo\Producto($productoE);
        $productoM->actualizarNombreImagen();
    }
} catch (Exception $e) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
