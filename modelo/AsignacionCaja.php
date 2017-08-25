<?php
namespace modelo;
session_start();
require_once($_SESSION['ruta'].'entorno/Conexion.php');
class AsignacionCaja {
    public $conexion;
    
    private $idAsignacionCaja;
    private $idCajero;
    private $idCaja;
    private $estado;
    private $fechaHoraInicial;
    private $fechaHoraFinal;
    
    public function __construct(\entidad\AsignacionCaja $asignacionCaja) {
        $this->idAsignacionCaja = $asignacionCaja->getIdAsignacionCaja();
        $this->idCajero = $asignacionCaja->getIdCajero();
        $this->idCaja = $asignacionCaja->getIdCaja();
        $this->estado = $asignacionCaja->getEstado();
        $this->fechaHoraInicial = $asignacionCaja->getFechaHoraInicial();
        $this->fechaHoraFinal = $asignacionCaja->getFechaHoraFinal();
        
        $this->conexion = new \Conexion();
    }
    
    public function consultar() {
        $sentenciaSql = "
                        SELECT
                            id_cajero
                            , id_caja
                            , estado
                            , to_char(fecha_hora_inicial, 'YYYY-MM-DD HH24:MI:SS') AS fecha_hora_inicial
                            , to_char(fecha_hora_final, 'YYYY-MM-DD HH24:MI:SS') AS fecha_hora_final
                        FROM
                            facturacion_inventario.asignacion_caja
                        WHERE
                            id_caja = $this->idCaja
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["idCajero"] = $fila->id_cajero;
            $retorno[$contador]["idCaja"] = $fila->id_caja;
            $retorno[$contador]["estado"] = $fila->estado;
            $retorno[$contador]["fechaHoraInicial"] = $fila->fecha_hora_inicial;
            $retorno[$contador]["fechaHoraFinal"] = $fila->fecha_hora_final;
            $contador++;
        }
        return $retorno;
    }
}
