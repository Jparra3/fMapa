<?php
namespace modelo;
require_once '../entorno/Conexion.php';
require_once '../entidad/Caja.php';
require_once '../entidad/FormatoImpresion.php';

class CajaFormatoImpresion{
    
    public $conexion;
    private $condicion;
    private $whereAnd;
    
    private $idCajaFormatoImpresion;
    private $caja;
    private $formatoImpresion;
    private $principal;
    private $estado;
    private $fechaCreacion;
    private $fechaModificacion;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    
    public function __construct(\entidad\CajaFormatoImpresion $cajaFormatoImpresionE) {
        $this->idCajaFormatoImpresion = $cajaFormatoImpresionE ->getIdCajaFormatoImpresion();
        $this->caja = $cajaFormatoImpresionE ->getCaja() != "" ? $cajaFormatoImpresionE->getCaja(): new \entidad\Caja();
        $this->formatoImpresion = $cajaFormatoImpresionE ->getFormatoImpresion() != "" ? $cajaFormatoImpresionE->getFormatoImpresion(): new \entidad\FormatoImpresion();
        $this->principal = $cajaFormatoImpresionE->getPrincipal();
        $this->estado = $cajaFormatoImpresionE->getEstado();
        $this->fechaCreacion = $cajaFormatoImpresionE->getFechaCreacion();
        $this->fechaModificacion = $cajaFormatoImpresionE->getFechaModificacion();
        $this->idUsuarioCreacion = $cajaFormatoImpresionE->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $cajaFormatoImpresionE->getIdUsuarioModificacion();
        
        $this->conexion = new \Conexion();
    }
    
    
    public function adicionar(){
        $sentenciaSql = "
            INSERT INTO facturacion_inventario.caja_formato_impresion
                (
                      id_caja
                    , id_formato_impresion
                    , principal
                    , estado
                    , fecha_creacion
                    , fecha_modificacion
                    , id_usuario_creacion
                    , id_usuario_modificacion
                )
            VALUES
                (
                      ".$this->caja->getIdCaja()."
                    , ".$this->formatoImpresion->getIdFormatoImpresion()."
                    , $this->principal
                    , $this->estado
                    , NOW()
                    , NOW()
                    , $this->idUsuarioCreacion
                    , $this->idUsuarioModificacion
                )
        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    
    public function actualizar(){
        $sentenciaSql = "
            UPDATE
                facturacion_inventario.caja_formato_impresion
            SET
                  principal = $this->principal
                , estado = '$this->estado'
                , fecha_modificacion = NOW()
                , id_usuario_modificacion = $this->idUsuarioModificacion
            WHERE 
                id_caja_formato_impresion = $this->idCajaFormatoImpresion
        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    
    public function consultar(){
        $this->obtenerCondicion();
        $sentenciaSql = "
            SELECT
                  cfi.id_caja_formato_impresion
                , c.id_caja  
                , fi.id_formato_impresion
                , fi.formato_impresion
                , fi.archivo
                , cfi.principal
            FROM
                facturacion_inventario.caja_formato_impresion AS cfi
                INNER JOIN facturacion_inventario.caja AS c ON c.id_caja = cfi.id_caja
                INNER JOIN facturacion_inventario.formato_impresion AS fi ON fi.id_formato_impresion = cfi.id_formato_impresion
                INNER JOIN contabilidad.tipo_naturaleza AS tn ON tn.id_tipo_naturaleza = fi.id_tipo_naturaleza
            $this->condicion
        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while($fila = $this->conexion->obtenerObjeto()){
            $retorno[$contador]['idCajaFormatoImpresion'] = $fila -> id_caja_formato_impresion;
            $retorno[$contador]['idCaja'] = $fila -> id_caja;
            $retorno[$contador]['idFormatoImpresion'] = $fila -> id_formato_impresion;
            $retorno[$contador]['formatoImpresion'] = $fila -> formato_impresion;
            $retorno[$contador]['archivo'] = $fila -> archivo;
            $retorno[$contador]['principal'] = $fila -> principal;
            
            $contador++;
        }
        return $retorno;
    }
    
    public function obtenerCondicion() {
        $this->condicion = "";
        $this->whereAnd = " WHERE ";
        
        if($this->idCajaFormatoImpresion != "" && $this->idCajaFormatoImpresion != "null" && $this->idCajaFormatoImpresion != null){
            $this->condicion .= $this->whereAnd . " cfi.id_caja_formato_impresion = ".$this->idCajaFormatoImpresion;
            $this->whereAnd = " AND ";
        }
        
        if($this->estado != "" && $this->estado != "null" && $this->estado != null){
            $this->condicion .= $this->whereAnd . " cfi.estado = ".$this->estado;
            $this->whereAnd = " AND ";
        }
        
        if($this->caja->getIdCaja() != "" && $this->caja->getIdCaja() != "null" && $this->caja->getIdCaja() != null){
            $this->condicion .= $this->whereAnd . " c.id_caja = ".$this->caja->getIdCaja();
            $this->whereAnd = " AND ";
        }
        
        if($this->formatoImpresion != null && $this->formatoImpresion != "null" && $this->formatoImpresion != ""){
            if($this->formatoImpresion->getIdFormatoImpresion() != null && $this->formatoImpresion->getIdFormatoImpresion() != "null" && $this->formatoImpresion->getIdFormatoImpresion() != ""){
                $this->condicion .= $this->whereAnd . " fi.id_formato_impresion = ".$this->formatoImpresion->getIdFormatoImpresion();
                $this->whereAnd = " AND ";
            }
            if($this->formatoImpresion->getIdTipoNaturaleza() != null && $this->formatoImpresion->getIdTipoNaturaleza() != "null" && $this->formatoImpresion->getIdTipoNaturaleza() != ""){
                $this->condicion .= $this->whereAnd . " fi.id_tipo_naturaleza = ".$this->formatoImpresion->getIdTipoNaturaleza();
                $this->whereAnd = " AND ";
            }
            if($this->formatoImpresion->getCodigoTipoNaturaleza() != null && $this->formatoImpresion->getCodigoTipoNaturaleza() != "null" && $this->formatoImpresion->getCodigoTipoNaturaleza() != ""){
                $this->condicion .= $this->whereAnd . " tn.codigo = '".$this->formatoImpresion->getCodigoTipoNaturaleza()."'";
                $this->whereAnd = " AND ";
            }
        }
    }
}
?>