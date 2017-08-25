<?php

namespace modelo;

session_start();
require_once($_SESSION['ruta'] . 'entorno/Conexion.php');
require_once '../../Seguridad/entidad/ParametroAplicacion.php';
require_once '../../Seguridad/modelo/ParametroAplicacion.php';

class ProductoImpuesto {

    public $conexion;
    private $idProductoImpuesto;
    private $idProducto;
    private $idImpuesto;
    private $valor;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;

    public function __construct(\entidad\ProductoImpuesto $productoImpuesto,  $conexion = null) {
        $this->idProductoImpuesto = $productoImpuesto->getIdProductoImpuesto();
        $this->idProducto = $productoImpuesto->getIdProducto();
        $this->idImpuesto = $productoImpuesto->getIdImpuesto();
        $this->valor = $productoImpuesto->getValor();
        $this->idUsuarioCreacion = $productoImpuesto->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $productoImpuesto->getIdUsuarioModificacion();
        $this->fechaCreacion = $productoImpuesto->getFechaCreacion();
        $this->fechaModificacion = $productoImpuesto->getFechaModificacion();

        $this->conexion = $conexion == null ? new \Conexion() : $conexion;
    }

    public function adicionar() {
        $sentenciaSql = "
                        INSERT INTO
                            facturacion_inventario.producto_impuesto
                        (
                            id_producto
                            , id_impuesto
                            , valor
                            , id_usuario_creacion
                            , id_usuario_modificacion
                            , fecha_creacion
                            , fecha_modificacion
                        )
                        VALUES
                        (
                            $this->idProducto
                            , $this->idImpuesto
                            , $this->valor
                            , $this->idUsuarioCreacion
                            , $this->idUsuarioModificacion
                            , NOW()
                            , NOW()
                        )
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }

    public function modificar() {
        $sentenciaSql = "
                        UPDATE
                            facturacion_inventario.producto_impuesto
                        SET
                            id_impuesto = $this->idImpuesto
                            , valor = $this->valor
                            , id_usuario_modificacion = $this->idUsuarioModificacion
                            , fecha_modificacion = NOW()
                        WHERE
                            id_producto_impuesto = $this->idProductoImpuesto
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }

    public function eliminar() {
        $sentenciaSql = "
                        DELETE FROM
                            facturacion_inventario.producto_impuesto
                        WHERE
                            id_producto_impuesto = $this->idProductoImpuesto
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }

    public function consultar() {
        $sentenciaSql = "
                        SELECT
                            pi.id_producto_impuesto
                            , pi.id_producto
                            , pi.id_impuesto
                            , i.impuesto
                            , i.id_tipo_impuesto
                            , ti.tipo_impuesto
                            , pi.valor
                        FROM
                            facturacion_inventario.producto_impuesto AS pi
                            INNER JOIN facturacion_inventario.impuesto AS i ON i.id_impuesto = pi.id_impuesto
                            INNER JOIN facturacion_inventario.tipo_impuesto AS ti ON ti.id_tipo_impuesto = i.id_tipo_impuesto
                        WHERE
                            pi.id_producto = $this->idProducto
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["idProductoImpuesto"] = $fila->id_producto_impuesto;
            $retorno[$contador]["idProducto"] = $fila->id_producto;
            $retorno[$contador]["idImpuesto"] = $fila->id_impuesto;
            $retorno[$contador]["impuesto"] = $fila->impuesto;
            $retorno[$contador]["idTipoImpuesto"] = $fila->id_tipo_impuesto;
            $retorno[$contador]["tipoImpuesto"] = $fila->tipo_impuesto;
            $retorno[$contador]["valor"] = $fila->valor;
            $contador++;
        }
        return $retorno;
    }

    public function obtenerTotalImpuestos($valorEntrada, $valorSalida) {

        /* ----------------------------------------------------------------------------------------------------------------- */
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
        /* ----------------------------------------------------------------------------------------------------------------- */

        $retorno = array("valorEntraConImpue" => 0, "valorSalidConImpue" => 0, "valorEntrada" => 0, "valorSalida" => 0);
        $data = $this->consultar();
        $contador = 0;
        $valorEntradaConImpue = $valorEntrada;
        $valorSalidaConImpue = $valorSalida;
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

            /* if ($data[$contador]["idTipoImpuesto"] == "1") {//FIJO
              $valorEntradaConImpue = $valorEntradaConImpue + $data[$contador]["valor"];
              $valorSalidaConImpue = $valorSalidaConImpue + $data[$contador]["valor"];
              } elseif ($data[$contador]["idTipoImpuesto"] == "2") {//PORCENTUAL
              $valorEntradaConImpue = $valorEntradaConImpue + ($valorEntrada * ($data[$contador]["valor"] / 100));
              $valorSalidaConImpue = $valorSalidaConImpue + ($valorSalida * ($data[$contador]["valor"] / 100));
              } */
            $contador++;
        }
        $retorno["valorEntraConImpue"] = $valorEntradaConImpue;
        $retorno["valorSalidConImpue"] = $valorSalidaConImpue;
        $retorno["valorEntrada"] = $valorEntrada;
        $retorno["valorSalida"] = $valorSalida;
        return $retorno;
    }

}
