<?php

session_start();
set_time_limit(0);

require_once '../entidad/Transaccion.php';
require_once '../entidad/TransaccionProducto.php';
require_once '../modelo/TransaccionProducto.php';
require_once '../entidad/ProductoImpuesto.php';
require_once '../modelo/ProductoImpuesto.php';
require_once '../entidad/Concepto.php';
require_once '../modelo/Concepto.php';
require_once '../entidad/Producto.php';
require_once '../modelo/Producto.php';
require_once '../entidad/TransaccionConcepto.php';
require_once '../modelo/TransaccionConcepto.php';

$retorno = array('exito' => 1, 'mensaje' => 'El inventario se guardó correctamente.');

try {
    $idTransaccion = $_POST["idTransaccion"];
    $data = $_POST["data"];
    $idUsuario = $_SESSION["idUsuario"];

    $transaccionE = new \entidad\Transaccion();
    $transaccionE->setIdTransaccion($idTransaccion);

    /* -------OBTENGO LOS PARAMETROS DEL CONCEPTO---------- */
    $transaccionConceptoE = new \entidad\TransaccionConcepto();
    $transaccionConceptoE->setTransaccion($transaccionE);
    $transaccionConceptoM = new \modelo\TransaccionConcepto($transaccionConceptoE);
    $arrInfoConcepto = $transaccionConceptoM->consultar();
    $modificaValorEntrada = $arrInfoConcepto[0]["modificaValorEntrada"];
    $modificaValorSalida = $arrInfoConcepto[0]["modificaValorSalida"];
    /* -------------------------------------------------- */

    $contador = 0;
    $secuencia = 1;
    while ($contador < count($data)) {

        $idTransaccionProducto = $data[$contador]["idTransaccionProducto"];

        $data[$contador]["valorUnitarioEntrada"] = str_replace('.', '', $data[$contador]["valorUnitarioEntrada"]);
        $data[$contador]["valorUnitarioEntrada"] = str_replace(',', '.', $data[$contador]["valorUnitarioEntrada"]);

        $data[$contador]["valorUnitarioSalida"] = str_replace('.', '', $data[$contador]["valorUnitarioSalida"]);
        $data[$contador]["valorUnitarioSalida"] = str_replace(',', '.', $data[$contador]["valorUnitarioSalida"]);

        //-----------------------OBTENGO LOS VALORES DE ENTRADA Y SALIDA CON IMPUESTO-------------------------------
        $productoImpuestoE = new \entidad\ProductoImpuesto();
        $productoImpuestoE->setIdProducto($data[$contador]["idProducto"]);

        $productoImpuestoM = new \modelo\ProductoImpuesto($productoImpuestoE);
        $arrImpuestos = $productoImpuestoM->obtenerTotalImpuestos($data[$contador]["valorUnitarioEntrada"], $data[$contador]["valorUnitarioSalida"]);
        //------------------------------------------------------------------------------------------------------------
        //---------------------------ACTUALIZO COSTOS EN LA TABLA PRODUCTO-------------------------------------
        $productoE = new \entidad\Producto();
        $productoE->setIdProducto($data[$contador]["idProducto"]);
        $productoE->setValorEntrada($arrImpuestos["valorEntrada"]);
        $productoE->setValorSalida($arrImpuestos["valorSalida"]);
        $productoE->setValorEntraConImpue($arrImpuestos["valorEntraConImpue"]);
        $productoE->setValorSalidConImpue($arrImpuestos["valorSalidConImpue"]);

        $productoM = new \modelo\Producto($productoE);
        if ($modificaValorEntrada == true)
            $productoM->modificarValorEntrada();
        if ($modificaValorSalida == true)
            $productoM->modificarValorSalida();
        //-----------------------------------------------------------------------------------------------------

        if ($data[$contador]["serial"] != null) {
            for ($i = 0; $i < count($data[$contador]["serial"]); $i++) {
                $transaccionProductoE = new \entidad\TransaccionProducto();
                $transaccionProductoE->setTransaccion($transaccionE);
                $transaccionProductoE->setIdProducto($data[$contador]["idProducto"]);
                $transaccionProductoE->setCantidad(1);
                $transaccionProductoE->setValorUnitarioEntrada($arrImpuestos["valorEntrada"]);
                $transaccionProductoE->setValorUnitarioSalida($arrImpuestos["valorSalida"]);
                $transaccionProductoE->setValorUnitaEntraConImpue($arrImpuestos["valorEntraConImpue"]);
                $transaccionProductoE->setValorUnitaSalidConImpue($arrImpuestos["valorSalidConImpue"]);
                $transaccionProductoE->setIdBodega($data[$contador]["idBodega"]);
                $transaccionProductoE->setSecuencia($secuencia);
                $transaccionProductoE->setNota($data[$contador]["nota"]);
                $transaccionProductoE->setSerial($data[$contador]["serial"][$i]);
                $transaccionProductoE->setIdUsuarioCreacion($idUsuario);
                $transaccionProductoE->setIdUsuarioModificacion($idUsuario);
                $transaccionProductoE->setIdUnidadMedidaPresentacion(null);

                $transaccionProductoM = new \modelo\TransaccionProducto($transaccionProductoE);
                $transaccionProductoM->adicionar();

                $secuencia++;
            }
        } else {
            $data[$contador]["cantidad"] = str_replace(',', '.', $data[$contador]["cantidad"]);
            
            $transaccionProductoE = new \entidad\TransaccionProducto();
            $transaccionProductoE->setTransaccion($transaccionE);
            $transaccionProductoE->setIdProducto($data[$contador]["idProducto"]);
            $transaccionProductoE->setCantidad($data[$contador]["cantidad"]);
            $transaccionProductoE->setValorUnitarioEntrada($arrImpuestos["valorEntrada"]);
            $transaccionProductoE->setValorUnitarioSalida($arrImpuestos["valorSalida"]);
            $transaccionProductoE->setValorUnitaEntraConImpue($arrImpuestos["valorEntraConImpue"]);
            $transaccionProductoE->setValorUnitaSalidConImpue($arrImpuestos["valorSalidConImpue"]);
            $transaccionProductoE->setIdBodega($data[$contador]["idBodega"]);
            $transaccionProductoE->setSecuencia($secuencia);
            $transaccionProductoE->setNota($data[$contador]["nota"]);
            $transaccionProductoE->setSerial($data[$contador]["serial"]);
            $transaccionProductoE->setIdUsuarioCreacion($idUsuario);
            $transaccionProductoE->setIdUsuarioModificacion($idUsuario);
            $transaccionProductoE->setIdUnidadMedidaPresentacion(null);

            $transaccionProductoM = new \modelo\TransaccionProducto($transaccionProductoE);
            $transaccionProductoM->adicionar();
        }

        $contador++;
        $secuencia++;
    }
} catch (Exception $e) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
