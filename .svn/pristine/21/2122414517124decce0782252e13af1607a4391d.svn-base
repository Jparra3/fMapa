<?php
try{
    require_once '../../Seguridad/entidad/ParametroAplicacion.php';
    require_once '../../Seguridad/modelo/ParametroAplicacion.php';
    
    $retorno = array("exito"=>1,"mensaje"=>"","valor"=>"");
    
    $parametroAplicacion = $_POST["parametroAplicacion"];
    
    $parametroAplicacionE = new \entidad\ParametroAplicacion();
    $parametroAplicacionE->setParametroAplicacion($parametroAplicacion);
    
    $parametroAplicacionM = new \modelo\ParametroAplicacion($parametroAplicacionE);
    $retornoData = $parametroAplicacionM->consultar();
    
    $retorno["valor"] = $retornoData[0]["valor"];
}  catch (Exception $e){
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $e->getMessage();
}
echo json_encode($retorno);