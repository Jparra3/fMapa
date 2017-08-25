<?php
namespace modelo;
require_once '../entorno/Conexion.php';
require_once '../entidad/Transaccion.php';
require_once '../entidad/Concepto.php';
require_once '../entidad/TransaccionConcepto.php';

class TransaccionConcepto{
    //Variables necesarias
    public $conexion;
    private $whereAnd;
    private $condicion;
    
    //Varibles base de datos
    private $idTransaccionConcepto;
    private $concepto;
    private $transaccion;
    private $valor;
    private $saldo;
    private $nota;
    private $estado;
    private $fechaCreacion;
    private $fechaModificacion;
    private $idUsuarioCrecion;
    private $idUsuarioModificacion;
    
    public function __construct(\entidad\TransaccionConcepto $trnsaccionConceptoE, $conexion = null) {
        $this-> idTransaccionConcepto = $trnsaccionConceptoE -> getIdTransaccionConcepto();
        $this-> concepto = $trnsaccionConceptoE -> getConcepto() !="" ? $trnsaccionConceptoE -> getConcepto():new \entidad\Concepto();
        $this-> transaccion = $trnsaccionConceptoE -> getTransaccion() !="" ? $trnsaccionConceptoE -> getTransaccion():new \entidad\Transaccion();
        $this-> valor = $trnsaccionConceptoE -> getValor();
        $this-> saldo = $trnsaccionConceptoE ->getSaldo();
        $this-> nota = $trnsaccionConceptoE -> getNota();
        $this-> estado = $trnsaccionConceptoE -> getEstado();
        $this-> fechaCreacion = $trnsaccionConceptoE -> getFechaCreacion();
        $this-> fechaModificacion = $trnsaccionConceptoE -> getFechaModificacion();
        $this-> idUsuarioCrecion = $trnsaccionConceptoE ->getIdUsuarioCrecion();
        $this-> idUsuarioModificacion = $trnsaccionConceptoE ->getIdUsuarioModificacion();
        
        //Instanciamos cconexion
        $this->conexion = $conexion == null ? new \Conexion() : $conexion;
    }
    
    public function adicionar(){
        $sentenciaSql = "
            INSERT INTO
                facturacion_inventario.transaccion_concepto
                (
                      id_concepto
                    , id_transaccion
                    , valor
                    , saldo
                    , nota
                    , estado
                    , fecha_creacion
                    , fecha_modificacion
                    , id_usuario_creacion
                    , id_usuario_modificacion
                )
            VALUES
                (
                      ".$this->concepto->getIdConcepto()."
                    , ".$this->transaccion->getIdTransaccion()."
                    , $this->valor
                    , $this->saldo    
                    , $this->nota
                    , TRUE
                    , NOW()
                    , NOW()
                    , $this->idUsuarioCrecion
                    , $this->idUsuarioModificacion
                )
        ";
        $this -> conexion -> ejecutar($sentenciaSql);
    }
    
    public function obtenerMaximo(){
        $sentenciaSql = "  
                        SELECT
                            MAX(id_transaccion_concepto) AS maximo
                        FROM
                            facturacion_inventario.transaccion_concepto
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $fila = $this->conexion->obtenerObjeto();
        return $fila->maximo;
    }
    
    public function consultar() {
        $sentenciaSql = "
                        SELECT
                            c.modifica_valor_entrada
                            , c.modifica_valor_salida
                        FROM
                            facturacion_inventario.transaccion_concepto AS tc
                            INNER JOIN facturacion_inventario.concepto AS c ON c.id_concepto = tc.id_concepto
                        WHERE
                            tc.id_transaccion = ".$this->transaccion->getIdTransaccion();
        $contador = 0;
        $this->conexion->ejecutar($sentenciaSql);
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["modificaValorEntrada"] = $fila->modifica_valor_entrada;
            $retorno[$contador]["modificaValorSalida"] = $fila->modifica_valor_salida;
            $contador++;
        }
        return $retorno;
    }
}

?>