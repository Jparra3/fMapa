<?php
session_start();
require_once '../entidad/Caja.php';
require_once '../modelo/Caja.php';
require_once '../entidad/TipoDocumento.php';
require_once '../modelo/TipoDocumento.php';

$retorno = array('exito'=>1, 'mensaje'=>'', 'data'=>null, 'numeroRegistros'=>0, 'tienePermisoTipoDocumento'=>true);

try {
    $codigoTipoNaturaleza = "CA"; //CAJA
    $idUsuario = $_SESSION['idUsuario'];
    $idCaja = $_POST['idCaja'];
    $direccionIp = '186.117.187.146'; //getenv('REMOTE_ADDR');
    
    $cajaE = new \entidad\Caja();
    $cajaE->setDireccionIp($direccionIp);
    $cajaE->setIdCaja($idCaja);
    
    $cajaM = new \modelo\Caja($cajaE);
    $retorno["data"] = $cajaM->consultarCajaTipoDocumento($idUsuario, $idTipoNaturaleza, $codigoTipoNaturaleza);
    $retorno['numeroRegistros'] = $cajaM->conexion->obtenerNumeroRegistros();
    if($retorno['numeroRegistros']>1){
        $retorno['exito'] =0;
        $retorno['mensaje'] = "Esta caja tiene mas de una naturaleza asociada";
        $retorno["data"] = null;
    }
    
    //--------------------VALIDO PERMISOS AL TIPO DE DOCUMENTO DE LA CAJA----------------------
    $tipoDocumentoM = new \modelo\TipoDocumento(new \entidad\TipoDocumento());
    $idTiposDocumentos = $tipoDocumentoM->obtenTipoDocumTienePermi();

    if ($idTiposDocumentos == "") {
        throw new Exception("No tiene permiso a ningún tipo de documento");
    }

    $data = $tipoDocumentoM->consultarTipoDocumento($idTiposDocumentos, $idTipoNaturaleza, $codigoTipoNaturaleza);
    $numeroRegistros = $tipoDocumentoM->conexion->obtenerNumeroRegistros();
    if ($numeroRegistros == 0) {
        $retorno['tienePermisoTipoDocumento'] = false;
        $retorno['mensaje'] = "No tiene permiso al tipo de documento CAJA";
    }
    if ($numeroRegistros > 1) {
        throw new Exception("Existen más de un tipo de documento con naturaleza: CAJA");
    }
    
} catch (Exception $e) {
    $retorno['exito'] =0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
