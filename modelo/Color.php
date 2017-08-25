<?php
namespace modelo;
session_start();
require_once($_SESSION['ruta'].'entorno/Conexion.php');
class Color {
    public $conexion;
    
    private $idColor;
    private $color;
    private $estado;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    
    public function __construct(\entidad\Color $color) {
        $this->idColor = $color->getIdColor();
        $this->color = $color->getColor();
        $this->estado = $color->getEstado();
        $this->idUsuarioCreacion = $color->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $color->getIdUsuarioCreacion();
        
        $this->conexion = new \Conexion();
    }
    
    
}
