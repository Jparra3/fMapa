<?php
namespace modelo;
session_start();
require_once($_SESSION['ruta'].'entorno/Conexion.php');
class TransaccionFormaPago {
    public $conexion = null;
    
    private $idTransaccionFormaPago;
    private $idFormaPago;
    private $idTransaccion;
    private $valor;
    private $nota;
    private $estado;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    
    public function __construct(\entidad\TransaccionFormaPago $transaccionFormaPago, $conexion = null) {
        $this->idTransaccionFormaPago = $transaccionFormaPago->getIdTransaccionFormaPago();
        $this->idFormaPago = $transaccionFormaPago->getIdFormaPago();
        $this->idTransaccion = $transaccionFormaPago->getIdTransaccion();
        $this->valor = $transaccionFormaPago->getValor();
        $this->nota = $transaccionFormaPago->getNota();
        $this->estado = $transaccionFormaPago->getEstado();
        $this->idUsuarioCreacion = $transaccionFormaPago->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $transaccionFormaPago->getIdUsuarioModificacion();
        $this->fechaCreacion = $transaccionFormaPago->getFechaCreacion();
        $this->fechaModificacion = $transaccionFormaPago->getFechaModificacion();
        
        $this->conexion = $conexion == null ? new \Conexion() : $conexion;
    }
    
    public function adicionar() {
        $sentenciaSql = "
                        INSERT INTO
                            facturacion_inventario.transaccion_forma_pago
                        (
                            id_forma_pago
                            , id_transaccion
                            , valor
                            , nota
                            , estado
                            , fecha_creacion
                            , fecha_modificacion
                            , id_usuario_creacion
                            , id_usuario_modificacion
                        )
                        VALUES
                        (
                            $this->idFormaPago
                            , $this->idTransaccion
                            , $this->valor
                            , $this->nota
                            , $this->estado
                            , NOW()
                            , NOW()
                            , $this->idUsuarioCreacion
                            , $this->idUsuarioModificacion
                        )
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    
    public function obtenerMaximo() {
        $sentenciaSql = "
                        SELECT
                            MAX(id_transaccion_forma_pago) AS maximo
                        FROM
                            facturacion_inventario.transaccion_forma_pago
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $fila = $this->conexion->obtenerObjeto();
        return $fila->maximo;
    }
    
    public function consultar() {
        $sentenciaSql = "
                        SELECT
                            id_forma_pago
                            , valor
                        FROM
                            facturacion_inventario.transaccion_forma_pago
                        WHERE
                            id_transaccion = $this->idTransaccion
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["idFormaPago"] = $fila->id_forma_pago;
            $retorno[$contador]["valor"] = $fila->valor;
            $contador++;
        }
        return $retorno;
    }
}
