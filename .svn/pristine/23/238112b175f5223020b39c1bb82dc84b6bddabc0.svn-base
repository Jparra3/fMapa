<?php
session_start();
require_once '../entidad/Cierre.php';
require_once '../modelo/Cierre.php';

$retorno = array('exito'=>1, 'mensaje'=>'El mes se obtuvo correctamente.', 'mes'=>"", "anio"=>"" ,"nombreMes"=>"", "mesAbierto"=>"");

try {
    $mesActual = date("m");    
    
    $cierreM = new \modelo\Cierre(new \entidad\Cierre());
    $arrUltimoCierre = $cierreM->obtenerUltimoCierre();//Obtengo las fechas del ultimo cierre
    $fechaInicialUltimoCierre = $arrUltimoCierre["fechaInicial"];
    $nuevafechaCierre = strtotime ('+1 month', strtotime ( $fechaInicialUltimoCierre ) ) ;//Le aumento un mes
    $mes = date("m", $nuevafechaCierre);//Obtengo el mes
    $anio = date("Y", $nuevafechaCierre);//Obtengo el año
    $nombreMes = strtoupper($cierreM->obtenerNombreMes($mes));//Obtengo el nombre del mes
    
    $retorno["mesAbierto"] = date("Y-m-d", $nuevafechaCierre);
    
    if($mesActual == $mes){
        throw new Exception("NO ES POSIBLE REALIZAR EL CIERRE CONTABLE YA QUE ".$nombreMes." NO HA TERMINADO AÚN.");
    }
    
    $retorno["mes"] = $mes;
    $retorno["anio"] = $anio;
    $retorno["nombreMes"] = $nombreMes;
    
} catch (Exception $e) {
    $retorno['exito'] =0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
