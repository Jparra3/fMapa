<?php
namespace modelo;
session_start();
require_once($_SESSION['ruta'].'entorno/Conexion.php');
class ClienteTransaccionProducto {
    public $conexion;
    private $condicion;
    private $whereAnd;
    
    private $idClienteTransaccionProducto;
    private $transaccionProducto;
    private $idTabla;
    private $tabla;
    private $cliente;
    private $estado;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    
    public function __construct(\entidad\clienteTransaccionProducto $clienteTransaccionProducto) {
        $this->idClienteTransaccionProducto = $clienteTransaccionProducto->getIdClienteTransaccionProducto();
        $this->transaccionProducto = $clienteTransaccionProducto->getTransaccionProducto() != "" ? $clienteTransaccionProducto->getTransaccionProducto(): new \entidad\TransaccionProducto;
        $this->idTabla = $clienteTransaccionProducto->getIdTabla();
        $this->tabla = $clienteTransaccionProducto->getTabla();
        $this->cliente = $clienteTransaccionProducto->getCliente() != "" ? $clienteTransaccionProducto->getCliente(): new \entidad\Cliente();
        $this->estado = $clienteTransaccionProducto->getEstado();
        $this->idUsuarioCreacion = $clienteTransaccionProducto->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $clienteTransaccionProducto->getIdUsuarioModificacion();
        
        $this->conexion = new \Conexion();
    }
    function adicionar(){
        $sentenciaSql = "
                            INSERT INTO
                                publics_services.cliente_transaccion_producto
                            (
                                id_transaccion_producto
                                ,id_tabla
                                ,tabla
                                ,id_cliente
                                ,estado
                                ,id_usuario_creacion
                                ,id_usuario_modificacion
                                ,fecha_creacion
                                ,fecha_modificacion
                            )
                            VALUES
                            (
                                ".$this->transaccionProducto->getIdTransaccionProducto()."
                                ,$this->idTabla
                                ,'".$this->tabla."'
                                ,".$this->cliente->getIdCliente()."
                                , ".$this->estado."
                                , $this->idUsuarioCreacion
                                , $this->idUsuarioModificacion
                                , NOW()
                                , NOW()
                            )
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
}
