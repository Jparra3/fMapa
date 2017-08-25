<?php
namespace modelo;
session_start();
require_once($_SESSION['ruta'].'entorno/Conexion.php');
class LineaProducto{
    
    public $conexion=null;
    private $condicion=null;
    private $whereAnd=null;
   
    private $idLineaProducto;
    private $lineaProducto;
    private $idLineaProductoPadre;
    private $estado;
    private $fechaCreacion;
    private $fechaModificacion;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    
    function __construct(\entidad\LineaProducto $lineaProducto) {
        $this->idLineaProducto = $lineaProducto->getIdLineaProducto();
        $this->lineaProducto = $lineaProducto->getLineaProducto();
        $this->idLineaProductoPadre = $lineaProducto->getIdLineaProductoPadre();
        $this->estado = $lineaProducto->getEstado();
        $this->fechaCreacion = $lineaProducto->getFechaCreacion();
        $this->fechaModificacion = $lineaProducto->getFechaModificacion();
        $this->idUsuarioCreacion = $lineaProducto->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $lineaProducto->getIdUsuarioModificacion();
        
        $this->conexion = new \Conexion();
    }
    
    public function consultar(){
        $this->obtenerCondicion();
        $sentenciaSql = "
            SELECT
                  id_linea_producto
                , linea_producto
                , estado
            FROM
                facturacion_inventario.linea_producto AS lp
            $this->condicion $this->whereAnd id_linea_producto_padre IS NULL
        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()){
            $retorno[$contador]['idLineaProducto'] = $fila->id_linea_producto;
            $retorno[$contador]['lineaProducto'] = $fila->linea_producto;
            $retorno[$contador]['estado'] = $fila->estado;
            
            $contador++;
        }
        return $retorno;
    }
    
    public function adicionar(){
        $sentenciaSql = "
            INSERT INTO
                facturacion_inventario.linea_producto
                (
                      
                      linea_producto
                    , estado
                    , id_linea_producto_padre
                    , fecha_creacion
                    , fecha_modificacion
                    , id_usuario_creacion
                    , id_usuario_modificacion
                )
            VALUES
                (
                      '$this->lineaProducto'
                    , TRUE
                    , $this->idLineaProductoPadre
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
                MAX(id_linea_producto) AS id_linea_producto
            FROM
                facturacion_inventario.linea_producto
        ";
        $this->conexion->ejecutar($sentenciaSql);
        $fila = $this->conexion->obtenerObjeto();
        return $fila->id_linea_producto;
    }
    
    public function obtenerLineasPadres(){
       $sentenciaSql = "
                    SELECT
                        *
                    FROM
                        facturacion_inventario.linea_producto 
                    WHERE
                        id_linea_producto_padre IS NULL
                        AND estado = TRUE
                    ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    function obtenerLineasHijas($idLineaProducto){
        $this->menu = '';
        $sentenciaSql = "
                        SELECT
                            *
                        FROM
                            facturacion_inventario.linea_producto
                        WHERE
                            id_linea_producto_padre = $idLineaProducto
                            AND estado = TRUE
                   ";
        $this->conexion->ejecutar($sentenciaSql);
        if($this->conexion->obtenerNumeroRegistros() != 0){
            $menuFinal .= '<ul>';
                while($fila = $this->conexion->obtenerObjeto()){
                    
                    $lineaProductoE = new \entidad\LineaProducto();
                    
                    $lineaProductoM = new \modelo\LineaProducto($lineaProductoE);
                    
                    $this->menu = '<li><a data-dismiss="modal" style="cursor:pointer;" onclick="asignar(\''.$fila->linea_producto.'\','.$fila->id_linea_producto.')"><span id="imgLinea" title="Linea Producto">'.$fila->linea_producto.'</a>';	
                    $menuFinal .= $this->menu;
                    $menuFinal .= $lineaProductoM->obtenerLineasHijas($fila->id_linea_producto);
                    $menuFinal .= '</li>';
                }
            $menuFinal .= '</ul>';
            return $menuFinal;
        }
    }
    
    public function obtenerCondicion(){
        $this->condicion = "";
        $this->whereAnd = " WHERE ";
        
        if($this->idLineaProducto != null && $this->idLineaProducto != "null" && $this->idLineaProducto != ""){
            $this->condicion .= $this->whereAnd . " lp.id_linea_producto = ".$this->idLineaProducto;
            $this->whereAnd = " AND ";
        }
        
        if($this->lineaProducto != null && $this->lineaProducto != "null" && $this->lineaProducto != ""){
            $this->condicion .= $this->whereAnd . " lp.linea_producto LIKE '%".$this->lineaProducto."%'";
            $this->whereAnd = " AND ";
        }
        
        if($this->estado != null && $this->estado != "null" && $this->estado != ""){
            $this->condicion .= $this->whereAnd . " lp.estado = ".$this->estado;
            $this->whereAnd = " AND ";
        }
    }
    
}
?>