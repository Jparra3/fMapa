<?php
namespace modelo;
session_start();
require_once($_SESSION['ruta'].'entorno/Conexion.php');
require_once '../entidad/Concepto.php';
require_once '../modelo/Concepto.php';
class TipoDocumento {
    public $conexion = null;
    public $whereAnd;
    public $condicion;
    
    private $idTipoDocumento;
    private $tipoDocumento;
    private $idNaturaleza;
    private $idOficina;
    private $numero;
    private $estado;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    private $codigo;


    public function __construct(\entidad\TipoDocumento $tipoDocumento, $conexion = null) {
        $this->idTipoDocumento = $tipoDocumento->getIdTipoDocumento();
        $this->tipoDocumento = $tipoDocumento->getTipoDocumento();
        $this->idNaturaleza = $tipoDocumento->getIdNaturaleza();
        $this->idOficina = $tipoDocumento->getIdOficina();
        $this->numero = $tipoDocumento->getNumero();
        $this->estado = $tipoDocumento->getEstado();
        $this->idUsuarioCreacion = $tipoDocumento->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $tipoDocumento->getIdUsuarioModificacion();
        $this->fechaCreacion = $tipoDocumento->getFechaCreacion();
        $this->fechaModificacion = $tipoDocumento->getFechaModificacion();
        $this->codigo = $tipoDocumento->getCodigo();
        
        $this->conexion = $conexion == null ? new \Conexion() : $conexion;
    }
    
    public function adicionar(){
        $sentenciaSql = "
                        INSERT INTO
                            contabilidad.tipo_documento
                        (
                            tipo_documento
                            , id_naturaleza
                            , id_oficina
                            , codigo
                            , estado
                            , fecha_creacion
                            , fecha_modificacion
                            , id_usuario_creacion
                            , id_usuario_modificacion
                        )
                        VALUES
                        (
                            '$this->tipoDocumento'
                            , $this->idNaturaleza
                            , $this->idOficina
                            , '$this->codigo'
                            , $this->estado
                            , NOW()
                            , NOW()
                            , $this->idUsuarioCreacion
                            , $this->idUsuarioModificacion
                        )
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    
    public function modificar(){
        $sentenciaSql = "
                        UPDATE
                            contabilidad.tipo_documento
                        SET
                            tipo_documento = '$this->tipoDocumento'
                            , id_naturaleza = $this->idNaturaleza
                            , id_oficina = $this->idOficina
                            , codigo = '$this->codigo'
                            , estado = $this->estado
                            , fecha_modificacion = NOW()
                            , id_usuario_modificacion = $this->idUsuarioModificacion
                        WHERE
                            id_tipo_documento = $this->idTipoDocumento
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    
    public function cargarTiposDocumento() {
        $condicion = "";
        $whereAnd = " WHERE ";
        
        if($this->idTipoDocumento != null && $this->idTipoDocumento != "null" && $this->idTipoDocumento != ""){
            $condicion = $whereAnd." id_tipo_documento NOT IN ($this->idTipoDocumento)";
            $whereAnd = " AND ";
        }
        
        $sentenciaSql = "
                            SELECT
                                id_tipo_documento
                                , tipo_documento
                            FROM
                                contabilidad.tipo_documento
                                $condicion $whereAnd estado = TRUE
                            ORDER BY tipo_documento
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["idTipoDocumento"] = $fila->id_tipo_documento;
            $retorno[$contador]["tipoDocumento"] = $fila->tipo_documento;
            $contador++;
        }
        return $retorno;
    }
    
    public function consultar($idTipoNaturaleza = "", $codigoTipoNaturaleza = ""){
        $this->obtenerCondicion($idTipoNaturaleza, $codigoTipoNaturaleza);
        $sentenciaSql = "
                        SELECT
                            td.id_tipo_documento
                            , td.tipo_documento
                            , td.codigo
                            , td.id_oficina
                            , td.id_naturaleza
                            , n.naturaleza
                            , (td.numero_tipo_documento + 1) AS proximo_numero
                            , td.numero_tipo_documento
                            , o.oficina
                            , td.estado
                        FROM
                            contabilidad.tipo_documento AS td
                            INNER JOIN  contabilidad.oficina AS o ON o.id_oficina = td.id_oficina
                            INNER JOIN contabilidad.naturaleza AS n ON n.id_naturaleza = td.id_naturaleza
                            INNER JOIN contabilidad.tipo_naturaleza AS tn ON tn.id_tipo_naturaleza = n.id_tipo_naturaleza
                            $this->condicion
                        ORDER BY td.tipo_documento
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["idTipoDocumento"] = $fila->id_tipo_documento;
            
            $conceptoE = new \entidad\Concepto();
            $conceptoE->setIdTipoDocumento($fila->id_tipo_documento);
            $conceptoM = new \modelo\Concepto($conceptoE);
            $arrConcepto = $conceptoM->consultar($parametros);
            $retorno[$contador]["concepto"]["idConcepto"] = $arrConcepto[0]["idConcepto"];
            $retorno[$contador]["concepto"]["requiereProveedor"] = $arrConcepto[0]["requiereProveedor"];
            $retorno[$contador]["concepto"]["requiereDocumentoExterno"] = $arrConcepto[0]["requiereDocumentoExterno"];
            $retorno[$contador]["concepto"]["requiereFormasPago"] = $arrConcepto[0]["requiereFormasPago"];
            
            $retorno[$contador]["tipoDocumento"] = $fila->tipo_documento;
            $retorno[$contador]["codigo"] = $fila->codigo;
            $retorno[$contador]["idOficina"] = $fila->id_oficina;
            $retorno[$contador]["idNaturaleza"] = $fila->id_naturaleza;
            $retorno[$contador]["naturaleza"] = $fila->naturaleza;
            $retorno[$contador]["proximoNumero"] = $fila->proximo_numero;
            $retorno[$contador]["numeroTipoDocumento"] = $fila->numero_tipo_documento;
            $retorno[$contador]["oficina"] = $fila->oficina;
            $retorno[$contador]["estado"] = $fila->estado;
            $contador++;
        }
        return $retorno;
    }
    
    function obtenerCondicion($idTipoNaturaleza, $codigoTipoNaturaleza){
        $this->condicion = "";
        $this->whereAnd = " WHERE ";
        
        if($this->idTipoDocumento != "" && $this->idTipoDocumento != "null" && $this->idTipoDocumento != null ){
            $this->condicion = $this->condicion.$this->whereAnd." td.id_tipo_documento IN (".$this->idTipoDocumento.")";
            $this->whereAnd = " AND ";
        }
        if($this->tipoDocumento != "" && $this->tipoDocumento != "null" && $this->tipoDocumento != null ){
            $this->condicion = $this->condicion.$this->whereAnd." td.tipo_documento ILIKE '%".$this->tipoDocumento."%'";
            $this->whereAnd = " AND ";
        }
        if($this->idNaturaleza != "" && $this->idNaturaleza != "null" && $this->idNaturaleza != null ){
            $this->condicion = $this->condicion.$this->whereAnd." td.id_naturaleza = ".$this->idNaturaleza;
            $this->whereAnd = " AND ";
        }
        if($this->estado != "" && $this->estado != "null" && $this->estado != null ){
            $this->condicion = $this->condicion.$this->whereAnd." td.estado = ".$this->estado;
            $this->whereAnd = " AND ";
        }        
        if($this->codigo != "" && $this->codigo != "null" && $this->codigo != null){
            $this->condicion = $this->condicion.$this->whereAnd." td.codigo ILIKE '".$this->codigo."'";
            $this->whereAnd = " AND ";
        }
        if($idTipoNaturaleza != '' && $idTipoNaturaleza != 'null' && $idTipoNaturaleza != null){
            $this->condicion = $this->condicion.$this->whereAnd." tn.id_tipo_naturaleza = $idTipoNaturaleza";
            $this->whereAnd = " AND ";
        }
        if($codigoTipoNaturaleza != '' && $codigoTipoNaturaleza != 'null' && $codigoTipoNaturaleza != null){
            $this->condicion = $this->condicion.$this->whereAnd." tn.codigo = '$codigoTipoNaturaleza'";
            $this->whereAnd = " AND ";
        }
    }
    
    public function inactivar() {
        $sentenciaSql = "
                        UPDATE
                            contabilidad.tipo_documento
                        SET
                            estado = FALSE
                            , fecha_modificacion = NOW()
                            , id_usuario_modificacion = ".$_SESSION['idUsuario']."
                        WHERE
                            id_tipo_documento = $this->idTipoDocumento
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    
    public function consultarTipoDocumento($idTiposDocumentos, $idTipoNaturaleza, $codigoTipoNaturaleza) {
        $condicion = "";
        if($idTipoNaturaleza != '' && $idTipoNaturaleza != 'null' && $idTipoNaturaleza != null){
            $condicion .= " AND tn.id_tipo_naturaleza = $idTipoNaturaleza";
        }
        if($codigoTipoNaturaleza != '' && $codigoTipoNaturaleza != 'null' && $codigoTipoNaturaleza != null){
            $condicion .= " AND tn.codigo = '$codigoTipoNaturaleza'";
        }
        $sentenciaSql = "
                        SELECT
                            td.id_tipo_documento
                            , td.tipo_documento
                            , td.id_oficina
                            , td.id_naturaleza
                        FROM
                            contabilidad.tipo_documento AS td
                            INNER JOIN contabilidad.naturaleza AS n ON n.id_naturaleza = td.id_naturaleza
                            INNER JOIN contabilidad.tipo_naturaleza AS tn ON tn.id_tipo_naturaleza = n.id_tipo_naturaleza
                        WHERE
                            td.id_tipo_documento IN ($idTiposDocumentos)
                            AND td.estado = TRUE
                            $condicion
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["idTipoDocumento"] = $fila->id_tipo_documento;
            $retorno[$contador]["tipoDocumento"] = $fila->tipo_documento;
            $retorno[$contador]["idOficina"] = $fila->id_oficina;
            $retorno[$contador]["idNaturaleza"] = $fila->id_naturaleza;
            $contador++;
        }
        return $retorno;
    }
    
    public function obtenerInfoTipoDocumento() {
        $sentenciaSql = "
                        SELECT
                            (td.numero_tipo_documento + 1) AS numero_tipo_documento
                            , td.id_oficina
                            , o.oficina
                            , td.id_naturaleza
                            , td.codigo
                        FROM
                            contabilidad.tipo_documento AS td
                            INNER JOIN contabilidad.oficina AS o ON o.id_oficina = td.id_oficina
                        WHERE
                            td.id_tipo_documento = $this->idTipoDocumento
                            AND td.estado = TRUE
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["numeroTipoDocumento"] = $fila->numero_tipo_documento;
            $retorno[$contador]["idOficina"] = $fila->id_oficina;
            $retorno[$contador]["oficina"] = $fila->oficina;
            $retorno[$contador]["idNaturaleza"] = $fila->id_naturaleza;
            $retorno[$contador]["codigo"] = $fila->codigo;
            $contador++;
        }
        return $retorno;
    }
    
    public function obtenTipoDocumTienePermi() {
        $idTiposDocumentos = "";
        $sentenciaSql = "
                        SELECT
                            id_tipo_documento
                        FROM
                            contabilidad.usuario_tipo_documento
                        WHERE
                            id_usuario = ".$_SESSION["idUsuario"];
        $this->conexion->ejecutar($sentenciaSql);
        while ($fila = $this->conexion->obtenerObjeto()) {
            $idTiposDocumentos = $idTiposDocumentos.$fila->id_tipo_documento . ",";
        }
        $idTiposDocumentos = substr($idTiposDocumentos, 0, -1);
        return $idTiposDocumentos;
    }
    function obtenerNumeroOrdenTrabajo(){
        $sentenciaSql = "   
                            SELECT
                                COALESCE(MAX(numero),0) + 1 AS maximo
                            FROM 
                                publics_services.orden_trabajo
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $fila = $this->conexion->obtenerObjeto();
        $numero = $fila->maximo;
        return $numero;
    }
    
    public function obtenerNumero(){
        $sentenciaSql = "
                        SELECT
                            (numero_tipo_documento + 1) AS numero
                        FROM
                            contabilidad.tipo_documento
                        WHERE
                            id_tipo_documento = $this->idTipoDocumento
                            AND estado = TRUE
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $fila = $this->conexion->obtenerObjeto();
        return $fila->numero;
    }
    public function actualizarNumero(){
        $sentenciaSql = "
                        UPDATE
                            contabilidad.tipo_documento
                        SET
                            numero_tipo_documento = $this->numero
                            , fecha_modificacion = NOW()
                            , id_usuario_modificacion = ".$_SESSION['idUsuario']."
                        WHERE
                            id_tipo_documento = $this->idTipoDocumento
                            AND estado = TRUE
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    
    public function validarExistenciaCodigo() {
        $sentenciaSql = "
                        SELECT
                            codigo
                        FROM
                            contabilidad.tipo_documento
                        WHERE
                            codigo ILIKE '$this->codigo'
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        if($this->conexion->obtenerNumeroRegistros() == 0){
            $retorno = false;
        }else{
            $retorno = true;
        }
        return $retorno;
    }
    
    function obtenerIdFormaPagoDefecto($codigo){
        $sentenciaSql = "
                        SELECT
                            valor
                        FROM
                            general.parametro_aplicacion
                        WHERE
                            codigo = '$codigo'
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $fila = $this->conexion->obtenerObjeto();
        return $fila->valor;
    }
}
