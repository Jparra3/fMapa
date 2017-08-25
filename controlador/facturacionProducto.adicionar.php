<?php

session_start();
set_time_limit(0);

require_once '../entidad/Transaccion.php';
require_once '../modelo/Transaccion.php';
require_once '../entidad/TransaccionProducto.php';
require_once '../modelo/TransaccionProducto.php';
require_once '../entidad/TransaccionProductoImpuesto.php';
require_once '../modelo/TransaccionProductoImpuesto.php';
require_once '../entidad/TransaccionImpuesto.php';
require_once '../modelo/TransaccionImpuesto.php';
require_once '../../Seguridad/entidad/ParametroAplicacion.php';
require_once '../../Seguridad/modelo/ParametroAplicacion.php';

$retorno = array('exito' => 1, 'mensaje' => 'La facturacion se guardó correctamente.');

try {
    
    //---------Se obtiene el tipo de actualizacion de costo-------------
    $parametroAplicacionE = new \entidad\ParametroAplicacion();
    $parametroAplicacionE->setCodigo('TIPO-COSTO');
    $parametroAplicacionM = new \modelo\ParametroAplicacion($parametroAplicacionE);
    $arrParametro = $parametroAplicacionM->consultar();
    if($parametroAplicacionM->conexion->obtenerNumeroRegistros() == 0){
        throw new Exception("No se encontro ningun parámetro para el código TIPO-COSTO");
    }
    $tipoActualizacionCosto = $arrParametro[0]["valor"];
    //------------------------------------------------------------------

    $idTransaccion = $_POST["idTransaccion"];
    $data = $_POST["data"];
    $idUsuario = $_SESSION["idUsuario"];
    $idBodega = $_POST["idBodega"];

    $transaccionE = new \entidad\Transaccion();
    $transaccionE->setIdTransaccion($idTransaccion);

    $contador = 0;
    $saldo = 0;
    while ($contador < count($data)) {

        if ($data[$contador]["serial"] != null) {
            for ($i = 0; $i < count($data[$contador]["serial"]); $i++) {
                
                $transaccionProductoM = new \modelo\TransaccionProducto(new \entidad\TransaccionProducto());
                $secuencia = $transaccionProductoM->obtenerSecuencia($idTransaccion);
                
                //OBTENGO LOS VALORES DE ENTRADA QUE HAYAN SIDO INVENTARIADAS DEPENDIENDO DE SU CORRESPONDIENTE SERIAL
                $transaccionProductoE = new \entidad\TransaccionProducto();
                $transaccionProductoE->setIdProducto($data[$contador]["idProducto"]);
                $transaccionProductoE->setSerial($data[$contador]["serial"][$i]);
                $transaccionProductoM = new \modelo\TransaccionProducto($transaccionProductoE);
                $arrRetorno = $transaccionProductoM->obtenerValorEntradaPorSerial();

                //VALIDO QUE EXISTAN CANTIDADES SUFICIENTES PARA VENDER
                if ($arrRetorno["cantidadTotal"] == 0) {
                    throw new Exception("No se encontró en el inventario el producto <b>" . $data[$contador]["producto"] . "</b> con serial -> <b>" . $data[$contador]["serial"][$i] . "</b>");
                }

                $idTransaccionProductoAfecta = $arrRetorno[0]["idTransaccionProducto"];
                $valorEntrada = $arrRetorno[0]["valorEntrada"];
                $valorEntradaConImpuesto = $arrRetorno[0]["valorEntradaConImpuesto"];

                $transaccionProductoE = new \entidad\TransaccionProducto();
                $transaccionProductoE->setTransaccion($transaccionE);
                $transaccionProductoE->setIdProducto($data[$contador]["idProducto"]);
                $transaccionProductoE->setCantidad(1);
                $transaccionProductoE->setValorUnitarioEntrada($valorEntrada);
                $transaccionProductoE->setValorUnitarioSalida($data[$contador]["valorUnitarioSalida"]);
                $transaccionProductoE->setValorUnitaEntraConImpue($valorEntradaConImpuesto);
                $transaccionProductoE->setValorUnitaSalidConImpue($data[$contador]["valorSalidConImpue"]);
                $transaccionProductoE->setIdBodega($idBodega);
                $transaccionProductoE->setSecuencia($secuencia);
                $transaccionProductoE->setNota($data[$contador]["nota"]);
                $transaccionProductoE->setSerial($data[$contador]["serial"][$i]);
                $transaccionProductoE->setIdUsuarioCreacion($idUsuario);
                $transaccionProductoE->setIdUsuarioModificacion($idUsuario);
                $transaccionProductoE->setIdTransaccionProductoAfecta($idTransaccionProductoAfecta);
                $transaccionProductoE->setIdUnidadMedidaPresentacion($data[$contador]["idUnidadMedidaPresentacion"]);

                $transaccionProductoM = new \modelo\TransaccionProducto($transaccionProductoE);
                $transaccionProductoM->adicionar();
                $idTransaccionProducto = $transaccionProductoM->obtenerMaximo();

                if ($data[$contador]["idPedidoProducto"]) {
                    if ($data[$contador]["idPedidoProducto"] != null && $data[$contador]["idPedidoProducto"] != 'null' && $data[$contador]["idPedidoProducto"] != '') {
                        $transaccionProductoM->actuaPedidProduMovil($data[$contador]["idPedidoProducto"], $idTransaccionProducto);
                    }
                }

                $saldo = $saldo + $data[$contador]["valorSalidConImpue"];

                //$transaccionProductoImpuestoM = new \modelo\TransaccionProductoImpuesto(new \entidad\TransaccionProductoImpuesto());
                //$transaccionProductoImpuestoM->guardDatosTransProduImpue($idTransaccionProducto, $data[$contador]["idProducto"], 1);

                //$secuencia++;
            }
        } else {
            
            $transaccionProductoM = new \modelo\TransaccionProducto(new \entidad\TransaccionProducto());
            $secuencia = $transaccionProductoM->obtenerSecuencia($idTransaccion);
            
            if ($tipoActualizacionCosto == "PEPS") {
                $idProducto = $data[$contador]["idProducto"];
                $saldoCantidadVenta = $data[$contador]["cantidad"];

                //OBTENGO LOS VALORES DE ENTRADA QUE HAYAN SIDO INVENTARIADAS
                $transaccionProductoE = new \entidad\TransaccionProducto();
                $transaccionProductoE->setIdProducto($idProducto);
                $transaccionProductoM = new \modelo\TransaccionProducto($transaccionProductoE);
                $arrRetorno = $transaccionProductoM->obtenerValorEntradaProductoPEPS();
                $contador2 = 0;

                //VALIDO QUE EXISTAN CANTIDADES SUFICIENTES PARA VENDER
                if ($saldoCantidadVenta > $arrRetorno["cantidadTotal"]) {
                    throw new Exception("La cantidad inventariada es insuficiente para el producto <b>" . $data[$contador]["producto"] . "</b> <br><br> cantidad faltante -> " . ( $saldoCantidadVenta - $arrRetorno["cantidadTotal"]));
                }

                while ($contador2 < count($arrRetorno)) {
                    $idTransaccionProductoAfecta = $arrRetorno[$contador2]["idTransaccionProducto"];
                    $valorEntrada = $arrRetorno[$contador2]["valorEntrada"];
                    $valorEntradaConImpuesto = $arrRetorno[$contador2]["valorEntradaConImpuesto"];
                    $saldoCantidadProducto = $arrRetorno[$contador2]["saldoCantidadProducto"];

                    if ($saldoCantidadVenta >= $saldoCantidadProducto) {//SI LA CANTIDAD VENDIDA SUPERA LA CANTIDAD INVENTARIADA
                        $cantidad = $saldoCantidadProducto;
                        $saldoCantidadVenta = $saldoCantidadVenta - $cantidad;
                        $saldoCantidadProducto = 0;
                    } else {//SI LA CANTIDAD VENDIDA ES MENOR A LA CANTIDAD INVENTARIADA
                        $cantidad = $saldoCantidadVenta;
                        $saldoCantidadVenta = 0;
                        $saldoCantidadProducto = $saldoCantidadProducto - $cantidad;
                    }

                    $transaccionProductoE = new \entidad\TransaccionProducto();
                    $transaccionProductoE->setTransaccion($transaccionE);
                    $transaccionProductoE->setIdProducto($data[$contador]["idProducto"]);
                    $transaccionProductoE->setCantidad($cantidad);
                    $transaccionProductoE->setValorUnitarioEntrada($valorEntrada);
                    $transaccionProductoE->setValorUnitarioSalida($data[$contador]["valorUnitarioSalida"]);
                    $transaccionProductoE->setValorUnitaEntraConImpue($valorEntradaConImpuesto);
                    $transaccionProductoE->setValorUnitaSalidConImpue($data[$contador]["valorSalidConImpue"]);
                    $transaccionProductoE->setIdBodega($idBodega);
                    $transaccionProductoE->setSecuencia($secuencia);
                    $transaccionProductoE->setNota($data[$contador]["nota"]);
                    $transaccionProductoE->setSerial($data[$contador]["serial"]);
                    $transaccionProductoE->setIdUsuarioCreacion($idUsuario);
                    $transaccionProductoE->setIdUsuarioModificacion($idUsuario);
                    $transaccionProductoE->setIdTransaccionProductoAfecta($idTransaccionProductoAfecta);
                    $transaccionProductoE->setIdUnidadMedidaPresentacion($data[$contador]["idUnidadMedidaPresentacion"]);

                    $transaccionProductoM = new \modelo\TransaccionProducto($transaccionProductoE);
                    $transaccionProductoM->adicionar();
                    $idTransaccionProducto = $transaccionProductoM->obtenerMaximo();

                    if ($data[$contador]["idPedidoProducto"]) {
                        if ($data[$contador]["idPedidoProducto"] != null && $data[$contador]["idPedidoProducto"] != 'null' && $data[$contador]["idPedidoProducto"] != '') {
                            $transaccionProductoM->actuaPedidProduMovil($data[$contador]["idPedidoProducto"], $idTransaccionProducto);
                        }
                    }

                    $contador2++;

                    if ($saldoCantidadVenta > 0) {
                        $transaccionProductoM = new \modelo\TransaccionProducto(new \entidad\TransaccionProducto());
                        $secuencia = $transaccionProductoM->obtenerSecuencia($idTransaccion);
                        continue;
                    } else {
                        break;
                    }
                }

                $saldo = $saldo + ($data[$contador]["valorSalidConImpue"] * $data[$contador]["cantidad"]);
            }else{
                throw new Exception("ERROR -> El tipo de actualización de costo parametrizado actualmente es <b>".$tipoActualizacionCosto."</b> y es diferente a PEPS");
            }

            //$transaccionProductoImpuestoM = new \modelo\TransaccionProductoImpuesto(new \entidad\TransaccionProductoImpuesto());
            //$transaccionProductoImpuestoM->guardDatosTransProduImpue($idTransaccionProducto, $data[$contador]["idProducto"], $data[$contador]["cantidad"]);
        }

        $contador++;
        //$secuencia++;
    }

    /* ----------------SE GUARDA EL CONSOLIDADO DE IMPUESTOS------------- */
//    $transaccionE = new \entidad\Transaccion();
//    $transaccionE->setIdTransaccion($idTransaccion);
//    
//    $transaccionProductoE = new \entidad\TransaccionProducto();
//    $transaccionProductoE->setTransaccion($transaccionE);
//    
//    $transaccionProductoM = new \modelo\TransaccionProducto($transaccionProductoE);
//    $arrDataImpuestos = $transaccionProductoM->consuTotalImpueFactu();
//    
//    $contador = 0;
//    while ($contador < count($arrDataImpuestos)) {
//        $transaccionImpuestoE = new \entidad\TransaccionImpuesto();
//        $transaccionImpuestoE->setIdTransaccion($idTransaccion);
//        $transaccionImpuestoE->setIdImpuesto($arrDataImpuestos[$contador]["idImpuesto"]);
//        $transaccionImpuestoE->setImpuestoValor($arrDataImpuestos[$contador]["impuestoValor"]);
//        $transaccionImpuestoE->setValor($arrDataImpuestos[$contador]["totalImpuesto"]);
//        $transaccionImpuestoE->setBase($arrDataImpuestos[$contador]["totalBase"]);
//        $transaccionImpuestoE->setIdUsuarioCreacion($idUsuario);
//        $transaccionImpuestoE->setIdUsuarioModificacion($idUsuario);
//        
//        $transaccionImpuestoM = new \modelo\TransaccionImpuesto($transaccionImpuestoE);
//        $transaccionImpuestoM->adicionar();
//        
//        $contador++;
//    }
    /* --------------------------------------------------------------------- */

    /* $transaccionE = new \entidad\Transaccion();
      $transaccionE->setIdTransaccion($idTransaccion);
      $transaccionE->setSaldo($saldo);

      $transaccionM = new \modelo\Transaccion($transaccionE);
      $transaccionM->actualizarSaldo(); */
} catch (Exception $e) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
