<?php
session_start();
require_once '../entidad/Producto.php';
require_once '../entidad/Cliente.php';
require_once '../entidad/OrdenTrabajo.php';
require_once '../entidad/OrdenTrabajoCliente.php';
require_once '../entidad/OrdenTrabajoProducto.php';
require_once '../modelo/OrdenTrabajoProducto.php';

$retorno = array('exito'=>1, 'mensaje'=>'', 'data'=>null);

try {
    $idMunicipio = $_POST['idMunicipio'];
    $idProducto = $_POST['idProducto'];
    $idCliente = $_POST['idCliente'];
    $numero = $_POST['numero'];
    $serial = $_POST['serial'];
    
    
    $productoE = new \entidad\Producto();
    $productoE->setIdProducto($idProducto);
    
    $clienteE = new \entidad\Cliente();
    $clienteE->setIdCliente($idCliente);
    
    $ordenTrabajoE = new \entidad\OrdenTrabajo();
    $ordenTrabajoE->setIdMunicipio($idMunicipio);
    $ordenTrabajoE->setNumero($numero);
    
    $ordenTrabajoClienteE = new \entidad\OrdenTrabajoCliente();
    $ordenTrabajoClienteE->setOrdenTrabajo($ordenTrabajoE);
    $ordenTrabajoClienteE->setCliente($clienteE);
    
    $ordenTrabajoProductoE = new \entidad\OrdenTrabajoProducto();
    $ordenTrabajoProductoE->setOrdenTrabajoCliente($ordenTrabajoClienteE);
    $ordenTrabajoProductoE->setSerial($serial);
    $ordenTrabajoProductoE->setProducto($productoE);
    $ordenTrabajoProductoE->setEstado("TRUE");
    
    $ordenTrabajoProductoM = new \modelo\OrdenTrabajoProducto($ordenTrabajoProductoE);
    $retorno['data'] = $ordenTrabajoProductoM->consultar();
    $retorno['numeroRegistros'] = $ordenTrabajoProductoM->conexion->obtenerNumeroRegistros();
    
} catch (Exception $exc) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $exc->getMessage();
}
echo json_encode($retorno);

?>