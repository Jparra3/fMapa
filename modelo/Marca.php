<?php
namespace modelo;
session_start();
require_once($_SESSION['ruta'].'entorno/Conexion.php');
class Marca {
    public $conexion;
    
    private $idMarca;
    private $marca;
    private $estado;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    
    public function __construct(\entidad\Marca $marca) {
        $this->idMarca = $marca->getIdMarca();
        $this->marca = $marca->getMarca();
        $this->estado = $marca->getEstado();
        $this->idUsuarioCreacion = $marca->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $marca->getIdUsuarioCreacion();
        
        $this->conexion = new \Conexion();
    }
    
    
}
