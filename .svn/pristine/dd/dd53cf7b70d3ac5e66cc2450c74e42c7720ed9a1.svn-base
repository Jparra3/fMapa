<?php
namespace modelo;
require_once '../entorno/Conexion.php';
class FormatoImpresion{
    public $conexion;
    private $condicion;
    private $whereAnd;
    
    private $idFormatoImpresion;
    private $formatoImpresion;
    private $archivo;
    private $observacion;
    private $estado;
    private $fechaCreacion;
    private $fechaModificacion;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $idTipoNaturaleza;
    private $codigoTipoNaturaleza;
    
    public function __construct(\entidad\FormatoImpresion $formatoImpresionE) {
        $this->idFormatoImpresion = $formatoImpresionE ->getIdFormatoImpresion();
        $this->formatoImpresion = $formatoImpresionE ->getFormatoImpresion();
        $this->archivo = $formatoImpresionE ->getArchivo();
        $this->observacion = $formatoImpresionE ->getObservacion();
        $this->estado = $formatoImpresionE ->getEstado();
        $this->fechaCreacion = $formatoImpresionE ->getFechaCreacion();
        $this->fechaModificacion = $formatoImpresionE ->getFechaModificacion();
        $this->idUsuarioCreacion = $formatoImpresionE ->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $formatoImpresionE ->getIdUsuarioModificacion();
        $this->idTipoNaturaleza = $formatoImpresionE->getIdTipoNaturaleza();
        $this->codigoTipoNaturaleza = $formatoImpresionE->getCodigoTipoNaturaleza();
        
        $this->conexion = new \Conexion();
    }
    
    public function consultar() {
        $this->obtenerCondicion();
        $sentenciaSql = "
            SELECT
                  fi.id_formato_impresion
                , fi.formato_impresion
                , fi.archivo
                , fi.observacion
                , fi.estado
                , fi.fecha_creacion
                , fi.id_usuario_creacion
                , tn.tipo_naturaleza
            FROM 
                facturacion_inventario.formato_impresion AS fi
                INNER JOIN contabilidad.tipo_naturaleza AS tn ON tn.id_tipo_naturaleza = fi.id_tipo_naturaleza
            $this->condicion
        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while($fila = $this->conexion->obtenerObjeto()){
            $retorno[$contador]['idFormatoImpresion'] = $fila->id_formato_impresion;
            $retorno[$contador]['formatoImpresion'] = $fila->formato_impresion."-".$fila->tipo_naturaleza;
            $retorno[$contador]['archivo'] = $fila->archivo;
            $retorno[$contador]['observacion'] = $fila->observacion;
            $retorno[$contador]['estado'] = $fila->estado;
            $retorno[$contador]['fechaCreacion'] = $fila->fecha_creacion;
            $retorno[$contador]['idUsuarioCreacion'] = $fila->id_usuario_creacion;
            
            $contador++;
        }
        return $retorno;
    }
    
    public function obtenerCondicion() {
        $this->condicion = "";
        $this->whereAnd = " WHERE ";
        
        if($this->idFormatoImpresion != "" && $this->idFormatoImpresion != "null" && $this->idFormatoImpresion != null){
            $this->condicion .= $this->whereAnd . " fi.id_formato_impresion = " . $this->idFormatoImpresion;
            $this->whereAnd = " AND ";
        }
        
        if($this->estado != "" && $this->estado != "null" && $this->estado != null){
            $this->condicion .= $this->whereAnd . " fi.estado = " . $this->estado;
            $this->whereAnd = " AND ";
        }
    }
}
?>