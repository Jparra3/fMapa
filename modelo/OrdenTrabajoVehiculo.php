<?php
namespace modelo;
session_start();
require_once($_SESSION['ruta'].'entorno/Conexion.php');
class OrdenTrabajoVehiculo {
    public $conexion;
    
    private $idOrdenTrabajoVehiculo;
    private $ordenTrabajo;
    private $vehiculo;
    private $estado;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    
    public function __construct(\entidad\OrdenTrabajoVehiculo $ordenTrabajoVehiculo) {
        $this->idOrdenTrabajoVehiculo = $ordenTrabajoVehiculo->getIdOrdenTrabajoVehiculo();
        $this->ordenTrabajo = $ordenTrabajoVehiculo->getOrdenTrabajo() != "" ? $ordenTrabajoVehiculo->getOrdenTrabajo(): new \entidad\OrdenTrabajo();
        $this->vehiculo = $ordenTrabajoVehiculo->getVehiculo() != "" ? $ordenTrabajoVehiculo->getVehiculo(): new \entidad\Vehiculo();
        $this->estado = $ordenTrabajoVehiculo->getEstado();
        $this->idUsuarioCreacion = $ordenTrabajoVehiculo->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $ordenTrabajoVehiculo->getIdUsuarioModificacion();
        
        $this->conexion = new \Conexion();
    }
    public function adicionar(){
        $sentenciaSql = "
                            INSERT INTO 
                                publics_services.orden_trabajo_vehiculo
                            (
                                id_orden_trabajo
                                ,id_vehiculo
                                ,estado
                                ,id_usuario_creacion
                                ,id_usuario_modificacion
                                ,fecha_creacion
                                ,fecha_modificacion
                            )    
                            VALUES
                            (
                                ".$this->ordenTrabajo->getIdOrdenTrabajo()."
                                ,".$this->vehiculo->getIdVehiculo()."
                                ,".$this->estado."
                                ,".$this->idUsuarioCreacion."
                                ,".$this->idUsuarioModificacion."
                                ,NOW()
                                ,NOW()
                            )
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    public function modificar(){
        $sentenciaSql = "
                            UPDATE 
                                publics_services.orden_trabajo_vehiculo 
                            SET     
                                id_orden_trabajo  = ".$this->ordenTrabajo->getIdOrdenTrabajo()."
                                ,id_vehiculo = ".$this->vehiculo->getIdVehiculo()."
                                ,estado = ".$this->estado."
                                ,id_usuario_modificacion = ".$this->idUsuarioModificacion."
                                ,fecha_modificacion = NOW()
                            WHERE 
                                id_orden_trabajo_vehiculo = $this->idOrdenTrabajoVehiculo
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    public function eliminar(){
        $sentenciaSql = "
                            DELETE FROM 
                                publics_services.orden_trabajo_vehiculo
                            WHERE 
                                id_orden_trabajo = ".$this->ordenTrabajo->getIdOrdenTrabajo()."
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    public function consultar(){
        $this->obtenerCondicion();
        $sentenciaSql = "
                                SELECT 
                                    otv.id_orden_trabajo_vehiculo
                                    ,v.id_vehiculo
                                    ,v.vehiculo
                                    ,v.id_tipo_vehiculo
                                    ,v.id_marca
                                    ,v.id_color
                                    ,tv.tipo_vehiculo
                                    ,m.marca
                                    ,c.color 
                                    ,v.placa
                                    ,'' AS hora_llegada
                                FROM
                                    publics_services.orden_trabajo_vehiculo AS otv
                                    INNER JOIN publics_services.vehiculo AS v ON v.id_vehiculo = otv.id_vehiculo
                                    INNER JOIN publics_services.marca AS m ON m.id_marca = v.id_marca
                                    INNER JOIN publics_services.tipo_vehiculo AS tv ON tv.id_tipo_vehiculo = v.id_tipo_vehiculo
                                    INNER JOIN general.color AS c ON c.id_color = v.id_color
                                 $this->condicion   
                                
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while($fila = $this->conexion->obtenerObjeto()){
            $retorno[$contador]["idOrdenTrabajoVehiculo"] = $fila->id_orden_trabajo_vehiculo;
            $retorno[$contador]["idVehiculo"] = $fila->id_vehiculo;
            $retorno[$contador]["vehiculo"] = $fila->vehiculo;
            $retorno[$contador]["idTipoVehiculo"] = $fila->id_tipo_vehiculo;
            $retorno[$contador]["idMarca"] = $fila->id_marca;
            $retorno[$contador]["idColor"] = $fila->id_color;
            $retorno[$contador]["tipoVehiculo"] = $fila->tipo_vehiculo;
            $retorno[$contador]["marca"] = $fila->marca;
            $retorno[$contador]["color"] = $fila->color;
            $retorno[$contador]["placa"] = $fila->placa;
            $retorno[$contador]["horaLlegada"] = $fila->hora_llegada;
            $contador++;
        }
        return $retorno;
    }
    function obtenerCondicion(){
        $this->condicion = "";
        $this->whereAnd = " WHERE ";
        
        if($this->idOrdenTrabajoVehiculo != "" && $this->idOrdenTrabajoVehiculo != "null" && $this->idOrdenTrabajoVehiculo != null){
            $this->condicion = $this->condicion.$this->whereAnd." otv.id_orden_trabajo_vehiculo = ".$this->idOrdenTrabajoVehiculo;
            $this->whereAnd = " AND ";
        }
        
        if($this->ordenTrabajo != "" && $this->ordenTrabajo != "null" && $this->ordenTrabajo != null){
            if($this->ordenTrabajo->getIdOrdenTrabajo() != "" && $this->ordenTrabajo->getIdOrdenTrabajo() != "null" && $this->ordenTrabajo->getIdOrdenTrabajo() != null){
                $this->condicion = $this->condicion.$this->whereAnd." otv.id_orden_trabajo = ".$this->ordenTrabajo->getIdOrdenTrabajo();
                $this->whereAnd = " AND ";
            }
        }
        
        if($this->estado != "" && $this->estado != "null" && $this->estado != null){
            $this->condicion = $this->condicion.$this->whereAnd." otv.estado = ".$this->estado;
            $this->whereAnd = " AND ";
        }
    }
    
    
}
