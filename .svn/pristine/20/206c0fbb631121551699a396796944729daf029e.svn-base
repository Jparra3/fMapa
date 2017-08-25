<?php
namespace modelo;
session_start();
require_once($_SESSION['ruta'].'entorno/Conexion.php');
class Cajero {
    public $conexion;
    
    private $idCajero;
    private $idUsuario;
    
    public function __construct(\entidad\Cajero $cajero) {
        $this->idCajero = $cajero->getIdCajero();
        $this->idUsuario = $cajero->getIdUsuario();
        
        $this->conexion = new \Conexion();
    }
    
    public function consultar() {
        $sentenciaSql = "
                        SELECT
                            id_cajero
                            , id_usuario
                            , modifica_valor_venta
                        FROM
                            facturacion_inventario.cajero
                        WHERE
                            id_cajero = $this->idCajero
                            AND id_usuario = ".$_SESSION["idUsuario"];
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["idCajero"] = $fila->id_cajero;
            $retorno[$contador]["idUsuario"] = $fila->id_usuario;
            $retorno[$contador]["modificaValorVenta"] = $fila->modifica_valor_venta;
            $contador++;
        }
        return $retorno;
    }
    
    public function consultarCajeros() {
        $sentenciaSql = "
                        SELECT
                            c.id_usuario
                            , p.persona
                        FROM
                            facturacion_inventario.cajero AS c
                            INNER JOIN seguridad.usuario AS u ON u.id_usuario = c.id_usuario
                            INNER JOIN general.persona AS p ON p.id_persona = u.id_persona
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["idUsuario"] = $fila->id_usuario;
            $retorno[$contador]["persona"] = $fila->persona;
            $contador++;
        }
        return $retorno;
    }
}
