<?php
require_once '../entidad/Caja.php';
require_once '../modelo/Caja.php';
require_once '../entidad/CajaFormatoImpresion.php';
$retorno = array("exito"=>1, "mensaje"=>"Se adicionó la información correctamente.", "idCaja"=>null);
try{
    //POST
    $prefijo = $_POST['prefijo'];
    $numeroMaximo = $_POST['numeroMaximo'];
    $numeroMinimo = $_POST['numeroMinimo'];
    $ultimoNumeroUtilizado = $_POST['ultimoNumeroUtilizado'];
    $direccionIp = utf8_decode($_POST['direccionIp']);
    $nombrePc = $_POST['nombrePc'];
    $numeroResolucion = $_POST['numeroResolucion'];
    $fechaExpedicionResolucion = $_POST['fechaExpedicionResolucion'];
    $idTipoDocumento = $_POST['idTipoDocumento'];
    $idBodega = $_POST['idBodega'];
    $estado = $_POST['estado'];
    $serialPc = $_POST['serialPc'];
    $macPc = $_POST['macPc'];
    $arrFormatoImpresion = json_decode($_POST['arrFormatoImpresion']);
    
    //SESSION
    $idUsuario = $_SESSION['idUsuario'];
    
    //Obtener numero de caja
    $cajaE = new \entidad\Caja();
    $cajaM = new \modelo\Caja($cajaE);
    $numeroCaja = $cajaM->obtenerNumeroCaja();
    
    $cajaE = new \entidad\Caja();
    $cajaE ->setPrefijo($prefijo);
    $cajaE ->setNumeroMaximo($numeroMaximo);
    $cajaE ->setNumeroMinimoSerial($numeroMinimo);
    $cajaE ->setUltimoNumeroUtilizado($ultimoNumeroUtilizado);
    $cajaE ->setDireccionIp($direccionIp);
    $cajaE ->setNombrePc($nombrePc);
    $cajaE ->setNumeroResolucion($numeroResolucion);
    $cajaE ->setFechaExpedicionResolucion($fechaExpedicionResolucion);
    $cajaE ->setIdTipoDocumento($idTipoDocumento);
    $cajaE ->setIdBodega($idBodega);
    $cajaE ->setEstado($estado);
    $cajaE ->setIdUsuarioCreacion($idUsuario);
    $cajaE ->setIdUsuarioModificacion($idUsuario);
    $cajaE ->setSerialPc($serialPc);
    $cajaE ->setMacPc($macPc);
    $cajaE ->setNumeroCaja($numeroCaja);
    
    $cajaM = new \modelo\Caja($cajaE);
    $retorno['idCaja'] = $cajaM->adicionar();
    
    $cajaE->setIdCaja($retorno['idCaja']);
    //FORMATOS DE IMPRESION 
    for($i = 0; $i < count($arrFormatoImpresion); $i++){
        $formatoImpresionE = new \entidad\FormatoImpresion();
        $formatoImpresionE ->setIdFormatoImpresion($arrFormatoImpresion[$i]->idFormatoImpresion);
        
        $cajaFormatoImpresionE = new \entidad\CajaFormatoImpresion();
        $cajaFormatoImpresionE ->setCaja($cajaE);
        $cajaFormatoImpresionE ->setFormatoImpresion($formatoImpresionE);
        $cajaFormatoImpresionE ->setPrincipal($arrFormatoImpresion[$i]->principal);
        $cajaFormatoImpresionE ->setEstado($arrFormatoImpresion[$i]->estado);
        $cajaFormatoImpresionE ->setIdUsuarioCreacion($idUsuario);
        $cajaFormatoImpresionE ->setIdUsuarioModificacion($idUsuario);
        
        $cajaFormatoImpresionM = new \modelo\CajaFormatoImpresion($cajaFormatoImpresionE);
        $cajaFormatoImpresionM->adicionar();
    }
    
} catch (Exception $ex) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $ex->getMessage();
}
echo json_encode($retorno);
?>