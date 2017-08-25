<?php
namespace modelo;
session_start();
require_once($_SESSION['ruta'] . 'entorno/Conexion.php');
require_once '../entidad/Transaccion.php';
class TransaccionImpuesto {
    public $conexion = null;
    
    private $idTransaccionImpuesto;
    private $idTransaccion;
    private $idImpuesto;
    private $impuestoValor;
    private $valor;
    private $base;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    
    public function __construct(\entidad\TransaccionImpuesto $transaccionImpuesto) {
        $this->idTransaccionImpuesto = $transaccionImpuesto->getIdTransaccionImpuesto();
        $this->idTransaccion = $transaccionImpuesto->getIdTransaccion();
        $this->idImpuesto = $transaccionImpuesto->getIdImpuesto();
        $this->impuestoValor = $transaccionImpuesto->getImpuestoValor();
        $this->valor = $transaccionImpuesto->getValor();
        $this->base = $transaccionImpuesto->getBase();
        $this->idUsuarioCreacion = $transaccionImpuesto->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $transaccionImpuesto->getIdUsuarioModificacion();
        $this->fechaCreacion = $transaccionImpuesto->getFechaCreacion();
        $this->fechaModificacion = $transaccionImpuesto->getFechaModificacion();
        
        $this->conexion = new \Conexion();
    }
    
    public function adicionar() {
        $sentenciaSql = "
                        INSERT INTO
                            facturacion_inventario.transaccion_impuesto
                        (
                            id_transaccion
                            , id_impuesto
                            , impuesto_valor
                            , valor
                            , base
                            , id_usuario_creacion
                            , id_usuario_modificacion
                            , fecha_creacion
                            , fecha_modificacion
                        )
                        VALUES
                        (
                            $this->idTransaccion
                            , $this->idImpuesto
                            , $this->impuestoValor
                            , $this->valor
                            , $this->base
                            , $this->idUsuarioCreacion
                            , $this->idUsuarioModificacion
                            , NOW()
                            , NOW()
                        )
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    
    public function consultar() {
        $sentenciaSql = "
                        SELECT
                            CONCAT_WS(' - ', i.impuesto, CONCAT(CEILING(ti.impuesto_valor), i.simbolo)) AS impuesto
                            , i.simbolo
                            , ROUND(ti.valor) AS total_impuesto
                            , ROUND(ti.base) AS total_base 
                        FROM
                            facturacion_inventario.transaccion_impuesto AS ti
                            INNER JOIN facturacion_inventario.impuesto AS i ON i.id_impuesto = ti.id_impuesto
                        WHERE
                            id_transaccion = $this->idTransaccion
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["impuesto"] = $fila->impuesto;
            $retorno[$contador]["simbolo"] = $fila->simbolo;
            $retorno[$contador]["totalImpuesto"] = $fila->total_impuesto;
            $retorno[$contador]["totalBase"] = $fila->total_base;
            $contador++;
        }
        return $retorno;
    }
}
