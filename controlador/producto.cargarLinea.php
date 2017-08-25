<?php
require_once '../entidad/LineaProducto.php';
require_once '../modelo/LineaProducto.php';
try {
    $menu = '';
    $lineaProductoEC = new \entidad\LineaProducto();
    $lineaProductoEC->setIdLineaProductoPadre(null);
    
    $lineaProductoMC = new \modelo\LineaProducto($lineaProductoEC);
    $lineaProductoMC->obtenerLineasPadres();
    
    
    $lineaProductoE = new \entidad\LineaProducto();
    $lineaProductoM = new \modelo\LineaProducto($lineaProductoE);
   
    $menu .= '<ul>';
        while($fila = $lineaProductoMC->conexion->obtenerObjeto()){
            $menu.= '<li><a data-dismiss="modal" style="cursor:pointer;" onclick="asignar(\''.$fila->linea_producto.'\','.$fila->id_linea_producto.')"><span id="imgLinea" title="Linea Producto">'.$fila->linea_producto.'</a>';	
            $menu .= $lineaProductoM->obtenerLineasHijas($fila->id_linea_producto);
            $menu .= '</li>';
        }
    $menu .= '</ul>';
} catch (Exception $e) {
    echo $e->getMessage();
}
 echo $menu;
?>