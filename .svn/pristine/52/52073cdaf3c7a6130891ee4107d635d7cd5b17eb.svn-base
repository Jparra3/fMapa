<?php
namespace modelo;
session_start();
require_once($_SESSION['ruta'].'entorno/Conexion.php');
class Bodega {
    public $conexion;
    private $condicion;
    private $whereAnd;
    
    private $idBodega;
    private $bodega;
    private $idOficina;
    private $estado;
    
    public function __construct(\entidad\Bodega $bodega) {
        $this->idBodega = $bodega->getIdBodega();
        $this->bodega = $bodega->getBodega();
        $this->idOficina = $bodega->getIdOficina();
        $this->estado = $bodega->getEstado();
        
        $this->conexion = new \Conexion();
    }
    
    public function consultar() {
        $this->obtenerCondicion();
        $sentenciaSql = "
                        SELECT
                              b.id_bodega
                            , b.bodega
                        FROM
                            facturacion_inventario.bodega AS b
                        $this->condicion
                            $this->whereAnd estado = TRUE
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["idBodega"] = $fila->id_bodega;
            $retorno[$contador]["bodega"] = $fila->bodega;
            $contador++;
        }
        return $retorno;
    }
    
    public function obtenerCondicion(){
        $this->condicion = "";
        $this->whereAnd = " WHERE ";
        
        if($this->idOficina != "" && $this->idOficina != null && $this->idOficina != "null"){
            $this->condicion .= $this->whereAnd ." b.id_oficina IN ($this->idOficina)";
            $this->whereAnd = " AND ";
        }
        
        if($this->bodega != "" && $this->bodega != null && $this->bodega != "null"){
            $this->condicion .= $this->whereAnd ." b.bodega = '$this->bodega'";
            $this->whereAnd = " AND ";
        }
        
    }
    
}
