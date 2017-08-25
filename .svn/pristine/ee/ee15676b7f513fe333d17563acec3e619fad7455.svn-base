<?php
namespace modelo;
session_start();
require_once($_SESSION['ruta'].'entorno/Conexion.php');
class TransaccionCliente {
    public $conexion = null;
    
    private $idTransaccionCliente;
    private $idTransaccion;
    private $idCliente;
    private $idUsuarioCreacion;
    private $idUsuarioModicacion;
    private $fechaCreacion;
    private $fechaModificacion;
    private $idCaja;
    private $prefijo;
    
    public function __construct(\entidad\TransaccionCliente $transaccionCliente, $conexion = null) {
        $this->idTransaccionCliente = $transaccionCliente->getIdTransaccionCliente();
        $this->idTransaccion = $transaccionCliente->getIdTransaccion();
        $this->idCliente = $transaccionCliente->getIdCliente();
        $this->idUsuarioCreacion = $transaccionCliente->getIdUsuarioCreacion();
        $this->idUsuarioModicacion = $transaccionCliente->getIdUsuarioModicacion();
        $this->fechaCreacion = $transaccionCliente->getFechaCreacion();
        $this->fechaModificacion = $transaccionCliente->getFechaModificacion();
        $this->idCaja = $transaccionCliente->getIdCaja();
        $this->prefijo = $transaccionCliente->getPrefijo();
        
        $this->conexion = $conexion == null ? new \Conexion() : $conexion;
    }
    public function adicionar() {
        $sentenciaSql = "
                        INSERT INTO
                            facturacion_inventario.transaccion_cliente
                        (
                            id_transaccion
                            , id_cliente
                            , id_usuario_creacion
                            , id_usuario_modificacion
                            , fecha_creacion
                            , fecha_modificacion
                            , id_caja
                            , prefijo
                        )
                        VALUES
                        (
                            $this->idTransaccion
                            , $this->idCliente
                            , $this->idUsuarioCreacion
                            , $this->idUsuarioModicacion
                            , NOW()
                            , NOW()
                            , $this->idCaja
                            , '$this->prefijo'
                        )
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
}
