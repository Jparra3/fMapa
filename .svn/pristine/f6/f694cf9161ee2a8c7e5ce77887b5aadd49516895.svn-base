<?php
namespace modelo;
session_start();
require_once($_SESSION['ruta'].'entorno/Conexion.php');
class TransaccionProveedor {
    public $conexion = null;
    
    private $idTransaccionProveedor;
    private $idTransaccion;
    private $idProveedor;
    private $idUsuarioCreacion;
    private $idUsuarioModicacion;
    private $fechaCreacion;
    private $fechaModificacion;
    private $documentoExterno;
    
    public function __construct(\entidad\TransaccionProveedor $transaccionProveedor, $conexion = null) {
        $this->idTransaccionProveedor = $transaccionProveedor->getIdTransaccionProveedor();
        $this->idTransaccion = $transaccionProveedor->getIdTransaccion();
        $this->idProveedor = $transaccionProveedor->getIdProveedor();
        $this->idUsuarioCreacion = $transaccionProveedor->getIdUsuarioCreacion();
        $this->idUsuarioModicacion = $transaccionProveedor->getIdUsuarioModicacion();
        $this->fechaCreacion = $transaccionProveedor->getFechaCreacion();
        $this->fechaModificacion = $transaccionProveedor->getFechaModificacion();
        $this->documentoExterno = $transaccionProveedor->getDocumentoExterno();
        
        $this->conexion = $conexion == null ? new \Conexion() : $conexion;
    }
    public function adicionar() {
        $sentenciaSql = "
                        INSERT INTO
                            facturacion_inventario.transaccion_proveedor
                        (
                            id_transaccion
                            , id_proveedor
                            , id_usuario_creacion
                            , id_usuario_modificacion
                            , fecha_creacion
                            , fecha_modificacion
                            , documento_externo
                        )
                        VALUES
                        (
                            $this->idTransaccion
                            , $this->idProveedor
                            , $this->idUsuarioCreacion
                            , $this->idUsuarioModicacion
                            , NOW()
                            , NOW()
                            , '$this->documentoExterno'
                        )
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    
    public function consultar() {
        $sentenciaSql = "
                        SELECT
                            id_proveedor
                            , documento_externo
                        FROM
                            facturacion_inventario.transaccion_proveedor
                        WHERE
                            id_transaccion = $this->idTransaccion
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $fila = $this->conexion->obtenerObjeto();
        $retorno["idProveedor"] = $fila->id_proveedor;
        $retorno["documentoExterno"] = $fila->documento_externo;
        return $retorno;
    }
}
