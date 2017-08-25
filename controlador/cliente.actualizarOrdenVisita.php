<?php
session_start();
require_once '../entidad/Cliente.php';
require_once '../modelo/Cliente.php';

$retorno = array ('exito'=>1, 'mensaje'=> 'La información se guardó correctamente.');

try {
    $arrOrdenCliente = $_POST['arrOrdenCliente'];
    $idUsuario = $_SESSION['idUsuario'];
    
    $contador = 0;
    while ($contador < count($arrOrdenCliente)) {
        
        $clienteE = new \entidad\Cliente();
        $clienteE->setIdCliente($arrOrdenCliente[$contador]["idCliente"]);
        $clienteE->setOrden($arrOrdenCliente[$contador]["orden"]);
        $clienteE->setIdUsuarioModificacion($idUsuario);
        
        $clienteM = new \modelo\Cliente($clienteE);
        $clienteM->actualizarOrden();
        
        $contador++;
    }
    
} catch (Exception $e) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
