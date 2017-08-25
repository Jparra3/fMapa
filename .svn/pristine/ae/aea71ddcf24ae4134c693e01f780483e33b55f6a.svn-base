<?php

namespace modelo;
session_start();
require_once($_SESSION['ruta'].'entorno/Conexion.php');
require_once '../../Seguridad/entidad/Persona.php';

class Vendedor {
    private $idVendedor;
    private $persona;
    private $idZona;
    private $estado;
    private $fechaCreacion;
    private $fechaModificacion;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    
    public $conexion;
    private $condicion;
    private $whereAnd;
    
    function __construct(\entidad\Vendedor $vendedor) {
        $this->idVendedor = $vendedor->getIdVendedor();
        $this->persona = $vendedor->getPersona() != "" ? $vendedor->getPersona(): new \entidad\Persona();
        $this->idZona = $vendedor->getIdZona();
        $this->estado = $vendedor->getEstado();
        $this->fechaCreacion = $vendedor -> getFechaCreacion();
        $this->fechaModificacion = $vendedor->getFechaModificacion();
        $this->idUsuarioCreacion = $vendedor ->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $vendedor ->getIdUsuarioModificacion();
        
        $this->conexion = new \Conexion();
    }
    
    public function consultar() {
        $this->obtenerCondicion();
        $sentenciaSql = "SELECT 
                            v.id_vendedor
                            , CONCAT_WS(' ', p.primer_nombre, p.segundo_nombre, p.primer_apellido, p.segundo_apellido) as nombre_completo
                            , z.id_zona
                            , z.zona
                            , p.numero_identificacion
                            , p.celular
                            , p.correo_electronico
                            , p.direccion
                            , p.id_persona
                            , v.estado
                            , CONCAT_WS(' - ',  z.zona, r.regional) as zona_regional
                         FROM
                            facturacion_inventario.vendedor v
                            INNER JOIN general.persona p ON p.id_persona = v.id_persona
                            INNER JOIN facturacion_inventario.zona AS z ON z.id_zona = v.id_zona
                            INNER JOIN facturacion_inventario.regional r ON r.id_regional = z.id_regional
                        $this->condicion
                ";
        $this->conexion->ejecutar($sentenciaSql);
        
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()){
            $retorno[$contador]['idVendedor'] = $fila->id_vendedor;
            $retorno[$contador]['nombreCompleto'] = $fila->nombre_completo;
            $retorno[$contador]['zona'] = $fila->zona_regional;
            $retorno[$contador]['documentoIdentidad'] = $fila->numero_identificacion;
            $retorno[$contador]['correoElectronico'] = $fila->correo_electronico;
            $retorno[$contador]['celular'] = $fila->celular;
            $retorno[$contador]['direccion'] = $fila->direccion;
            $retorno[$contador]['idPersona'] = $fila->id_persona;
            $retorno[$contador]['idZona'] = $fila->id_zona;
            $retorno[$contador]['estado'] = $fila->estado;
            
            $contador++;
        }
        
        return $retorno;
    }
    
    public function buscarVendedor($vendedor) {
        $sentenciaSql = "SELECT
                            v.id_vendedor
                            , CONCAT_WS(' ', p.primer_nombre, p.segundo_nombre, p.primer_apellido, p.segundo_apellido) as nombre_completo
                            , numero_identificacion
                         FROM
                            facturacion_inventario.vendedor v
                            INNER JOIN general.persona p ON p.id_persona = v.id_persona
                         WHERE
                            CONCAT_WS(' ', p.primer_nombre, p.segundo_nombre, p.primer_apellido, p.segundo_apellido) ILIKE '%$vendedor%'
                               ";
       $this->conexion->ejecutar($sentenciaSql);
       while($fila = $this->conexion->obtenerObjeto()){
           $datos[] = array("value" => $fila->nombre_completo,
                           "idVendedor" => $fila->id_vendedor,
                           "numeroIdentificacion" => $fila->numero_identificacion
                           );
       }
       return $datos;
    }
    
    public function adicionar() {
        $sentenciaSql = "
            INSERT INTO facturacion_inventario.vendedor
                (
                      id_persona
                    , id_zona
                    , estado
                    , fecha_creacion
                    , fecha_modificacion
                    , id_usuario_creacion
                    , id_usuario_modificacion
                )
            VALUES 
                (
                      ".$this->persona->getIdPersona()."
                    , $this->idZona
                    , $this->estado
                    , NOW()
                    , NOW()
                    , $this->idUsuarioCreacion
                    , $this->idUsuarioModificacion
                )
        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    
    public function modificar() {
        $sentenciaSql = "
            UPDATE
                facturacion_inventario.vendedor
            SET
                  id_zona = $this->idZona
                , estado = $this->estado      
                , fecha_modificacion = NOW()
                , id_usuario_modificacion = $this->idUsuarioModificacion
            WHERE 
                id_vendedor = $this->idVendedor;
        ";
       $this->conexion->ejecutar($sentenciaSql);
    }
    
    function obtenerCondicion(){
        $this->condicion = '';
        $this->whereAnd = ' AND ';
        
        if($this->idVendedor != '' && $this->idVendedor != 'null' ){
            $this->condicion .= $this->whereAnd .' v.id_vendedor = '.$this->idVendedor;
            $this->whereAnd = ' AND ';
        }
        
        if($this->persona->getNumeroIdentificacion() != '' && $this->persona->getNumeroIdentificacion() != 'null' ){
            $this->condicion .= $this->whereAnd .' p.numero_identificacion = '.$this->persona->getNumeroIdentificacion();
            $this->whereAnd = ' AND ';
        }
        
        if($this->persona->getIdPersona() != '' && $this->persona->getIdPersona() != 'null' ){
            $this->condicion .= $this->whereAnd .' p.id_persona = '.$this->persona->getIdPersona();
            $this->whereAnd = ' AND ';
        }
        
        if($this->idZona != '' && $this->idZona != 'null' ){
            $this->condicion .= $this->whereAnd .' v.id_zona = '.$this->idZona;
            $this->whereAnd = ' AND ';
        }
    }
}
