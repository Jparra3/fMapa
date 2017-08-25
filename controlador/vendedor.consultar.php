<?php
require_once '../../Seguridad/entidad/Persona.php';
require_once '../entidad/Vendedor.php';
require_once '../modelo/Vendedor.php';

$retorno = array ('exito'=>1, 'mensaje'=> '', 'data'=>null, 'numeroRegistros'=>0);

try {
    $idVendedor = $_POST['idVendedor'];
    $idZona = $_POST['idZona'];
    $numeroIdentificacion = $_POST['numeroIdentificacion'];
    $idPersona = $_POST['idPersona'];
    
    $personaE = new \entidad\Persona();
    $personaE->setNumeroIdentificacion($numeroIdentificacion);
    $personaE->setIdPersona($idPersona);
    
    $vendedorE = new \entidad\Vendedor();
    $vendedorE->setPersona($personaE);
    $vendedorE->setIdZona($idZona);
    $vendedorE->setIdVendedor($idVendedor);
    
    $vendedorM = new \modelo\Vendedor($vendedorE);
    $retorno['data'] = $vendedorM->consultar();
    $retorno['numeroRegistros']= $vendedorM->conexion->obtenerNumeroRegistros();
} catch (Exception $e) {
    $retorno['exito']=0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
