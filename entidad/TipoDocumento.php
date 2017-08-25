<?php 
namespace  entidad;
class TipoDocumento{
    private $idTipoDocumento;
    private $tipoDocumento;
    private $idNaturaleza;
    private $idOficina;
    private $numero;
    private $estado;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    private $codigo;
    
    function getCodigo() {
        return $this->codigo;
    }

    function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    function getIdNaturaleza() {
        return $this->idNaturaleza;
    }

    function getIdOficina() {
        return $this->idOficina;
    }

    function setIdNaturaleza($idNaturaleza) {
        $this->idNaturaleza = $idNaturaleza;
    }

    function setIdOficina($idOficina) {
        $this->idOficina = $idOficina;
    }
    
    public function getFechaModificacion() 
    {
      return $this->fechaModificacion;
    }

    public function setFechaModificacion($fechaModificacion) 
    {
      $this->fechaModificacion = $fechaModificacion;
    }

    public function getFechaCreacion() 
    {
      return $this->fechaCreacion;
    }

    public function setFechaCreacion($fechaCreacion) 
    {
      $this->fechaCreacion = $fechaCreacion;
    }


    public function getIdUsuarioModificacion() 
    {
      return $this->idUsuarioModificacion;
    }

    public function setIdUsuarioModificacion($idUsuarioModificacion) 
    {
      $this->idUsuarioModificacion = $idUsuarioModificacion;
    }

    public function getIdUsuarioCreacion() 
    {
      return $this->idUsuarioCreacion;
    }

    public function setIdUsuarioCreacion($idUsuarioCreacion) 
    {
      $this->idUsuarioCreacion = $idUsuarioCreacion;
    }


    public function getEstado() 
    {
      return $this->estado;
    }

    public function setEstado($estado) 
    {
      $this->estado = $estado;
    }


    public function getNumero() 
    {
      return $this->numero;
    }

    public function setNumero($numero) 
    {
      $this->numero = $numero;
    }

    public function getTipoDocumento() 
    {
      return $this->tipoDocumento;
    }

    public function setTipoDocumento($tipoDocumento) 
    {
      $this->tipoDocumento = $tipoDocumento;
    }

    public function getIdTipoDocumento() 
    {
      return $this->idTipoDocumento;
    }

    public function setIdTipoDocumento($idTipoDocumento) 
    {
      $this->idTipoDocumento = $idTipoDocumento;
    }

}	

?>