<?php
namespace modelo;
session_start();
require_once($_SESSION['ruta'].'entorno/Conexion.php');
class TransaccionEstado {
    public $conexion;
    
    private $idTransaccionEstado;
    private $transaccionEstado;
    private $estado;
    private $inicial;
    
    public function __construct(\entidad\TransaccionEstado $transaccionEstado, $conexion = null) {
        $this->idTransaccionEstado = $transaccionEstado->getIdTransaccionEstado();
        $this->transaccionEstado = $transaccionEstado->getTransaccionEstado();
        $this->estado = $transaccionEstado->getEstado();
        $this->inicial = $transaccionEstado->getInicial();
        
        $this->conexion = $conexion == null ? new \Conexion() : $conexion;
    }
    
    public function consultar() {
        $sentenciaSql = "
                        SELECT
                            id_estado_transaccion
                            , estado_transaccion
                            , inicial
                        FROM
                            facturacion_inventario.estado_transaccion
                        WHERE
                            estado = TRUE
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["idEstadoTransaccion"] = $fila->id_estado_transaccion;
            $retorno[$contador]["estadoTransaccion"] = $fila->estado_transaccion;
            $retorno[$contador]["inicial"] = $fila->inicial;
            $contador++;
        }
        return $retorno;
    }
    
    public function obtenCantiEstadInact() {
        $sentenciaSql = "
                        SELECT
                            COUNT(*) AS total
                        FROM
                            facturacion_inventario.estado_transaccion
                        WHERE
                            estado = TRUE
                            AND activo = FALSE --INDICA QUE EL ESTADO SIRVE PARA INACTIVAR LA TRANSACCION
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $fila = $this->conexion->obtenerObjeto();
        return $fila->total;
    }
}
