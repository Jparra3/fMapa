<?php

session_start();
require_once '../entidad/Producto.php';
require_once '../modelo/Producto.php';
require_once '../entidad/ProductoImpuesto.php';
require_once '../modelo/ProductoImpuesto.php';
require_once '../../Seguridad/entidad/ParametroAplicacion.php';
require_once '../../Seguridad/modelo/ParametroAplicacion.php';
require_once '../comunes/funciones.php';

$retorno = array('exito' => 1, 'mensaje' => 'Los impuestos se guardaron correctamente.');

try {
    
    /*-----------------------------------------------------------------------------------------------------------------*/
    $parametroAplicacionE = new \entidad\ParametroAplicacion();
    $parametroAplicacionE->setIdParametroAplicacion(19); //PARAMETRO-> ENTRADA - Calcular valor base de los producto impuesto
    $parametroAplicacionM = new \modelo\ParametroAplicacion($parametroAplicacionE);
    $arrParametroAplicacion = $parametroAplicacionM->consultar();
    $calculaValorBaseEntrada = $arrParametroAplicacion[0]["valor"];

    $parametroAplicacionE = new \entidad\ParametroAplicacion();
    $parametroAplicacionE->setIdParametroAplicacion(20); //PARAMETRO-> SALIDA - Calcular valor base de los producto impuesto
    $parametroAplicacionM = new \modelo\ParametroAplicacion($parametroAplicacionE);
    $arrParametroAplicacion = $parametroAplicacionM->consultar();
    $calculaValorBaseSalida = $arrParametroAplicacion[0]["valor"];
    /*-----------------------------------------------------------------------------------------------------------------*/

    $idProducto = $_POST["idProducto"];

    $valorEntrada = str_replace('.', '', $_POST["valorEntrada"]);
    $valorEntrada = str_replace(',', '.', $valorEntrada);

    $valorSalida = str_replace('.', '', $_POST["valorSalida"]);
    $valorSalida = str_replace(',', '.', $valorSalida);

    $data = $_POST["data"];
    $idUsuario = $_SESSION["idUsuario"];
    $contador = 0;
    $valorEntradaConImpue = $valorEntrada;
    $valorSalidaConImpue = $valorSalida;

    if (count($data) > 1) {
        throw new Exception("Error -> No se puede adicionar más de un impuesto");
    }

    while ($contador < count($data)) {

        if ($calculaValorBaseEntrada == "1") { //TENEMOS EL VALOR DEL PRODUCTO CON IMPUESTO Y SE CALCULARA EL VALOR BASE
            if ($data[$contador]["idTipoImpuesto"] == "1") {//FIJO
                $valorEntrada = $valorEntrada - $data[$contador]["valor"];
            } elseif ($data[$contador]["idTipoImpuesto"] == "2") {//PORCENTUAL
                $valor = (int) $data[$contador]["valor"];
                $valorEntrada = $valorEntrada / (($valor / 100) + 1); //SE CALCULA LA BASE
            }
        } else {//YA TENEMOS EL VALOR BASE Y LE SUMAMOS EL IMPUESTO AL PRODUCTO
            if ($data[$contador]["idTipoImpuesto"] == "1") {//FIJO
                $valorEntradaConImpue = $valorEntradaConImpue + $data[$contador]["valor"];
            } elseif ($data[$contador]["idTipoImpuesto"] == "2") {//PORCENTUAL
                $valorEntradaConImpue = $valorEntradaConImpue + ($valorEntrada * ($data[$contador]["valor"] / 100));
            }
        }

        if ($calculaValorBaseSalida == "1") {//TENEMOS EL VALOR DEL PRODUCTO CON IMPUESTO Y SE CALCULARA EL VALOR BASE
            if ($data[$contador]["idTipoImpuesto"] == "1") {//FIJO
                $valorSalida = $valorSalida - $data[$contador]["valor"];
            } elseif ($data[$contador]["idTipoImpuesto"] == "2") {//PORCENTUAL
                $valor = (int) $data[$contador]["valor"];
                $valorSalida = $valorSalida / (($valor / 100) + 1); //SE CALCULA LA BASE
            }
        } else {//YA TENEMOS EL VALOR BASE Y LE SUMAMOS EL IMPUESTO AL PRODUCTO
            if ($data[$contador]["idTipoImpuesto"] == "1") {//FIJO
                $valorSalidaConImpue = $valorSalidaConImpue + $data[$contador]["valor"];
            } elseif ($data[$contador]["idTipoImpuesto"] == "2") {//PORCENTUAL
                $valorSalidaConImpue = $valorSalidaConImpue + ($valorSalida * ($data[$contador]["valor"] / 100));
            }
        }

        $idProductoImpuesto = $data[$contador]["idProductoImpuesto"];

        $productoImpuestoE = new \entidad\ProductoImpuesto();
        $productoImpuestoE->setIdProductoImpuesto($idProductoImpuesto);
        $productoImpuestoE->setIdProducto($idProducto);
        $productoImpuestoE->setIdImpuesto($data[$contador]["idImpuesto"]);
        $productoImpuestoE->setValor($data[$contador]["valor"]);
        $productoImpuestoE->setIdUsuarioCreacion($idUsuario);
        $productoImpuestoE->setIdUsuarioModificacion($idUsuario);

        $productoImpuestoM = new \modelo\ProductoImpuesto($productoImpuestoE);
        if ($idProductoImpuesto == "" || $idProductoImpuesto == null || $idProductoImpuesto == "null") {
            $productoImpuestoM->adicionar();
        } else {
            $productoImpuestoM->modificar();
        }
        $contador++;
    }

    $productoE = new \entidad\Producto();
    $productoE->setIdProducto($idProducto);
    $productoE->setValorEntraConImpue($valorEntradaConImpue);
    $productoE->setValorSalidConImpue($valorSalidaConImpue);
    $productoE->setValorEntrada($valorEntrada);
    $productoE->setValorSalida($valorSalida);

    $productoM = new \modelo\Producto($productoE);
    $productoM->actualizarImpuestos();
    
} catch (Exception $e) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
