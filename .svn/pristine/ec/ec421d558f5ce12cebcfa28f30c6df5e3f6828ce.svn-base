<?php
namespace modelo;
session_start();
require_once($_SESSION['ruta'].'entorno/Conexion.php');
class PeriodicidadVisita {
    
    public $conexion=null;
    private $condicion=null;
    private $whereAnd=null;
    
    private $idPeriocidadVisita;
    private $periodicidadVisita;
    private $idPeriodicidad;
    private $estado;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    
    public function __construct(\entidad\PeriodicidadVisita $periodicidadVisita) {
        $this->idPeriocidadVisita = $periodicidadVisita->getIdPeriodicidadVisita();
        $this->periodicidadVisita = $periodicidadVisita->getPeriodicidadVisita();
        $this->idPeriodicidad = $periodicidadVisita->getIdPeriodicidad();
        $this->estado = $periodicidadVisita->getEstado();
        $this->fechaCreacion = $periodicidadVisita->getFechaCreacion();
        $this->fechaModificacion = $periodicidadVisita->getFechaModificacion();
        $this->idUsuarioCreacion = $periodicidadVisita->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $periodicidadVisita->getIdUsuarioModificacion();
        
        $this->conexion = new \Conexion();     
    }
    public function adicionar() {
        $sentenciaSql = "
                        INSERT INTO
                            facturacion_inventario.periodicidad_visita
                        (
                            periodicidad_visita
                            , id_periodicidad
                            , estado
                            , id_usuario_creacion
                            , id_usuario_modificacion
                            , fecha_modificacion
                            , fecha_creacion
                        )
                        VALUES
                        (
                            '$this->periodicidadVisita'
                            , $this->idPeriodicidad
                            , $this->estado
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
                            facturacion_inventario.periodicidad_visita
                        SET
                            periodicidad_visita = '$this->periodicidadVisita'
                            , id_periodicidad = $this->idPeriodicidad
                            , estado= $this->estado
                            , id_usuario_modificacion = $this->idUsuarioModificacion
                            , fecha_modificacion = NOW()
                        WHERE
                            id_periodicidad_visita = $this->idPeriocidadVisita
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    public function consultar(){
        $this->obtenerCondicion();
        $sentenciaSql = "
                        SELECT 
                            *
                        FROM
                            facturacion_inventario.periodicidad_visita pv
                            LEFT OUTER JOIN facturacion_inventario.periodicidad_dia AS pd ON pv.id_periodicidad_visita = pd.id_periodicidad_visita
                            $this->condicion
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]['idPeriodicidadVisita'] = $fila->id_periodicidad_visita;
            $retorno[$contador]['periodicidadVisita'] = $fila->periodicidad_visita;
            $retorno[$contador]['idDia'] = $fila->id_dia;
            $retorno[$contador]['idPeriodicidad'] = $fila->id_periodicidad;
            $retorno[$contador]['idPeriodicidadDia'] = $fila->id_periodicidad_dia;
            $retorno[$contador]['estado'] = $fila->estado;
            $contador++;
        }
        return $retorno;
    }
    public function obtenerCondicion(){
        $this->whereAnd = ' WHERE ';
        $this->condicion = '';
        
        if($this->idPeriocidadVisita != '' && $this->idPeriocidadVisita != 'null'){
            $this->condicion = $this->condicion.$this->whereAnd." pv.id_periodicidad_visita  = ".$this->idPeriocidadVisita;
            $this->whereAnd = ' AND ';
        }
        if($this->periodicidadVisita != '' && $this->periodicidadVisita != 'null'){
            $this->condicion = $this->condicion.$this->whereAnd." pv.periodicidad_visita  ILIKE '%$this->periodicidadVisita%'";
            $this->whereAnd = ' AND ';
        }
        if($this->idPeriodicidad != '' && $this->idPeriodicidad != 'null'){
            $this->condicion = $this->condicion.$this->whereAnd." pv.id_periodicidad  = ".$this->idPeriodicidad;
            $this->whereAnd = ' AND ';
        }
        if($this->estado != '' && $this->estado != 'null'){
            $this->condicion = $this->condicion.$this->whereAnd." pv.estado  = $this->estado";
            $this->whereAnd = ' AND ';
        }
    }
    public function consultarBandejaEntrada() {
        $this->obtenerCondicion();
        $sentenciaSql = "
                        SELECT 
                            pv.id_periodicidad_visita
                            , pv.periodicidad_visita
                            , pv.id_periodicidad
                            , p.periodicidad
                            , pv.estado
                        FROM
                            facturacion_inventario.periodicidad_visita pv
                            LEFT OUTER JOIN facturacion_inventario.periodicidad AS p ON p.id_periodicidad = pv.id_periodicidad
                            $this->condicion
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]['idPeriodicidadVisita'] = $fila->id_periodicidad_visita;
            $retorno[$contador]['periodicidadVisita'] = $fila->periodicidad_visita;
            $retorno[$contador]['idPeriodicidad'] = $fila->id_periodicidad;
            $retorno[$contador]['periodicidad'] = $fila->periodicidad;
            $retorno[$contador]['estado'] = $fila->estado;
            $contador++;
        }
        return $retorno;
    }
    public function cargarPeriodicidadVisita() {
        $sentenciaSql = "
                        SELECT
                            pv.id_periodicidad_visita
                            , pv.periodicidad_visita
                        FROM
                            facturacion_inventario.periodicidad_visita AS pv
                        WHERE
                            pv.estado = TRUE
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]['idPeriodicidadVisita'] = $fila->id_periodicidad_visita;
            $retorno[$contador]['periodicidadVisita'] = $fila->periodicidad_visita;
            $contador++;
        }
        return $retorno;
    }
    public function cargarPeriodicidad() {
        $sentenciaSql = "
                            SELECT
                                id_periodicidad
                                , periodicidad
                            FROM
                                reinh.periodicidad
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]['idPeriodicidad'] = $fila->id_periodicidad;
            $retorno[$contador]['periodicidad'] = $fila->periodicidad;
            $contador++;
        }
        return $retorno;
    }
    public function cargarDias() {
        $sentenciaSql = "
                            SELECT
                                id_dia
                                , dia
                            FROM
                                general.dia
                            WHERE
                                estado = TRUE
                            ORDER BY orden ASC
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]['idDia'] = $fila->id_dia;
            $retorno[$contador]['dia'] = $fila->dia;
            $contador++;
        }
        return $retorno;
    }
    public function obtenerMaximo() {
        $sentenciaSql = "
                        SELECT
                            MAX(id_periodicidad_visita) AS maximo
                        FROM
                            facturacion_inventario.periodicidad_visita
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $fila = $this->conexion->obtenerObjeto();
        return $fila->maximo;
    }
    
    public function inactivar() {
        $sentenciaSql = "
                        UPDATE
                            facturacion_inventario.periodicidad_visita
                        SET
                            estado = FALSE
                        WHERE
                            id_periodicidad_visita = $this->idPeriocidadVisita
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
}
