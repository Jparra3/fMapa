<?php
session_start();
require_once '../entidad/OrdenTrabajoProducto.php';
require_once '../modelo/OrdenTrabajoProducto.php';
require_once '../entidad/OrdenTrabajoCliente.php';
require_once '../modelo/OrdenTrabajoCliente.php';

$retorno = array('exito'=>1, 'mensaje'=>'','idOrdenTrabajoCliente'=>null);

try {
    $idOrdenTrabajoCliente = $_POST['idOrdenTrabajoCliente'];
    
    if(count($idOrdenTrabajoCliente) > 0){
        foreach($idOrdenTrabajoCliente as $idEvaluar){
            
            $ordenTrabajoClienteE = new \entidad\OrdenTrabajoCliente();
            $ordenTrabajoClienteE->setIdOrdenTrabajoCliente($idOrdenTrabajoCliente);
            
            $ordenTrabajoClienteM = new \modelo\OrdenTrabajoCliente($ordenTrabajoClienteE);
            $arrInforClien = $ordenTrabajoClienteM->consultar();
            
            foreach($arrInforClien as $cliente){
                $idTercero = $cliente["idTercero"];
            }
            
            $terceroE = new \entidad\Tercero();
            $terceroE->setIdTercero($idTercero);

            $transaccionE = new \entidad\Transaccion();
            $transaccionE->setIdTipoDocumento($idTipoDocumento);
            $transaccionE->setTercero($terceroE);
            $transaccionE->setIdTransaccionEstado($idTransaccionEstado);
            $transaccionE->setIdOficina($idOficina);
            $transaccionE->setIdNaturaleza($idNaturaleza);
            $transaccionE->setNumeroTipoDocumento($numeroTipoDocumento);
            $transaccionE->setNota($nota);
            $transaccionE->setFecha($fecha);
            $transaccionE->setFechaVencimiento($fechaVencimiento);
            $transaccionE->setValor(0);
            $transaccionE->setIdUsuarioCreacion($idUsuario);
            $transaccionE->setIdUsuarioModificacion($idUsuario);

            $transaccionM = new \modelo\Transaccion($transaccionE);
            $transaccionM->adicionar();
            $retorno['idTransaccion'] = $transaccionM->obtenerMaximo();


            $tipoDocumentoE = new \entidad\TipoDocumento();
            $tipoDocumentoE->setIdTipoDocumento($idTipoDocumento);
            $tipoDocumentoE->setNumero($numeroTipoDocumento);

            $tipoDocumentoM = new \modelo\TipoDocumento($tipoDocumentoE);
            $tipoDocumentoM->actualizarNumero();
            
            
            $ordenTrabajoProductoE = new \entidad\OrdenTrabajoProducto();
            $ordenTrabajoProductoE->setOrdenTrabajoCliente($ordenTrabajoCliente);
            $ordenTrabajoProductoE->setEstado("TRUE");
            
            $ordenTrabajoProductoM = new \modelo\OrdenTrabajoProducto($ordenTrabajoProductoE);
            $arrProductoReversar = $ordenTrabajoProductoM->consultar();
            
        }
    }
} catch (Exception $exc) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $exc->getMessage();
}
echo json_encode($retorno);

?>