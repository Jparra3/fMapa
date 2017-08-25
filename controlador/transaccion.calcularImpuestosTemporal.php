<?php
$retorno = array('exito'=>1, 'mensaje'=>'', 'data'=>null, 'numeroRegistros'=>0);

try {
    $data = json_decode($_POST['dataProductos']);
    
    //-----------------------------------SE AGRUPAN (TOTALIZAN) LOS IMPUESTOS QUE SEAN IGUALES----------------------------------------
    $arrImpuestos = array();
    $contador = 0;
    while($contador < count($data)){        
        $data[$contador]->valorUnitarioEntrada =  str_replace ('.', '', $data[$contador]->valorUnitarioEntrada);
        $data[$contador]->valorUnitarioEntrada =  str_replace (',', '.', $data[$contador]->valorUnitarioEntrada);
        
        $arrImpuestos[$data[$contador]->valorImpuesto]['impuesto'] = $data[$contador]->impuesto;
        $arrImpuestos[$data[$contador]->valorImpuesto]['tipoImpuesto'] = $data[$contador]->idTipoImpuesto;
        
        if($data[$contador]->idTipoImpuesto == "1"){
            $arrImpuestos[$data[$contador]->valorImpuesto]['valorImpuesto'] += ($data[$contador]->valorImpuesto * $data[$contador]->cantidad);
        }else if($data[$contador]->idTipoImpuesto == "2"){
            $arrImpuestos[$data[$contador]->valorImpuesto]['valorImpuesto'] = $data[$contador]->valorImpuesto;
        }        
        
        $arrImpuestos[$data[$contador]->valorImpuesto]['totalConImpuesto'] += ($data[$contador]->valorUnitarioEntrada * $data[$contador]->cantidad);
        $contador++;
    }
    
    //--------------------------------------SE CALCULAN LOS IMPUESTOS CONSOLIDADOS----------------------------------------------------
    $contador = 0;
    $arrDataImpuestos = array();
    foreach ($arrImpuestos as $value) {
        
        if($value["tipoImpuesto"] == "1"){//FIJO
            $base = $value["totalConImpuesto"] - $value["valorImpuesto"];
            $impuesto = $value["valorImpuesto"];
        }else if($value["tipoImpuesto"] == "2"){//PORCENTUAL
            $base = $value["totalConImpuesto"] / (($value["valorImpuesto"] / 100) + 1);
            $impuesto = $value["totalConImpuesto"] - $base;
        }
        
        $arrDataImpuestos[$contador]["impuesto"] = $value["impuesto"];
        $arrDataImpuestos[$contador]["base"] = round($base);
        $arrDataImpuestos[$contador]["valor"] = round($impuesto);
        
        $contador++;
    }
    
    $retorno["data"] = $arrDataImpuestos;
    $retorno["numeroRegistros"] = count($arrDataImpuestos);
    
} catch (Exception $e) {
    $retorno['exito'] =0;
    $retorno['mensaje'] = $e->getMessage();
}
echo json_encode($retorno);
?>
