<?php
namespace entidad;
class TransaccionConcepto{
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
    
    function getSaldo() {
        return $this->saldo;
    }

    function setSaldo($saldo) {
        $this->saldo = $saldo;
    }
    
    public function getIdTransaccionConcepto() {
        return $this->idTransaccionConcepto;
    }

    public function getConcepto() {
        return $this->concepto;
    }

    public function getTransaccion() {
        return $this->transaccion;
    }

    public function getValor() {
        return $this->valor;
    }

    public function getNota() {
        return $this->nota;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function getFechaCreacion() {
        return $this->fechaCreacion;
    }

    public function getFechaModificacion() {
        return $this->fechaModificacion;
    }

    public function getIdUsuarioCrecion() {
        return $this->idUsuarioCrecion;
    }

    public function getIdUsuarioModificacion() {
        return $this->idUsuarioModificacion;
    }

    public function setIdTransaccionConcepto($idTransaccionConcepto) {
        $this->idTransaccionConcepto = $idTransaccionConcepto;
    }

    public function setConcepto(\entidad\Concepto $conceptoE) {
        $this->concepto = $conceptoE;
    }

    public function setTransaccion(\entidad\Transaccion $transaccionE) {
        $this->transaccion = $transaccionE;
    }

    public function setValor($valor) {
        $this->valor = $valor;
    }

    public function setNota($nota) {
        
        if($nota != "" && $nota != null && $nota != "null"){
            $this->nota = "'".$nota."'";
        }else{
            $this->nota = "null";
        }
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

    public function setFechaCreacion($fechaCreacion) {
        $this->fechaCreacion = $fechaCreacion;
    }

    public function setFechaModificacion($fechaModificacion) {
        $this->fechaModificacion = $fechaModificacion;
    }

    public function setIdUsuarioCrecion($idUsuarioCrecion) {
        $this->idUsuarioCrecion = $idUsuarioCrecion;
    }

    public function setIdUsuarioModificacion($idUsuarioModificacion) {
        $this->idUsuarioModificacion = $idUsuarioModificacion;
    }


}
?>