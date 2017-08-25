<?php
namespace modelo;
require_once '../../Seguridad/entidad/Sucursal.php';
class Proveedor{
    public $conexion;
    private $whereAnd;
    private $condicion;
    
    private $idProveedor;
    private $sucursal;
    private $idProveedorEstado;
    private $fechaCreacion;
    private $fechaModificacion;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    
    public function __construct(\entidad\Proveedor $proveedorE) {
        $this->idProveedor = $proveedorE->getIdProveedor();
        $this->sucursal = $proveedorE->getSucursal() != "" ? $proveedorE->getSucursal():new \entidad\Sucursal();
        $this->idProveedorEstado = $proveedorE->getIdProveedorEstado();
        $this->fechaCreacion = $proveedorE->getFechaCreacion();
        $this->fechaModificacion = $proveedorE->getFechaModificacion();
        $this->idUsuarioCreacion = $proveedorE->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $proveedorE->getIdUsuarioModificacion();
        
        $this->conexion = new \Conexion();
    }
    
    public function adicionar(){
        $sentenciaSql = "
            INSERT INTO facturacion_inventario.proveedor
                (
                      id_sucursal
                    , id_proveedor_estado
                    , fecha_creacion
                    , fecha_modificacion
                    , id_usuario_creacion
                    , id_usuario_modificacion
                )
            VALUES
                (
                      ".$this->sucursal->getIdSucursal()."
                    , $this->idProveedorEstado
                    , NOW()
                    , NOW()
                    , $this->idUsuarioCreacion
                    , $this->idUsuarioModificacion
                )
        ";
        $this->conexion->ejecutar($sentenciaSql);
        return $this->obtenerMaximo();
    }
    
    public function obtenerMaximo(){
        $sentenciaSql = "
            SELECT
                MAX(id_proveedor) AS id_proveedor
            FROM
                facturacion_inventario.proveedor
        ";
        $this->conexion->ejecutar($sentenciaSql);
        $fila = $this->conexion->obtenerObjeto();                   
        return $fila->id_proveedor;
    }
    
    public function consultar(){
        $this->obtenerCondicion();
        $sentenciaSql = "
            SELECT
                  p.id_proveedor
                , t.id_tercero
                , s.id_sucursal  
            FROM 
                facturacion_inventario.proveedor AS p
                INNER JOIN contabilidad.sucursal AS s ON s.id_sucursal = p.id_sucursal AND s.principal = TRUE
                INNER JOIN contabilidad.tercero AS t ON t.id_tercero = s.id_tercero 
            $this->condicion
        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while($fila = $this->conexion->obtenerObjeto()){
            $retorno[$contador]['idProveedor'] = $fila -> id_proveedor;
            $retorno[$contador]['idTercero'] = $fila -> id_tercero;
            $retorno[$contador]['idSucursal'] = $fila -> id_sucursal;
            
            $contador++;
        }
        return $retorno;
    }

        
    public function consultarProveedorTercero($nit){
        $sentenciaSql = "
            SELECT
                  p.id_proveedor
                , t.id_tercero
                , s.id_sucursal  
            FROM 
                facturacion_inventario.proveedor AS p
                INNER JOIN contabilidad.sucursal AS s ON s.id_sucursal = p.id_sucursal AND s.principal = TRUE
                INNER JOIN contabilidad.tercero AS t ON t.id_tercero = s.id_tercero 
            WHERE t.nit = $nit
        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while($fila = $this->conexion->obtenerObjeto()){
            $retorno[$contador]['idProveedor'] = $fila -> id_proveedor;
            $retorno[$contador]['idTercero'] = $fila -> id_tercero;
            $retorno[$contador]['idSucursal'] = $fila -> id_sucursal;
            
            $contador++;
        }
        return $retorno;
    }
    
    public function obtenerCondicion(){
        $this->condicion = "";
        $this->whereAnd = " WHERE ";
        
        if($this->idProveedor != "" && $this->idProveedor != "null" && $this->idProveedor != null){
            $this->condicion .= $this->whereAnd . " p.id_proveedor = " . $this->idProveedor;
            $this->whereAnd = " AND ";
        }
    }
}
?>