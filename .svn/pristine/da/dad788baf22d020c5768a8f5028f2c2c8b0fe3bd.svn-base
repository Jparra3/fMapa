<?php
namespace modelo;
session_start();
require_once($_SESSION['ruta'].'entorno/Conexion.php');
class FormaPago {
    public $conexion;
    
    private $idFormaPago;
    private $formaPago;
    private $estado;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    private $principal;
    private $idTipoDocumento;
    private $visualiza;
    
    public function __construct(\entidad\FormaPago $formaPago, $conexion = null) {
        $this->idFormaPago = $formaPago->getIdFormaPago();
        $this->formaPago = $formaPago->getFormaPago();
        $this->estado = $formaPago->getEstado();
        $this->idUsuarioCreacion = $formaPago->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $formaPago->getIdUsuarioModificacion();
        $this->fechaCreacion = $formaPago->getFechaCreacion();
        $this->fechaModificacion = $formaPago->getFechaModificacion();
        $this->principal = $formaPago->getPrincipal();
        $this->idTipoDocumento = $formaPago->getIdTipoDocumento();
        $this->visualiza = $formaPago->getVisualiza();
        
        $this->conexion = $conexion == null ? new \Conexion() : $conexion;
    }
    
    public function consultar($codigoTipoNaturaleza) {
        $sentenciaSql = "   
                        SELECT
                            fp.id_forma_pago
                            , td.tipo_documento AS forma_pago
                        FROM
                            facturacion_inventario.forma_pago AS fp
                            INNER JOIN contabilidad.tipo_documento AS td ON td.id_tipo_documento = fp.id_tipo_documento
                            INNER JOIN contabilidad.naturaleza AS n ON n.id_naturaleza = td.id_naturaleza
                            INNER JOIN contabilidad.tipo_naturaleza AS tn ON tn.id_tipo_naturaleza = n.id_tipo_naturaleza
                        WHERE
                            fp.estado = TRUE
                            AND fp.visiualiza = TRUE
                            AND td.estado = TRUE
                            AND tn.codigo = '$codigoTipoNaturaleza'
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]['idFormaPago'] = $fila->id_forma_pago;
            $retorno[$contador]['formaPago'] = $fila->forma_pago;
            $contador++;
        }
        return $retorno;
    }
    
    public function obtenerInfoFormaPago(){
        $sentenciaSql = "
                        SELECT
                            id_tipo_documento
                            , cruza
                            , cruza_otro
                        FROM
                            facturacion_inventario.forma_pago
                        WHERE
                            id_forma_pago = $this->idFormaPago
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $fila = $this->conexion->obtenerObjeto();
        $retorno['idTipoDocumento'] = $fila->id_tipo_documento;
        $retorno['cruza'] = $fila->cruza;
        $retorno['cruzaOtro'] = $fila->cruza_otro;
        
        return $retorno;
    }
}
