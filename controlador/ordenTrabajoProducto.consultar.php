<?php
session_start();
require_once '../entidad/OrdenTrabajoCliente.php';
require_once '../entidad/OrdenTrabajoProducto.php';
require_once '../modelo/OrdenTrabajoProducto.php';

$retorno = array('exito'=>1, 'mensaje'=>'', 'data'=>null);

try {
    $idOrdenTrabajoProducto = $_POST['idOrdenTrabajoProducto'];
    $idOrdenTrabajoCliente = $_POST['idOrdenTrabajoCliente'];
    $estado = $_POST['estado'];
    
    $ordenTrabajoClienteE = new \entidad\OrdenTrabajoCliente();
    $ordenTrabajoClienteE->setIdOrdenTrabajoCliente($idOrdenTrabajoCliente);
    
    $ordenTrabajoProductoE = new \entidad\OrdenTrabajoProducto();
    $ordenTrabajoProductoE->setIdOrdenTrabajoProducto($idOrdenTrabajoProducto);
    $ordenTrabajoProductoE->setOrdenTrabajoCliente($ordenTrabajoClienteE);
    $ordenTrabajoProductoE->setEstado($estado);
    
    $ordenTrabajoProductoM = new \modelo\OrdenTrabajoProducto($ordenTrabajoProductoE);
    $retorno['data'] = $ordenTrabajoProductoM->consultar();
    $retorno['numeroRegistros'] = $ordenTrabajoProductoM->conexion->obtenerNumeroRegistros();
    
} catch (Exception $exc) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $exc->getMessage();
}
echo json_encode($retorno);

?>