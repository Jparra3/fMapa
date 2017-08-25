<?php
namespace entidad;
class Concepto {
    private $idConcepto;
    private $idTipoDocumento;
    private $modificaValorSalida;
    private $modificaValorEntrada;
    private $requiereProveedor;
    private $requiereDocumentoExterno;
    
    function getIdConcepto() {
        return $this->idConcepto;
    }

    function getIdTipoDocumento() {
        return $this->idTipoDocumento;
    }

    function getModificaValorSalida() {
        return $this->modificaValorSalida;
    }

    function getModificaValorEntrada() {
        return $this->modificaValorEntrada;
    }

    function getRequiereProveedor() {
        return $this->requiereProveedor;
    }

    function getRequiereDocumentoExterno() {
        return $this->requiereDocumentoExterno;
    }

    function setIdConcepto($idConcepto) {
        $this->idConcepto = $idConcepto;
    }

    function setIdTipoDocumento($idTipoDocumento) {
        $this->idTipoDocumento = $idTipoDocumento;
    }

    function setModificaValorSalida($modificaValorSalida) {
        $this->modificaValorSalida = $modificaValorSalida;
    }

    function setModificaValorEntrada($modificaValorEntrada) {
        $this->modificaValorEntrada = $modificaValorEntrada;
    }

    function setRequiereProveedor($requiereProveedor) {
        $this->requiereProveedor = $requiereProveedor;
    }

    function setRequiereDocumentoExterno($requiereDocumentoExterno) {
        $this->requiereDocumentoExterno = $requiereDocumentoExterno;
    }


}
