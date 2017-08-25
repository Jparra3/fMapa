<?php
namespace modelo;
session_start();
require_once($_SESSION['ruta'].'entorno/Conexion.php');
class Naturaleza {
    public $conexion = null;
    private $condicion;
    private $whereAnd;
    
    private $idNaturaleza;
    private $naturaleza;
    private $signo;
    private $idTipoNaturaleza;
    private $mueveInventario;
    private $idNaturalezaAfecta;
    private $estado;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    
    public function __construct(\entidad\Naturaleza $naturaleza) {
        $this->idNaturaleza = $naturaleza->getIdNaturaleza();
        $this->naturaleza = $naturaleza->getNaturaleza();
        $this->signo = $naturaleza->getSigno();
        $this->idTipoNaturaleza = $naturaleza->getIdTipoNaturaleza();
        $this->mueveInventario = $naturaleza->getMueveInventario();
        $this->idNaturalezaAfecta = $naturaleza->getIdNaturalezaAfecta();
        $this->estado = $naturaleza->getEstado();
        $this->idUsuarioCreacion = $naturaleza->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $naturaleza->getIdUsuarioModificacion();
        $this->fechaCreacion = $naturaleza->getFechaCreacion();
        $this->fechaModificacion = $naturaleza->getFechaModificacion();
        
        $this->conexion = new \Conexion();
    }
    
    public function consultar() {
        $sentenciaSql = "
                        SELECT
                            id_naturaleza
                            , naturaleza
                        FROM
                            contabilidad.naturaleza
                        WHERE
                            estado = TRUE
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["idNaturaleza"] = $fila->id_naturaleza;
            $retorno[$contador]["naturaleza"] = $fila->naturaleza;
            $contador++;
        }
        return $retorno;
    }
}
