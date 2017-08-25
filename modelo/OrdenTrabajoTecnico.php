<?php
namespace modelo;
session_start();
require_once($_SESSION['ruta'].'entorno/Conexion.php');
class OrdenTrabajoTecnico {
    public $conexion;
    private $condicion;
    private $whereAnd;
    
    private $idOrdenTrabajoTecnico;
    private $ordenTrabajo;
    private $tecnico;
    private $principal;
    private $estado;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    
    public function __construct(\entidad\OrdenTrabajoTecnico $ordenTrabajoTecnico) {
        $this->idOrdenTrabajoTecnico = $ordenTrabajoTecnico->getIdOrdenTrabajoTecnico();
        $this->ordenTrabajo = $ordenTrabajoTecnico->getOrdenTrabajo() != "" ? $ordenTrabajoTecnico->getOrdenTrabajo(): new \entidad\OrdenTrabajo();
        $this->tecnico = $ordenTrabajoTecnico->getTecnico() != "" ? $ordenTrabajoTecnico->getTecnico(): new \entidad\Tecnico();
        $this->principal = $ordenTrabajoTecnico->getPrincipal();
        $this->estado = $ordenTrabajoTecnico->getEstado();
        $this->idUsuarioCreacion = $ordenTrabajoTecnico->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $ordenTrabajoTecnico->getIdUsuarioModificacion();
        
        $this->conexion = new \Conexion();
    }
    function adicionar(){
        $sentenciaSql = "
                            INSERT INTO
                                publics_services.orden_trabajo_tecnico
                            (
                                id_orden_trabajo
                                ,id_tecnico
                                ,principal
                                ,estado
                                ,id_usuario_creacion
                                ,id_usuario_modificacion
                                ,fecha_creacion
                                ,fecha_modificacion
                            )
                            VALUES
                            (
                                ".$this->ordenTrabajo->getIdOrdenTrabajo()."
                                ,".$this->tecnico->getIdTecnico()."
                                ,$this->principal
                                , ".$this->estado."
                                , $this->idUsuarioCreacion
                                , $this->idUsuarioModificacion
                                , NOW()
                                , NOW()
                            )
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    function modificar(){
        $sentenciaSql = "
                            UPDATE
                                publics_services.orden_trabajo_tecnico
                            SET
                                id_orden_trabajo = ".$this->ordenTrabajo->getIdOrdenTrabajo()."
                                ,id_tecnico = ".$this->ordenTrabajo->getIdTecnico()."
                                ,principal = $this->principal
                                ,estado = ".$this->estado."
                                ,id_usuario_creacion = $this->idUsuarioCreacion 
                                ,fecha_modificacion = NOW()
                            WHERE
                                id_orden_trabajo_tecnico = $this->idOrdenTrabajoTecnico
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    public function consultar() {
        $this->obtenerCondicion();
        $sentenciaSql = "
                        SELECT
                            t.id_tecnico
                            , p.persona AS tecnico
                            , p.numero_identificacion
                            , ott.principal
                        FROM
                            publics_services.orden_trabajo_tecnico ott
                            INNER JOIN publics_services.orden_trabajo ot ON ot.id_orden_trabajo = ott.id_orden_trabajo
                            INNER JOIN publics_services.tecnico t ON t.id_tecnico = ott.id_tecnico
                            INNER JOIN nomina.empleado e ON e.id_empleado = t.id_empleado
                            INNER JOIN general.persona p ON p.id_persona = e.id_persona
                            
                            $this->condicion
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["idTecnico"] = $fila->id_tecnico;
            $retorno[$contador]["tecnico"] = $fila->tecnico;
            $retorno[$contador]["numeroIdentificacion"] = $fila->numero_identificacion;
            $retorno[$contador]["principal"] = $fila->principal;
            $contador++;
        }
        return $retorno;
    }
    
    public function consultarOrdenTrabajoTecnico(){
        $this->obtenerCondicion();
        $sentenciaSql = "
                    SELECT
                        p.persona AS tecnico
                        , p.numero_identificacion
                        , p.direccion
                        , p.telefono
                        , ott.principal
                        , cargo
                        , eps.eps
                        , a.arl
                        , CONCAT_WS(' / ', eps.eps, a.arl) as eps_arl
                        , ROW_NUMBER() OVER (ORDER BY id_orden_trabajo_tecnico) AS item
                    FROM
                        publics_services.orden_trabajo_tecnico ott
                        INNER JOIN publics_services.orden_trabajo ot ON ot.id_orden_trabajo = ott.id_orden_trabajo
                        INNER JOIN publics_services.tecnico t ON t.id_tecnico = ott.id_tecnico
                        INNER JOIN nomina.empleado e ON e.id_empleado = t.id_empleado
                        LEFT OUTER JOIN general.cargo c ON c.id_cargo = e.id_cargo
                        LEFT OUTER JOIN nomina.arl a ON a.id_arl = e.id_arl
                        LEFT OUTER JOIN nomina.eps eps ON eps.id_eps = e.id_eps
                        INNER JOIN general.persona p ON p.id_persona = e.id_persona                            
                            $this->condicion
                        ORDER BY id_orden_trabajo_tecnico    
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {            
            $retorno[$contador]["tecnico"] = $fila->tecnico;
            $retorno[$contador]["numeroIdentificacion"] = $fila->numero_identificacion;
            $retorno[$contador]["direccion"] = $fila->direccion;
            $retorno[$contador]["telefono"] = $fila->telefono;
            $retorno[$contador]["principal"] = $fila->principal;
            $retorno[$contador]["cargo"] = $fila->cargo;
            $retorno[$contador]["eps"] = $fila->eps;
            $retorno[$contador]["arl"] = $fila->arl;
            $retorno[$contador]["epsArl"] = $fila->eps_arl;
            $retorno[$contador]["item"] = $fila->item;
            $contador++;
        }
        return $retorno;
    }
    
    function eliminar(){
        $sentenciaSql = "
                            DELETE FROM
                                publics_services.orden_trabajo_tecnico
                            WHERE
                                id_orden_trabajo = ".$this->ordenTrabajo->getIdOrdenTrabajo();
        $this->conexion->ejecutar($sentenciaSql);
    }
    
    function obtenerCondicion(){
        $this->condicion = '';
        $this->whereAnd = ' WHERE ';
        
        if($this->ordenTrabajo->getIdOrdenTrabajo() != '' && $this->ordenTrabajo->getIdOrdenTrabajo() != 'null' && $this->ordenTrabajo->getIdOrdenTrabajo() != null ){
            $this->condicion .= $this->whereAnd . ' ot.id_orden_trabajo = ' . $this->ordenTrabajo->getIdOrdenTrabajo();
            $this->whereAnd = ' AND ';
        }
        if($this->idOrdenTrabajoTecnico != '' && $this->idOrdenTrabajoTecnico != 'null' && $this->idOrdenTrabajoTecnico != null ){
            $this->condicion .= $this->whereAnd . ' ott.id_orden_trabajo_tecnico = ' . $this->idOrdenTrabajoTecnico;
            $this->whereAnd = ' AND ';
        }
    }
}
