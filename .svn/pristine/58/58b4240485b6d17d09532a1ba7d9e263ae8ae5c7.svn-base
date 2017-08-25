<?php
session_start();
require_once '../entidad/OrdenTrabajoProducto.php';
require_once '../modelo/OrdenTrabajoProducto.php';

$retorno = array('exito'=>1, 'mensaje'=>'');

try {
    $idOrdenTrabajoProducto = $_POST["idOrdenTrabajoProducto"];
    if(count($idOrdenTrabajoProducto) > 0){
        foreach($idOrdenTrabajoProducto as $idEliminar){
            $ordenTrabajoProductoE =  new \entidad\OrdenTrabajoProducto();
            $ordenTrabajoProductoE->setIdOrdenTrabajoProducto($idEliminar);

            $ordenTrabajoProductoM = new \modelo\OrdenTrabajoProducto($ordenTrabajoProductoE);
            $ordenTrabajoProductoM->inactivar();
        }
    }
} catch (Exception $exc) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $exc->getMessage();
}
echo json_encode($retorno);

?>