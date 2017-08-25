<?php

namespace modelo;

require_once '../entorno/Conexion.php';

class Concepto {

    public $conexion;
    private $idConcepto;
    private $idTipoDocumento;
    private $modificaValorSalida;
    private $modificaValorEntrada;
    private $requiereProveedor;
    private $requiereDocumentoExterno;

    public function __construct(\entidad\Concepto $concepto, $conexion = null) {
        $this->idConcepto = $concepto->getIdConcepto();
        $this->idTipoDocumento = $concepto->getIdTipoDocumento();
        $this->modificaValorSalida = $concepto->getModificaValorSalida();
        $this->modificaValorEntrada = $concepto->getModificaValorEntrada();
        $this->requiereProveedor = $concepto->getRequiereProveedor();
        $this->requiereDocumentoExterno = $concepto->getRequiereDocumentoExterno();

        $this->conexion = $conexion == null ? new \Conexion() : $conexion;
    }

    public function consultar($parametros) {
        $this->obtenerCondicion($parametros);
        $sentenciaSql = "
            SELECT 
                  c.id_concepto
                , c.id_tipo_documento
                , c.id_formulario
                , t.tipo_documento
                , f.formulario
                , f.ubicacion
                , f.etiqueta
                , t.codigo
                , c.id_forma_pago
                , t.id_oficina
                , t.id_naturaleza
                , c.requiere_proveedor
                , c.requiere_documento_externo
                , c.requiere_formas_pago
                , n.codigo AS codigo_naturaleza
            FROM 
                facturacion_inventario.concepto AS c
                LEFT OUTER JOIN contabilidad.tipo_documento AS t ON t.id_tipo_documento = c.id_tipo_documento
                LEFT OUTER JOIN contabilidad.naturaleza AS n ON n.id_naturaleza = t.id_naturaleza
                LEFT OUTER JOIN contabilidad.tipo_naturaleza AS tn ON tn.id_tipo_naturaleza = n.id_tipo_naturaleza
                LEFT OUTER JOIN seguridad.formulario AS f ON f.id_formulario = c.id_formulario
            $this->condicion    $this->whereAnd t.visualiza = TRUE
        ";
        /* Revisar que se cambiaron de INNER JOIN a LEFT OUTER JOIN para poder traer el tipo_documento y formulario
         */
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["idConcepto"] = $fila->id_concepto;
            $retorno[$contador]["idTipoDocumento"] = $fila->id_tipo_documento;
            $retorno[$contador]["idFormulario"] = $fila->id_formulario;
            $retorno[$contador]["tipoDocumento"] = $fila->tipo_documento;
            $retorno[$contador]["formulario"] = $fila->formulario;
            $retorno[$contador]["ubicacion"] = $fila->ubicacion;
            $retorno[$contador]["etiqueta"] = $fila->etiqueta;
            $retorno[$contador]["codigo"] = $fila->codigo;
            $retorno[$contador]["idFormaPago"] = $fila->id_forma_pago;
            $retorno[$contador]["idOficina"] = $fila->id_oficina;
            $retorno[$contador]["idNaturaleza"] = $fila->id_naturaleza;
            $retorno[$contador]["requiereProveedor"] = $fila->requiere_proveedor;
            $retorno[$contador]["requiereDocumentoExterno"] = $fila->requiere_documento_externo;
            $retorno[$contador]["requiereFormasPago"] = $fila->requiere_formas_pago;
            $retorno[$contador]["codigoNaturaleza"] = $fila->codigo_naturaleza;
            $contador++;
        }
        return $retorno;
    }

    //Funcion pasada de financiera a Faiho
    public function buscarConcepto($concepto, $codigoTipoNaturaleza = "") {

        $condicion = "";
        if ($codigoTipoNaturaleza != "") {
            $condicion = " AND tn.codigo IN ($codigoTipoNaturaleza)";
        }

        $sentenciaSql = "
            SELECT  DISTINCT
                      t.tipo_documento
                      , t.codigo
                      , c.id_concepto
                      , t.id_tipo_documento
                      , f.id_formulario
                      , f.formulario
                      , c.archivo
                    , CONCAT_WS
                    (
                          ' '
                        , t.codigo    
                        , c.id_concepto
			, t.id_tipo_documento
			, t.tipo_documento
			, f.id_formulario
			, f.formulario
                        , c.archivo
                    ) AS concepto
                FROM
                    facturacion_inventario.concepto AS c
                    INNER JOIN contabilidad.tipo_documento AS t ON t.id_tipo_documento = c.id_tipo_documento
                    INNER JOIN contabilidad.naturaleza AS n ON n.id_naturaleza = t.id_naturaleza
                    INNER JOIN contabilidad.tipo_naturaleza AS tn ON tn.id_tipo_naturaleza = n.id_tipo_naturaleza
                    LEFT OUTER JOIN seguridad.formulario AS f ON f.id_formulario = c.id_formulario
                WHERE
                    CONCAT_WS
                    (
                          ' '
                        , t.codigo  
			, t.tipo_documento
                    ) ILIKE '%$concepto%'
                    AND t.visualiza = TRUE
                    $condicion
                ORDER BY
                    concepto
                LIMIT 15;
        ";
        $this->conexion->ejecutar($sentenciaSql);
        while ($fila = $this->conexion->obtenerObjeto()) {
            $datos[] = array(
                "value" => $fila->tipo_documento
                , "codigo" => $fila->codigo
                , "idConcepto" => $fila->id_concepto
                , "idTipoDocumento" => $fila->id_tipo_documento
                , "idFormulario" => $fila->id_formulario
                , "formulario" => $fila->formulario
                , "archivo" => $fila->archivo
            );
        }
        return $datos;
    }

    public function consultarConcepto($codigo, $codigoTipoNaturaleza = "") {
        $condicion = "";
        if ($codigoTipoNaturaleza != "") {
            $condicion = " AND tn.codigo IN ($codigoTipoNaturaleza)";
        }
        $sentenciaSql = "
            SELECT 
                  c.id_concepto
                , t.id_tipo_documento
                , t.tipo_documento
                , f.id_formulario
                , f.formulario
                , t.codigo
                , c.archivo
                , c.permite_fecha_aplicacion
            FROM
                facturacion_inventario.concepto AS c
                INNER JOIN contabilidad.tipo_documento AS t ON t.id_tipo_documento = c.id_tipo_documento
                INNER JOIN contabilidad.naturaleza AS n ON n.id_naturaleza = t.id_naturaleza
                INNER JOIN contabilidad.tipo_naturaleza AS tn ON tn.id_tipo_naturaleza = n.id_tipo_naturaleza
                LEFT OUTER JOIN seguridad.formulario AS f ON f.id_formulario = c.id_formulario
            WHERE
                t.codigo = '$codigo'
                AND t.visualiza = TRUE
                $condicion
        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["idConcepto"] = $fila->id_concepto;
            $retorno[$contador]["idTipoDocumento"] = $fila->id_tipo_documento;
            $retorno[$contador]["idFormulario"] = $fila->id_formulario;
            $retorno[$contador]["tipoDocumento"] = $fila->tipo_documento;
            $retorno[$contador]["formulario"] = $fila->formulario;
            $retorno[$contador]["codigo"] = $fila->codigo;
            $retorno[$contador]["archivo"] = $fila->archivo;
            $retorno[$contador]["permiteFechaAplicacion"] = $fila->permite_fecha_aplicacion;
            $contador++;
        }
        return $retorno;
    }

    public function obtenerConcepto() {
        $retorno = array("exito" => 1, "mensaje" => "", "data" => null);
        $sentenciaSql = "
                        SELECT
                            id_concepto
                            , id_tipo_documento
                            , modifica_valor_salida
                            , modifica_valor_entrada
                            , requiere_proveedor
                            ,requiere_documento_externo
                        FROM
                            facturacion_inventario.concepto
                        WHERE
                            id_tipo_documento = $this->idTipoDocumento
                        ";
        $this->conexion->ejecutar($sentenciaSql);

        if ($this->conexion->obtenerNumeroRegistros() == 0) {
            $retorno["exito"] = 0;
            $retorno["mensaje"] = "No se encontraron conceptos con ese id tipo documento => " . $this->idTipoDocumento;
            return $retorno;
        }

        if ($this->conexion->obtenerNumeroRegistros() == 1) {
            $fila = $this->conexion->obtenerObjeto();
            $retorno["data"]["idConcepto"] = $fila->id_concepto;
            $retorno["data"]["idTipoDocumento"] = $fila->id_tipo_documento;
            $retorno["data"]["modificaValorSalida"] = $fila->modifica_valor_salida;
            $retorno["data"]["modificaValorEntrada"] = $fila->modifica_valor_entrada;
            $retorno["data"]["requiereProveedor"] = $fila->requiere_proveedor;
            $retorno["data"]["requiereDocumentoExterno"] = $fila->requiere_documento_externo;
        } else {
            if ($this->conexion->obtenerNumeroRegistros() > 1) {
                $retorno["exito"] = 0;
                $retorno["mensaje"] = "Hay mas de un concepto con el id => " . $this->idTipoDocumento;
            }
        }
        return $retorno;
    }

    public function obtenerCondicion($parametros) {
        $this->condicion = "";
        $this->whereAnd = " WHERE ";

        if ($this->idConcepto != "" && $this->idConcepto != "null" && $this->idConcepto != null) {
            $this->condicion = $this->condicion . $this->whereAnd . " c.id_concepto = " . $this->idConcepto;
            $this->whereAnd = " AND ";
        }
        if ($this->idTipoDocumento != "" && $this->idTipoDocumento != "null" && $this->idTipoDocumento != null) {
            $this->condicion = $this->condicion . $this->whereAnd . " c.id_tipo_documento = " . $this->idTipoDocumento;
            $this->whereAnd = " AND ";
        }
        if ($parametros["codigoTipoNaturaleza"] != "" && $parametros["codigoTipoNaturaleza"] != "null" && $parametros["codigoTipoNaturaleza"] != null) {
            $this->condicion = $this->condicion . $this->whereAnd . " tn.codigo = '" . $parametros["codigoTipoNaturaleza"] . "'";
            $this->whereAnd = " AND ";
        }
//        if($this->tipoDocumento->getIdTipoDocumento() != "" && $this->tipoDocumento->getIdTipoDocumento() != "null" && $this->tipoDocumento->getIdTipoDocumento() != null){
//            $this->condicion = $this->condicion.$this->whereAnd." c.id_tipo_documento = ".$this->tipoDocumento->getIdTipoDocumento();
//            $this->whereAnd = " AND ";
//        }
//        if($this->estado != "" && $this->estado != "null" && $this->estado != null){
//            $this->condicion = $this->condicion.$this->whereAnd." c.estado = ".$this->estado;
//            $this->whereAnd = " AND ";
//        }
//        if($this->formulario->getIdFormulario() != "" && $this->formulario->getIdFormulario() != "null" && $this->formulario->getIdFormulario() != null){
//            $this->condicion = $this->condicion.$this->whereAnd." c.id_formulario = ".$this->formulario->getIdFormulario();
//            $this->whereAnd = " AND ";
//        }
    }

    public function obtenerIdConcepto() {
        $retorno = array("exito" => 1, "mensaje" => "", "idConcepto" => "");
        try {
            $sentenciaSql = "
                        SELECT
                            id_concepto
                        FROM
                            facturacion_inventario.concepto
                        WHERE
                            id_tipo_documento = $this->idTipoDocumento
                        ";
            $this->conexion->ejecutar($sentenciaSql);
            if ($this->conexion->obtenerNumeroRegistros() > 1) {
                throw new \Exception("No puede haber más de un concepto con el mismo tipo de documento -> " . $this->idTipoDocumento);
            }
            $fila = $this->conexion->obtenerObjeto();
            $retorno["idConcepto"] = $fila->id_concepto;
        } catch (\Exception $exc) {
            $retorno["exito"] = 0;
            $retorno["mensaje"] = $exc->getMessage();
        }
        return $retorno;
    }

}
