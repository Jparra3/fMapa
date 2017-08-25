<?php
session_start();
require_once '../entidad/Producto.php';
require_once '../entidad/OrdenTrabajoCliente.php';
require_once '../entidad/OrdenTrabajoProducto.php';
require_once '../modelo/OrdenTrabajoProducto.php';
require_once '../entidad/ClienteServicioProducto.php';
require_once '../modelo/ClienteServicioProducto.php';

$retorno = array('exito'=>1, 'mensaje'=>'');

try {
    $idClienteServicio = $_POST['idClienteServicio'];
    $idOrdenTrabajoCliente = $_POST['idOrdenTrabajoCliente'];
    $productos = $_POST['producto'];
    $idUsuario = $_SESSION["idUsuario"];
    
    $secuencia = 1;
    if(count($productos) > 0){
        
        //Actualizar Estado de los productos
        $ordenTrabajoClienteE = new \entidad\OrdenTrabajoCliente();
        $ordenTrabajoClienteE->setIdOrdenTrabajoCliente($idOrdenTrabajoCliente);
        
        $ordenTrabajoProductoE = new \entidad\OrdenTrabajoProducto();
        $ordenTrabajoProductoE->setOrdenTrabajoCliente($ordenTrabajoClienteE);
        
        $ordenTrabajoProductoM = new \modelo\OrdenTrabajoProducto($ordenTrabajoProductoE);
        $ordenTrabajoProductoM->actualizarEstadoProductosCliente(3);
        
        foreach($productos as $informacionProducto){
            
            $ordenTrabajoProductoE = new \entidad\OrdenTrabajoProducto();
            $ordenTrabajoProductoE->setIdOrdenTrabajoProducto($informacionProducto["idOrdenTrabajoProducto"]);
            $ordenTrabajoProductoE->setEstado("TRUE");
            $ordenTrabajoProductoE->setIdEstaOrdeTrabProdu(3);//Instalado

            $ordenTrabajoProductoM = new \modelo\OrdenTrabajoProducto($ordenTrabajoProductoE);
            $arrRetorno = $ordenTrabajoProductoM->consultar();
            $dataProducto = $arrRetorno[0];
            
            $clienteServicioE = new \entidad\ClienteServicio();
            $clienteServicioE->setIdClienteServicio($idClienteServicio);
            
            $clienteServicioProductoE = new \entidad\ClienteServicioProducto();
            
            $productoE = new \entidad\Producto();
            $productoE->setIdProducto($dataProducto["idProducto"]);
            
            $clienteServicioProductoE->setIdOrdenTrabajo($dataProducto["idOrdenTrabajo"]);
            $clienteServicioProductoE->setClienteServicio($clienteServicioE);
            $clienteServicioProductoE->setIdBodega($dataProducto["idBodega"]);
            $clienteServicioProductoE->setSecuencia($secuencia);
            $clienteServicioProductoE->setProducto($productoE);            
            $clienteServicioProductoE->setNota($informacionProducto["nota"]);
            
            //Se valida si la cantidad es igual a cero
            if($informacionProducto["cantidad"] == 0){
                $estado = "FALSE";
                $idEstaClieServProd = 2;//Desinstalado
            }else{
                $idEstaClieServProd = 1;//Instalado
                $estado = "TRUE";
            }
            
            $clienteServicioProductoE->setIdEstaClieServProd($idEstaClieServProd);
            $clienteServicioProductoE->setEstado($estado);
            $clienteServicioProductoE->setValorUnitaConImpue($dataProducto["valorEntraConImpue"]);
            $clienteServicioProductoE->setSerial($dataProducto["serial"]);
            $clienteServicioProductoE->setIdUsuarioCreacion($idUsuario);
            $clienteServicioProductoE->setIdUsuarioModificacion($idUsuario);
            $clienteServicioProductoE->setCantidad($informacionProducto["cantidad"]);

            $clienteServicioProductoM = new \modelo\ClienteServicioProducto($clienteServicioProductoE);
            $clienteServicioProductoM->adicionar();
            
            $secuencia++;
        }
    }
    
} catch (Exception $exc) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $exc->getMessage();
}
echo json_encode($retorno);

?>