<?php

namespace modelo;

session_start();
require_once($_SESSION['ruta'] . 'entorno/Conexion.php');
require_once '../entidad/Transaccion.php';
require_once '../../Seguridad/entidad/ParametroAplicacion.php';
require_once '../../Seguridad/modelo/ParametroAplicacion.php';
class TransaccionProducto {

    public $conexion = null;
    private $condicion;
    private $whereAnd;
    private $idTransaccionProducto;
    private $transaccion;
    private $idProducto;
    private $cantidad;
    private $valorUnitarioEntrada;
    private $valorUnitarioSalida;
    private $idBodega;
    private $secuencia;
    private $nota;
    private $serial;
    private $serialInterno;
    private $estado;
    private $fechaInicio;
    private $fechaFin;
    private $valorUnitaEntraConImpue;
    private $valorUnitaSalidConImpue;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    private $idTransaccionProductoAfecta;
    private $saldoCantidadProducto;
    private $idUnidadMedidaPresentacion;

    public function __construct(\entidad\TransaccionProducto $transaccionProducto, $conexion = null) {
        $this->idTransaccionProducto = $transaccionProducto->getIdTransaccionProducto();
        $this->transaccion = $transaccionProducto->getTransaccion() != "" ? $transaccionProducto->getTransaccion() : new \entidad\Transaccion();
        $this->idProducto = $transaccionProducto->getIdProducto();
        $this->cantidad = $transaccionProducto->getCantidad();
        $this->valorUnitarioEntrada = $transaccionProducto->getValorUnitarioEntrada();
        $this->valorUnitarioSalida = $transaccionProducto->getValorUnitarioSalida();
        $this->idBodega = $transaccionProducto->getIdBodega();
        $this->secuencia = $transaccionProducto->getSecuencia();
        $this->nota = $transaccionProducto->getNota();
        $this->serial = $transaccionProducto->getSerial();
        $this->serialInterno = $transaccionProducto->getSerialInterno();
        $this->estado = $transaccionProducto->getEstado();
        $this->fechaInicio = $transaccionProducto->getFechaInicio();
        $this->fechaFin = $transaccionProducto->getFechaFin();
        $this->valorUnitaEntraConImpue = $transaccionProducto->getValorUnitaEntraConImpue();
        $this->valorUnitaSalidConImpue = $transaccionProducto->getValorUnitaSalidConImpue();
        $this->idUsuarioCreacion = $transaccionProducto->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $transaccionProducto->getIdUsuarioModificacion();
        $this->fechaCreacion = $transaccionProducto->getFechaCreacion();
        $this->fechaModificacion = $transaccionProducto->getFechaModificacion();
        $this->saldoCantidadProducto = $transaccionProducto->getSaldoCantidadProducto() != "" ? $transaccionProducto->getSaldoCantidadProducto() : "null";
        $this->idUnidadMedidaPresentacion = $transaccionProducto->getIdUnidadMedidaPresentacion() != "" ? $transaccionProducto->getIdUnidadMedidaPresentacion() : "null";
        
        if ($transaccionProducto->getIdTransaccionProductoAfecta() == "" || $transaccionProducto->getIdTransaccionProductoAfecta() == null)
            $this->idTransaccionProductoAfecta = "null";
        else
            $this->idTransaccionProductoAfecta = $transaccionProducto->getIdTransaccionProductoAfecta();

        $this->conexion = $conexion == null ? new \Conexion() : $conexion;
    }

    public function adicionar() {
        
        if($this->saldoCantidadProducto == "null" || $this->saldoCantidadProducto == null)
            $this->saldoCantidadProducto = "0";
        
        $sentenciaSql = "
                        INSERT INTO
                            facturacion_inventario.transaccion_producto
                        (
                            id_transaccion
                            , id_producto
                            , cantidad
                            , valor_unitario_entrada                            
                            , valor_unitario_salida
                            , id_bodega
                            , secuencia
                            , nota
                            , serial
                            , serial_interno
                            , estado
                            , valor_unita_salid_con_impue
                            , valor_unita_entra_con_impue
                            , id_usuario_creacion
                            , id_usuario_modificacion
                            , fecha_creacion
                            , fecha_modificacion
                            , id_transaccion_producto_afecta
                            , id_unidad_medida_presentacion
                            , saldo_cantidad_producto
                        )
                        VALUES
                        (
                            " . $this->transaccion->getIdTransaccion() . "
                            , $this->idProducto
                            , $this->cantidad
                            , $this->valorUnitarioEntrada
                            , $this->valorUnitarioSalida
                            , $this->idBodega
                            , $this->secuencia
                            , $this->nota
                            , $this->serial
                            , $this->serialInterno
                            , TRUE
                            , $this->valorUnitaSalidConImpue
                            , $this->valorUnitaEntraConImpue    
                            , $this->idUsuarioCreacion
                            , $this->idUsuarioModificacion
                            , NOW()
                            , NOW()
                            , $this->idTransaccionProductoAfecta
                            , $this->idUnidadMedidaPresentacion
                            , $this->saldoCantidadProducto
                        )
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }

    public function consultar() {
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
        
        $this->obtenerCondicion();
        
        $sentenciaSql = "
                        SELECT
                            tp.id_producto
                            , p.codigo
                            , p.producto
                            , um.unidad_medida
                            , tp.cantidad AS cantidad
                            , CEILING(tp.valor_unitario_entrada) AS valor_unitario_entrada
                            , CEILING(tp.valor_unitario_salida) AS valor_unitario_salida
                            , CEILING(tp.valor_unita_salid_con_impue) AS valor_unita_salid_con_impue
                            , CEILING(tp.valor_unita_entra_con_impue) AS valor_unita_entra_con_impue
                            , tp.id_bodega
                            , b.bodega
                            , tp.nota
                            , COALESCE(tp.serial,'') as serial
                            , COALESCE(tp.serial_interno,'') as serial_interno
                            , tp.secuencia
                            , tp.saldo_cantidad_producto
                        FROM
                            facturacion_inventario.transaccion_producto AS tp
                            INNER JOIN facturacion_inventario.producto AS p ON p.id_producto = tp.id_producto
                            INNER JOIN facturacion_inventario.unidad_medida AS um ON um.id_unidad_medida = p.id_unidad_medida
                            INNER JOIN facturacion_inventario.bodega AS b ON b.id_bodega = tp.id_bodega
                        $this->condicion ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["idProducto"] = $fila->id_producto;
            $retorno[$contador]["producto"] = $fila->producto;
            $retorno[$contador]["unidadMedida"] = $fila->unidad_medida;
            $retorno[$contador]["cantidad"] = $fila->cantidad;
            $retorno[$contador]["valorUnitarioEntrada"] = $fila->valor_unitario_entrada;
            $retorno[$contador]["valorUnitarioSalida"] = $fila->valor_unitario_salida;
            $retorno[$contador]["valorUnitaSalidConImpue"] = $fila->valor_unita_salid_con_impue;
            $retorno[$contador]["valorUnitaEntraConImpue"] = $fila->valor_unita_entra_con_impue;
            $retorno[$contador]["idBodega"] = $fila->id_bodega;
            $retorno[$contador]["bodega"] = $fila->bodega;
            $retorno[$contador]["nota"] = $fila->nota;
            $retorno[$contador]["codigo"] = $fila->codigo;
            $retorno[$contador]["serial"] = $fila->serial;
            $retorno[$contador]["serialInterno"] = $fila->serial_interno;
            $retorno[$contador]["secuencia"] = $fila->secuencia;
            $retorno[$contador]["saldoCantidadProducto"] = $fila->saldo_cantidad_producto;
            
            if($calculaValorBaseEntrada == "1"){
                $retorno[$contador]["valorEntradaMostrar"] = $fila->valor_unita_entra_con_impue;
            }else{
                $retorno[$contador]["valorEntradaMostrar"] = $fila->valor_unitario_entrada;
            }
            
            if($calculaValorBaseSalida == "1"){
                $retorno[$contador]["valorSalidaMostrar"] = $fila->valor_unita_salid_con_impue;
            }else{
                $retorno[$contador]["valorSalidaMostrar"] = $fila->valor_unitario_salida;
            }
            
            $contador++;
        }
        return $retorno;
    }

    public function obtenerSecuencia($idTransaccion) {
        $sentenciaSql = "
                        SELECT
                            (COALESCE(MAX(secuencia), 0) + 1) AS secuencia
                        FROM
                            facturacion_inventario.transaccion_producto
                        WHERE
                            id_transaccion = $idTransaccion
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $fila = $this->conexion->obtenerObjeto();
        return $fila->secuencia;
    }
    
    public function consultarKardexProducto($idOficinas) {
        $this->obtenerCondicion();
        $sentenciaSql = "
                            SELECT
                                to_char(t.fecha , 'YYYY-MM-DD HH24:MI:SS') AS fecha
                                ,td.tipo_documento
                                ,t.numero_tipo_documento
                                , tp.cantidad AS cantidad
                                , CEILING(tp.valor_unitario_entrada) AS valor_unitario_entrada
                                , CEILING(tp.valor_unitario_salida) AS valor_unitario_salida
                                ,tp.serial
                                , tp.nota
                                , b.id_bodega
                                , b.bodega
                                , CEILING(tp.valor_unita_entra_con_impue) AS valor_unita_entra_con_impue
                                , CEILING(tp.valor_unita_salid_con_impue) AS valor_unita_salid_con_impue
                                , n.signo
                            FROM
                                facturacion_inventario.transaccion_producto AS tp
                                INNER JOIN facturacion_inventario.transaccion AS t ON t.id_transaccion = tp.id_transaccion
                                INNER JOIN facturacion_inventario.transaccion_concepto AS tc ON tc.id_transaccion = t.id_transaccion
                                INNER JOIN facturacion_inventario.concepto AS c ON c.id_concepto = tc.id_concepto
                                INNER JOIN contabilidad.tipo_documento AS td ON td.id_tipo_documento = c.id_tipo_documento
                                INNER JOIN contabilidad.naturaleza AS n ON n.id_naturaleza = td.id_naturaleza
                                INNER JOIN facturacion_inventario.producto AS p ON p.id_producto = tp.id_producto
                                INNER JOIN facturacion_inventario.unidad_medida AS um ON um.id_unidad_medida = p.id_unidad_medida
                                INNER JOIN facturacion_inventario.bodega AS b ON b.id_bodega = tp.id_bodega
                            $this->condicion $this->whereAnd b.id_oficina IN ($idOficinas) $this->whereAnd t.id_transaccion_estado IN (1)
                            ORDER BY
                                tp.id_bodega,t.fecha
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["fecha"] = $fila->fecha;
            $retorno[$contador]["tipoDocumento"] = $fila->tipo_documento;
            $retorno[$contador]["numeroTipoDocumento"] = $fila->numero_tipo_documento;
            $retorno[$contador]["cantidad"] = $fila->cantidad;
            $retorno[$contador]["valorUnitarioEntrada"] = $fila->valor_unitario_entrada;
            $retorno[$contador]["valorUnitarioSalida"] = $fila->valor_unitario_salida;
            $retorno[$contador]["serial"] = $fila->serial;
            $retorno[$contador]["nota"] = $fila->nota;
            $retorno[$contador]["idBodega"] = $fila->id_bodega;
            $retorno[$contador]["bodega"] = $fila->bodega;
            $retorno[$contador]["valorUnitaEntraConImpue"] = $fila->valor_unita_entra_con_impue;
            $retorno[$contador]["valorUnitaSalidConImpue"] = $fila->valor_unita_salid_con_impue;
            $retorno[$contador]["signo"] = $fila->signo;
            $contador++;
        }
        return $retorno;
    }

    public function obtenerMaximo() {
        $sentenciaSql = "
                        SELECT
                            MAX(id_transaccion_producto) AS maximo
                        FROM
                            facturacion_inventario.transaccion_producto
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $fila = $this->conexion->obtenerObjeto();
        return $fila->maximo;
    }

    public function consultarReporteVenta($parametros) {
        $this->obtenerCondicionReporteVenta($parametros);
        $sentenciaSql = "
                        SELECT
                            t.id_transaccion
                            , t.id_tercero
                            , tc.id_cliente
                            , CONCAT_WS(' - ', ter.tercero, s.sucursal) AS cliente
			    , to_char(t.fecha , 'YYYY-MM-DD HH24:MI:SS') AS fecha
			    , t.numero_tipo_documento AS numero_recibo
                            , cj.numero_caja
			    , o.oficina
                            , tp.id_bodega
			    , b.bodega
                            , p.codigo
                            , tp.id_transaccion_producto
                            , tp.id_producto
                            , p.producto
                            , lp.linea_producto
                            , tp.cantidad AS cantidad
                            , CEILING(tp.saldo_cantidad_producto) AS saldo_cantidad_producto
                            , CEILING(tp.valor_unita_salid_con_impue) AS valor_unita_salid_con_impue
                            , CEILING(tp.valor_unita_salid_con_impue * tp.cantidad) AS valor_total
                        FROM
                            facturacion_inventario.transaccion_producto AS tp
                            INNER JOIN facturacion_inventario.bodega AS b ON b.id_bodega = tp.id_bodega
                            INNER JOIN facturacion_inventario.producto AS p ON p.id_producto = tp.id_producto
                            INNER JOIN facturacion_inventario.linea_producto AS lp ON lp.id_linea_producto = p.id_linea_producto
                            INNER JOIN facturacion_inventario.transaccion AS t ON t.id_transaccion = tp.id_transaccion
                            INNER JOIN contabilidad.tipo_documento AS td ON td.id_tipo_documento = t.id_tipo_documento
                            INNER JOIN contabilidad.naturaleza AS n ON n.id_naturaleza = td.id_naturaleza
                            INNER JOIN contabilidad.tipo_naturaleza AS tn ON tn.id_tipo_naturaleza = n.id_tipo_naturaleza
                            INNER JOIN contabilidad.oficina AS o ON o.id_oficina = t.id_oficina
                            INNER JOIN facturacion_inventario.transaccion_cliente AS tc ON tc.id_transaccion = tp.id_transaccion
                            INNER JOIN facturacion_inventario.caja AS cj ON cj.id_caja = tc.id_caja
                            INNER JOIN facturacion_inventario.cliente AS c ON c.id_cliente = tc.id_cliente
                            INNER JOIN contabilidad.sucursal AS s ON s.id_sucursal = c.id_sucursal
                            INNER JOIN contabilidad.tercero AS ter ON ter.id_tercero = s.id_tercero
                            $this->condicion $this->whereAnd t.id_transaccion_estado = 1 AND tn.codigo = 'VE'
                        ORDER BY
                            t.fecha,t.numero_tipo_documento
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        $total = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno["detalle"][$contador]["idTransaccion"] = $fila->id_transaccion;
            $retorno["detalle"][$contador]["idTercero"] = $fila->id_tercero;
            $retorno["detalle"][$contador]["idCliente"] = $fila->id_cliente;
            $retorno["detalle"][$contador]["cliente"] = $fila->cliente;
            $retorno["detalle"][$contador]["fecha"] = $fila->fecha;
            $retorno["detalle"][$contador]["numeroRecibo"] = $fila->numero_recibo;
            $retorno["detalle"][$contador]["numeroCaja"] = $fila->numero_caja;
            $retorno["detalle"][$contador]["oficina"] = $fila->oficina;
            $retorno["detalle"][$contador]["idBodega"] = $fila->id_bodega;
            $retorno["detalle"][$contador]["bodega"] = $fila->bodega;
            $retorno["detalle"][$contador]["codigo"] = $fila->codigo;
            $retorno["detalle"][$contador]["idTransaccionProducto"] = $fila->id_transaccion_producto;
            $retorno["detalle"][$contador]["idProducto"] = $fila->id_producto;
            $retorno["detalle"][$contador]["producto"] = $fila->producto;
            $retorno["detalle"][$contador]["lineaProducto"] = $fila->linea_producto;
            $retorno["detalle"][$contador]["cantidad"] = $fila->cantidad;
            $retorno["detalle"][$contador]["saldoCantidadProducto"] = $fila->saldo_cantidad_producto;
            $retorno["detalle"][$contador]["valorUnitaSalidConImpue"] = $fila->valor_unita_salid_con_impue;
            $retorno["detalle"][$contador]["valorTotal"] = $fila->valor_total;
            $total = $total + $fila->valor_total;
            $contador++;
        }
        $retorno["total"] = $total;
        return $retorno;
    }

    function obtenerCondicionReporteVenta($parametros) {
        $this->condicion = "";
        $this->whereAnd = " WHERE ";

        if ($parametros["fecha"]["inicio"] != '' && $parametros["fecha"]["fin"]) {
            $this->condicion = $this->condicion . $this->whereAnd . " CAST(t.fecha AS DATE) BETWEEN '" . $parametros["fecha"]["inicio"] . "' AND '" . $parametros["fecha"]["fin"] . "'";
            $this->whereAnd = ' AND ';
        } elseif ($parametros["fecha"]["inicio"] != '' && $parametros["fecha"]["fin"] == '') {
            $this->condicion = $this->condicion . $this->whereAnd . " CAST(t.fecha AS DATE) = '" . $parametros["fecha"]["inicio"] . "'";
            $this->whereAnd = ' AND ';
        } elseif ($parametros["fecha"]["inicio"] == '' && $parametros["fecha"]["fin"] != '') {
            $this->condicion = $this->condicion . $this->whereAnd . " CAST(t.fecha AS DATE) = '" . $parametros["fecha"]["fin"] . "'";
            $this->whereAnd = ' AND ';
        }

        if ($parametros["idCliente"] != "" && $parametros["idCliente"] != null && $parametros["idCliente"] != "null") {
            $this->condicion = $this->condicion . $this->whereAnd . " tc.id_cliente IN (" . $parametros["idCliente"] . ")";
            $this->whereAnd = " AND ";
        }

        if ($parametros["idLineaProducto"] != "" && $parametros["idLineaProducto"] != null && $parametros["idLineaProducto"] != "null") {
            $this->condicion = $this->condicion . $this->whereAnd . " p.id_linea_producto IN (" . $parametros["idLineaProducto"] . ")";
            $this->whereAnd = " AND ";
        }

        if ($this->idProducto != "" && $this->idProducto != null && $this->idProducto != "null") {
            $this->condicion = $this->condicion . $this->whereAnd . " tp.id_producto IN (" . $this->idProducto . ")";
            $this->whereAnd = " AND ";
        }
        if ($this->idBodega != "" && $this->idBodega != null && $this->idBodega != "null") {
            $this->condicion = $this->condicion . $this->whereAnd . " tp.id_bodega IN (" . $this->idBodega . ")";
            $this->whereAnd = " AND ";
        }

        if ($parametros["numeroRecibo"] != "" && $parametros["numeroRecibo"] != null && $parametros["numeroRecibo"] != "null") {
            $this->condicion = $this->condicion . $this->whereAnd . " t.numero_tipo_documento IN (" . $parametros["numeroRecibo"] . ")";
            $this->whereAnd = " AND ";
        }

        if ($parametros["idCaja"] != "" && $parametros["idCaja"] != null && $parametros["idCaja"] != "null") {
            $this->condicion = $this->condicion . $this->whereAnd . " tc.id_caja IN (" . $parametros["idCaja"] . ")";
            $this->whereAnd = " AND ";
        }

        if ($parametros["idOficina"] != "" && $parametros["idOficina"] != null && $parametros["idOficina"] != "null") {
            $this->condicion = $this->condicion . $this->whereAnd . " t.id_oficina IN (" . $parametros["idOficina"] . ")";
            $this->whereAnd = " AND ";
        }
    }

    //------------------------------------MÉTODOS DE FACTURACIÓN----------------------------------
    public function consultarProductosFactura() {
//        $sentenciaSql = "
//                        SELECT
//                            p.codigo
//                            , p.producto
//                            , um.unidad_medida
//                            , CEILING(tp.cantidad) AS cantidad
//                            , CEILING(tp.valor_unitario_entrada) AS valor_unitario_entrada
//                            , CEILING(tp.valor_unitario_salida) AS valor_unitario_salida
//                            , CEILING(tp.valor_unita_salid_con_impue) AS valor_unita_salid_con_impue
//                            , CEILING(tp.valor_unita_entra_con_impue) AS valor_unita_entra_con_impue
//                            , b.bodega
//                            , tp.nota
//                            , tp.serial
//                            , ( SELECT 
//                                        string_agg(i.simbolo, '') AS value             
//                                FROM
//                                        facturacion_inventario.transaccion_producto AS tpsimbolo
//                                        LEFT OUTER JOIN facturacion_inventario.transaccion_producto_impuesto AS tpi ON tpi.id_transaccion_producto = tpsimbolo.id_transaccion_producto
//                                        LEFT OUTER JOIN facturacion_inventario.impuesto AS i ON i.id_impuesto = tpi.id_impuesto
//                                WHERE
//                                        tp.id_producto = p.id_producto
//                                        AND tpsimbolo.id_transaccion_producto = tp.id_transaccion_producto
//                                        AND tp.id_transaccion = " . $this->transaccion->getIdTransaccion() . "
//                                ) AS simbolo
//                            , (SELECT 
//                                        CAST(tpi.impuesto_valor AS INT)
//                                FROM
//                                        facturacion_inventario.transaccion_producto AS tpsimbolo
//                                        INNER JOIN facturacion_inventario.transaccion_producto_impuesto AS tpi ON tpi.id_transaccion_producto = tpsimbolo.id_transaccion_producto
//                                        INNER JOIN facturacion_inventario.impuesto AS i ON i.id_impuesto = tpi.id_impuesto
//                                WHERE
//                                        tpsimbolo.id_transaccion_producto = tp.id_transaccion_producto
//                                        AND tpi.id_impuesto = 1
//                                        AND tpsimbolo.id_transaccion = " . $this->transaccion->getIdTransaccion() . "
//                                ) AS iva
//                        FROM
//                                facturacion_inventario.transaccion_producto AS tp
//                                INNER JOIN facturacion_inventario.producto AS p ON p.id_producto = tp.id_producto
//                                INNER JOIN facturacion_inventario.unidad_medida AS um ON um.id_unidad_medida = p.id_unidad_medida
//                                INNER JOIN facturacion_inventario.bodega AS b ON b.id_bodega = tp.id_bodega
//                        WHERE
//                                id_transaccion = " . $this->transaccion->getIdTransaccion();
        
        $sentenciaSql = "
                        SELECT
                            p.id_producto
                            , p.codigo
                            , tp.id_transaccion
                            , p.producto
                            , um.unidad_medida
                            , SUM(tp.cantidad) AS cantidad
                            --, CEILING(tp.valor_unitario_entrada) AS valor_unitario_entrada
                            , tp.valor_unitario_salida AS valor_unitario_salida
                            , tp.valor_unita_salid_con_impue AS valor_unita_salid_con_impue
                            --, CEILING(tp.valor_unita_entra_con_impue) AS valor_unita_entra_con_impue
                            , b.bodega
                            , tp.nota
                            , tp.serial
                            , '%' AS simbolo
                            , '0' AS iva
                            , ump.unidad_medida as presentacion
                        FROM
                            facturacion_inventario.transaccion_producto AS tp
                            INNER JOIN facturacion_inventario.producto AS p ON p.id_producto = tp.id_producto
                            INNER JOIN facturacion_inventario.unidad_medida AS um ON um.id_unidad_medida = p.id_unidad_medida
                            INNER JOIN facturacion_inventario.bodega AS b ON b.id_bodega = tp.id_bodega
                            LEFT OUTER JOIN facturacion_inventario.unidad_medida AS ump ON ump.id_unidad_medida = tp.id_unidad_medida_presentacion
                        WHERE
                            tp.id_transaccion = ".$this->transaccion->getIdTransaccion()."
                            AND tp.id_transaccion_producto_padre IS NULL
                        GROUP BY 
                            p.id_producto
                            , p.codigo
                            , tp.id_transaccion
                            , p.producto
                            , um.unidad_medida
                            , tp.valor_unitario_salida
                            , tp.valor_unita_salid_con_impue
                            , b.bodega
                            , tp.nota
                            , tp.serial
                            , ump.unidad_medida
                        ";
        
        
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["codigo"] = $fila->codigo;
            $retorno[$contador]["producto"] = $fila->producto;
            $retorno[$contador]["presentacion"] = $fila->presentacion;
            $retorno[$contador]["unidadMedida"] = $fila->unidad_medida;
            $retorno[$contador]["cantidad"] = $fila->cantidad;
            $retorno[$contador]["valorUnitarioEntrada"] = $fila->valor_unitario_entrada;
            $retorno[$contador]["valorUnitarioSalida"] = $fila->valor_unitario_salida;
            $retorno[$contador]["bodega"] = $fila->bodega;
            $retorno[$contador]["nota"] = $fila->nota;
            $retorno[$contador]["serial"] = $fila->serial;
            $retorno[$contador]["valorUnitaSalidConImpue"] = $fila->valor_unita_salid_con_impue;
            $retorno[$contador]["valorUnitaEntraConImpue"] = $fila->valor_unita_entra_con_impue;
            $retorno[$contador]["simbolo"] = $this->obtenerSimboloImpuesto($fila->id_producto, $fila->id_transaccion);
            $retorno[$contador]["iva"] = $this->obtenerIva($fila->id_producto, $fila->id_transaccion);
            $contador++;
        }
        return $retorno;
    }
    
    private function obtenerSimboloImpuesto($idProducto, $idTransaccion){
        $conexion = new \Conexion();
        $simbolo = '';
        $sentenciaSql = "
                        SELECT  DISTINCT
                                i.simbolo
                        FROM
                                facturacion_inventario.transaccion_producto AS tp
                                INNER JOIN facturacion_inventario.transaccion_producto_impuesto AS tpi ON tpi.id_transaccion_producto = tp.id_transaccion_producto
                                INNER JOIN facturacion_inventario.impuesto AS i ON i.id_impuesto = tpi.id_impuesto
                        WHERE
                                tp.id_producto = $idProducto
                                AND tp.id_transaccion = $idTransaccion
                        ";
        $conexion->ejecutar($sentenciaSql);
        while ($fila = $conexion->obtenerObjeto()) {
            $simbolo .= $fila->simbolo . ' ';
        }
        return $simbolo;
    }
    
    private function obtenerIva($idProducto, $idTransaccion){
        $conexion = new \Conexion();
        $iva = '';
        $sentenciaSql = "
                        SELECT DISTINCT
                            CAST(tpi.impuesto_valor AS INT) AS iva
                        FROM
                            facturacion_inventario.transaccion_producto AS tp
                            INNER JOIN facturacion_inventario.transaccion_producto_impuesto AS tpi ON tpi.id_transaccion_producto =  tp.id_transaccion_producto
                            INNER JOIN facturacion_inventario.impuesto AS i ON i.id_impuesto = tpi.id_impuesto
                        WHERE
                            tp.id_producto = $idProducto
                            AND tp.id_transaccion = $idTransaccion
                            AND tpi.id_impuesto = 1
                        ";
        $conexion->ejecutar($sentenciaSql);
        
        if($conexion->obtenerNumeroRegistros() == 0){
            $iva = '0';
            return $iva;
        }
        
        while ($fila = $conexion->obtenerObjeto()) {
            $iva .= $fila->iva . ' ';
        }
        
        return $iva;
    }

    public function obtenerImpuestosIva() {
        $sentenciaSql = "
                        SELECT
                            i.id_impuesto
                            , i.impuesto
                            , i.simbolo
                            , tp.valor_unita_salid_con_impue
                            , tp.cantidad
                        FROM
                            facturacion_inventario.transaccion_producto AS tp
                            INNER JOIN facturacion_inventario.transaccion_producto_impuesto AS tpi ON tpi.id_transaccion_producto = tp.id_transaccion_producto
                            INNER JOIN facturacion_inventario.impuesto AS i ON i.id_impuesto = tpi.id_impuesto
                        WHERE
                            tp.id_transaccion = " . $this->transaccion->getIdTransaccion() . "
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $total = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            
            if($fila->id_impuesto == 1){// IVA                
                $retorno[0]['simbolo'] = $fila->simbolo;
                $retorno[0]['impuesto'] = $fila->impuesto;
                $total += ($fila->valor_unita_salid_con_impue * $fila->cantidad);
            }else{//SIN IVA
                $retorno[1]['simbolo'] = $fila->simbolo;
                $retorno[1]['impuesto'] = $fila->impuesto;
                $retorno[1]['totalBase'] += ($fila->valor_unita_salid_con_impue * $fila->cantidad);
                $retorno[1]['totalImpuesto'] = 0;
            }
            
        }
        
        $sentenciaSql = "
                        SELECT DISTINCT
                            CAST(MAX(pi.valor) AS INT) AS valor_iva
                        FROM
                            facturacion_inventario.transaccion_producto AS tp
                            INNER JOIN facturacion_inventario.transaccion_producto_impuesto AS tpi ON tpi.id_transaccion_producto = tp.id_transaccion_producto
                            INNER JOIN facturacion_inventario.impuesto AS i ON i.id_impuesto = tpi.id_impuesto
                            INNER JOIN facturacion_inventario.producto_impuesto AS pi ON pi.id_producto = tp.id_producto AND pi.id_impuesto = 1
                        WHERE
                            tp.id_transaccion = " . $this->transaccion->getIdTransaccion() . "
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $fila = $this->conexion->obtenerObjeto();
        $valorIva = (($fila->valor_iva/100) + 1);
        $base = $total / $valorIva;
        $iva = $total - $base;
        $retorno[0]['totalBase'] = $base;
        $retorno[0]['totalImpuesto'] = $iva;
        return $retorno;
    }

    public function consuTotalImpueFactu() {
        $sentenciaSql = "
                        SELECT
                            i.id_impuesto
                            , i.simbolo
                            , tpi.impuesto_valor
                            , CONCAT_WS(' - ', i.impuesto, CONCAT(CEILING(tpi.impuesto_valor), i.simbolo)) AS impuesto
                            , ROUND(SUM(tpi.valor)) AS total_impuesto
                            , ROUND(SUM(tpi.base)) AS total_base
                        FROM
                            facturacion_inventario.transaccion_producto AS tp
                            INNER JOIN facturacion_inventario.transaccion_producto_impuesto AS tpi ON tpi.id_transaccion_producto = tp.id_transaccion_producto
                            INNER JOIN facturacion_inventario.impuesto AS i ON i.id_impuesto = tpi.id_impuesto
                        WHERE
                            tp.id_transaccion = " . $this->transaccion->getIdTransaccion() . "
                        GROUP BY i.id_impuesto, tpi.impuesto_valor";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["idImpuesto"] = $fila->id_impuesto;
            $retorno[$contador]["simbolo"] = $fila->simbolo;
            $retorno[$contador]["impuestoValor"] = $fila->impuesto_valor;
            $retorno[$contador]["impuesto"] = $fila->impuesto;
            $retorno[$contador]["totalImpuesto"] = $fila->total_impuesto;
            $retorno[$contador]["totalBase"] = $fila->total_base;
            $contador++;
        }
        return $retorno;
    }

    public function adicionarProductoDevuelto() {

        $sentenciaSql = "INSERT INTO 
                            facturacion_inventario.transaccion_producto
                            (
                                id_transaccion,
                                id_producto,
                                cantidad,
                                valor_unitario_salida,
                                valor_unitario_entrada,
                                id_bodega,
                                secuencia,
                                nota,
                                serial,
                                estado,
                                id_usuario_creacion,
                                id_usuario_modificacion,
                                fecha_creacion,
                                fecha_modificacion,
                                valor_unita_salid_con_impue,
                                valor_unita_entra_con_impue,
                                id_transaccion_producto_afecta
                            )
                            (
                                SELECT DISTINCT
                                    " . $this->transaccion->getIdTransaccion() . " 
                                    , tp.id_producto
                                    , $this->cantidad
                                    , tp.valor_unitario_salida
                                    , tp.valor_unitario_entrada
                                    , $this->idBodega
                                    , $this->secuencia
                                    , $this->nota
                                    , $this->serial
                                    , TRUE
                                    , $this->idUsuarioCreacion
                                    , $this->idUsuarioModificacion
                                    , NOW()
                                    , NOW()
                                    , (SELECT valor_salid_con_impue FROM facturacion_inventario.producto WHERE id_producto = $this->idProducto)
                                    , (SELECT valor_entra_con_impue FROM facturacion_inventario.producto WHERE id_producto = $this->idProducto)
                                    , $this->idTransaccionProductoAfecta
                                FROM
                                    facturacion_inventario.transaccion_producto tp
                                WHERE
                                    tp.id_producto = $this->idProducto
                                    AND id_bodega = $this->idBodega
                            )
                ";

        $this->conexion->ejecutar($sentenciaSql);
    }

    public function actuaPedidProduMovil($idPedidoProducto, $idTransaccionProducto){
        $sentenciaSql = "
            UPDATE 
                facturacion_inventario.transaccion_producto
            SET
                id_pedido_producto = $idPedidoProducto
            WHERE
                id_transaccion_producto = $idTransaccionProducto
        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    
    function obtenerCondicion() {
        $this->condicion = '';
        $this->whereAnd = ' WHERE ';
        
        if($this->transaccion->getIdTransaccion() != '' && $this->transaccion->getIdTransaccion() != null && $this->transaccion->getIdTransaccion() != 'null'){
            $this->condicion .= $this->whereAnd . ' tp.id_transaccion = ' . $this->transaccion->getIdTransaccion();
            $this->whereAnd = ' AND ';
        }
        
        if ($this->idProducto != '' && $this->idProducto != 'null' && $this->idProducto != null) {
            $this->condicion .= $this->whereAnd . ' tp.id_producto = ' . $this->idProducto;
            $this->whereAnd = ' AND ';
        }

        if ($this->idBodega != '' && $this->idBodega != 'null' && $this->idBodega != null) {
            $this->condicion .= $this->whereAnd . ' tp.id_bodega IN(' . $this->idBodega . ")";
            $this->whereAnd = ' AND ';
        }

        if ($this->fechaInicio != '' && $this->fechaInicio != 'null' && $this->fechaInicio != null) {
            $this->condicion .= $this->whereAnd . " CAST(t.fecha AS date) >= '" . $this->fechaInicio . "'";
            $this->whereAnd = ' AND ';
        }

        if ($this->fechaFin != '' && $this->fechaFin != 'null' && $this->fechaFin != null) {
            $this->condicion .= $this->whereAnd . " CAST(t.fecha AS date) <= '" . $this->fechaFin . "'";
            $this->whereAnd = ' AND ';
        }
        
        if($this->saldoCantidadProducto != '' && $this->saldoCantidadProducto != 'null' && $this->saldoCantidadProducto != null){
            $this->condicion .= $this->whereAnd . " tp.saldo_cantidad_producto " . $this->saldoCantidadProducto;
            $this->whereAnd = ' AND ';
        }
    }
    
    
    public function obtenerValorEntradaProductoPEPS() {
        $sentenciaSql = "
                        SELECT
                            tp.id_transaccion_producto
                            , tp.valor_unitario_entrada AS valor_entrada
                            , tp.valor_unita_entra_con_impue AS valor_entrada_con_impuesto
                            , tp.saldo_cantidad_producto
                        FROM
                            facturacion_inventario.transaccion_producto AS tp
                            INNER JOIN facturacion_inventario.transaccion AS t ON t.id_transaccion = tp.id_transaccion
                            INNER JOIN facturacion_inventario.transaccion_concepto AS tc ON tc.id_transaccion = t.id_transaccion
                            INNER JOIN facturacion_inventario.concepto AS c ON c.id_concepto = tc.id_concepto
                            INNER JOIN contabilidad.tipo_documento AS td ON td.id_tipo_documento = c.id_tipo_documento
                        WHERE
                            tp.saldo_cantidad_producto > 0
                            AND tp.id_producto = ".$this->idProducto."
                            AND td.codigo IN ('CO-IN-EN', 'VE-AN', 'CO-AN-EN', 'IN-IN')
                            AND t.id_transaccion_estado = 1
                        ORDER BY
                            t.fecha  ASC
                        ";
        $contador = 0;
        $cantidadTotal = 0;
        $this->conexion->ejecutar($sentenciaSql);
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["idTransaccionProducto"] = $fila->id_transaccion_producto;
            $retorno[$contador]["valorEntrada"] = $fila->valor_entrada;
            $retorno[$contador]["valorEntradaConImpuesto"] = $fila->valor_entrada_con_impuesto;
            $retorno[$contador]["saldoCantidadProducto"] = $fila->saldo_cantidad_producto;
            
            $cantidadTotal += $fila->saldo_cantidad_producto;
            $contador++;
        }
        $retorno["cantidadTotal"] = $cantidadTotal;
        return $retorno;
    }
    
    public function obtenerValorEntradaPorSerial() {
        $sentenciaSql = "
                        SELECT
                            tp.id_transaccion_producto
                            , tp.valor_unitario_entrada AS valor_entrada
                            , tp.valor_unita_entra_con_impue AS valor_entrada_con_impuesto
                            , tp.saldo_cantidad_producto
                        FROM
                            facturacion_inventario.transaccion_producto AS tp
                            INNER JOIN facturacion_inventario.transaccion AS t ON t.id_transaccion = tp.id_transaccion
                            INNER JOIN facturacion_inventario.transaccion_concepto AS tc ON tc.id_transaccion = t.id_transaccion
                            INNER JOIN facturacion_inventario.concepto AS c ON c.id_concepto = tc.id_concepto
                            INNER JOIN contabilidad.tipo_documento AS td ON td.id_tipo_documento = c.id_tipo_documento
                        WHERE
                            tp.saldo_cantidad_producto > 0
                            AND tp.id_producto = ".$this->idProducto."
                            AND tp.serial = ".$this->serial."
                            AND td.codigo IN ('CO-IN-EN', 'VE-AN', 'CO-AN-EN', 'IN-IN')
                        ORDER BY
                            t.fecha  ASC
                        ";
        $contador = 0;
        $cantidadTotal = 0;
        $this->conexion->ejecutar($sentenciaSql);
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["idTransaccionProducto"] = $fila->id_transaccion_producto;
            $retorno[$contador]["valorEntrada"] = $fila->valor_entrada;
            $retorno[$contador]["valorEntradaConImpuesto"] = $fila->valor_entrada_con_impuesto;
            $retorno[$contador]["saldoCantidadProducto"] = $fila->saldo_cantidad_producto;
            
            $cantidadTotal += $fila->saldo_cantidad_producto;
            $contador++;
        }
        $retorno["cantidadTotal"] = $cantidadTotal;
        return $retorno;
    }
}
