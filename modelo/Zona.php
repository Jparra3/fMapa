<?php

namespace modelo;
session_start();
require_once($_SESSION['ruta'].'entorno/Conexion.php');

class Zona {
    private $idZona;
    private $zona;
    private $estado;
    private $idRegional;
    private $fechaCreacion;
    private $fechaModificacion;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    
    public $conexion;
    private $condicion;
    private $whereAnd;
            
    function __construct(\entidad\Zona $zona) {
        $this->idZona = $zona->getIdZona();
        $this->zona = $zona->getZona();
        $this->estado = $zona->getEstado();
        $this->idRegional = $zona->getIdRegional();
        $this->fechaCreacion = $zona->getFechaCreacion();
        $this->fechaModificacion = $zona->getFechaModificacion();
        $this->idUsuarioCreacion = $zona->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $zona->getIdUsuarioModificacion();
                
        $this->conexion = new \Conexion();
    }
    
    public function adiconar(){
        $sentenciaSql = "
            INSERT INTO facturacion_inventario.zona
                (
                      zona
                    , estado
                    , id_regional
                    , fecha_creacion
                    , fecha_modificacion
                    , id_usuario_creacion
                    , id_usuario_modificacion
                )
            VALUES
                (
                      '$this->zona'
                    , TRUE
                    , $this->idRegional
                    , NOW()
                    , NOW()
                    , $this->idUsuarioCreacion
                    , $this->idUsuarioModificacion
                )
        ";
        $this->conexion->ejecutar($sentenciaSql);
        return $this->obtenerMaximo();
    }
    
    public function obtenerMaximo(){
        $sentenciaSql = "
            SELECT
                MAX(id_zona) AS id_zona
            FROM
                facturacion_inventario.zona
        ";
        $this->conexion->ejecutar($sentenciaSql);
        $fila = $this->conexion->obtenerObjeto();
        return $fila->id_zona;
    }
    
    public function consultar() {
        $this->obtenerCondicion();
        $sentenciaSql = "SELECT 
                            z.id_zona
                            , z.zona
                            , CONCAT_WS(' - ',  z.zona, r.regional) as zona_regional
                            , z.id_regional
                        FROM
                            facturacion_inventario.zona z
                            INNER JOIN facturacion_inventario.regional r ON r.id_regional = z.id_regional
                        $this->condicion
                            ORDER BY r.regional, z.zona
                ";
        $this->conexion->ejecutar($sentenciaSql);
        
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()){
            $retorno[$contador]['idZona'] = $fila->id_zona;
            $retorno[$contador]['zona'] = $fila->zona;
            $retorno[$contador]['zonaRegional'] = $fila->zona_regional;
            $retorno[$contador]['idRegional'] = $fila->id_regional;
            
            $contador++;
        }
        
        return $retorno;
    }
    
    public function buscarZona($zona) {
        $sentenciaSql = "SELECT
                            *
                         FROM
                            facturacion_inventario.zona
                         WHERE
                            zona ILIKE '%$zona%'
                               ";
       $this->conexion->ejecutar($sentenciaSql);
       while($fila = $this->conexion->obtenerObjeto()){
           $datos[] = array("value" => $fila->zona,
                           "idZona" => $fila->id_zona,
                           "idRegional"=>$fila->id_regional
                           );
       }
       return $datos;
    }
    
    function obtenerCondicion(){
        $this->condicion = '';
        $this->whereAnd = ' AND ';
        
        if($this->zona != '' && $this->zona != 'null' ){
            $this->condicion .= $this->whereAnd ." z.zona = '".$this->zona."'";
            $this->whereAnd = ' AND ';
        }
        if($this->idZona != '' && $this->idZona != 'null' ){
            $this->condicion .= $this->whereAnd .' z.id_zona = '.$this->idZona;
            $this->whereAnd = ' AND ';
        }
        if($this->idRegional != '' && $this->idRegional != 'null' && $this->idRegional != null ){
            $this->condicion .= $this->whereAnd .' r.id_regional = '.$this->idRegional;
            $this->whereAnd = ' AND ';
        }
    }
}
