<?php

session_start();
set_time_limit(0);
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
    
    /*-----------------INSERTO EL ENCABEZADO DEL INVENTARIO EN TRANSACCION----------------*/
    /* ---PARAMETRIZAR--- */
    $idTipoDocumento = 39; //----------INVENTARIO INICIAL----------
    $idOficina = 12;
    $idNaturaleza = 7;
    
    $idConcepto = 10;
    $idTercero = 0;
    $idProveedor = 0;
    $idTransaccionEstado = 1;
    $nota = 'INVENTARIO INICIAL';
    $fecha = "NOW()";
    $fechaVencimiento = "NOW()";
    $idUsuario = $_SESSION["idUsuario"];
    $documentoExterno = null;
    $totalCompra = 0;
    $dataFormasPago = null; //json_decode($_POST["dataFormasPago"]);
    $fecha = "NOW()";
    /* ---PARAMETRIZAR--- */
    
    include 'migracionTransaccion.adicionar.php';
    if($arrRetorno["exito"] == 0){
        throw new Exception("ERROR AL GUARDAR LA TRANSACCION (INVENTARIO) -> ".$arrRetorno["mensaje"]);
    }
    $idTransaccion = $arrRetorno["idTransaccion"];
    /*------------------------------------------------------------------------------------*/
    
    $contador = 0;
    $arrDataProductos = array();
    
    $idUsuario = $_SESSION['idUsuario'];
    $idEmpresaUnidadNegocio = 38;//"Servicios Internet y Redes"
    $arrInformacion = json_decode($_POST['arrInformacion']);
    for ($i = 0; $i < count($arrInformacion); $i++) {        
        $productoE = new \entidad\Producto();
        
        $idProducto = validarExistenciaProducto($arrInformacion[$i]->codigo, $productoE);
        if($idProducto == null){
            $productoE->setProducto($arrInformacion[$i]->producto);
            $productoE->setCodigo($arrInformacion[$i]->codigo);
            $productoE->setTangible($arrInformacion[$i]->tangible);
            $productoE->setEstado("TRUE");
            $productoE->setIdUsuarioCreacion($idUsuario);
            $productoE->setIdUsuarioModificacion($idUsuario);
            $productoE->setIdEmpresaUnidadNegocio($idEmpresaUnidadNegocio);
            $productoE->setImagen("null");
            $productoE->setProductoServicio("TRUE");
            $unidadMedidaE = new \entidad\UnidadMedida();
            $idUnidadMedida = validarExistenciaUnidadMedida($arrInformacion[$i]->unidadMedida, $unidadMedidaE);
            $productoE->setIdUnidadMedida($idUnidadMedida);
            $lineaProductoE = new \entidad\LineaProducto();
            $idLineaProducto = validarExistenciaLineaProducto($arrInformacion[$i]->lineaProducto, $lineaProductoE);
            $productoE->setIdLineaProducto($idLineaProducto);
            $productoE->setManejaInventario($arrInformacion[$i]->manejaInventario);
            
            
            
            
            $valorEntradaImpuesto = str_replace(".", "", $arrInformacion[$i]->valorCompraConIva);
            $valorSalidaImpuesto = str_replace(".", "", $arrInformacion[$i]->valorSalidaConIva);
            $impuesto = $arrInformacion[$i]->valorImpuesto;
            
            if($impuesto != "" && $impuesto != null && $impuesto != "null"){
                $valorEntrada = $valorEntradaImpuesto / (($impuesto / 100) + 1); //SE CALCULA LA BASE
                $valorSalida = $valorSalidaImpuesto / (($impuesto / 100) + 1); //SE CALCULA LA BASE
            }else{
                $valorEntrada = $valorEntradaImpuesto;
                $valorSalida = $valorSalidaImpuesto;
            }
            
            $productoE->setValorEntrada($valorEntrada);
            $productoE->setValorSalida($valorSalida);
            if($arrInformacion[$i]->serial != null && $arrInformacion[$i]->serial != 'null' && $arrInformacion[$i]->serial != "" && $arrInformacion[$i]->serial != "undefined"){
                $productoE->setProductoSerial("true");
            }else{
                $productoE->setProductoSerial("false");
            }
            $productoE->setProductoComposicion("false");
            
            $productoM = new \modelo\Producto($productoE);
            $productoM->adicionar();
            $idProducto = $productoM->obtenerMaximo();
            
            if($arrInformacion[$i]->valorImpuesto != "" &&  $arrInformacion[$i]->valorImpuesto != null &&  $arrInformacion[$i]->valorImpuesto != "null"){
                $idImpuesto = 1;//IVA
                $valor = $arrInformacion[$i]->valorImpuesto;
            }else{
                $idImpuesto = 3;//SIN IMPUESTO
                $valor = 0;
            }
            
            /*************************Adicionar producto impuesto*************/
            $productoImpuestoE = new \entidad\ProductoImpuesto();
            $productoImpuestoE->setIdProducto($idProducto);
            $productoImpuestoE->setIdImpuesto($idImpuesto);
            $productoImpuestoE->setValor($valor);
            $productoImpuestoE->setIdUsuarioCreacion($idUsuario);
            $productoImpuestoE->setIdUsuarioModificacion($idUsuario);

            $productoImpuestoM = new \modelo\ProductoImpuesto($productoImpuestoE);
            $productoImpuestoM->adicionar();
            
            /*----------------------Actualizar el valor de los impuestos----------------------*/
            $productoE = new \entidad\Producto();
            $productoE->setIdProducto($idProducto);
            $productoE->setValorEntraConImpue($valorEntradaImpuesto);
            $productoE->setValorSalidConImpue($valorSalidaImpuesto);
            $productoE->setValorEntrada($valorEntrada);
            $productoE->setValorSalida($valorSalida);

            $productoM = new \modelo\Producto($productoE);
            $productoM->actualizarImpuestos();
            
        }
        
        $arrDataProductos[$contador]["idProducto"] = $idProducto;
        $arrDataProductos[$contador]["cantidad"] = str_replace(',', '.', $arrInformacion[$i]->cantidad);
        $arrDataProductos[$contador]["valorUnitarioEntrada"] = $arrInformacion[$i]->valorCompraConIva;
        $arrDataProductos[$contador]["valorUnitarioSalida"] = $arrInformacion[$i]->valorSalidaConIva;
        $arrDataProductos[$contador]["idBodega"] = 1;
        
        $contador++;
    }
    
    
    /*-------------------GUARDO EL DETALLE DEL INVENTARIO EN TRANSACCION_PRODUCTO-------------------*/
    $data = $arrDataProductos;
    include 'migracionTransaccionProducto.adicionar.php';
    if($arrRetorno["exito"] == 0){
        throw new Exception("ERROR AL GUARDAR LA TRANSACCION PRODUCTO (DETALLE DEL INVENTARIO) -> ".$arrRetorno["mensaje"]);
    }
    /*----------------------------------------------------------------------------------------------*/
    
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

/*************************Validar existencia Unidad medida********************/
function validarExistenciaUnidadMedida($unidadMedida, $unidadMedidaE){
    $idUsuario = $_SESSION['idUsuario'];
    $idUnidadMedida = null;
    $unidadMedidaE->setUnidadMedida($unidadMedida);
    $unidadMedidaM = new \modelo\UnidadMedida($unidadMedidaE);
    
    $arrUnidadMedida = $unidadMedidaM->consultar();
    if(count($arrUnidadMedida)>0){
        $idUnidadMedida = $arrUnidadMedida[0]['idUnidadMedida'];
    }else{
        $unidadMedidaE->setUnidadMedida($unidadMedida);
        $unidadMedidaE->setIdUsuarioCreacion($idUsuario);
        $unidadMedidaE->setIdUsuarioModificacion($idUsuario);
        $unidadMedidaM = new \modelo\UnidadMedida($unidadMedidaE);
        $idUnidadMedida = $unidadMedidaM->adicionar();
    }
    return $idUnidadMedida;
}

/**********************Validar existencia línea producto**********************/
function validarExistenciaLineaProducto($lineaProducto, $lineaProductoE){
    $idUsuario = $_SESSION['idUsuario'];
    $idLineaProducto = null;
    $lineaProductoE -> setLineaProducto($lineaProducto);
    $lineaProductoM = new \modelo\LineaProducto($lineaProductoE);
    $arrLineaProducto = $lineaProductoM->consultar();
    if(count($arrLineaProducto)>0){
        $idLineaProducto = $arrLineaProducto[0]['idLineaProducto'];
    }else{
        $lineaProductoE -> setLineaProducto($lineaProducto);
        $lineaProductoE -> setIdLineaProductoPadre("null");
        $lineaProductoE -> setIdUsuarioCreacion($idUsuario);
        $lineaProductoE -> setIdUsuarioModificacion($idUsuario);
        
        $lineaProductoM = new \modelo\LineaProducto($lineaProductoE);
        $idLineaProducto = $lineaProductoM->adicionar();
    }
    return $idLineaProducto;
}
echo json_encode($retorno);
?>