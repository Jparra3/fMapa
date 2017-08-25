<?php
session_start();
require_once '../entidad/Producto.php';
require_once '../entidad/OrdenTrabajoCliente.php';
require_once '../entidad/OrdenTrabajoProducto.php';
require_once '../modelo/OrdenTrabajoProducto.php';

$retorno = array('exito'=>1, 'mensaje'=>'');

try {
    $idOrdenTrabajoCliente = $_POST['idOrdenTrabajoCliente'];
    $idProducto = $_POST['idProducto'];
    $nota = $_POST['nota'];
    $cantidad = $_POST['cantidad'];
    $valorUnitaConImpue = $_POST['valorUnitario'];
    $serial = $_POST['serial'];
    $idBodega = $_POST['idBodega'];
    $idTransaccion = $_POST['idTransaccion'];
    $productoSerial = $_POST['productoSerial'];
    $idEstaOrdeTrabProd = $_POST['idEstaOrdeTrabProd'];
    $idUsuario = $_SESSION["idUsuario"];
    
    
    
    $ordenTrabajoClienteE =  new \entidad\OrdenTrabajoCliente();
    $ordenTrabajoClienteE->setIdOrdenTrabajoCliente($idOrdenTrabajoCliente);
    
    $ordenTrabajoProductoE = new \entidad\OrdenTrabajoProducto();
    $ordenTrabajoProductoE->setOrdenTrabajoCliente($ordenTrabajoClienteE);
    
    $ordenTrabajoProductoM = new \modelo\OrdenTrabajoProducto($ordenTrabajoProductoE);
    $secuencia = $ordenTrabajoProductoM->obtenMaximSecueProd() + 1;
    
    $productoE = new \entidad\Producto();
    $productoE->setIdProducto($idProducto);
    
    $ordenTrabajoProductoE->setIdTransaccion($idTransaccion);
    $ordenTrabajoProductoE->setIdBodega($idBodega);
    $ordenTrabajoProductoE->setSecuencia($secuencia);
    $ordenTrabajoProductoE->setProducto($productoE);
    $ordenTrabajoProductoE->setNota($nota);
    $ordenTrabajoProductoE->setEstado("TRUE");
    $ordenTrabajoProductoE->setValorUnitaConImpue($valorUnitaConImpue);
    $ordenTrabajoProductoE->setSerial($serial);
    $ordenTrabajoProductoE->setIdEstaOrdeTrabProdu($idEstaOrdeTrabProd);
    $ordenTrabajoProductoE->setIdUsuarioCreacion($idUsuario);
    $ordenTrabajoProductoE->setIdUsuarioModificacion($idUsuario);
    
    //Se valida si el producto maneja serial y se almacenan cada uno de los productos con cantidad de 1

    if($productoSerial != "true"){
        $ordenTrabajoProductoE->setCantidad($cantidad);
        
        $ordenTrabajoProductoM = new \modelo\OrdenTrabajoProducto($ordenTrabajoProductoE);
        $ordenTrabajoProductoM->adicionar();
    }else{
        for($i = 0 ; $i < $cantidad ; $i++){
            $ordenTrabajoProductoE->setCantidad(1);
            
            $ordenTrabajoProductoM = new \modelo\OrdenTrabajoProducto($ordenTrabajoProductoE);
            $ordenTrabajoProductoM->adicionar();
        }
    }
} catch (Exception $exc) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $exc->getMessage();
}
echo json_encode($retorno);

?>