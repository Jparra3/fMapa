<?php
namespace modelo;
session_start();
require_once($_SESSION['ruta'].'entorno/Conexion.php');
class TransaccionCruce {
    public $conexion;
    
    private $idTransaccionCruce;
    private $idTransaccionConceptoAfectado;
    private $idTransaccionFormaPago;
    private $valor;
    private $secuencia;
    private $fecha;
    private $observacion;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    private $idTransaccionConceptoOrigen;
    
    public function __construct(\entidad\TransaccionCruce $transaccionCruce, $conexion = null) {
        $this->idTransaccionCruce = $transaccionCruce->getIdTransaccionCruce();
        $this->idTransaccionConceptoAfectado = $transaccionCruce->getIdTransaccionConceptoAfectado();
        $this->idTransaccionFormaPago = $transaccionCruce->getIdTransaccionFormaPago();
        $this->valor = $transaccionCruce->getValor();
        $this->secuencia = $transaccionCruce->getSecuencia();
        $this->fecha = $transaccionCruce->getFecha();
        $this->observacion = $transaccionCruce->getObservacion();
        $this->idUsuarioCreacion = $transaccionCruce->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $transaccionCruce->getIdUsuarioModificacion();
        $this->fechaCreacion = $transaccionCruce->getFechaCreacion();
        $this->fechaModificacion = $transaccionCruce->getFechaModificacion();
        $this->idTransaccionConceptoOrigen = $transaccionCruce->getIdTransaccionConceptoOrigen();
        
        $this->conexion = $conexion == null ? new \Conexion() : $conexion;
    }
    public function adicionar() {
        $sentenciaSql = "
                        INSERT INTO
                            facturacion_inventario.transaccion_cruce
                        (
                            id_transaccion_concepto_afectado
                            , id_transaccion_forma_pago
                            , valor
                            , secuencia
                            , fecha
                            , observacion
                            , fecha_creacion
                            , fecha_modificacion
                            , id_usuario_creacion
                            , id_usuario_modificacion
                            , id_transaccion_concepto_origen
                        )
                        VALUES
                        (
                            $this->idTransaccionConceptoAfectado
                            , $this->idTransaccionFormaPago
                            , $this->valor
                            , $this->secuencia
                            , $this->fecha
                            , $this->observacion
                            , NOW()
                            , NOW()
                            , $this->idUsuarioCreacion
                            , $this->idUsuarioModificacion
                            , $this->idTransaccionConceptoOrigen
                        )
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    
    public function consultarEstadoCaja() {
        $sentenciaSql = "
                        SELECT
                            CONCAT(td.tipo_documento, ' | ', tn.tipo_naturaleza) AS forma_pago
                            , SUM(CEILING(tcr.valor)) AS valor
                        FROM
                            facturacion_inventario.transaccion_cruce AS tcr
                            INNER JOIN facturacion_inventario.transaccion_forma_pago AS tfp ON tfp.id_transaccion_forma_pago = tcr.id_transaccion_forma_pago
			    INNER JOIN facturacion_inventario.forma_pago AS fp ON fp.id_forma_pago = tfp.id_forma_pago
                            INNER JOIN contabilidad.tipo_documento AS td ON td.id_tipo_documento = fp.id_tipo_documento
                            INNER JOIN contabilidad.naturaleza AS n ON n.id_naturaleza = td.id_naturaleza
                            INNER JOIN contabilidad.tipo_naturaleza AS tn ON tn.id_tipo_naturaleza = n.id_tipo_naturaleza
                        WHERE
                                tcr.id_usuario_creacion = $this->idUsuarioCreacion
                                AND CAST(tcr.fecha_creacion AS DATE) = '$this->fechaCreacion'
                                --AND tn.codigo = 'VE-FP' --VENTAS - FORMAS DE PAGO
                        GROUP BY td.id_tipo_documento, tn.tipo_naturaleza
                        ORDER BY tn.tipo_naturaleza
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        $total = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno["detalle"][$contador]["formaPago"] = $fila->forma_pago;
            $retorno["detalle"][$contador]["valor"] = $fila->valor;
            $total = $total + $fila->valor;
            $contador++;
        }
        $retorno["total"] = $total;
        return $retorno;
    }
    
    //------------------------------------MÉTODOS DE FACTURACIÓN----------------------------------
    public function consultarFormasPagoFactura() {
        $sentenciaSql = "
                        SELECT
                            td.tipo_documento AS forma_pago
                            , CEILING(tc.valor) AS valor
                        FROM
                            facturacion_inventario.transaccion_cruce AS tc
                            INNER JOIN facturacion_inventario.transaccion_forma_pago AS  tfp  ON tfp.id_transaccion_forma_pago = tc.id_transaccion_forma_pago
                            INNER JOIN facturacion_inventario.forma_pago AS fp ON fp.id_forma_pago = tfp.id_forma_pago
                            INNER JOIN contabilidad.tipo_documento AS td ON td.id_tipo_documento = fp.id_tipo_documento
                        WHERE
                            tc.id_transaccion_concepto_afectado = ".$this->idTransaccionConceptoAfectado;
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["formaPago"] = $fila->forma_pago;
            $retorno[$contador]["valor"] = $fila->valor;
            $contador++;
        }
        return $retorno;
    }
    
    public function obtenerSecuencia(){
        $sentenciaSql = "
                        SELECT
                            (MAX(secuencia) + 1) AS secuencia
                        FROM
                            facturacion_inventario.transaccion_cruce
                        WHERE
                            id_transaccion_concepto_afectado = $this->idTransaccionConceptoAfectado
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $fila = $this->conexion->obtenerObjeto();
        return $fila->secuencia;
    }
}
