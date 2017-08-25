<?php
namespace modelo;
session_start();
require_once($_SESSION['ruta'].'entorno/Conexion.php');
class TipoVehiculo {
    public $conexion;
    
    private $idTipoVehiculo;
    private $tipoVehiculo;
    private $estado;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    
    public function __construct(\entidad\TipoVehiculo $tipoVehiculo) {
        $this->idTipoVehiculo = $tipoVehiculo->getIdTipoVehiculo();
        $this->tipoVehiculo = $tipoVehiculo->getTipoVehiculo();
        $this->estado = $tipoVehiculo->getEstado();
        $this->idUsuarioCreacion = $tipoVehiculo->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $tipoVehiculo->getIdUsuarioCreacion();
        
        $this->conexion = new \Conexion();
    }
    
    
}
