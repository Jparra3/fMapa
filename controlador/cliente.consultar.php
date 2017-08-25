<?php
require_once '../../Seguridad/entidad/Sucursal.php';
require_once '../entidad/Cliente.php';
require_once '../modelo/Cliente.php';

$retorno = array ('exito'=>1, 'mensaje'=> '', 'data'=>null, 'numeroRegistros'=>'');

try {
    $idTercero = $_POST['idTercero'];
    $idCliente = $_POST['idCliente'];
    $idZona = $_POST['idZona'];
    $idEmpresa = $_POST['idEmpresa'];
    $estado = $_POST['estado'];//Estado para las sucursales
    
    $sucursalE = new \entidad\Sucursal();
    $sucursalE->setIdTercero($idTercero);
    
    $clienteE = new \entidad\Cliente();
    $clienteE->setSucursal($sucursalE);
    $clienteE->setIdZona($idZona);
    $clienteE->setIdCliente($idCliente);
    $clienteE->setEstadoSucursal($estado);
    $clienteE->setIdEmpresa($idEmpresa);
    
    $clienteM = new \modelo\Cliente($clienteE);
    $retorno['data'] = $clienteM->consultar();
    $retorno['numeroRegistros']= $clienteM->conexion->obtenerNumeroRegistros();
} catch (Exception $e) {
    $retorno['exito']=0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
