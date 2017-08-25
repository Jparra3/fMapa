<?php
session_start();
require_once '../entidad/OrdenTrabajoProducto.php';
require_once '../modelo/OrdenTrabajoProducto.php';
require_once '../entidad/ClienteServicioProducto.php';
require_once '../modelo/ClienteServicioProducto.php';
require_once '../entidad/Transaccion.php';
require_once '../modelo/Transaccion.php';
require_once '../entidad/TransaccionProducto.php';
require_once '../modelo/TransaccionProducto.php';
require_once '../entidad/Cliente.php';
require_once '../modelo/Cliente.php';
require_once '../entidad/TipoDocumento.php';
require_once '../modelo/TipoDocumento.php';
require_once '../../Seguridad/entidad/Tercero.php'; 
require_once '../entidad/ClienteTransaccionProducto.php';
require_once '../modelo/ClienteTransaccionProducto.php';


$retorno = array('exito'=>1, 'mensaje'=>'La transacción se adicionó correctamente.', 'idTransaccion'=>'');

try {
    
    $fechaActual = date("Y-m-d");
    
    
    $idTransaccion = $_POST["idTransaccion"];
    $tipo = $_POST["tipo"];
    $productos = $_POST["productos"];
    $idUsuario = $_SESSION["idUsuario"];
    
    /*----------------------------Transaccion Producto ----------------------------------------*/
    $secuencia = 1;
    if(count($productos) > 0){
        foreach($productos as $informacionProducto){
            $tipo = $informacionProducto["tipo"];
            
            //Servicio
            if($tipo == "Servicio"){
                $tabla = "cliente_servicio";
                $idTabla = $informacionProducto["idClienteServicioProducto"];
                
                $clienteServicioProductoE = new \entidad\ClienteServicioProducto();
                $clienteServicioProductoE->setIdClienteServicioProducto($informacionProducto["idClienteServicioProducto"]);
                
                $clienteServicioProductoM = new \modelo\ClienteServicioProducto($clienteServicioProductoE);
                $arrRetorno = $clienteServicioProductoM->consultar();
                $dataProducto = $arrRetorno[0];
                
            }else{
                $tabla = "orden_trabajo_producto";
                $idTabla = $informacionProducto["idOrdenTrabajoProducto"];
                $ordenTrabajoProductoE = new \entidad\OrdenTrabajoProducto();
                $ordenTrabajoProductoE->setIdOrdenTrabajoProducto($informacionProducto["idOrdenTrabajoProducto"]);

                $ordenTrabajoProductoM = new \modelo\OrdenTrabajoProducto($ordenTrabajoProductoE);

                $arrRetorno = $ordenTrabajoProductoM->consultar();
                $dataProducto = $arrRetorno[0];
            }
            
            $clienteE = new \entidad\Cliente();
            $clienteE->setIdCliente($dataProducto["idCliente"]);
            
            $transaccionE = new \entidad\Transaccion();
            $transaccionE->setIdTransaccion($idTransaccion);

            $transaccionProductoE = new \entidad\TransaccionProducto();
            $transaccionProductoE->setTransaccion($transaccionE);
            $transaccionProductoE->setIdProducto($dataProducto["idProducto"]);
            $transaccionProductoE->setCantidad($informacionProducto["cantidad"]);
            $transaccionProductoE->setValorUnitarioEntrada($dataProducto["valorEntrada"]);
            $transaccionProductoE->setValorUnitarioSalida($dataProducto["valorSalida"]);
            $transaccionProductoE->setIdBodega($dataProducto["idBodega"]);
            $transaccionProductoE->setSecuencia($secuencia);
            $transaccionProductoE->setNota($informacionProducto["nota"]);
            $transaccionProductoE->setSerial($dataProducto["serial"]);
            $transaccionProductoE->setSerialInterno("");
            $transaccionProductoE->setValorUnitaEntraConImpue($dataProducto["valorEntraConImpue"]);
            $transaccionProductoE->setValorUnitaSalidConImpue($dataProducto["valorSalidConImpue"]);
            $transaccionProductoE->setIdUsuarioCreacion($idUsuario);
            $transaccionProductoE->setIdUsuarioModificacion($idUsuario);

            $transaccionProductoM = new \modelo\TransaccionProducto($transaccionProductoE);
            $transaccionProductoM->adicionar();
            
            $idTransaccionProducto = $transaccionProductoM->obtenerMaximo();
            
            
            /*-------------------------------------Cliente Transaccion Producto-------------------------*/
            $transaccionProductoE = new \entidad\TransaccionProducto();
            $transaccionProductoE->setIdTransaccionProducto($idTransaccionProducto);

            $clienteTransaccionProductoE = new \entidad\clienteTransaccionProducto();
            $clienteTransaccionProductoE->setCliente($clienteE);
            $clienteTransaccionProductoE->setTransaccionProducto($transaccionProductoE);
            $clienteTransaccionProductoE->setTabla($tabla);
            $clienteTransaccionProductoE->setIdTabla($idTabla);
            $clienteTransaccionProductoE->setEstado("TRUE");
            $clienteTransaccionProductoE->setIdUsuarioCreacion($idUsuario);
            $clienteTransaccionProductoE->setIdUsuarioModificacion($idUsuario);

            $clienteTransaccionProductoM = new \modelo\ClienteTransaccionProducto($clienteTransaccionProductoE);
            $clienteTransaccionProductoM->adicionar();
            /*---------------------------------------------------------------------------------------------*/

            $secuencia++;
        }
    }
} catch (Exception $e) {
    $retorno['exito'] =0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
