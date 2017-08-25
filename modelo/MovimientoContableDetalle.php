<?php
namespace modelo;
session_start();
require_once($_SESSION['ruta'].'entorno/Conexion.php');
require_once '../../Seguridad/entidad/Tercero.php';
class MovimientoContableDetalle {
    public $conexion;
    private $condicion;
    private $condicionPagos;
    private $condicionDesembolsos;
    private $whereAnd;

    private $idMovimientoContableDetalle;
    private $movimientoContable;
    private $tercero;
    private $idCuentaContable;
    private $idCentroCosto;
    private $valor;
    private $nota;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    private $estado;
    
    public function __construct(\entidad\MovimientoContableDetalle $movimientoContableDetalle) {
        $this->idMovimientoContableDetalle = $movimientoContableDetalle->getIdMovimientoContableDetalle();
        $this->movimientoContable = $movimientoContableDetalle->getMovimientoContable() != "" ? $movimientoContableDetalle->getMovimientoContable() : new \entidad\MovimientoContable();
        $this->tercero = $movimientoContableDetalle->getTercero() != "" ? $movimientoContableDetalle->getTercero() : new \entidad\Tercero();
        $this->idCuentaContable = $movimientoContableDetalle->getIdCuentaContable();
        $this->idCentroCosto = $movimientoContableDetalle->getIdCentroCosto();
        $this->valor = $movimientoContableDetalle->getValor();
        $this->nota = $movimientoContableDetalle->getNota();
        $this->idUsuarioCreacion = $movimientoContableDetalle->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $movimientoContableDetalle->getIdUsuarioModificacion();
        $this->fechaCreacion = $movimientoContableDetalle->getFechaCreacion();
        $this->fechaModificacion = $movimientoContableDetalle->getFechaModificacion();
        $this->estado = $movimientoContableDetalle->getEstado();
        
        $this->conexion = new \Conexion();
    }
    
    public function adicionar() {
        $sentenciaSql = "
                        INSERT INTO
                            contabilidad.movimiento_contable_cuenta
                        (
                            id_movimiento_contable
                            ,id_tercero
                            , id_cuenta_contable
                            , id_centro_costo
                            , valor
                            , nota
                            , id_usuario_creacion
                            , id_usuario_modificacion
                            , fecha_creacion
                            , fecha_modificacion
                            , estado
                        )
                        VALUES
                        (
                            ".$this->movimientoContable->getIdMovimientoContable()."
                            , ".$this->tercero->getIdTercero()."
                            , $this->idCuentaContable
                            , $this->idCentroCosto
                            , $this->valor
                            , $this->nota
                            , $this->idUsuarioCreacion
                            , $this->idUsuarioModificacion
                            , NOW()
                            , NOW()
                            , TRUE
                        )
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    public function modificar() {
        $sentenciaSql = "
                        UPDATE
                            contabilidad.movimiento_contable_cuenta
                        SET
                            id_tercero = ".$this->tercero->getIdTercero()."
                            , nota = $this->nota
                            , valor = $this->valor
                            , id_usuario_modificacion = $this->idUsuarioModificacion
                            , fecha_modificacion = NOW()
                        WHERE
                            id_movimiento_contable_cuenta = $this->idMovimientoContableDetalle
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    
    public function consultar() {
        $sentenciaSql = "
                        SELECT
                            mcd.id_movimiento_contable_cuenta
                            , mcd.id_movimiento_contable
                            , mcd.id_tercero
                            , t.nit
                            , t.tercero
                            , mcd.id_cuenta_contable
                            , cc.cuenta_contable
                            , mcd.id_centro_costo
                            , cct.centro_costo
                            , ceiling(mcd.valor) AS valor
                            , mcd.nota
                        FROM
                            contabilidad.movimiento_contable_cuenta AS mcd
                            INNER JOIN contabilidad.movimiento_contable AS mc ON mc.id_movimiento_contable = mcd.id_movimiento_contable
                            INNER JOIN contabilidad.tercero AS t ON t.id_tercero = mcd.id_tercero
                            INNER JOIN contabilidad.cuenta_contable AS cc ON cc.id_cuenta_contable = mcd.id_cuenta_contable
                            INNER JOIN contabilidad.centro_costo AS cct ON cct.id_centro_costo = mcd.id_centro_costo
                        WHERE
                            mcd.id_movimiento_contable = ".$this->movimientoContable->getIdMovimientoContable()."
                        ORDER BY mcd.id_movimiento_contable_cuenta ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["idMovimientoContableCuenta"] = $fila->id_movimiento_contable_cuenta;
            $retorno[$contador]["idMovimientoContable"] = $fila->id_movimiento_contable;
            $retorno[$contador]["idTercero"] = $fila->id_tercero;
            $retorno[$contador]["nit"] = $fila->nit;
            $retorno[$contador]["tercero"] = $fila->tercero;
            $retorno[$contador]["idCuentaContable"] = $fila->id_cuenta_contable;
            $retorno[$contador]["cuentaContable"] = $fila->cuenta_contable;
            $retorno[$contador]["idCentroCosto"] = $fila->id_centro_costo;
            $retorno[$contador]["centroCosto"] = $fila->centro_costo;
            $retorno[$contador]["valor"] = $fila->valor;
            $retorno[$contador]["nota"] = $fila->nota;
            $contador++;
        }
        return $retorno;
    }
    public function eliminar(){
        $sentenciaSql = "
                        DELETE FROM
                            contabilidad.movimiento_contable_cuenta
                        WHERE
                            id_movimiento_contable_cuenta = $this->idMovimientoContableDetalle
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    
    public function consultarReporte(){
        $this->obtenerCondicion();
        $sentenciaSql = "
                        --MOVIMIENTOS CONTABLES
                        SELECT
                            t.tercero
                            , td.tipo_documento
                            , n.naturaleza
                            , mc.fecha
                            , mc.nota
                            , CEILING((mcc.valor * n.signo)) AS valor
                            , 'movimientos' AS tipo
                        FROM
                            contabilidad.movimiento_contable_cuenta AS mcc
                            INNER JOIN contabilidad.movimiento_contable AS mc ON mc.id_movimiento_contable = mcc.id_movimiento_contable
                            INNER JOIN contabilidad.tipo_documento AS td ON td.id_tipo_documento = mc.id_tipo_documento
                            INNER JOIN contabilidad.naturaleza AS n ON n.id_naturaleza = td.id_naturaleza
                            INNER JOIN contabilidad.tercero AS t ON t.id_tercero = mcc.id_tercero
                            $this->condicion $this->whereAnd mc.estado = TRUE
                        UNION ALL

                        --PAGOS CRÉDITOS
                        SELECT
                            t.tercero
                            , td.tipo_documento
                            , n.naturaleza
                            , CAST(NOW() AS DATE) AS fecha
                            , '' AS nota
                            , CEILING(SUM(p.valor) * n.signo) AS valor
                            , 'pagos' AS tipo
                        FROM
                            cm_solutions.pago AS p
                            INNER JOIN cm_solutions.credito AS c ON c.id_credito = p.id_credito
                            INNER JOIN contabilidad.tercero AS t ON t.id_tercero = (SELECT * FROM contabilidad.obtener_id_terce_empre_unida_negoc())

                            , contabilidad.tipo_documento AS td 
                            INNER JOIN contabilidad.naturaleza AS n ON n.id_naturaleza = td.id_naturaleza
                            
                            $this->condicionPagos $this->whereAnd c.estado = TRUE

                            AND td.estado = TRUE
                            AND td.id_tipo_documento = (SELECT * FROM cm_solutions.cm_solutions.obtener_id_tipo_docum_pagos_credi())
                        GROUP BY t.tercero, td.tipo_documento, n.naturaleza, n.signo

                        UNION ALL

                        --DESEMBOLSOS CRÉDITOS
                        SELECT
                            t.tercero
                            , td.tipo_documento
                            , n.naturaleza
                            , CAST(NOW() AS DATE) AS fecha
                            , '' AS nota
                            , CEILING(SUM(c.valor) * n.signo) AS valor
                            , 'creditos' AS tipo
                        FROM
                            cm_solutions.credito AS c
                            INNER JOIN contabilidad.tercero AS t ON t.id_tercero = (SELECT * FROM contabilidad.obtener_id_terce_empre_unida_negoc())

                            , contabilidad.tipo_documento AS td 
                            INNER JOIN contabilidad.naturaleza AS n ON n.id_naturaleza = td.id_naturaleza
                        
                            $this->condicionDesembolsos $this->whereAnd c.estado = TRUE

                            AND td.estado = TRUE
                            AND td.id_tipo_documento = (SELECT * FROM cm_solutions.cm_solutions.obtener_id_tipo_docum_desem_credi())
                        GROUP BY t.tercero, td.tipo_documento, n.naturaleza, n.signo
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        $total = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            
            if($fila->tipo == 'pagos' || $fila->tipo == 'creditos'){
                $fila->fecha = "";
            }
            
            $retorno["detalle"][$contador]["tercero"] = $fila->tercero;
            $retorno["detalle"][$contador]["tipoDocumento"] = $fila->tipo_documento;
            $retorno["detalle"][$contador]["naturaleza"] = $fila->naturaleza;
            $retorno["detalle"][$contador]["fecha"] = $fila->fecha;
            $retorno["detalle"][$contador]["nota"] = $fila->nota;
            $retorno["detalle"][$contador]["valor"] = $fila->valor;
            $total = $total + $fila->valor;
            $contador++;
        }
        $retorno["total"] = $total;
        $_SESSION['arregloEvaluar'] = $retorno;
        return $retorno;
    }
    function obtenerCondicion(){
        $this->condicion = "";
        $this->condicionPagos = "";
        $this->condicionDesembolsos = "";
        $this->whereAnd = " WHERE ";
        
        $fecha = $this->movimientoContable->getFecha();
        
        if($fecha["inicio"] != '' && $fecha["fin"]){
            $this->condicion = $this->condicion.$this->whereAnd." mc.fecha BETWEEN '".$fecha["inicio"]."' AND '".$fecha["fin"]."'";
            $this->condicionPagos = $this->condicionPagos.$this->whereAnd." CAST(p.fecha_creacion AS DATE) BETWEEN '".$fecha["inicio"]."' AND '".$fecha["fin"]."'";
            $this->condicionDesembolsos = $this->condicionDesembolsos.$this->whereAnd." CAST(c.fecha_desembolso AS DATE) BETWEEN '".$fecha["inicio"]."' AND '".$fecha["fin"]."'";
            $this->whereAnd = ' AND ';
        }elseif ($fecha["inicio"] != '' && $fecha["fin"] == '') {
            $this->condicion = $this->condicion.$this->whereAnd."  mc.fecha = '".$fecha["inicio"]."'";
            $this->condicionPagos = $this->condicionPagos.$this->whereAnd." CAST(p.fecha_creacion AS DATE) = '".$fecha["inicio"]."'";
            $this->condicionDesembolsos = $this->condicionDesembolsos.$this->whereAnd." CAST(c.fecha_desembolso AS DATE) = '".$fecha["inicio"]."'";
            $this->whereAnd = ' AND ';
        }elseif ($fecha["inicio"] == '' && $fecha["fin"] != '') {
            $this->condicion = $this->condicion.$this->whereAnd." mc.fecha = '".$fecha["fin"]."'";
            $this->condicionPagos = $this->condicionPagos.$this->whereAnd." CAST(p.fecha_creacion AS DATE) = '".$fecha["fin"]."'";
            $this->condicionDesembolsos = $this->condicionDesembolsos.$this->whereAnd." CAST(c.fecha_desembolso AS DATE) = '".$fecha["fin"]."'";
            $this->whereAnd = ' AND ';
        }
    }
}
