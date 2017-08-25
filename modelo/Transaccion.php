<?php

namespace modelo;

session_start();
require_once($_SESSION['ruta'] . 'entorno/Conexion.php');
require_once '../../Seguridad/entidad/Tercero.php';
require_once '../entidad/TipoDocumento.php';
require_once '../modelo/TipoDocumento.php';
require_once '../entidad/Concepto.php';
require_once '../modelo/Concepto.php';

class Transaccion {

    public $conexion;
    private $condicion;
    private $whereAnd;
    private $idTransaccion;
    private $idTipoDocumento;
    private $tercero;
    private $idNaturaleza;
    private $numeroTipoDocumento;
    private $idTransaccionEstado;
    private $idOficina;
    private $nota;
    private $fecha;
    private $fechaVencimiento;
    private $valor;
    private $saldo;
    private $numeroImpresiones;
    private $idTransaccionAfecta;
    private $idFormatoImpresion;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    private $idTransaccionPadre;

    public function __construct(\entidad\Transaccion $transaccion, $conexion = null) {
        $this->idTransaccion = $transaccion->getIdTransaccion();
        $this->idTipoDocumento = $transaccion->getIdTipoDocumento();
        $this->tercero = $transaccion->getTercero() != "" ? $transaccion->getTercero() : new \entidad\Tercero();
        $this->idNaturaleza = $transaccion->getIdNaturaleza();
        $this->numeroTipoDocumento = $transaccion->getNumeroTipoDocumento();
        $this->idTransaccionEstado = $transaccion->getIdTransaccionEstado();
        $this->idOficina = $transaccion->getIdOficina();
        $this->nota = $transaccion->getNota();
        $this->fecha = $transaccion->getFecha();
        $this->fechaVencimiento = $transaccion->getFechaVencimiento();
        $this->valor = $transaccion->getValor();
        $this->saldo = $transaccion->getSaldo() != "" ? $transaccion->getSaldo() : "0";
        $this->numeroImpresiones = $transaccion->getNumeroImpresiones();
        $this->idTransaccionAfecta = $transaccion->getIdTransaccionAfecta() != "" ? $transaccion->getIdTransaccionAfecta() : "null";
        $this->idFormatoImpresion = $transaccion->getIdFormatoImpresion() != "" ? $transaccion->getIdFormatoImpresion() : "null";
        $this->idUsuarioCreacion = $transaccion->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $transaccion->getIdUsuarioModificacion();
        $this->fechaCreacion = $transaccion->getFechaCreacion();
        $this->fechaModificacion = $transaccion->getFechaModificacion();
        $this->idTransaccionPadre = $transaccion->getIdTransaccionPadre() != "" ? $transaccion->getIdTransaccionPadre() : "null";

        $this->conexion = $conexion == null ? new \Conexion() : $conexion;
    }

    public function consultarInformacionFactura() {
        $sentenciaSql = "
                        SELECT
                            t.id_transaccion
                            , td.tipo_documento
                            , t.id_tercero
                            , CONCAT_WS(' - ', ter.nit, ter.tercero) AS tercero
                            , o.oficina
                            , t.nota
                            , CAST(t.fecha AS DATE) AS fecha
                            , CAST(t.fecha_vencimiento AS DATE) AS fecha_vencimiento
                            , et.estado_transaccion
                            , t.numero_tipo_documento
                            , u.usuario
                            , td.codigo
                        FROM
                            facturacion_inventario.transaccion AS t
                            INNER JOIN seguridad.usuario AS u ON u.id_usuario = t.id_usuario_creacion
                            INNER JOIN contabilidad.tipo_documento AS td ON td.id_tipo_documento = t.id_tipo_documento
                            INNER JOIN contabilidad.naturaleza AS n ON n.id_naturaleza = td.id_naturaleza
                            INNER JOIN contabilidad.tercero AS ter ON ter.id_tercero = t.id_tercero
                            INNER JOIN contabilidad.oficina AS o ON o.id_oficina = t.id_oficina
                            INNER JOIN facturacion_inventario.estado_transaccion AS et ON et.id_estado_transaccion = t.id_transaccion_estado
                        WHERE 
                            t.id_transaccion = $this->idTransaccion
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["idTransaccion"] = $fila->id_transaccion;
            $retorno[$contador]["tipoDocumento"] = $fila->tipo_documento;
            $retorno[$contador]["idTercero"] = $fila->id_tercero;
            $retorno[$contador]["tercero"] = $fila->tercero;
            $retorno[$contador]["oficina"] = $fila->oficina;
            $retorno[$contador]["nota"] = $fila->nota;
            $retorno[$contador]["usuario"] = $fila->usuario;
            $retorno[$contador]["fecha"] = $fila->fecha;
            $retorno[$contador]["codigo"] = $fila->codigo;
            $retorno[$contador]["fechaVencimiento"] = $fila->fecha_vencimiento;
            $retorno[$contador]["transaccionEstado"] = $fila->estado_transaccion;
            $retorno[$contador]["numeroTipoDocumento"] = $fila->numero_tipo_documento;
            $contador++;
        }
        return $retorno;
    }

    public function adicionar() {
        $sentenciaSql = "
                        INSERT INTO
                            facturacion_inventario.transaccion
                        (
                            id_tipo_documento
                            , id_tercero
                            , id_naturaleza
                            , numero_tipo_documento
                            , id_transaccion_estado
                            , id_oficina
                            , nota
                            , fecha
                            , fecha_vencimiento
                            , valor
                            , saldo
                            , id_transaccion_afecta
                            , id_formato_impresion
                            , id_usuario_creacion
                            , id_usuario_modificacion
                            , fecha_creacion
                            , fecha_modificacion
                            , id_transaccion_padre
                        )
                        VALUES
                        (
                            $this->idTipoDocumento
                            , " . $this->tercero->getIdTercero() . "
                            , $this->idNaturaleza
                            , $this->numeroTipoDocumento
                            , $this->idTransaccionEstado
                            , $this->idOficina
                            , $this->nota
                            , '$this->fecha'
                            , '$this->fechaVencimiento'
                            , $this->valor
                            , $this->saldo
                            , $this->idTransaccionAfecta
                            , $this->idFormatoImpresion
                            , $this->idUsuarioCreacion
                            , $this->idUsuarioModificacion
                            , NOW()
                            , NOW()
                            , $this->idTransaccionPadre
                        )
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }

    public function consultar($visualiza = '') {
        
        $condicion = '';
        if($visualiza == ''){
            $condicion = 'AND td.visualiza = TRUE';
        }
        
        $this->obtenerCondicion();
        $sentenciaSql = "
                        SELECT
                            t.id_transaccion
                            , td.tipo_documento
                            , t.id_tercero
                            , CONCAT_WS(' - ', ter.nit, ter.tercero) AS tercero
                            , o.oficina
                            , t.nota
                            , CAST(t.fecha AS DATE) AS fecha
                            , CAST(t.fecha_vencimiento AS DATE) AS fecha_vencimiento
                            , t.fecha AS fecha_hora
                            , t.fecha_vencimiento AS fecha_vencimiento_hora
                            , et.estado_transaccion
                            , t.numero_tipo_documento
                            ,u.usuario
                            , t.valor
                            , t.saldo
                        FROM
                            facturacion_inventario.transaccion AS t
                            INNER JOIN facturacion_inventario.transaccion_concepto AS tc ON tc.id_transaccion = t.id_transaccion
                            INNER JOIN facturacion_inventario.concepto AS c ON c.id_concepto = tc.id_concepto
                            INNER JOIN seguridad.usuario AS u ON u.id_usuario = t.id_usuario_creacion
                            INNER JOIN contabilidad.tipo_documento AS td ON td.id_tipo_documento = c.id_tipo_documento
                            INNER JOIN contabilidad.naturaleza AS n ON n.id_naturaleza = td.id_naturaleza
                            INNER JOIN contabilidad.tercero AS ter ON ter.id_tercero = t.id_tercero
                            INNER JOIN contabilidad.oficina AS o ON o.id_oficina = t.id_oficina
                            INNER JOIN facturacion_inventario.estado_transaccion AS et ON et.id_estado_transaccion = t.id_transaccion_estado
                            $this->condicion /* $this->whereAnd n.id_tipo_naturaleza = 1 */ $condicion
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["idTransaccion"] = $fila->id_transaccion;
            $retorno[$contador]["tipoDocumento"] = $fila->tipo_documento;
            $retorno[$contador]["idTercero"] = $fila->id_tercero;
            $retorno[$contador]["tercero"] = $fila->tercero;
            $retorno[$contador]["oficina"] = $fila->oficina;
            $retorno[$contador]["nota"] = $fila->nota;
            $retorno[$contador]["fecha"] = $fila->fecha;
            $retorno[$contador]["fechaVencimiento"] = $fila->fecha_vencimiento;
            $retorno[$contador]["transaccionEstado"] = $fila->estado_transaccion;
            $retorno[$contador]["numeroTipoDocumento"] = $fila->numero_tipo_documento;
            $retorno[$contador]["valor"] = $fila->valor;
            $retorno[$contador]["saldo"] = $fila->saldo;
            $retorno[$contador]["fechaHora"] = $fila->fecha_hora;
            $retorno[$contador]["fechaVencimientoHora"] = $fila->fecha_vencimiento_hora;
            $contador++;
        }
        return $retorno;
    }

    public function transaccionConsultar($numeroFactura, $idConcepto, $codigoTipoNaturaleza) {//Se crea para poder enviar un parámetro de entrada
        $this->obtenerCondicion();
        if ($numeroFactura != null && $numeroFactura != "" && $numeroFactura != "null") {
            $this->condicion .= $this->whereAnd . " tp.documento_externo='" . $numeroFactura . "'";
            $this->whereAnd = " AND ";
        }
        if ($idConcepto != null && $idConcepto != "" && $idConcepto != "null") {
            $this->condicion .= $this->whereAnd . " tc.id_concepto=" . $idConcepto;
            $this->whereAnd = " AND ";
        }
        if ($codigoTipoNaturaleza != null && $codigoTipoNaturaleza != "" && $codigoTipoNaturaleza != "null") {
            $this->condicion .= $this->whereAnd . " tn.codigo='" . $codigoTipoNaturaleza . "'";
            $this->whereAnd = " AND ";
        }
        $sentenciaSql = "
                        SELECT
                            t.id_transaccion
                            , td.tipo_documento
                            , t.id_tercero
                            , CONCAT_WS(' - ', ter.nit, ter.tercero) AS tercero
                            , o.oficina
                            , t.nota
                            , CAST(t.fecha AS DATE) AS fecha
                            , CAST(t.fecha_vencimiento AS DATE) AS fecha_vencimiento
                            , et.estado_transaccion
                            , t.id_transaccion_estado
                            , t.numero_tipo_documento
                            , tp.documento_externo
                            , ROUND(t.valor) AS valor
                            , ROUND(t.saldo) AS saldo
                        FROM
                            facturacion_inventario.transaccion AS t
                            INNER JOIN facturacion_inventario.transaccion_concepto AS tc ON tc.id_transaccion = t.id_transaccion
                            INNER JOIN facturacion_inventario.concepto AS c ON c.id_concepto = tc.id_concepto
                            INNER JOIN contabilidad.tipo_documento AS td ON td.id_tipo_documento = c.id_tipo_documento
                            INNER JOIN contabilidad.naturaleza AS n ON n.id_naturaleza = td.id_naturaleza
                            INNER JOIN contabilidad.tipo_naturaleza AS tn ON tn.id_tipo_naturaleza = n.id_tipo_naturaleza
                            INNER JOIN contabilidad.tercero AS ter ON ter.id_tercero = t.id_tercero
                            INNER JOIN contabilidad.oficina AS o ON o.id_oficina = t.id_oficina
                            INNER JOIN facturacion_inventario.estado_transaccion AS et ON et.id_estado_transaccion = t.id_transaccion_estado
                            LEFT OUTER JOIN facturacion_inventario.transaccion_proveedor AS tp ON tp.id_transaccion = t.id_transaccion
                            $this->condicion  --AND td.visualiza = TRUE
                        ORDER BY 
                            t.fecha
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["idTransaccion"] = $fila->id_transaccion;
            $retorno[$contador]["tipoDocumento"] = $fila->tipo_documento;
            $retorno[$contador]["idTercero"] = $fila->id_tercero;
            $retorno[$contador]["tercero"] = $fila->tercero;
            $retorno[$contador]["oficina"] = $fila->oficina;
            $retorno[$contador]["nota"] = $fila->nota;
            $retorno[$contador]["fecha"] = $fila->fecha;
            $retorno[$contador]["fechaVencimiento"] = $fila->fecha_vencimiento;
            $retorno[$contador]["transaccionEstado"] = $fila->estado_transaccion;
            $retorno[$contador]["numeroTipoDocumento"] = $fila->numero_tipo_documento;
            $retorno[$contador]["documentoExterno"] = $fila->documento_externo;
            $retorno[$contador]["valor"] = $fila->valor;
            $retorno[$contador]["saldo"] = $fila->saldo;
            $retorno[$contador]["idTransaccionEstado"] = $fila->id_transaccion_estado;
            $contador++;
        }
        return $retorno;
    }

    public function consuPagoAsoci($arrParametros) {
        $condicion = "";
        $whereAnd = " WHERE ";
        if ($arrParametros[0] != "" && $arrParametros[0] != null && $arrParametros[0] != "null") {
            if ($arrParametros[1] != "" && $arrParametros[1] != null && $arrParametros[1] != "null") {
                $condicion .= $whereAnd . " CAST(tra.fecha AS DATE) >= '" . $arrParametros[0] . "' AND CAST(tra.fecha AS DATE) <= '" . $arrParametros[1] . "'";
                $whereAnd = " AND ";
            } else {
                $condicion .= $whereAnd . " CAST(tra.fecha AS DATE) = '" . $arrParametros[0] . "'";
                $whereAnd = " AND ";
            }
        } else {
            if ($arrParametros[1] != "" && $arrParametros[1] != null && $arrParametros[1] != "null") {
                $condicion .= $whereAnd . " CAST(tra.fecha AS DATE) = '" . $arrParametros[1] . "'";
                $whereAnd = " AND ";
            }
        }

        if ($arrParametros[2] != "" && $arrParametros[2] != null && $arrParametros[2] != "null") {
            $condicion .= $whereAnd . " c.id_concepto  IN (" . $arrParametros[2] . ") ";
            $whereAnd = " AND ";
        }

        if ($arrParametros[3] != "" && $arrParametros[3] != null && $arrParametros[3] != "null") {
            $condicion .= $whereAnd . " t.id_tercero = " . $arrParametros[3];
        }

        $sentenciaSql = "
            SELECT
                  t.tercero
                , t.nit
                , tc.valor
                , tra.fecha
                , td.tipo_documento
            FROM
                facturacion_inventario.transaccion AS tra 
                INNER JOIN facturacion_inventario.transaccion_concepto AS tc ON tc.id_transaccion = tra.id_transaccion
                INNER JOIN contabilidad.tercero AS t ON t.id_tercero = tra.id_tercero
                INNER JOIN facturacion_inventario.concepto AS c ON c.id_concepto = tc.id_concepto
                INNER JOIN contabilidad.tipo_documento AS td ON td.id_tipo_documento = c.id_tipo_documento
            $condicion
        ";
        $contador = 0;
        $this->conexion->ejecutar($sentenciaSql);
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["tercero"] = $fila->tercero;
            $retorno[$contador]["valor"] = $fila->valor;
            $retorno[$contador]["nit"] = $fila->nit;
            $retorno[$contador]["fecha"] = $fila->fecha;
            $retorno[$contador]["tipoDocumento"] = $fila->tipo_documento;

            $contador++;
        }
        return $retorno;
    }

    function obtenerCondicion() {
        $this->condicion = "";
        $this->whereAnd = " WHERE ";
        if ($this->idTransaccion != "" && $this->idTransaccion != null && $this->idTransaccion != "null") {
            $this->condicion = $this->condicion . $this->whereAnd . " t.id_transaccion = " . $this->idTransaccion;
            $this->whereAnd = " AND ";
        }
        if ($this->idTipoDocumento != "" && $this->idTipoDocumento != null && $this->idTipoDocumento != "null") {
            $this->condicion = $this->condicion . $this->whereAnd . " t.id_tipo_documento = " . $this->idTipoDocumento;
            $this->whereAnd = " AND ";
        }
        if ($this->tercero->getIdTercero() != "" && $this->tercero->getIdTercero() != null && $this->tercero->getIdTercero() != "null") {
            $this->condicion = $this->condicion . $this->whereAnd . " t.id_tercero = " . $this->tercero->getIdTercero();
            $this->whereAnd = " AND ";
        }
        if ($this->idOficina != "" && $this->idOficina != null && $this->idOficina != "null") {
            $this->condicion = $this->condicion . $this->whereAnd . " t.id_oficina = " . $this->idOficina;
            $this->whereAnd = " AND ";
        }
        if ($this->idTransaccionEstado != "" && $this->idTransaccionEstado != null && $this->idTransaccionEstado != "null") {
            $this->condicion = $this->condicion . $this->whereAnd . " t.id_transaccion_estado = " . $this->idTransaccionEstado;
            $this->whereAnd = " AND ";
        }
        if ($this->idTransaccionAfecta != "" && $this->idTransaccionAfecta != null && $this->idTransaccionAfecta != "null") {
            $this->condicion = $this->condicion . $this->whereAnd . " t.id_transaccion_afecta = " . $this->idTransaccionAfecta;
            $this->whereAnd = " AND ";
        }
        if ($this->fecha["inicio"] != '' && $this->fecha["fin"]) {
            $this->condicion = $this->condicion . $this->whereAnd . " CAST(t.fecha AS DATE) BETWEEN '" . $this->fecha["inicio"] . "' AND '" . $this->fecha["fin"] . "'";
            $this->whereAnd = ' AND ';
        } elseif ($this->fecha["inicio"] != '' && $this->fecha["fin"] == '') {
            $this->condicion = $this->condicion . $this->whereAnd . " CAST(t.fecha AS DATE) = '" . $this->fecha["inicio"] . "'";
            $this->whereAnd = ' AND ';
        } elseif ($this->fecha["inicio"] == '' && $this->fecha["fin"] != '') {
            $this->condicion = $this->condicion . $this->whereAnd . " CAST(t.fecha AS DATE) = '" . $this->fecha["fin"] . "'";
            $this->whereAnd = ' AND ';
        }

        if ($this->fechaVencimiento["inicio"] != '' && $this->fechaVencimiento["fin"]) {
            $this->condicion = $this->condicion . $this->whereAnd . " CAST(t.fecha_vencimiento AS DATE) BETWEEN '" . $this->fechaVencimiento["inicio"] . "' AND '" . $this->fechaVencimiento["fin"] . "'";
            $this->whereAnd = ' AND ';
        } elseif ($this->fechaVencimiento["inicio"] != '' && $this->fechaVencimiento["fin"] == '') {
            $this->condicion = $this->condicion . $this->whereAnd . " CAST(t.fecha_vencimiento AS DATE) = '" . $this->fechaVencimiento["inicio"] . "'";
            $this->whereAnd = ' AND ';
        } elseif ($this->fechaVencimiento["inicio"] == '' && $this->fechaVencimiento["fin"] != '') {
            $this->condicion = $this->condicion . $this->whereAnd . " CAST(t.fecha_vencimiento AS DATE) = '" . $this->fechaVencimiento["fin"] . "'";
            $this->whereAnd = ' AND ';
        }
    }

    public function obtenerMaximo() {
        $sentenciaSql = "
                        SELECT
                            MAX(id_transaccion) AS maximo
                        FROM
                            facturacion_inventario.transaccion
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $fila = $this->conexion->obtenerObjeto();
        return $fila->maximo;
    }

    public function consultarOficinas() {
        $sentenciaSql = "
                        SELECT
                            id_oficina
                            , oficina
                        FROM
                            contabilidad.oficina
                        WHERE
                            id_oficina IN ($this->idOficina)
                            AND estado = TRUE
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["idOficina"] = $fila->id_oficina;
            $retorno[$contador]["oficina"] = $fila->oficina;
            $contador++;
        }
        return $retorno;
    }

    public function buscarTercero($tercero, $limite = '') {

        $top = '';
        if ($limite != '') {
            $top = ' LIMIT ' . $limite;
        }

        $datos = array();
        $sentenciaSql = "
                SELECT DISTINCT
                    id_tercero
                    , tercero
                    , CONCAT_WS(' - ', nit, tercero) AS value
                FROM
                    contabilidad.tercero
                WHERE
                    CONCAT_WS(' - ', nit, tercero) ILIKE '%$tercero%'
                    AND estado = TRUE
                ORDER BY
                    tercero
                $top
                ";
        $this->conexion->ejecutar($sentenciaSql);
        while ($fila = $this->conexion->obtenerObjeto()) {
            $datos[] = array("value" => $fila->value,
                "idTercero" => $fila->id_tercero
            );
        }
        return $datos;
    }

    public function buscarProveedor($proveedor) {
        $datos = array();
        $sentenciaSql = "
                SELECT DISTINCT
                    p.id_proveedor
                    , CONCAT_WS(' - ', t.nit, t.tercero) AS value
                FROM
                    facturacion_inventario.proveedor p
                    INNER JOIN contabilidad.sucursal s ON s.id_sucursal = p.id_sucursal
                    INNER JOIN contabilidad.tercero t ON t.id_tercero = s.id_tercero
                WHERE
                    CONCAT_WS(' - ', t.nit, t.tercero) ILIKE '%$proveedor%'
                    AND t.estado = TRUE
                ";
        $this->conexion->ejecutar($sentenciaSql);
        while ($fila = $this->conexion->obtenerObjeto()) {
            $datos[] = array("value" => $fila->value,
                "idProveedor" => $fila->id_proveedor
            );
        }
        return $datos;
    }

    public function buscarTerceroSucursal($tercero, $limite = '') {

        $top = '';
        if ($limite != '') {
            $top = ' LIMIT ' . $limite;
        }

        $datos = array();
        $sentenciaSql = "
                SELECT DISTINCT
                    t.id_tercero
                    , t.nit
                    , p.id_proveedor
                    , CONCAT_WS(' - ', t.tercero, s.sucursal) AS value
                FROM
                    contabilidad.tercero AS t
                    INNER JOIN contabilidad.sucursal AS s ON s.id_tercero = t.id_tercero
                    INNER JOIN facturacion_inventario.proveedor AS p ON p.id_sucursal = s.id_sucursal
                WHERE
                    CONCAT_WS(' - ', t.tercero, s.sucursal) ILIKE '%$tercero%'
                    AND t.estado = TRUE
                ORDER BY
                    value
                $top
                ";
        $this->conexion->ejecutar($sentenciaSql);
        while ($fila = $this->conexion->obtenerObjeto()) {
            $datos[] = array("value" => $fila->value,
                "idTercero" => $fila->id_tercero,
                "nit" => $fila->nit,
                "idProveedor" => $fila->id_proveedor
            );
        }
        return $datos;
    }

    public function consultarTerceroSucursal() {
        $this->obtenerCondicion();
        $sentenciaSql = "
                        SELECT
                            t.id_tercero
                            , p.id_proveedor
                            , CONCAT_WS(' - ', t.tercero, s.sucursal) AS tercero_sucursal
                        FROM
                            contabilidad.tercero as t
                            INNER JOIN contabilidad.sucursal AS s ON s.id_tercero = t.id_tercero
                            INNER JOIN facturacion_inventario.proveedor AS p ON p.id_sucursal = s.id_sucursal
                        WHERE
                            t.nit = " . $this->tercero->getNit() . "
                            AND t.estado = TRUE";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]['idTercero'] = $fila->id_tercero;
            $retorno[$contador]['idProveedor'] = $fila->id_proveedor;
            $retorno[$contador]['terceroSucursal'] = $fila->tercero_sucursal;
            $contador++;
        }
        return $retorno;
    }

    public function inactivar() {
        $sentenciaSql = "
                        UPDATE
                            facturacion_inventario.transaccion
                        SET
                            id_transaccion_estado = (
                                                    SELECT 
                                                        id_estado_transaccion 
                                                    FROM 
                                                        facturacion_inventario.estado_transaccion 
                                                    WHERE
                                                        estado = TRUE
                                                        AND activo = FALSE --INDICA QUE EL ESTADO SIRVE PARA INACTIVAR LA TRANSACCION
                                                    )
                            , id_usuario_modificacion = $this->idUsuarioModificacion
                            , fecha_modificacion = NOW()
                        WHERE
                            id_transaccion = $this->idTransaccion
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }

    public function actualizarSaldo() {
        $sentenciaSql = "
                        UPDATE
                            facturacion_inventario.transaccion
                        SET
                            saldo = $this->saldo
                        WHERE
                            id_transaccion = $this->idTransaccion
                       ";
        $this->conexion->ejecutar($sentenciaSql);
    }

    public function obtenerParametroAplicacion($idParametroAplicacion) {
        $sentenciaSql = "
                            SELECT 
                                valor
                            FROM
                                general.parametro_aplicacion
                            WHERE
                                id_parametro_aplicacion = $idParametroAplicacion
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $fila = $this->conexion->obtenerObjeto();
        return $fila->valor;
    }

    public function obtenerSignoTransaccion() {
        $sentenciaSql = "
                        SELECT
                            n.signo
                        FROM
                            facturacion_inventario.transaccion AS t
                            INNER JOIN facturacion_inventario.transaccion_concepto AS tc ON tc.id_transaccion = t.id_transaccion
                            INNER JOIN facturacion_inventario.concepto AS c ON c.id_concepto = tc.id_concepto
                            INNER JOIN contabilidad.tipo_documento AS td ON td.id_tipo_documento = c.id_tipo_documento
                            INNER JOIN contabilidad.naturaleza AS n ON n.id_naturaleza = td.id_naturaleza
                        WHERE
                            t.id_transaccion = $this->idTransaccion
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $fila = $this->conexion->obtenerObjeto();
        return $fila->signo;
    }

    public function obtenerInfoTipoDocumento($codigoNaturaleza) {
        $sentenciaSql = "
                        SELECT
                            id_naturaleza
                        FROM
                            contabilidad.naturaleza AS n
                        WHERE
                            codigo = '$codigoNaturaleza'
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $fila = $this->conexion->obtenerObjeto();
        $idNaturaleza = $fila->id_naturaleza;

        $tipoDocumentoE = new \entidad\TipoDocumento();
        $tipoDocumentoE->setIdNaturaleza($idNaturaleza);

        $tipoDocumentoM = new \modelo\TipoDocumento($tipoDocumentoE);
        return $tipoDocumentoM->consultar();
    }
    
    public function obtenerIdTransaccionHijo($idTransaccionPadre) {
        $sentenciaSql = "
                        SELECT
                            id_transaccion AS id_transaccion_hijo
                        FROM
                            facturacion_inventario.transaccion
                        WHERE
                            id_transaccion_padre = $idTransaccionPadre
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $fila = $this->conexion->obtenerObjeto();
        $idTransaccionHijo = $fila->id_transaccion_hijo;
        return $idTransaccionHijo;
    }

    //------------------------------------MÉTODOS DE FACTURACIÓN----------------------------------

    public function obtenerEstilosFactura() {
        $retorno = array("exito" => 1, "mensaje" => "", "data" => null);
        try {

            $sentenciaSql = "
                            SELECT
                                ruta_logo_empresa
                                , ruta_fondo
                                , color_fondo_titulos
                                , color_fondo_contenido
                                , color_texto_titulos
                                , color_texto_contenido
                                , numero_registros_pagina
                                , tamanio_fuente
                            FROM
                                facturacion_inventario.parametrizacion_impresion_documento
                            ";
            $this->conexion->ejecutar($sentenciaSql);

            if ($this->conexion->obtenerNumeroRegistros() == 0) {
                throw new \Exception("ERROR -> No se ha realizado la parametrización para la impresión del documento.");
            }

            $fila = $this->conexion->obtenerObjeto();
            $retorno["data"]["rutaLogoEmpresa"] = $fila->ruta_logo_empresa;
            $retorno["data"]["rutaFondo"] = $fila->ruta_fondo;
            $retorno["data"]["colorFondoTitulos"] = $fila->color_fondo_titulos;
            $retorno["data"]["colorFondoContenido"] = $fila->color_fondo_contenido;
            $retorno["data"]["colorTextoTitulos"] = $fila->color_texto_titulos;
            $retorno["data"]["colorTextoContenido"] = $fila->color_texto_contenido;
            $retorno["data"]["numeroRegistrosPagina"] = $fila->numero_registros_pagina;
            $retorno["data"]["tamanioFuente"] = $fila->tamanio_fuente;
        } catch (\Exception $e) {
            $retorno["exito"] = 0;
            $retorno["mensaje"] = $e->getMessage();
        }
        return $retorno;
    }

    public function obtenerInfoEmpresaUsuario() {
        $sentenciaSql = "
                        SELECT
                            t.nit
                            , UPPER(t.tercero) AS empresa
                            , t.digito_verificacion
                            , td.tercero_direccion || ' ' || UPPER(m.municipio) AS direccion
                            , te.tercero_email AS email
                            --, tt.tercero_telefono AS telefono
                            , (SELECT 
                                string_agg(telefonos_tt.tercero_telefono, ' / ') AS value             
                            FROM
                                contabilidad.tercero AS telefonos_t
                                INNER JOIN contabilidad.tercero_telefono AS telefonos_tt ON telefonos_tt.id_tercero = telefonos_t.id_tercero
                            WHERE
                                telefonos_t.id_tercero = t.id_tercero
                            ) AS telefonos
                            , tr.tipo_regimen
                            , tr.factura
                        FROM
                            seguridad.usuario AS u
                            INNER JOIN contabilidad.empresa AS e ON e.id_empresa = u.id_empresa
                            INNER JOIN contabilidad.tercero AS t ON t.id_tercero = e.id_tercero
                            INNER JOIN contabilidad.tercero_direccion AS td ON td.id_tercero = t.id_tercero
                            INNER JOIN general.municipio AS m ON m.id_municipio = td.id_municipio
                            --INNER JOIN contabilidad.tercero_telefono AS tt ON tt.id_tercero = t.id_tercero
                            INNER JOIN contabilidad.tercero_email AS te ON te.id_tercero = t.id_tercero
                            INNER JOIN contabilidad.tipo_regimen AS tr ON tr.id_tipo_regimen = t.id_tipo_regimen
                        WHERE
                            u.id_usuario = " . $_SESSION["idUsuario"];
        $this->conexion->ejecutar($sentenciaSql);
        if ($this->conexion->obtenerNumeroRegistros() > 0) {
            $fila = $this->conexion->obtenerObjeto();
            $retorno["nit"] = $fila->nit;
            $retorno["empresa"] = $fila->empresa;
            $retorno["digitoVerificacion"] = $fila->digito_verificacion;
            $retorno["direccion"] = $fila->direccion;
            $retorno["email"] = $fila->email;
            $retorno["telefono"] = $fila->telefonos;
            $retorno["tipoRegimen"] = $fila->tipo_regimen;
            $retorno["factura"] = $fila->factura;
        }
        return $retorno;
    }

    public function obtenerInfoFactura() {

        //Configuro fecha en español para postgres
        $sentenciaSql = "SET lc_time='Spanish_Spain.1252'";
        $this->conexion->ejecutar($sentenciaSql);

        $sentenciaSql = "
                        SELECT
                            t.numero_tipo_documento AS numero_factura
                            , CONCAT(
				(select to_char(t.fecha_vencimiento, 'TMMonth'))
				, ' ', to_char(t.fecha_vencimiento, 'DD')
				, ' de ', to_char(t.fecha_vencimiento, 'YYYY')
                            )AS fecha_vencimiento
                            , cfi.visualizar_forma_pago
                        FROM
                            facturacion_inventario.transaccion AS t
                            INNER JOIN facturacion_inventario.transaccion_cliente AS tc ON t.id_transaccion = tc.id_transaccion
                            INNER JOIN facturacion_inventario.caja_formato_impresion AS cfi ON t.id_formato_impresion = cfi.id_formato_impresion AND tc.id_caja= cfi.id_caja 
                        WHERE
                            t.id_transaccion = $this->idTransaccion
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        if ($this->conexion->obtenerNumeroRegistros() > 0) {
            $fila = $this->conexion->obtenerObjeto();
            $retorno["numeroFactura"] = $fila->numero_factura;
            $retorno["fechaVencimiento"] = $fila->fecha_vencimiento;
            $retorno["visualizarFormaPago"] = $fila->visualizar_forma_pago;
        }
        return $retorno;
    }

    public function obtenerInfoCajero() {
        $sentenciaSql = "
                        SELECT
                            p.persona AS cajero
                            , c.numero_caja
                            , c.id_caja
                            , c.prefijo
                            , c.fecha_expedicion_resolucion
                            , c.numero_maximo
                            , c.numero_minimo
                            , c.numero_resolucion
                            , u.usuario
                        FROM
                            facturacion_inventario.asignacion_caja AS ac
                            INNER JOIN facturacion_inventario.caja AS c ON c.id_caja = ac.id_caja
                            INNER JOIN facturacion_inventario.cajero AS cj ON cj.id_cajero = ac.id_cajero
                            INNER JOIN seguridad.usuario AS u ON u.id_usuario = cj.id_usuario
                            INNER JOIN general.persona AS p ON p.id_persona = u.id_persona
                        WHERE
                            u.id_usuario = " . $_SESSION["idUsuario"];
        $this->conexion->ejecutar($sentenciaSql);
        if ($this->conexion->obtenerNumeroRegistros() > 0) {
            $fila = $this->conexion->obtenerObjeto();
            $retorno["numeroCaja"] = $fila->numero_caja;
            $retorno["cajero"] = $fila->cajero;
            $retorno["idCaja"] = $fila->id_caja;
            $retorno["prefijo"] = $fila->prefijo;
            $retorno["fechaExpedicionResolucion"] = $fila->fecha_expedicion_resolucion;
            $retorno["numeroMaximo"] = $fila->numero_maximo;
            $retorno["numeroMinimo"] = $fila->numero_minimo;
            $retorno["numeroResolucion"] = $fila->numero_resolucion;
            $retorno["usuario"] = $fila->usuario;
        }
        return $retorno;
    }

    public function obtenerInfoCliente() {
        $sentenciaSql = "
                        SELECT
                            t.nit
                            , t.digito_verificacion
                            , CONCAT_WS(' - ', t.tercero, s.sucursal) AS cliente
                            , td.tercero_direccion AS direccion
                            , UPPER(m.municipio) AS municipio
                            , tt.tercero_telefono AS telefono
                            , t.tercero AS tercero
                            , c.muestra_forma_pago_factura
                            , c.muestra_firmas_factura
                        FROM
                            facturacion_inventario.transaccion_cliente AS tc
                            INNER JOIN facturacion_inventario.cliente AS c ON c.id_cliente = tc.id_cliente
                            INNER JOIN contabilidad.sucursal AS s ON s.id_sucursal = c.id_sucursal
                            INNER JOIN contabilidad.tercero AS t ON t.id_tercero = s.id_tercero
                            INNER JOIN contabilidad.tercero_direccion AS td ON td.id_tercero_direccion = s.id_tercero_direccion AND td.principal = true
                            INNER JOIN general.municipio AS m ON m.id_municipio = td.id_municipio
                            INNER JOIN contabilidad.tercero_telefono AS tt ON tt.id_tercero_telefono = s.id_tercero_telefono AND tt.principal = true
                        WHERE
                            tc.id_transaccion = " . $this->idTransaccion;
        $this->conexion->ejecutar($sentenciaSql);
        if ($this->conexion->obtenerNumeroRegistros() > 0) {
            $fila = $this->conexion->obtenerObjeto();

            if ($fila->digito_verificacion == '-1') {
                $retorno["nit"] = $fila->nit;
            } else {
                $retorno["nit"] = $fila->nit . ' - ' . $fila->digito_verificacion;
            }

            $retorno["cliente"] = $fila->cliente;
            $retorno["direccion"] = $fila->direccion;
            $retorno["telefono"] = $fila->telefono;
            $retorno["tercero"] = $fila->tercero;
            $retorno["municipio"] = $fila->municipio;
            $retorno["muestraFormaPagoFactura"] = $fila->muestra_forma_pago_factura;
            $retorno["muestraFirmasFactura"] = $fila->muestra_firmas_factura;
        }
        return $retorno;
    }

    public function obtenerInfoConcepto() {
        $sentenciaSql = "
                        SELECT
                            tc.id_transaccion_concepto
                            , tc.id_concepto
                            , td.tipo_documento AS concepto
                        FROM
                            facturacion_inventario.transaccion_concepto AS tc
                            INNER JOIN facturacion_inventario.concepto AS c ON c.id_concepto = tc.id_concepto
                            INNER JOIN contabilidad.tipo_documento AS td ON td.id_tipo_documento = c.id_tipo_documento
                        WHERE
                            tc.id_transaccion = " . $this->idTransaccion;
        $this->conexion->ejecutar($sentenciaSql);
        if ($this->conexion->obtenerNumeroRegistros() > 0) {
            $fila = $this->conexion->obtenerObjeto();
            $retorno["idTransaccionConcepto"] = $fila->id_transaccion_concepto;
            $retorno["idConcepto"] = $fila->id_concepto;
            $retorno["concepto"] = $fila->concepto;
        }
        return $retorno;
    }

    public function consultarDetalleTransaccion($parametros) {
        $this->obtenerCondicion();
        $bodega = '';
        if ($parametros['bodega'] == 'true') {
            $bodega = ', bodega';
        }
        if ($parametros['idProveedor'] != "" && $parametros['idProveedor'] != null && $parametros['idProveedor'] != "null") {
            $this->condicion = $this->condicion . $this->whereAnd . " tpr.id_proveedor IN(" . $parametros['idProveedor'] . ")";
            $this->whereAnd = " AND ";
        }
        if ($parametros['idLineaProducto'] != "" && $parametros['idLineaProducto'] != null && $parametros['idLineaProducto'] != "null") {
            $this->condicion = $this->condicion . $this->whereAnd . " lp.id_linea_producto IN(" . $parametros['idLineaProducto'] . ")";
            $this->whereAnd = " AND ";
        }
        if ($parametros['idProducto'] != "" && $parametros['idProducto'] != null && $parametros['idProducto'] != "null") {
            $this->condicion = $this->condicion . $this->whereAnd . " p.id_producto IN(" . $parametros['idProducto'] . ")";
            $this->whereAnd = " AND ";
        }
        if ($parametros['idBodega'] != "" && $parametros['idBodega'] != null && $parametros['idBodega'] != "null") {
            $this->condicion = $this->condicion . $this->whereAnd . " pe.id_bodega IN(" . $parametros['idBodega'] . ")";
            $this->whereAnd = " AND ";
        }
        $sentenciaSql = "SELECT DISTINCT
                            o.oficina
                            , td.tipo_documento
                            , t.fecha
                            , lp.linea_producto
                            , p.codigo
                            , p.producto
                            , SUM(pe.cantidad) as cantidad
                            , CONCAT_WS(' - ', ter.nit, ter.tercero) AS tercero
                            , CAST(t.fecha AS DATE) AS fecha
                            , CONCAT_WS(' - ', trc.nit, trc.tercero) AS proveedor
                            , et.estado_transaccion
                            $bodega
                        FROM
                            facturacion_inventario.transaccion AS t
                            INNER JOIN contabilidad.tipo_documento AS td ON td.id_tipo_documento = t.id_tipo_documento
                            INNER JOIN contabilidad.tercero AS ter ON ter.id_tercero = t.id_tercero
                            INNER JOIN contabilidad.oficina AS o ON o.id_oficina = t.id_oficina
                            INNER JOIN facturacion_inventario.estado_transaccion AS et ON et.id_estado_transaccion = t.id_transaccion_estado
                            INNER JOIN facturacion_inventario.transaccion_producto AS tp ON tp.id_transaccion = t.id_transaccion
                            INNER JOIN facturacion_inventario.producto AS p ON p.id_producto = tp.id_producto
                            INNER JOIN facturacion_inventario.producto_existencia AS pe ON pe.id_producto = p.id_producto
                            INNER JOIN facturacion_inventario.linea_producto AS lp ON lp.id_linea_producto = p.id_linea_producto
                            INNER JOIN facturacion_inventario.transaccion_proveedor AS tpr ON tpr.id_transaccion = t.id_transaccion
                            INNER JOIN facturacion_inventario.proveedor AS pro ON pro.id_proveedor = tpr.id_proveedor
                            INNER JOIN contabilidad.sucursal s ON s.id_sucursal = pro.id_sucursal
                            INNER JOIN contabilidad.tercero trc ON trc.id_tercero = s.id_tercero
                            INNER JOIN facturacion_inventario.bodega AS b ON b.id_bodega = pe.id_bodega
                            $this->condicion
                        GROUP BY
                            t.id_transaccion, o.oficina, td.tipo_documento, t.fecha, lp.linea_producto, p.producto
                            , ter.nit, ter.tercero, t.nota, t.fecha, t.fecha_vencimiento, trc.nit, trc.tercero, et.estado_transaccion
                            , t.numero_tipo_documento, p.codigo $bodega
                        ORDER BY producto $bodega
	";


        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        $retorno = array();

        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]['idTransaccion'] = $fila->id_transaccion;
            $retorno[$contador]['oficina'] = $fila->oficina;
            $retorno[$contador]['bodega'] = $fila->bodega;
            $retorno[$contador]['tipoDocumento'] = $fila->tipo_documento;
            $retorno[$contador]['fecha'] = $fila->fecha;
            $retorno[$contador]['lineaProducto'] = $fila->linea_producto;
            $retorno[$contador]['codigo'] = $fila->codigo;
            $retorno[$contador]['producto'] = $fila->producto;
            $retorno[$contador]['cantidad'] = $fila->cantidad;
            $retorno[$contador]['tercero'] = $fila->tercero;
            $retorno[$contador]['proveedor'] = $fila->proveedor;
            $retorno[$contador]['estadoTransaccion'] = $fila->estado_transaccion;

            $contador++;
        }
        return $retorno;
    }

    public function obtenerEstadoInicial() {
        $sentenciaSql = "
                        SELECT
                            id_estado_transaccion
                        FROM
                            facturacion_inventario.estado_transaccion
                        WHERE
                            inicial = true
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        if ($this->conexion->obtenerNumeroRegistros() == 0) {
            $return['exito'] = 0;
            $return['mensaje'] = 'No hay ningún estado de transacción inicial.';
        }

        if ($this->conexion->obtenerNumeroRegistros() > 1) {
            $return['exito'] = 0;
            $return['mensaje'] = 'Hay mas de un estado de tipo inicial.';
        }

        $fila = $this->conexion->obtenerObjeto();

        return $fila->id_estado_transaccion;
    }

    public function consultarDetalleTraslado() {
        $this->obtenerCondicion();
        $sentenciaSql = "
                        SELECT
                            t.id_transaccion
                            , td.tipo_documento
                            , t.id_tercero
                            , CONCAT_WS(' - ', ter.nit, ter.tercero) AS tercero
                            , o.oficina
                            , t.nota
                            , t.nota
                            , CAST(t.fecha AS DATE) AS fecha
                            , CAST(t.fecha_vencimiento AS DATE) AS fecha_vencimiento
                            , et.estado_transaccion
                            , t.numero_tipo_documento
                        FROM
                            facturacion_inventario.transaccion AS t
                            INNER JOIN contabilidad.tipo_documento AS td ON td.id_tipo_documento = t.id_tipo_documento
                            INNER JOIN contabilidad.naturaleza AS n ON n.id_naturaleza = td.id_naturaleza
                            INNER JOIN contabilidad.tercero AS ter ON ter.id_tercero = t.id_tercero
                            INNER JOIN contabilidad.oficina AS o ON o.id_oficina = t.id_oficina
                            INNER JOIN facturacion_inventario.estado_transaccion AS et ON et.id_estado_transaccion = t.id_transaccion_estado
                            $this->condicion $this->whereAnd td.visualiza = TRUE
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["idTransaccion"] = $fila->id_transaccion;
            $retorno[$contador]["tipoDocumento"] = $fila->tipo_documento;
            $retorno[$contador]["idTercero"] = $fila->id_tercero;
            $retorno[$contador]["tercero"] = $fila->tercero;
            $retorno[$contador]["oficina"] = $fila->oficina;
            $retorno[$contador]["nota"] = $fila->nota;
            $retorno[$contador]["fecha"] = $fila->fecha;
            $retorno[$contador]["fechaVencimiento"] = $fila->fecha_vencimiento;
            $retorno[$contador]["transaccionEstado"] = $fila->estado_transaccion;
            $retorno[$contador]["numeroTipoDocumento"] = $fila->numero_tipo_documento;
            $contador++;
        }
        return $retorno;
    }

    public function consultarFacturas($parametros) {
        $this->obtenerCondicionFatura($parametros);
        $sentenciaSql = "
                        SELECT
                            t.id_transaccion
                            , CONCAT_WS(' - ', ter.tercero, s.sucursal) AS cliente
			    , to_char(t.fecha , 'YYYY-MM-DD HH12:MI:SS AM') AS fecha
			    , t.numero_tipo_documento AS numero_recibo
                            , cj.numero_caja
			    , o.oficina
                            , te.estado_transaccion
                            , ta.numero_tipo_documento AS numero_recibo_afecta
                        FROM
                            facturacion_inventario.transaccion t
                            INNER JOIN facturacion_inventario.estado_transaccion te ON te.id_estado_transaccion = t.id_transaccion_estado
                            INNER JOIN contabilidad.oficina AS o ON o.id_oficina = t.id_oficina
                            INNER JOIN facturacion_inventario.transaccion_cliente AS tc ON tc.id_transaccion = t.id_transaccion
                            INNER JOIN facturacion_inventario.caja AS cj ON cj.id_caja = tc.id_caja
                            INNER JOIN facturacion_inventario.cliente AS c ON c.id_cliente = tc.id_cliente
                            INNER JOIN contabilidad.sucursal AS s ON s.id_sucursal = c.id_sucursal
                            INNER JOIN contabilidad.tercero AS ter ON ter.id_tercero = s.id_tercero
                            INNER JOIN facturacion_inventario.transaccion AS ta ON ta.id_transaccion = t.id_transaccion_afecta
                            $this->condicion $this->whereAnd t.id_tipo_documento IN(15)
                        ORDER BY
                            t.fecha
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["idTransaccion"] = $fila->id_transaccion;
            $retorno[$contador]["cliente"] = $fila->cliente;
            $retorno[$contador]["fecha"] = $fila->fecha;
            $retorno[$contador]["numeroRecibo"] = $fila->numero_recibo;
            $retorno[$contador]["numeroReciboAfecta"] = $fila->numero_recibo_afecta;
            $retorno[$contador]["numeroCaja"] = $fila->numero_caja;
            $retorno[$contador]["oficina"] = $fila->oficina;
            $retorno[$contador]["estadoTransaccion"] = $fila->estado_transaccion;
            $total = $total + $fila->valor_total;
            $contador++;
        }
        return $retorno;
    }

    function obtenerFacturaAfectada($idTransaccion) {
        $conexionNueva = new \Conexion();
        $sentenciaSql = "
                            SELECT DISTINCT
                                t.numero_tipo_documento AS numero_recibo
                            FROM 
				
				facturacion_inventario.transaccion_producto AS tp
				INNER JOIN facturacion_inventario.transaccion_producto AS tpa ON tpa.id_transaccion_producto = tp.id_transaccion_producto_afecta
				INNER JOIN facturacion_inventario.transaccion AS t ON t.id_transaccion = tpa.id_transaccion
			    WHERE
			         tp.id_transaccion = $idTransaccion		
                        ";
        $conexionNueva->ejecutar($sentenciaSql);
        $fila = $conexionNueva->obtenerObjeto();
        return $fila->numero_recibo;
    }

    function obtenerCondicionFatura($parametros) {
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

        if ($parametros["numeroReciboAfecta"] != "" && $parametros["numeroReciboAfecta"] != null && $parametros["numeroReciboAfecta"] != "null") {
            $this->condicion = $this->condicion . $this->whereAnd . " ta.numero_tipo_documento IN (" . $parametros["numeroReciboAfecta"] . ")";
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

        if ($parametros["idEstadoTransaccion"] != "" && $parametros["idEstadoTransaccion"] != null && $parametros["idEstadoTransaccion"] != "null") {
            $this->condicion = $this->condicion . $this->whereAnd . " t.id_transaccion_estado IN (" . $parametros["idEstadoTransaccion"] . ")";
            $this->whereAnd = " AND ";
        }
    }

    public function consultarRecibosCaja($idTransaccionConcepto) {
        $sentenciaSql = "
                        SELECT
                            t.numero_tipo_documento
                            , td.tipo_documento
                            , to_char(t.fecha , 'YYYY-MM-DD HH12:MI:SS AM') AS fecha
                            , tcp.valor
                        FROM
                            facturacion_inventario.transaccion_cruce AS tc
                            INNER JOIN facturacion_inventario.transaccion_concepto AS tcp ON tcp.id_transaccion_concepto = tc.id_transaccion_concepto_origen
                            INNER JOIN facturacion_inventario.concepto AS c ON c.id_concepto = tcp.id_concepto
                            INNER JOIN contabilidad.tipo_documento AS td ON td.id_tipo_documento = c.id_tipo_documento
                            INNER JOIN facturacion_inventario.transaccion AS t ON t.id_transaccion = tcp.id_transaccion
                        WHERE
                            tc.id_transaccion_concepto_afectado = $idTransaccionConcepto
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]['numeroDocumento'] = $fila->numero_tipo_documento;
            $retorno[$contador]['tipoDocumento'] = $fila->tipo_documento;
            $retorno[$contador]['fecha'] = $fila->fecha;
            $retorno[$contador]['valor'] = $fila->valor;
            $contador++;
        }
        return $retorno;
    }

    public function consultarTiposDocumento($parametros) {

        $innerJoinClienteProveedor = "";
        $nombreCampo = "";

        if ($parametros['tipoClienteProveedor'] == 'P') {
            $innerJoinClienteProveedor = "INNER JOIN facturacion_inventario.transaccion_proveedor AS tc ON tc.id_transaccion = tcp.id_transaccion";
            $nombreCampo = "id_proveedor";
        } else {
            $innerJoinClienteProveedor = "INNER JOIN facturacion_inventario.transaccion_cliente AS tc ON tc.id_transaccion = tcp.id_transaccion";
            $nombreCampo = "id_cliente";
        }

        $sentenciaSql = "
                        SELECT DISTINCT
                            c.id_tipo_documento
                            , td.tipo_documento
                        FROM
                            facturacion_inventario.transaccion_concepto AS tcp
                            INNER JOIN facturacion_inventario.concepto AS c ON c.id_concepto = tcp.id_concepto
                            INNER JOIN contabilidad.tipo_documento AS td ON td.id_tipo_documento = c.id_tipo_documento
                            --INNER JOIN facturacion_inventario.transaccion_cliente AS tc ON tc.id_transaccion = tcp.id_transaccion
                            $innerJoinClienteProveedor
                        WHERE
                            tc.$nombreCampo = " . $parametros["idCliente"] . "
                            AND c.id_tipo_documento <> " . $parametros["idTipoDocumentoOmitir"];
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]['idTipoDocumento'] = $fila->id_tipo_documento;
            $retorno[$contador]['tipoDocumento'] = $fila->tipo_documento;
            $contador++;
        }
        return $retorno;
    }

    public function consultarFacturacion($parametros) {
        $this->obtenerCondicionFacturacion($parametros);

        $innerJoinClienteProveedor = "";

        if ($parametros["tipoClienteProveedor"] == 'P') {
            $innerJoinClienteProveedor = "
                            INNER JOIN facturacion_inventario.transaccion_proveedor AS tc ON tc.id_transaccion = t.id_transaccion
                            INNER JOIN facturacion_inventario.proveedor AS c ON c.id_proveedor = tc.id_proveedor
                         ";
        } else {
            $innerJoinClienteProveedor = "
                            INNER JOIN facturacion_inventario.transaccion_cliente AS tc ON tc.id_transaccion = t.id_transaccion
                            INNER JOIN facturacion_inventario.cliente AS c ON c.id_cliente = tc.id_cliente
                         ";
        }

        $sentenciaSql = "
                        SELECT DISTINCT
                            t.id_transaccion
                            , t.numero_tipo_documento AS numero_factura
                            , ter.nit
                            --, ter.tercero || ' - ' || s.sucursal AS cliente
                            , ter.tercero AS cliente
                            , to_char(t.fecha , 'YYYY-MM-DD HH12:MI:SS AM') AS fecha
                            , et.estado_transaccion
                            , tcp.valor
                            , tcp.saldo
                            , td.tipo_documento
                            , tcp.id_transaccion_concepto
                            , n.id_tipo_naturaleza
                            , tn.codigo
                            , tnt.codigo AS codigo_tipo_naturaleza
                            , (SELECT COALESCE(SUM(valor), 0) FROM facturacion_inventario.transaccion_impuesto WHERE id_transaccion = t.id_transaccion) AS total_impuesto
                        FROM
                            facturacion_inventario.transaccion AS t 
                            INNER JOIN contabilidad.tipo_documento AS tdt ON tdt.id_tipo_documento = t.id_tipo_documento
                            INNER JOIN contabilidad.naturaleza AS nt ON nt.id_naturaleza = tdt.id_naturaleza
                            INNER JOIN contabilidad.tipo_naturaleza AS tnt ON tnt.id_tipo_naturaleza = nt.id_tipo_naturaleza
                            INNER JOIN facturacion_inventario.transaccion_concepto AS tcp ON tcp.id_transaccion = t.id_transaccion
                            INNER JOIN facturacion_inventario.concepto AS cp ON cp.id_concepto = tcp.id_concepto
                            INNER JOIN contabilidad.tipo_documento AS td ON td.id_tipo_documento = cp.id_tipo_documento
                            INNER JOIN contabilidad.naturaleza AS n ON n.id_naturaleza = td.id_naturaleza
                            INNER JOIN contabilidad.tipo_naturaleza AS tn ON tn.id_tipo_naturaleza = n.id_tipo_naturaleza
                            INNER JOIN facturacion_inventario.transaccion_forma_pago AS tfp ON tfp.id_transaccion = t.id_transaccion
                            INNER JOIN facturacion_inventario.estado_transaccion AS et ON et.id_estado_transaccion = t.id_transaccion_estado
                            /* $innerJoinClienteProveedor */
                            --INNER JOIN facturacion_inventario.transaccion_cliente AS tc ON tc.id_transaccion = t.id_transaccion
                            --INNER JOIN facturacion_inventario.cliente AS c ON c.id_cliente = tc.id_cliente
                            --INNER JOIN contabilidad.sucursal AS s ON s.id_sucursal = c.id_sucursal
                            INNER JOIN contabilidad.tercero AS ter ON ter.id_tercero = t.id_tercero
                            $this->condicion
                        ORDER BY
                            t.numero_tipo_documento DESC
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]['idTransaccion'] = $fila->id_transaccion;
            $retorno[$contador]['numeroFactura'] = $fila->numero_factura;
            $retorno[$contador]['nit'] = $fila->nit;
            $retorno[$contador]['cliente'] = $fila->cliente;
            $retorno[$contador]['fecha'] = $fila->fecha;
            $retorno[$contador]['estadoTransaccion'] = $fila->estado_transaccion;
            $retorno[$contador]['valor'] = $fila->valor;
            $retorno[$contador]['saldo'] = $fila->saldo;
            $retorno[$contador]['tipoDocumento'] = $fila->tipo_documento;
            $retorno[$contador]['idTransaccionConcepto'] = $fila->id_transaccion_concepto;
            $retorno[$contador]['tipo'] = $fila->codigo;
            $retorno[$contador]['codigoTipoNaturaleza'] = $fila->codigo_tipo_naturaleza;
            $retorno[$contador]['totalImpuesto'] = $fila->total_impuesto;
            $contador++;
        }
        return $retorno;
    }

    private function obtenerCondicionFacturacion($parametros) {
        $this->condicion = "";
        $this->whereAnd = " WHERE ";

        if ($this->tercero->getIdTercero() != "" && $this->tercero->getIdTercero() != null && $this->tercero->getIdTercero() != "null") {
            $this->condicion = $this->condicion . $this->whereAnd . " t.id_tercero = " . $this->tercero->getIdTercero();
            $this->whereAnd = " AND ";
        }

        if ($this->idTipoDocumento != "" && $this->idTipoDocumento != null && $this->idTipoDocumento != "null") {
            $this->condicion = $this->condicion . $this->whereAnd . " cp.id_tipo_documento = " . $this->idTipoDocumento;
            $this->whereAnd = " AND ";
        }

        if ($this->numeroTipoDocumento != "" && $this->numeroTipoDocumento != null && $this->numeroTipoDocumento != "null") {
            $this->condicion = $this->condicion . $this->whereAnd . " t.numero_tipo_documento = " . $this->numeroTipoDocumento;
            $this->whereAnd = " AND ";
        }

        if ($this->fecha["inicio"] != '' && $this->fecha["fin"] != '') {
            $this->condicion = $this->condicion . $this->whereAnd . " CAST(t.fecha AS DATE) BETWEEN '" . $this->fecha["inicio"] . "' AND '" . $this->fecha["fin"] . "'";
            $this->whereAnd = ' AND ';
        } elseif ($this->fecha["inicio"] != '' && $this->fecha["fin"] == '') {
            $this->condicion = $this->condicion . $this->whereAnd . " CAST(t.fecha AS DATE) = '" . $this->fecha["inicio"] . "'";
            $this->whereAnd = ' AND ';
        } elseif ($this->fecha["inicio"] == '' && $this->fecha["fin"] != '') {
            $this->condicion = $this->condicion . $this->whereAnd . " CAST(t.fecha AS DATE) = '" . $this->fecha["fin"] . "'";
            $this->whereAnd = ' AND ';
        }

        if ($parametros['idCaja'] != "" && $parametros['idCaja'] != null && $parametros['idCaja'] != "null") {
            $this->condicion = $this->condicion . $this->whereAnd . " tc.id_caja = " . $parametros['idCaja'];
            $this->whereAnd = " AND ";
        }

        if ($parametros['idFormaPago'] != "" && $parametros['idFormaPago'] != null && $parametros['idFormaPago'] != "null") {
            $this->condicion = $this->condicion . $this->whereAnd . " tfp.id_forma_pago = " . $parametros['idFormaPago'];
            $this->whereAnd = " AND ";
        }

        if ($parametros['idCliente'] != "" && $parametros['idCliente'] != null && $parametros['idCliente'] != "null") {

            if ($parametros['tipoClienteProveedor'] == 'P') {
                $this->condicion = $this->condicion . $this->whereAnd . " tc.id_proveedor = " . $parametros['idCliente'];
            } else {
                $this->condicion = $this->condicion . $this->whereAnd . " tc.id_cliente = " . $parametros['idCliente'];
            }

            $this->whereAnd = " AND ";
        }

        if ($parametros['tipo'] != "" && $parametros['tipo'] != null && $parametros['tipo'] != "null") {
            $this->condicion = $this->condicion . $this->whereAnd . " tnt.codigo = '" . $parametros['tipo'] . "'";
            $this->whereAnd = " AND ";
        }

        if ($this->idTransaccionEstado != "" && $this->idTransaccionEstado != null && $this->idTransaccionEstado != "null") {
            $this->condicion = $this->condicion . $this->whereAnd . " t.id_transaccion_estado = " . $this->idTransaccionEstado;
            $this->whereAnd = " AND ";
        }

        if ($this->saldo != "" && $this->saldo != null && $this->saldo != "null" && $this->saldo != "0") {
            $this->condicion = $this->condicion . $this->whereAnd . " tcp.saldo " . $this->saldo;
            $this->whereAnd = " AND ";
        }
    }

    public function transaccionConsultarProductoCompuesto($parametros){
        $this->obtenerCondicionProductoCompuesto($parametros);
        $sentenciaSql = "
                        SELECT
                            t.id_transaccion
                            , tp.id_producto
                            , p.codigo
                            , p.producto
                            , um.unidad_medida
                            , lp.linea_producto
                            , tp.cantidad
                            , tp.saldo_cantidad_producto
                            , t.id_transaccion_estado
                            , et.estado_transaccion
                            , CAST(t.fecha AS DATE) AS fecha
                            , td.tipo_documento
                        FROM
                            facturacion_inventario.transaccion AS t
                            INNER JOIN facturacion_inventario.transaccion_concepto AS tc ON tc.id_transaccion = t.id_transaccion
                            INNER JOIN facturacion_inventario.concepto AS c ON c.id_concepto = tc.id_concepto
                            INNER JOIN contabilidad.tipo_documento AS td ON td.id_tipo_documento = c.id_tipo_documento
                            INNER JOIN facturacion_inventario.transaccion_producto AS tp ON tp.id_transaccion = t.id_transaccion
                            INNER JOIN facturacion_inventario.producto AS p ON p.id_producto = tp.id_producto
                            INNER JOIN facturacion_inventario.unidad_medida AS um ON um.id_unidad_medida = p.id_unidad_medida
                            INNER JOIN facturacion_inventario.linea_producto AS lp ON lp.id_linea_producto = p.id_linea_producto
                            INNER JOIN facturacion_inventario.estado_transaccion AS et  ON et.id_estado_transaccion = t.id_transaccion_estado
                            $this->condicion
                        ORDER BY
                            t.fecha
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]['idTransaccion'] = $fila->id_transaccion;
            $retorno[$contador]['idProducto'] = $fila->id_producto;
            $retorno[$contador]['codigo'] = $fila->codigo;
            $retorno[$contador]['producto'] = $fila->producto;
            $retorno[$contador]['unidadMedida'] = $fila->unidad_medida;
            $retorno[$contador]['lineaProducto'] = $fila->linea_producto;
            $retorno[$contador]['cantidad'] = $fila->cantidad;
            $retorno[$contador]['saldoCantidadProducto'] = $fila->saldo_cantidad_producto;
            $retorno[$contador]['idTransaccionEstado'] = $fila->id_transaccion_estado;
            $retorno[$contador]['transaccionEstado'] = $fila->estado_transaccion;
            $retorno[$contador]['fecha'] = $fila->fecha;
            $retorno[$contador]['tipoDocumento'] = $fila->tipo_documento;
            $contador++;
        }
        return $retorno;
    }
    
    private function obtenerCondicionProductoCompuesto($parametros) {
        $this->condicion = "";
        $this->whereAnd = " WHERE ";

        if ($this->fecha["inicio"] != '' && $this->fecha["fin"] != '') {
            $this->condicion = $this->condicion . $this->whereAnd . " CAST(t.fecha AS DATE) BETWEEN '" . $this->fecha["inicio"] . "' AND '" . $this->fecha["fin"] . "'";
            $this->whereAnd = ' AND ';
        } elseif ($this->fecha["inicio"] != '' && $this->fecha["fin"] == '') {
            $this->condicion = $this->condicion . $this->whereAnd . " CAST(t.fecha AS DATE) = '" . $this->fecha["inicio"] . "'";
            $this->whereAnd = ' AND ';
        } elseif ($this->fecha["inicio"] == '' && $this->fecha["fin"] != '') {
            $this->condicion = $this->condicion . $this->whereAnd . " CAST(t.fecha AS DATE) = '" . $this->fecha["fin"] . "'";
            $this->whereAnd = ' AND ';
        }

        if ($parametros['idProducto'] != "" && $parametros['idProducto'] != null && $parametros['idProducto'] != "null") {
            $this->condicion = $this->condicion . $this->whereAnd . " tp.id_producto = '" . $parametros['idProducto'] . "'";
            $this->whereAnd = " AND ";
        }

        if ($parametros['codigoTipoDocumento'] != "" && $parametros['codigoTipoDocumento'] != null && $parametros['codigoTipoDocumento'] != "null") {
            $this->condicion = $this->condicion . $this->whereAnd . " td.codigo = '" . $parametros['codigoTipoDocumento'] . "'";
            $this->whereAnd = " AND ";
        }
        
        if ($this->idTransaccionEstado != "" && $this->idTransaccionEstado != null && $this->idTransaccionEstado != "null") {
            $this->condicion = $this->condicion . $this->whereAnd . " t.id_transaccion_estado = " . $this->idTransaccionEstado;
            $this->whereAnd = " AND ";
        }
    }
    
    public function anularFactura() {
        try {
            $this->conexion = new \Conexion();
            $this->conexion->iniciarTransaccion();

            $arrInfoTipoDocumento = $this->obtenerInfoTipoDocumento('VE-AN'); //Se envia "VE-AN" porque es el codigo de la naturaleza que tiene el tipo de documento "ANULACION DE FACTURAS"
            $idTipoDocumento = $arrInfoTipoDocumento[0]["idTipoDocumento"];

            //--------------CONSULTO EL CONCEPTO A PARTIR DEL TIPO DE DOCUMENTO-----------------------
            $conceptoE = new \entidad\Concepto();
            $conceptoE->setIdTipoDocumento($idTipoDocumento);
            $conceptoM = new \modelo\Concepto($conceptoE, $this->conexion);
            $arrConcepto = $conceptoM->obtenerIdConcepto(); //OBTENGO EL ID CONCEPTO A PARTIR DEL ID TIPO DE DOCUMENTO DE ANULACION DE FACTURA
            if ($arrConcepto["exito"] == 0) {
                throw new \Exception("ERROR -> No puede haber más de un concepto con un mismo tipo de documento -> id: " . $idTipoDocumento);
            }
            if($arrConcepto["idConcepto"] == null || $arrConcepto["idConcepto"] == "null" || $arrConcepto["idConcepto"] == ""){
                throw new \Exception("ERROR -> No se encontró un concepto con el tipo de documento de id: ". $idTipoDocumento);
            }
            $idConcepto = $arrConcepto["idConcepto"];

            //---------------CONSULTA LA INFORMACIÓN DE LA TRANSACCIÓN Y LA ADICIONA A LA TRANSACCIÓN DE ANULACIÓN------------------
            $sentenciaSql = "
                        SELECT
                            id_tipo_documento
                            , id_tercero
                            , id_naturaleza
                            , numero_tipo_documento
                            , id_transaccion_estado
                            , id_oficina
                            , nota
                            , fecha
                            , fecha_vencimiento
                            , valor
                            , saldo
                            , numero_impresiones
                            , id_usuario_creacion
                            , id_usuario_modificacion
                            , fecha_creacion
                            , fecha_modificacion
                            , id_transaccion_afecta
                        FROM
                            facturacion_inventario.transaccion
                        WHERE
                            id_transaccion = $this->idTransaccion
                        ";
            $this->conexion->ejecutar($sentenciaSql);
            $fila = $this->conexion->obtenerObjeto();
            $sentenciaSql = "
                        INSERT INTO
                            facturacion_inventario.transaccion
                        (
                            id_tipo_documento
                            , id_tercero
                            , id_naturaleza
                            , numero_tipo_documento
                            , id_transaccion_estado
                            , id_oficina
                            , nota
                            , fecha
                            , fecha_vencimiento
                            , valor
                            , saldo
                            , numero_impresiones
                            , id_usuario_creacion
                            , id_usuario_modificacion
                            , fecha_creacion
                            , fecha_modificacion
                            , id_transaccion_afecta
                        )VALUES(
                            $idTipoDocumento
                            , $fila->id_tercero
                            , $fila->id_naturaleza
                            , 1
                            , 1
                            , $fila->id_oficina
                            , 'ANULACIÓN DE FACTURA'
                            , '$fila->fecha'
                            , '$fila->fecha_vencimiento'
                            , $fila->valor
                            , $fila->saldo
                            , $fila->numero_impresiones
                            , $this->idUsuarioCreacion
                            , $this->idUsuarioModificacion
                            , NOW()
                            , NOW()
                            , $this->idTransaccion
                        )
                        ";
            $this->conexion->ejecutar($sentenciaSql);
            //---------------------------------------------------------------------------------
            //--------------OBTIENE EL ULTIMO ID TRANSACCION (EL DE ANULACIÓN)------------------
            $sentenciaSql = "SELECT MAX(id_transaccion) AS maximo FROM facturacion_inventario.transaccion";
            $this->conexion->ejecutar($sentenciaSql);
            $fila = $this->conexion->obtenerObjeto();
            $idTransaccionAnulacion = $fila->maximo;
            //---------------------------------------------------------------------------------
            //----------------------CONSULTA EL CLIENTE DE LA TRANSACCION Y SE GUARDA EN transaccion_cliente DE lA ANULACIÓN-----------
            $sentenciaSql = "
                        SELECT
                            id_cliente
                            , id_caja
                            , prefijo
                        FROM
                            facturacion_inventario.transaccion_cliente
                        WHERE
                            id_transaccion = $this->idTransaccion
                        ";
            $this->conexion->ejecutar($sentenciaSql);
            $fila = $this->conexion->obtenerObjeto();
            $sentenciaSql = "
                            INSERT INTO
                                facturacion_inventario.transaccion_cliente
                            (
                                id_transaccion
                                , id_cliente
                                , id_usuario_creacion
                                , id_usuario_modificacion
                                , fecha_creacion 
                                , fecha_modificacion
                                , id_caja
                                , prefijo
                            )VALUES(
                                $idTransaccionAnulacion
                                , $fila->id_cliente
                                , $this->idUsuarioCreacion
                                , $this->idUsuarioModificacion
                                , NOW()
                                , NOW()
                                , $fila->id_caja
                                , '$fila->prefijo'
                            )
                            ";
            $this->conexion->ejecutar($sentenciaSql);
            
            //----------------------------------------------------------------------------------------------------------------
            
            //-------------------CONSULTA EL CONCEPTO DE LA TRANSACCIÓN Y SE GUARDA EN transaccion_concepto DE LA ANULACIÓN------------------
            $sentenciaSql = "
                        SELECT
                            valor
                            , saldo
                            , nota
                            , estado
                        FROM
                            facturacion_inventario.transaccion_concepto
                        WHERE
                            id_transaccion = $this->idTransaccion
                        ";
            $this->conexion->ejecutar($sentenciaSql);
            $fila = $this->conexion->obtenerObjeto();

            if ($fila->estado == 1)
                $fila->estado = "true";
            else
                $fila->estado = "false";

            $sentenciaSql = "
                            INSERT INTO
                                facturacion_inventario.transaccion_concepto
                            (
                                id_concepto
                                , id_transaccion
                                , valor
                                , nota
                                , estado
                                , fecha_creacion 
                                , fecha_modificacion
                                , id_usuario_creacion
                                , id_usuario_modificacion
                                , saldo
                            )VALUES(
                                $idConcepto
                                , $idTransaccionAnulacion
                                , $fila->valor
                                , '$fila->nota'
                                , $fila->estado
                                , NOW()
                                , NOW()
                                , $this->idUsuarioCreacion
                                , $this->idUsuarioModificacion
                                , $fila->saldo
                            )
                            ";
            $this->conexion->ejecutar($sentenciaSql);
            //---------------------------------------------------------------------------------------
            //-------------------CONSULTAR LOS PRODUCTOS DE LA TRANSACCIÓN Y SE GUARDAN EN LA TRANSACCIÓN DE LA ANULACIÓN------------------
            $sentenciaSql = "
                        SELECT
                            id_transaccion_producto
                            , id_transaccion
                            , id_producto
                            , cantidad
                            , valor_unitario_salida
                            , valor_unitario_entrada
                            , id_bodega 
                            , secuencia
                            , nota
                            , serial
                            , estado
                            , id_usuario_creacion
                            , id_usuario_modificacion
                            , fecha_creacion
                            , fecha_modificacion
                            , valor_unita_salid_con_impue
                            , valor_unita_entra_con_impue
                            , id_transaccion_producto_afecta
                            , saldo_cantidad_producto
                            , id_padre_principal
                            , id_padre_inmediato
                        FROM
                            facturacion_inventario.transaccion_producto
                        WHERE
                            id_transaccion = $this->idTransaccion
                            AND id_transaccion_producto_padre IS NULL
                        ";
            $this->conexion->ejecutar($sentenciaSql);
            $arrFila = $this->conexion->obtenerObjetoCompleto();
            $contador = 0;
            while ($contador < count($arrFila)) {
                $fila = $arrFila[$contador];
                $sentenciaSql = "
                            INSERT INTO
                                facturacion_inventario.transaccion_producto
                            (
                                id_transaccion
                                , id_producto
                                , cantidad
                                , valor_unitario_salida
                                , valor_unitario_entrada
                                , id_bodega 
                                , secuencia
                                , nota
                                , serial
                                , estado
                                , id_usuario_creacion
                                , id_usuario_modificacion
                                , fecha_creacion
                                , fecha_modificacion
                                , valor_unita_salid_con_impue
                                , valor_unita_entra_con_impue
                                , id_transaccion_producto_afecta
                                , saldo_cantidad_producto
                                , id_padre_principal
                                , id_padre_inmediato
                            )VALUES(
                                $idTransaccionAnulacion
                                , $fila->id_producto
                                , $fila->cantidad
                                , $fila->valor_unitario_salida
                                , $fila->valor_unitario_entrada
                                , $fila->id_bodega
                                , $fila->secuencia
                                , 'ANULACIÓN DE FACTURA $fila->nota'
                                , null
                                , true
                                , $this->idUsuarioCreacion
                                , $this->idUsuarioModificacion
                                , NOW()
                                , NOW()
                                , $fila->valor_unita_salid_con_impue
                                , $fila->valor_unita_entra_con_impue
                                , $fila->id_transaccion_producto
                                , $fila->cantidad
                                , null
                                , null
                            )
                            ";
                $this->conexion->ejecutar($sentenciaSql);
                $contador++;
            }
            //---------------------------------------------------------------------------------------
            //-------------------CONSULTAR LAS FORMAS DE PAGO DE LA TRANSACCIÓN Y SE GUARDAN EN transaccion_forma_pago DE LA ANULACIÓN------------------
            $sentenciaSql = "
                        SELECT
                            id_forma_pago
                            , valor
                            , nota
                            , estado
                        FROM
                            facturacion_inventario.transaccion_forma_pago
                        WHERE
                            id_transaccion = $this->idTransaccion
                        ";
            $this->conexion->ejecutar($sentenciaSql);
            $arrFila = $this->conexion->obtenerObjetoCompleto();
            $contador = 0;
            while ($contador < count($arrFila)) {

                $fila = $arrFila[$contador];

                if ($fila->estado == 1)
                    $fila->estado = "true";
                else
                    $fila->estado = "false";

                $sentenciaSql = "
                            INSERT INTO
                                facturacion_inventario.transaccion_forma_pago
                            (
                                id_forma_pago
                                , id_transaccion
                                , valor
                                , nota
                                , estado
                                , fecha_creacion 
                                , fecha_modificacion
                                , id_usuario_creacion
                                , id_usuario_modificacion
                            )VALUES(
                                $fila->id_forma_pago
                                , $idTransaccionAnulacion
                                , $fila->valor
                                , '$fila->nota'
                                , $fila->estado
                                , NOW()
                                , NOW()
                                , $this->idUsuarioCreacion
                                , $this->idUsuarioModificacion
                            )
                            ";
                $this->conexion->ejecutar($sentenciaSql);
                $contador++;
            }
            //---------------------------------------------------------------------------------------
            //--------------------------CAMBIO EL ESTADO DE LA TRANSACCION INICIAL A ANULADO--------------------------------------
            $sentenciaSql = "UPDATE
                            facturacion_inventario.transaccion
                        SET    
                            id_transaccion_estado = 2
                        WHERE
                            id_transaccion = $this->idTransaccion
                        ";
            $this->conexion->ejecutar($sentenciaSql);
            //--------------------------------------------------------------------------------------------------------------

            $this->conexion->confirmarTransaccion();
        } catch (Exception $e) {
            $this->conexion->cancelarTransaccion();
        }
    }
    public function obtenerMuestraEmpaque() {
        $retorno = array("muestraEmpaque"=>1);
        $sentenciaSql = "
                        select 
                            valor 
                        from 
                            general.parametro_aplicacion 
                        where 
                            codigo = 'MUESTRA-EMPAQUE'
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        if ($this->conexion->obtenerNumeroRegistros() > 0) {
            $fila = $this->conexion->obtenerObjeto();
            $retorno["muestraEmpaque"] = $fila->valor;
        }
        return $retorno;
    }
}