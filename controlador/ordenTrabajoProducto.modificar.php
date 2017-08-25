<?php
session_start();
require_once '../entidad/Producto.php';
require_once '../entidad/OrdenTrabajoCliente.php';
require_once '../entidad/OrdenTrabajoProducto.php';
require_once '../modelo/OrdenTrabajoProducto.php';

$retorno = array('exito'=>1, 'mensaje'=>'');

try {
    $idOrdenTrabajoProducto = $_POST['idOrdenTrabajoProducto'];
    $idOrdenTrabajoCliente = $_POST['idOrdenTrabajoCliente'];
    $idProducto = $_POST['idProducto'];
    $nota = $_POST['nota'];
    $cantidad = $_POST['cantidad'];
    $valorUnitaConImpue = $_POST['valorUnitario'];
    $idBodega = $_POST['idBodega'];
    $serial = $_POST['serial'];
    $idTransaccion = $_POST['idTransaccion'];
    $productoSerial = $_POST['serial'];
    $idUsuario = $_SESSION["idUsuario"];
    
    
    
    $ordenTrabajoClienteE =  new \entidad\OrdenTrabajoCliente();
    $ordenTrabajoClienteE->setIdOrdenTrabajoCliente($idOrdenTrabajoCliente);
    
    $ordenTrabajoProductoE = new \entidad\OrdenTrabajoProducto();
    $ordenTrabajoProductoE->setOrdenTrabajoCliente($ordenTrabajoClienteE);
    
    $ordenTrabajoProductoM = new \modelo\OrdenTrabajoProducto($ordenTrabajoProductoE);
    $secuencia = $ordenTrabajoProductoM->obtenMaximSecueProd() + 1;
    
    $productoE = new \entidad\Producto();
    $productoE->setIdProducto($idProducto);
    
    $ordenTrabajoProductoE->setIdBodega($idBodega);
    $ordenTrabajoProductoE->setIdOrdenTrabajoProducto($idOrdenTrabajoProducto);
    $ordenTrabajoProductoE->setSecuencia($secuencia);
    $ordenTrabajoProductoE->setProducto($productoE);
    $ordenTrabajoProductoE->setNota($nota);
    $ordenTrabajoProductoE->setIdTransaccion($idTransaccion);
    $ordenTrabajoProductoE->setEstado("TRUE");
    $ordenTrabajoProductoE->setValorUnitaConImpue($valorUnitaConImpue);
    $ordenTrabajoProductoE->setSerial($serial);
    $ordenTrabajoProductoE->setIdUsuarioCreacion($idUsuario);
    $ordenTrabajoProductoE->setIdUsuarioModificacion($idUsuario);
    $ordenTrabajoProductoE->setCantidad($cantidad);

    $ordenTrabajoProductoM = new \modelo\OrdenTrabajoProducto($ordenTrabajoProductoE);
    
    if($idOrdenTrabajoProducto != "" && $idOrdenTrabajoProducto != null && $idOrdenTrabajoProducto != "null"){
        $ordenTrabajoProductoM->modificar();
    }else{
        $ordenTrabajoProductoM->adicionar();
        $idOrdenTrabajoProducto = $ordenTrabajoProductoM->obtenerMaximo();
        
        if($idTransaccion != "" && $idTransaccion != null && $idTransaccion != "null"){
            $ordenTrabajoProductoE = new \entidad\OrdenTrabajoProducto();
            $ordenTrabajoProductoE->setIdOrdenTrabajoProducto($idOrdenTrabajoProducto);
            $ordenTrabajoProductoE->setIdEstaOrdeTrabProdu(2);//Entregado
            
            $ordenTrabajoProductoM = new \modelo\OrdenTrabajoProducto($ordenTrabajoProductoE);
            $ordenTrabajoProductoM->actualizarEstado();
            
        }
    }
    
    
    
} catch (Exception $exc) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $exc->getMessage();
}
echo json_encode($retorno);

?>