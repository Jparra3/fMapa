<?php

session_start();
date_default_timezone_set('America/Bogota');
require_once '../../Seguridad/entidad/Tercero.php';
require_once '../../Seguridad/modelo/Tercero.php';
require_once '../../Seguridad/entidad/TerceroDireccion.php';
require_once '../../Seguridad/modelo/TerceroDireccion.php';
require_once '../../Seguridad/entidad/Sucursal.php';
require_once '../../Seguridad/modelo//Sucursal.php';
require_once '../../Seguridad/modelo/TerceroDireccion.php';
require_once '../entidad/Cliente.php';
require_once '../modelo/Cliente.php';
require_once '../entidad/Zona.php';
require_once '../modelo/Zona.php';
require_once '../entidad/Regional.php';
require_once '../entidad/OrdenTrabajo.php';
require_once '../modelo/OrdenTrabajo.php';
require_once '../entidad/OrdenTrabajoCliente.php';
require_once '../modelo/OrdenTrabajoCliente.php';
require_once '../entidad/Transaccion.php';
require_once '../modelo/Transaccion.php';
require_once '../entidad/OrdenTrabajoTecnico.php';
require_once '../modelo/OrdenTrabajoTecnico.php';
require_once '../entidad/OrdenTrabajoVehiculo.php';
require_once '../modelo/OrdenTrabajoVehiculo.php';
require_once '../entidad/TipoDocumento.php';
require_once '../modelo/TipoDocumento.php';
require_once '../entidad/ClienteServicio.php';
require_once '../modelo/ClienteServicio.php';
require_once '../entidad/Producto.php';
require_once '../modelo/Producto.php';
require_once '../entidad/Vendedor.php';
require_once '../entidad/Pedido.php';
require_once '../modelo/Pedido.php';
$retorno = array("exito" => 1, "mensaje" => "Se registró la información correctamente", "data" => null);
try {
    $idUsuario = $_SESSION['idUsuario'];
    $arrInformacion = json_decode($_POST['arrInformacion']);
    for ($i = 0; $i < count($arrInformacion); $i++) {

        //Tercero
        $arrTercero = validarExistenciaTercero($arrInformacion[$i]->nit, $arrInformacion[$i]->direccionSucursal);
        if ($arrTercero != null) {
            $idTercero = $arrTercero[0]['idTercero'];
            $arrCliente = validarExistenciaCliente($arrTercero['idSucursal']);

            if ($arrCliente != null) {

                $productoE = new \entidad\Producto();
                $idProductoCompuesto = validarExistenciaProductoServicio($arrInformacion[$i]->codigoServicio, $productoE);

                $clienteE = new \entidad\Cliente();
                $idCliente = $arrCliente[0]['idCliente'];
                $clienteE->setIdCliente($idCliente);

                $idMunicipio = $arrCliente[0]['idMunicipio'];
                $vendedorE = new \entidad\Vendedor();
                $vendedorE->setIdVendedor(-1);//SIN DEFINIR

                //PEDIDO
                $pedidoE = new \entidad\Pedido();
                $pedidoE->setCliente($clienteE);
                $pedidoE->setIdPedidoEstado(3); //FINALIZADO
                $pedidoE->setVendedor($vendedorE); //sin definir
                $pedidoE->setIdZona(1); //LA DEL VENDEDOR
                $pedidoE->setTipoPedido(1); //PEDIDO INSTALACION
                $pedidoE->setFecha($arrInformacion[$i]->fechaInicio);
                $pedidoE->setNota("");
                $pedidoE->setIdClienteServicio("null");
                $pedidoE->setIdUsuarioCreacion($idUsuario);
                $pedidoE->setIdUsuarioModificacion($idUsuario);

                $pedidoM = new \modelo\Pedido($pedidoE);
                $pedidoM->adicionar();
                $idPedido = $pedidoM->obtenerMaximo();

                //ORDEN DE TARABAJO
                $idTipoDocumento = 4; //INSTALACION
                $tipoDocumentoE = new \entidad\TipoDocumento();
                $tipoDocumentoE->setIdTipoDocumento($idTipoDocumento);

                $tipoDocumentoM = new \modelo\TipoDocumento($tipoDocumentoE);
                $numeroTipoDocumento = $tipoDocumentoM->obtenerNumero();


                $ordenadorE = new \entidad\Ordenador();
                $ordenadorE->setIdOrdenador(1);

                $clienteServicioE = new \entidad\ClienteServicio();
                $clienteServicioE->setIdClienteServicio('null');

                $ordenTrabajoE = new \entidad\OrdenTrabajo();
                $ordenTrabajoE->setClienteServicio($clienteServicioE);
                $ordenTrabajoE->setIdTipoDocumento($idTipoDocumento);
                $ordenTrabajoE->setNumero($numeroTipoDocumento);
                $ordenTrabajoE->setEstado("TRUE");
                $ordenTrabajoE->setObservacion("");
                $ordenTrabajoE->setOrdenador($ordenadorE);
                $ordenTrabajoE->setIdMunicipio($idMunicipio);
                $ordenTrabajoE->setFechaInicio($arrInformacion[$i]->fechaInicio);
                $ordenTrabajoE->setFechaFin($arrInformacion[$i]->fechaFin);
                $ordenTrabajoE->setTipoOrdenTrabajo(1); //INSTALACION
                $ordenTrabajoE->setIdEstadoOrdenTrabajo(3); //INSTALADO
                $ordenTrabajoE->setIdUsuarioCreacion($idUsuario);
                $ordenTrabajoE->setIdUsuarioModificacion($idUsuario);

                $ordenTrabajoM = new \modelo\OrdenTrabajo($ordenTrabajoE);
                $ordenTrabajoM->adicionar();
                $idOrdenTrabajo = $ordenTrabajoM->obtenerMaximo();

                $tipoDocumentoE = new \entidad\TipoDocumento();
                $tipoDocumentoE->setIdTipoDocumento($idTipoDocumento);
                $tipoDocumentoE->setNumero($numeroTipoDocumento);

                $tipoDocumentoM = new \modelo\TipoDocumento($tipoDocumentoE);
                $tipoDocumentoM->actualizarNumero();

                //ORDEN TRABAJO CLIENTE
                $ordenTrabajoE = new \entidad\OrdenTrabajo();
                $ordenTrabajoE->setIdOrdenTrabajo($idOrdenTrabajo);

                $clienteE = new \entidad\Cliente();
                $clienteE->setIdCliente($idCliente);

                $pedidoE = new \entidad\Pedido();
                $pedidoE->setIdPedido($idPedido);

                $ordenTrabajoClienteE = new \entidad\OrdenTrabajoCliente();
                $ordenTrabajoClienteE->setOrdenTrabajo($ordenTrabajoE);
                $ordenTrabajoClienteE->setCliente($clienteE);
                $ordenTrabajoClienteE->setIdProductoComposicion($idProductoCompuesto);
                $ordenTrabajoClienteE->setPedido($pedidoE);
                $ordenTrabajoClienteE->setEstado("TRUE");
                $ordenTrabajoClienteE->setIdUsuarioCreacion($idUsuario);
                $ordenTrabajoClienteE->setIdUsuarioModificacion($idUsuario);

                $ordenTrabajoClienteM = new \modelo\OrdenTrabajoCliente($ordenTrabajoClienteE);
                $ordenTrabajoClienteM->adicionar();

                $idOrdenTrabajoCliente = $ordenTrabajoClienteM->obtenerMaximo();

                //ORDEN DE TRABAJO CLIENTE
                $ordenTrabajoE = new \entidad\OrdenTrabajo();
                $ordenTrabajoE->setIdOrdenTrabajo($idOrdenTrabajo);

                $tecnicoE = new \entidad\Tecnico();
                $tecnicoE->setIdTecnico(1); //

                $ordenTrabajoTecnicoE = new \entidad\OrdenTrabajoTecnico();
                $ordenTrabajoTecnicoE->setOrdenTrabajo($ordenTrabajoE);
                $ordenTrabajoTecnicoE->setTecnico($tecnicoE);
                $ordenTrabajoTecnicoE->setPrincipal("TRUE");
                $ordenTrabajoTecnicoE->setEstado("TRUE");
                $ordenTrabajoTecnicoE->setIdUsuarioCreacion($idUsuario);
                $ordenTrabajoTecnicoE->setIdUsuarioModificacion($idUsuario);

                $ordenTrabajoTecnicoM = new \modelo\OrdenTrabajoTecnico($ordenTrabajoTecnicoE);
                $ordenTrabajoTecnicoM->adicionar();

                //ORDEN TRABAJO VEHICULO
                $vehiculoE = new \entidad\Vehiculo();
                $vehiculoE->setIdVehiculo(2);//Camioneta servicio

                $ordenTrabajoVehiculoE = new \entidad\OrdenTrabajoVehiculo();
                $ordenTrabajoVehiculoE->setOrdenTrabajo($ordenTrabajoE);
                $ordenTrabajoVehiculoE->setVehiculo($vehiculoE);
                $ordenTrabajoVehiculoE->setEstado("TRUE");
                $ordenTrabajoVehiculoE->setIdUsuarioCreacion($idUsuario);
                $ordenTrabajoVehiculoE->setIdUsuarioModificacion($idUsuario);

                $ordenTrabajoVehiculoM = new \modelo\OrdenTrabajoVehiculo($ordenTrabajoVehiculoE);
                $ordenTrabajoVehiculoM->adicionar();

                //TRANSACCION
                //Se obtiene la información vinculada al tipo de documento
                $tipoDocumentoECons = new \entidad\TipoDocumento();
                $tipoDocumentoECons->setIdTipoDocumento($idTipoDocumento);

                $tipoDocumentoMCons = new \modelo\TipoDocumento($tipoDocumentoECons);
                $arrTipoDocum = $tipoDocumentoMCons->consultar();

                $numeroTipoDocumento = $tipoDocumentoMCons->obtenerNumero();

                //Se obtiene la información del tipo de documento para guardar la transacción
                if (count($arrTipoDocum) == 0) {
                    throw new Exception("El tipo de documento enviado no existe");
                } else {
                    foreach ($arrTipoDocum as $fila) {
                        $idNaturaleza = $fila["idNaturaleza"];
                        $idOficina = $fila["idOficina"];
                    }
                }


                $terceroE = new \entidad\Tercero();
                $terceroE->setIdTercero($idTercero);

                $transaccionE = new \entidad\Transaccion();
                $transaccionE->setIdTipoDocumento($idTipoDocumento);
                $transaccionE->setTercero($terceroE);
                $transaccionE->setIdTransaccionEstado(1);//Activo
                $transaccionE->setIdOficina(12);//OFICINAS NEIVA
                $transaccionE->setIdNaturaleza(14);//ORDEN TRABAJO
                $transaccionE->setNumeroTipoDocumento($numeroTipoDocumento);
                $transaccionE->setNota("");
                $transaccionE->setFecha($arrInformacion[$i]->fechaInicio);
                $transaccionE->setFechaVencimiento($arrInformacion[$i]->fechaFin);
                $transaccionE->setValor(0);
                $transaccionE->setIdUsuarioCreacion($idUsuario);
                $transaccionE->setIdUsuarioModificacion($idUsuario);

                $transaccionM = new \modelo\Transaccion($transaccionE);
                $transaccionM->adicionar();
                $idTransaccion = $transaccionM->obtenerMaximo();


                $tipoDocumentoE = new \entidad\TipoDocumento();
                $tipoDocumentoE->setIdTipoDocumento($idTipoDocumento);
                $tipoDocumentoE->setNumero($numeroTipoDocumento);

                $tipoDocumentoM = new \modelo\TipoDocumento($tipoDocumentoE);
                $tipoDocumentoM->actualizarNumero();

                //CLIENTE SERVICIO
                $clienteE = new \entidad\Cliente();
                $clienteE->setIdCliente($idCliente);

                $ordenTrabajoClienteE = new \entidad\OrdenTrabajoCliente();
                $ordenTrabajoClienteE->setIdOrdenTrabajoCliente($idOrdenTrabajoCliente);

                $clienteServicioE = new \entidad\ClienteServicio();
                $clienteServicioE->setCliente($clienteE);
                $clienteServicioE->setValor($arrInformacion[$i]->valor);
                $clienteServicioE->setOrdenTrabajoCliente($ordenTrabajoClienteE);
                $clienteServicioE->setIdProductoComposicion($idProductoCompuesto);
                $clienteServicioE->setFechaInicial(date('Y-m-d', strtotime($arrInformacion[$i]->fechaInicio)));
                $clienteServicioE->setFechaFinal("null");
                $clienteServicioE->setIdTransaccion($idTransaccion);
                $clienteServicioE->setIdEstadoClienteServicio(1);
                $clienteServicioE->setEstado("TRUE");
                $clienteServicioE->setIdUsuarioCreacion($idUsuario);
                $clienteServicioE->setIdUsuarioModificacion($idUsuario);

                $clienteServicioM = new \modelo\ClientServicio($clienteServicioE);
                $clienteServicioM->adicionar();

                $retorno["idClienteServicio"] = $clienteServicioM->obtenerMaximo();
            }
        }
    }
} catch (Exception $ex) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $ex->getMessage();
}

/* * *********************** Validar existencia tercero ********************** */

function validarExistenciaTercero($nit, $sucursal) {
    $terceroE = new \entidad\Tercero();
    $terceroE->setNit($nit);

    $terceroM = new \modelo\Tercero($terceroE);
    $arrTercero = $terceroM->consultar();
    if (count($arrTercero) > 0) {
        $terceroDireccionE = new \entidad\TerceroDireccion();
        $terceroDireccionE -> setTerceroDireccion($sucursal);
        $terceroDireccionE -> setIdTercero($arrTercero[0]['idTercero']);
        
        $terceroDireccionM = new \modelo\TerceroDireccion($terceroDireccionE);
        $idTerceroDireccion = $terceroDireccionM -> consultar();
        
        $sucursalE = new \entidad\Sucursal();
        $sucursalE -> setIdTerceroDireccion($idTerceroDireccion[0]['idTerceroDireccion']);
        
        $sucursalM = new \modelo\Sucursal($sucursalE);
        $arrSucursal = $sucursalM -> consultar();
        
        $arrTercero['idSucursal'] = $arrSucursal[0]['idSucursal'];
        return $arrTercero;
    } else {
        return null;
    }
}

/* * ************* Validar existencia cliente ******************************** */

function validarExistenciaCliente($idSucursal) {
    $sucursalE = new \entidad\Sucursal();
    $sucursalE->setIdSucursal($idSucursal);

    $clienteE = new \entidad\Cliente();
    $clienteE->setSucursal($sucursalE);

    $clienteM = new \modelo\Cliente($clienteE);
    $arrCliente = $clienteM->consultar();
    if (count($arrCliente) > 0) {
        return $arrCliente;
    } else {
        return null;
    }
}

/* * *************************** Validar existencia producto ****************** */

function validarExistenciaProductoServicio($codigo, $productoE) {
    $idProducto = null;
    $productoE->setCodigo($servicio);
    $productoE->setProductoComposicion('true');
    $productoM = new \modelo\Producto($productoE);
    $arrInformacionProducto = $productoM->consultar();
    if (count($arrInformacionProducto) > 0) {
        $idProducto = $arrInformacionProducto[0]['idProducto'];
    }
    return $idProducto;
}

echo json_encode($retorno);
?>

