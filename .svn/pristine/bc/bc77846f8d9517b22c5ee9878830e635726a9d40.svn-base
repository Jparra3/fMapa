<?php
namespace modelo;
session_start();
require_once($_SESSION['ruta'].'entorno/Conexion.php');
class Vehiculo {
    public $conexion;
    public $condicion;
    public $whereAnd;
    
    private $idVehiculo;
    private $vehiculo;
    private $placa;
    private $marca;
    private $color;
    private $tipoVehiculo;
    private $estado;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    
    public function __construct(\entidad\Vehiculo $vehiculo) {
        $this->idVehiculo = $vehiculo->getIdVehiculo();
        $this->vehiculo = $vehiculo->getVehiculo();
        $this->placa = $vehiculo->getPlaca();
        $this->marca = $vehiculo->getMarca() != "" ? $vehiculo->getMarca(): new \entidad\Marca();
        $this->color = $vehiculo->getColor() != "" ? $vehiculo->getColor(): new \entidad\Color();
        $this->tipoVehiculo = $vehiculo->getTipoVehiculo() != "" ? $vehiculo->getTipoVehiculo(): new \entidad\TipoVehiculo();
        $this->estado = $vehiculo->getEstado();
        $this->idUsuarioCreacion = $vehiculo->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $vehiculo->getIdUsuarioModificacion();
        
        $this->conexion = new \Conexion();
    }
    public function consultar(){
        $this->obtenerCondicion();
        $sentenciaSql = "
                                SELECT 
                                    v.id_vehiculo
                                    ,v.vehiculo
                                    ,v.id_tipo_vehiculo
                                    ,v.id_marca
                                    ,v.id_color
                                    ,tv.tipo_vehiculo
                                    ,m.marca
                                    ,c.color 
                                FROM
                                    publics_services.vehiculo AS v
                                    INNER JOIN publics_services.marca AS m ON m.id_marca = v.id_marca
                                    INNER JOIN publics_services.tipo_vehiculo AS tv ON tv.id_tipo_vehiculo = v.id_tipo_vehiculo
                                    INNER JOIN general.color AS c ON c.id_color = v.id_color
                                 $this->condicion   
                                
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while($fila = $this->conexion->obtenerObjeto()){
            $retorno[$contador]["idVehiculo"] = $fila->id_vehiculo;
            $retorno[$contador]["vehiculo"] = $fila->vehiculo;
            $retorno[$contador]["idTipoVehiculo"] = $fila->id_tipo_vehiculo;
            $retorno[$contador]["idMarca"] = $fila->id_marca;
            $retorno[$contador]["idColor"] = $fila->id_color;
            $retorno[$contador]["tipoVehiculo"] = $fila->tipo_vehiculo;
            $retorno[$contador]["marca"] = $fila->marca;
            $retorno[$contador]["color"] = $fila->color;
            $contador++;
        }
        return $retorno;
    }
    
    function obtenerCondicion(){
        $this->condicion = "";
        $this->whereAnd = " WHERE ";
        
        if($this->idVehiculo != "" && $this->idVehiculo != "null" && $this->idVehiculo != null){
            $this->condicion = $this->condicion.$this->whereAnd." v.id_vehiculo = ".$this->idVehiculo;
            $this->whereAnd = " AND ";
        }
        if($this->estado != "" && $this->estado != "null" && $this->estado != null){
            $this->condicion = $this->condicion.$this->whereAnd." v.estado = ".$this->estado;
            $this->whereAnd = " AND ";
        }
    }
}
