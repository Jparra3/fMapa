<?php
session_start();
require_once '../entidad/ClienteServicioProducto.php';
require_once '../modelo/ClienteServicioProducto.php';

$retorno = array('exito'=>1, 'mensaje'=>'');

try {
    $idClienteServicio = $_POST['idClienteServicio'];
    $productos = $_POST['producto'];
    $idUsuario = $_SESSION["idUsuario"];
    
    if(count($productos) > 0){
        
        $clienteServicioE = new \entidad\ClienteServicio();
        $clienteServicioE->setIdClienteServicio($idClienteServicio);
        
        foreach($productos as $informacionProducto){
            
            $clienteServicioProductoE = new \entidad\ClienteServicioProducto();
            $clienteServicioProductoE->setClienteServicio($clienteServicioE);
            $clienteServicioProductoE->setIdClienteServicioProducto($informacionProducto["idClienteServicioProducto"]);
            $clienteServicioProductoE->setCantidad($informacionProducto["cantidad"]);
            $clienteServicioProductoE->setNota($informacionProducto["nota"]);
            
            if($informacionProducto["cantidad"] == 0){
                $clienteServicioProductoE->setEstado("FALSE");
            }else{
                $clienteServicioProductoE->setEstado("TRUE");
            }
            
            $clienteServicioProductoM = new \modelo\ClienteServicioProducto($clienteServicioProductoE);
            $clienteServicioProductoM->actualizarCantidadProducto();
            
        }
    }
    
} catch (Exception $exc) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $exc->getMessage();
}
echo json_encode($retorno);

?>