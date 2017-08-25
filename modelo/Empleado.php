<?php

namespace modelo;

require_once '../entorno/Conexion.php';
require_once '../../Seguridad/entidad/Persona.php';

class Empleado {

    public $conexion;
    private $condicion;
    private $whereAnd;
    private $idEmpleado;
    private $persona;
    private $idEps;
    private $idArl;
    private $estado;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    private $idCargo;

    public function __construct(\entidad\Empleado $empleadoE) {
        $this->idEmpleado = $empleadoE->getIdEmpleado();
        $this->persona = $empleadoE->getPersona() != "" ? $empleadoE->getPersona() : new \entidad\Persona();
        $this->idEps = $empleadoE->getIdEps();
        $this->idArl = $empleadoE->getIdArl();
        $this->estado = $empleadoE->getEstado();
        $this->idUsuarioCreacion = $empleadoE->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $empleadoE->getIdUsuarioModificacion();
        $this->fechaCreacion = $empleadoE->getFechaCreacion();
        $this->fechaModificacion = $empleadoE->getFechaModificacion();
        $this->idCargo = $empleadoE->getIdCargo();

        $this->conexion = new \Conexion();
    }

    public function adicionar() {
        $sentenciaSql = "
                INSERT INTO nomina.empleado
                    (
                          id_persona
                        , id_eps
                        , id_arl
                        , estado
                        , id_usuario_creacion
                        , id_usuario_modificacion
                        , fecha_creacion
                        , fecha_modificacion
                        , id_cargo
                    )
                VALUES
                    (
                          " . $this->persona->getIdPersona() . "
                        , $this->idEps
                        , $this->idArl
                        , TRUE
                        , $this->idUsuarioCreacion
                        , $this->idUsuarioModificacion
                        , NOW()
                        , NOW()
                        , $this->idCargo
                    )
                    
            ";
        $this->conexion->ejecutar($sentenciaSql);
        return $this->obtenerMaximo();
    }

    public function modificar() {
        $sentenciaSql = "
                UPDATE
                    nomina.empleado
                SET
                      id_eps = $this->idEps
                    , id_arl = $this->idArl
                    , id_usuario_modificacion = $this->idUsuarioModificacion
                    , fecha_modificacion = NOW()
                    , id_cargo = $this->idCargo
                WHERE
                    id_empleado = $this->idEmpleado
                    
            ";
        $this->conexion->ejecutar($sentenciaSql);
    }

    public function obtenerMaximo() {
        $sentenciaSql = "
                SELECT
                    MAX(id_empleado) AS id_empleado
                FROM
                    nomina.empleado
            ";
        $this->conexion->ejecutar($sentenciaSql);
        $fila = $this->conexion->obtenerObjeto();
        return $fila->id_empleado;
    }

    public function consultar() {
        $this->obtenerCondicion();
        $sentenciaSql = "
            SELECT
                  e.id_empleado
                , e.estado
            FROM
                nomina.empleado AS e
                INNER JOIN general.persona AS p ON p.id_persona = e.id_persona
            $this->condicion    
        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()){
            $retorno[$contador]['idEmpleado'] = $fila->id_empleado;
            $retorno[$contador]['estado'] = $fila->estado;
            
            $contador++;
        }
        return $retorno;
    }

    public function obtenerCondicion(){
        $this->condicion = "";
        $this->whereAnd = " WHERE ";
        
        if($this->persona->getIdPersona() != null && $this->persona->getIdPersona() != 'null' && $this->persona->getIdPersona() != ""){
            $this->condicion .= $this->whereAnd . " p.id_persona = " . $this->persona->getIdPersona();
            $this->whereAnd = " AND ";
        }
        
        if($this->idEmpleado != null && $this->idEmpleado != 'null' && $this->idEmpleado != ""){
            $this->condicion .= $this->whereAnd . " e.id_empleado = " . $this->idEmpleado;
            $this->whereAnd = " AND ";
        }
        
        if($this->estado != null && $this->estado != 'null' && $this->estado != ""){
            $this->condicion .= $this->whereAnd . " e.estado = " . $this->estado;
            $this->whereAnd = " AND ";
        }
    }
    
}

?>