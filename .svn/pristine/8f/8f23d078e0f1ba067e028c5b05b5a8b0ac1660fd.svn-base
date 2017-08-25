<?php
session_start();
ini_set('max_execution_time', 1200);
require_once '../../Seguridad/entidad/ParametroAplicacion.php';
require_once '../../Seguridad/modelo/ParametroAplicacion.php';
require_once '../entidad/TipoDocumento.php';
require_once '../modelo/TipoDocumento.php';
require_once '../entidad/Producto.php';
require_once '../modelo/Producto.php';
require_once '../entidad/Transaccion.php';
require_once '../modelo/Transaccion.php';
require_once '../entidad/TransaccionConcepto.php';
require_once '../modelo/TransaccionConcepto.php';
require_once '../entidad/TransaccionProveedor.php';
require_once '../modelo/TransaccionProveedor.php';
require_once '../entidad/TipoDocumento.php';
require_once '../modelo/TipoDocumento.php';
require_once '../../Seguridad/entidad/Tercero.php';
require_once '../../Seguridad/modelo/Tercero.php';
require_once '../entidad/Proveedor.php';
require_once '../../Seguridad/entidad/Sucursal.php';
require_once '../../Seguridad/modelo/Sucursal.php';
require_once '../../Seguridad/entidad/TerceroDireccion.php';
require_once '../../Seguridad/modelo/TerceroDireccion.php';
require_once '../../Seguridad/entidad/TerceroTelefono.php';
require_once '../../Seguridad/modelo/TerceroTelefono.php';
require_once '../../Seguridad/entidad/TerceroEmail.php';
require_once '../../Seguridad/modelo/TerceroEmail.php';
require_once '../entidad/Bodega.php';
require_once '../modelo/Bodega.php';
require_once '../entidad/TransaccionProducto.php';
require_once '../modelo/TransaccionProducto.php';
require_once '../entidad/Proveedor.php';
require_once '../modelo/Proveedor.php';

$retorno = array("exito" => 1, "mensaje" => "Se registró la información correctamente", "data" => null);

try {
    $contadorSecuencia = 1;
    $idUsuario = $_SESSION['idUsuario'];
    $arrInformacion = json_decode($_POST['arrInformacion']);
    
    
    /*------------------------Obtener el tipo de documento para inventario-----------------------*/
    $tipoDocumentoE = new \entidad\TipoDocumento();
    $tipoDocumentoE->setCodigo("001");//COMPRAS - INVENTARIO ENTRADA
    
    $tipoDocumentoM = new \modelo\TipoDocumento($tipoDocumentoE);
    $arrRetorno = $tipoDocumentoM->consultar();
            
    $idNaturaleza = $arrRetorno[0]["idNaturaleza"];
    $idTipoDocumento = $arrRetorno[0]["idTipoDocumento"];
    

    for ($i = 0; $i < count($arrInformacion); $i++) {

        $arrInfoProducto = validarProductoExistencia($arrInformacion[$i]->codigo, $arrInformacion[$i]->bodega);
        if ($arrInfoProducto != null) {
            $tipoDocumentoE = new \entidad\TipoDocumento();
            $tipoDocumentoE->setIdTipoDocumento($idTipoDocumento); //COMPRAS

            $tipoDocumentoM = new \modelo\TipoDocumento($tipoDocumentoE);
            $numeroTipoDocumento = $tipoDocumentoM->obtenerNumero();

            if($arrInformacion[$i]->cantidad == "" || $arrInformacion[$i]->cantidad == null || $arrInformacion[$i]->cantidad == "null"){
                $arrInformacion[$i]->cantidad = 0;
            }
            
            /*--------------------Obtener la información del proveedor---------------------------------------------*/
            if($arrInformacion[$i]->nitProveedor == "" || $arrInformacion[$i]->nitProveedor == null || $arrInformacion[$i]->nitProveedor == "null"){
                $idTercero = obtenerParametroAplicacion("TERCERO DEFECTO");
                $idProveedor = obtenerParametroAplicacion("PROVEEDOR DEFECTO");
                $idOficina = obtenerParametroAplicacion("OFICINA DEFECTO");
            }else{
                //Consultamos proveedor
                $idTercero = validarExistenciaProveedor($arrInformacion[$i]->nitProveedor, $arrInformacion[$i]->proveedor);

                $arrTercero = explode("-", $idTercero);
                $idTercero = $arrTercero[0];
                $idProveedor = $arrTercero[1];
                
                $idOficina = obtenerParametroAplicacion("OFICINA DEFECTO");
            }

            $terceroE = new \entidad\Tercero();
            $terceroE->setIdTercero($idTercero);

            $transaccionE = new \entidad\Transaccion();
            $transaccionE->setIdTipoDocumento($idTipoDocumento);
            $transaccionE->setTercero($terceroE);
            $transaccionE->setIdTransaccionEstado(1);
            $transaccionE->setIdOficina($idOficina);
            $transaccionE->setIdNaturaleza($idNaturaleza); 
            $transaccionE->setNumeroTipoDocumento($numeroTipoDocumento);
            $transaccionE->setNota("");
            $transaccionE->setFecha("NOW()");
            $transaccionE->setFechaVencimiento("NOW()");
            $transaccionE->setValor(0);
            $transaccionE->setIdUsuarioCreacion($idUsuario);
            $transaccionE->setIdUsuarioModificacion($idUsuario);

            $transaccionM = new \modelo\Transaccion($transaccionE);
            $transaccionM->adicionar();
            $idTransaccion = $transaccionM->obtenerMaximo();
            $transaccionE->setIdTransaccion($idTransaccion);
            
            
            $conceptoE = new \entidad\Concepto();
            $conceptoE->setIdTipoDocumento($idTipoDocumento);
            $conceptoM = new \modelo\Concepto($conceptoE, $conexion);
            $arrConcepto = $conceptoM->obtenerIdConcepto();//OBTENGO EL ID CONCEPTO A PARTIR DEL ID TIPO DE DOCUMENTO DE FACTURACIÓN
            if ($arrConcepto["exito"] == 0) {
                throw new Exception("ERROR -> No puede haber más de un concepto con un mismo tipo de documento -> id: " . $idTipoDocumento);
            }
            $idConcepto = $arrConcepto["idConcepto"];
            
            /* --------------------INSERTO EN TRANSACCION CONCEPTO---------------------- */
            $conceptoE = new \entidad\Concepto();
            $conceptoE->setIdConcepto($idConcepto);

            $transaccionE = new \entidad\Transaccion();
            $transaccionE->setIdTransaccion($idTransaccion);

            $transaccionConceptoE = new \entidad\TransaccionConcepto();
            $transaccionConceptoE->setConcepto($conceptoE);
            $transaccionConceptoE->setTransaccion($transaccionE);
            $transaccionConceptoE->setValor(0);
            $transaccionConceptoE->setSaldo(0);
            $transaccionConceptoE->setNota(null);
            $transaccionConceptoE->setIdUsuarioCrecion($idUsuario);
            $transaccionConceptoE->setIdUsuarioModificacion($idUsuario);

            $transaccionConceptoM = new \modelo\TransaccionConcepto($transaccionConceptoE);
            $transaccionConceptoM->adicionar();
            $idTransaccionConcepto = $transaccionConceptoM->obtenerMaximo();

            /** **************** Transaccion proveedor *************************** */
            $transaccionProveedorE = new \entidad\TransaccionProveedor();
            $transaccionProveedorE->setIdTransaccion($idTransaccion);
            $transaccionProveedorE->setIdProveedor($idProveedor);
            $transaccionProveedorE->setIdUsuarioCreacion($idUsuario);
            $transaccionProveedorE->setIdUsuarioModicacion($idUsuario);

            $transaccionProveedorM = new \modelo\TransaccionProveedor($transaccionProveedorE);
            $transaccionProveedorM->adicionar();

            /*             * ******************* Actualizar  numero de documento ************** */
            $tipoDocumentoE = new \entidad\TipoDocumento();
            $tipoDocumentoE->setIdTipoDocumento($idTipoDocumento); //COMPRAS
            $tipoDocumentoE->setNumero($numeroTipoDocumento);

            $tipoDocumentoM = new \modelo\TipoDocumento($tipoDocumentoE);
            $tipoDocumentoM->actualizarNumero();

            /*             * *****************   Transaccion producto  ************************* */
            $transaccionProductoE = new \entidad\TransaccionProducto();
            $transaccionProductoE->setTransaccion($transaccionE);
            $transaccionProductoE->setIdProducto($arrInfoProducto[0]['idProducto']);
            $transaccionProductoE->setCantidad($arrInformacion[$i]->cantidad);
            $transaccionProductoE->setValorUnitarioEntrada($arrInfoProducto[0]['valorEntrada']);
            $transaccionProductoE->setValorUnitarioSalida($arrInfoProducto[0]['valorSalida']);
            $transaccionProductoE->setValorUnitaEntraConImpue($arrInfoProducto[0]['valorEntraConImpue']);
            $transaccionProductoE->setValorUnitaSalidConImpue($arrInfoProducto[0]['valorSalidConImpue']);
            $transaccionProductoE->setIdBodega($arrInfoProducto['idBodega']);
            $transaccionProductoE->setSecuencia($contadorSecuencia);
            $transaccionProductoE->setIdUnidadMedidaPresentacion("NULL");
            $transaccionProductoE->setNota("");
            $transaccionProductoE->setSerial($arrInformacion[$i]->serial);
            $transaccionProductoE->setSerialInterno($arrInformacion[$i]->serialInterno);
            $transaccionProductoE->setIdUsuarioCreacion($idUsuario);
            $transaccionProductoE->setIdUsuarioModificacion($idUsuario);

            $transaccionProductoM = new \modelo\TransaccionProducto($transaccionProductoE);
            $transaccionProductoM->adicionar();

            $contadorSecuencia++;
        }
    }
} catch (Exception $ex) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $ex->getMessage();
}
/* * ******************** Validar existencia proveedor ********************** */

function validarExistenciaProveedor($nit, $proveedor) {
    $idUsuario = $_SESSION['idUsuario'];
    $idTercero = null;
    
    /*--------------------Validar Existencia del tercero-----------------*/
    $terceroE = new \entidad\Tercero();
    $terceroE->setNit($nit);
    
    $terceroM = new \modelo\Tercero($terceroE);
    $arrTercero = $terceroM->consultar();
    
    if($arrTercero != "" && $arrTercero != null && $arrTercero != "null"){
        $idTercero = $arrTercero[0]["idTercero"];
        $idTerceroDireccion = $arrTercero[0]["idTerceroDireccion"];
        $idTerceroTelefono = $arrTercero[0]["idTerceroTelefono"];
        $idTerceroEmail = $arrTercero[0]["idTerceroEmail"];
    }
    $proveedorE = new \entidad\Proveedor();
    $proveedorM = new \modelo\Proveedor($proveedorE);
    $arrProveedor = $proveedorM->consultarProveedorTercero($nit);
    if (count($arrProveedor) > 0) {
        $idTercero = $arrProveedor[0]['idTercero'] . "-" . $arrProveedor[0]['idProveedor'];
    } else {
        if($idTercero == "" || $idTercero == null || $idTercero == "null"){
            $terceroE = new \entidad\Tercero();
            $terceroE->setNit($nit);
            $terceroE->setTercero($proveedor);
            $terceroE->setLogo("");
            $terceroE->setDigitoVerificacion(1);
            $terceroE->setIdTipoRegimen(4);
            $terceroE->setIdEmpresa(1);
            $terceroE->setIdUsuarioCreacion($idUsuario);
            $terceroE->setIdUsuarioModificacion($idUsuario);
            $terceroE->setEstado("TRUE");

            $terceroM = new \modelo\Tercero($terceroE);
            $terceroM->adicionar();
            $idTercero = $terceroM->obtenerMaximo();

            //Tercero direccion
            $terceroDireccionE = new \entidad\TerceroDireccion();
            $terceroDireccionE->setIdTercero($idTercero);
            $terceroDireccionE->setIdMunicipio(1122); //sin definir
            $terceroDireccionE->setTerceroDireccion("SIN DEFINIR");
            $terceroDireccionE->setEstado("'TRUE'");
            $terceroDireccionE->setPrincipal("true");

            $terceroDireccionM = new \modelo\TerceroDireccion($terceroDireccionE);
            $idTerceroDireccion = $terceroDireccionM->adicionar();

            //Tercero telefono
            $terceroTelefonoE = new \entidad\TerceroTelefono();
            $terceroTelefonoE->setIdTercero($idTercero);
            $terceroTelefonoE->setIdTipoTelefono(1); //CELULAR
            $terceroTelefonoE->setTerceroTelefono(0);
            $terceroTelefonoE->setContacto($proveedor);
            $terceroTelefonoE->setEstado("TRUE");
            $terceroTelefonoE->setPrincipal("true");

            $terceroTelefonoM = new \modelo\TerceroTelefono($terceroTelefonoE);
            $idTerceroTelefono = $terceroTelefonoM->adicionar();

            //Tercero email
            $terceroEmailE = new \entidad\TerceroEmail();
            $terceroEmailE->setIdTercero($idTercero);
            $terceroEmailE->setTerceroEmail("SINDEFINIR@SINDEFINIR.com");
            $terceroEmailE->setEstado("TRUE");
            $terceroEmailE->setPrincipal("false");

            $terceroEmailM = new \modelo\TerceroEmail($terceroEmailE);
            $idTerceroEmail = $terceroEmailM->adicionar();
        }

        //Tercero sucursal
        $sucursalE = new \entidad\Sucursal();
        $sucursalE->setIdTercero($idTercero);
        $sucursalE->setSucursal("SIN DEFINIR");
        $sucursalE->setIdTerceroDireccion($idTerceroDireccion);
        $sucursalE->setIdTerceroTelefono($idTerceroTelefono);
        $sucursalE->setIdTerceroMail($idTerceroEmail);
        $sucursalE->setIdUsuarioCreacion($idUsuario);
        $sucursalE->setIdUsuarioModificacion($idUsuario);
        $sucursalE->setEstado("TRUE");
        $sucursalE->setPrincipal("TRUE");

        $sucursalM = new \modelo\Sucursal($sucursalE);
        $idSucursal = $sucursalM->adicionar();
        $sucursalE->setIdSucursal($idSucursal);

        //Proveedor
        $proveedorE = new \entidad\Proveedor();
        $proveedorE->setSucursal($sucursalE);
        $proveedorE->setIdProveedorEstado(1);
        $proveedorE->setFechaCreacion("NOW()");
        $proveedorE->setFechaModificacion("NOW()");
        $proveedorE->setIdUsuarioCreacion($idUsuario);
        $proveedorE->setIdUsuarioModificacion($idUsuario);

        $proveedorM = new \modelo\Proveedor($proveedorE);
        $idProveedor = $proveedorM->adicionar();

        $idTercero = $idTercero . "-" . $idProveedor;
    }
    return $idTercero;
}

/* * ********************* Validar producto ********************************** */

function validarProductoExistencia($codigo, $bodega) {
    $idUsuario = $_SESSION['idUsuario'];
    $productoE = new \entidad\Producto();
    $productoE->setCodigo($codigo);

    $productoM = new \modelo\Producto($productoE);
    $arrProducto = $productoM->consultar();
    if (count($arrProducto) < 1) {
        return null;
    }
    $bodegaE = new \entidad\Bodega();
    $bodegaE->setBodega($bodega);

    $bodegaM = new \modelo\Bodega($bodegaE);
    $arrBodega = $bodegaM->consultar();
    $idBodega = $arrBodega[0]['idBodega'];
    if (count($arrBodega) < 1) {
        return null;
    }
    $arrProducto['idBodega'] = $idBodega;

    return $arrProducto;
}
function obtenerParametroAplicacion($parametroAplicacion){
    $parametroAplicacionE = new \entidad\ParametroAplicacion();
    $parametroAplicacionE->setParametroAplicacion($parametroAplicacion);
    
    $parametroAplicacionM = new \modelo\ParametroAplicacion($parametroAplicacionE);
    $retornoData = $parametroAplicacionM->consultar();
    
    return $retornoData[0]["valor"];
}
echo json_encode($retorno);
?>