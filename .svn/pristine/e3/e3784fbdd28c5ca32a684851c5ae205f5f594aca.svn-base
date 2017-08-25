<?php
namespace modelo;
session_start();
require_once($_SESSION['ruta'].'entorno/Conexion.php');
class MovimientoContable {
    public $conexion;
    private $condicion;
    private $whereAnd;
    
    private $idMovimientoContable;
    private $idTipoDocumento;
    private $numeroTipoDocumento;
    private $fecha;
    private $nota;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    private $estado;
    
    
    public function __construct(\entidad\MovimientoContable $movimientoContable) {
        $this->idMovimientoContable = $movimientoContable->getIdMovimientoContable();
        $this->idTipoDocumento = $movimientoContable->getIdTipoDocumento();
        $this->numeroTipoDocumento = $movimientoContable->getNumeroTipoDocumento();
        $this->fecha = $movimientoContable->getFecha();
        $this->nota = $movimientoContable->getNota();
        $this->idUsuarioCreacion = $movimientoContable->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $movimientoContable->getIdUsuarioModificacion();
        $this->fechaCreacion = $movimientoContable->getFechaCreacion();
        $this->fechaModificacion = $movimientoContable->getFechaModificacion();
        $this->estado = $movimientoContable->getEstado();
        
        $this->conexion = new \Conexion();
    }
    
    public function adicionar() {
        $sentenciaSql = "
                        INSERT INTO
                            contabilidad.movimiento_contable
                        (
                            id_tipo_documento
                            , numero_tipo_documento
                            , fecha
                            , nota
                            , id_usuario_creacion
                            , id_usuario_modificacion
                            , fecha_creacion
                            , fecha_modificacion
                            , estado
                        )
                        VALUES
                        (
                            $this->idTipoDocumento
                            , $this->numeroTipoDocumento
                            , '$this->fecha'
                            , $this->nota
                            , $this->idUsuarioCreacion
                            , $this->idUsuarioModificacion
                            , NOW()
                            , NOW()
                            , TRUE
                        )
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    
    public function modificar() {
        $sentenciaSql = "
                        UPDATE
                            contabilidad.movimiento_contable
                        SET
                            fecha = '$this->fecha'
                            , nota = $this->nota
                            , id_usuario_modificacion =  $this->idUsuarioModificacion
                            , fecha_modificacion = NOW()
                        WHERE
                            id_movimiento_contable = $this->idMovimientoContable
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    
    public function consultar() {
        $this->obtenerCondicion();
        $sentenciaSql = "
                        SELECT
                            mc.id_movimiento_contable
                            , mc.id_tipo_documento
                            , td.tipo_documento
                            , mc.numero_tipo_documento
                            , mc.fecha
                            , mc.nota
                            , mc.estado
                        FROM
                            contabilidad.movimiento_contable AS mc
                            INNER JOIN contabilidad.tipo_documento AS td ON td.id_tipo_documento = mc.id_tipo_documento
                            $this->condicion
                        ORDER BY mc.id_movimiento_contable DESC
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["idMovimientoContable"] = $fila->id_movimiento_contable;
            $retorno[$contador]["idTipoDocumento"] = $fila->id_tipo_documento;
            $retorno[$contador]["tipoDocumento"] = $fila->tipo_documento;
            $retorno[$contador]["numeroTipoDocumento"] = $fila->numero_tipo_documento;
            $retorno[$contador]["fecha"] = $fila->fecha;
            $retorno[$contador]["nota"] = $fila->nota;
            $retorno[$contador]["estado"] = $fila->estado;
           $contador++; 
        }
        return $retorno;
    }
    function obtenerCondicion(){
        $this->condicion = "";
        $this->whereAnd = " WHERE ";
        
        if($this->idMovimientoContable != "" && $this->idMovimientoContable != "null" && $this->idMovimientoContable != null){
            $this->condicion = $this->condicion.$this->whereAnd." mc.id_movimiento_contable = ".$this->idMovimientoContable;
            $this->whereAnd = " AND ";
        }  
        
        if($this->idTipoDocumento != "" && $this->idTipoDocumento != "null" && $this->idTipoDocumento != null){
            $this->condicion = $this->condicion.$this->whereAnd." mc.id_tipo_documento IN (".$this->idTipoDocumento.")";
            $this->whereAnd = " AND ";
        }
        
        if($this->numeroTipoDocumento != "" && $this->numeroTipoDocumento != "null" && $this->numeroTipoDocumento != null){
            $this->condicion = $this->condicion.$this->whereAnd." mc.numero_tipo_documento = ".$this->numeroTipoDocumento;
            $this->whereAnd = " AND ";
        } 
        
        if($this->estado != "" && $this->estado != "null" && $this->estado != null){
            $this->condicion = $this->condicion.$this->whereAnd." mc.estado = ".$this->estado;
            $this->whereAnd = " AND ";
        } 
        
        if($this->fecha["inicio"] != '' && $this->fecha["fin"]){
            $this->condicion = $this->condicion.$this->whereAnd." CAST(mc.fecha AS DATE) BETWEEN '".$this->fecha["inicio"]."' AND '".$this->fecha["fin"]."'";
            $this->whereAnd = ' AND ';
        }elseif ($this->fecha["inicio"] != '' && $this->fecha["fin"] == '') {
            $this->condicion = $this->condicion.$this->whereAnd." CAST(mc.fecha AS DATE) = '".$this->fecha["inicio"]."'";
            $this->whereAnd = ' AND ';
        }elseif ($this->fecha["inicio"] == '' && $this->fecha["fin"] != '') {
            $this->condicion = $this->condicion.$this->whereAnd." CAST(mc.fecha AS DATE) = '".$this->fecha["fin"]."'";
            $this->whereAnd = ' AND ';
        }
    }
    public function obtenerMaximo() {
        $sentenciaSql = "
                        SELECT
                            MAX(id_movimiento_contable) AS maximo
                        FROM
                            contabilidad.movimiento_contable
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $fila = $this->conexion->obtenerObjeto();
        return $fila->maximo;
    }
    public function inactivar(){
        $sentenciaSql = "
                        UPDATE
                            contabilidad.movimiento_contable
                        SET
                            estado = FALSE
                        WHERE
                            id_movimiento_contable = $this->idMovimientoContable
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
}
