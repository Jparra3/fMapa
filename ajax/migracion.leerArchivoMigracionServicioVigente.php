<?php

session_start();
$retorno = array("exito" => 1, "mensaje" => "", "data" => null, "tTable" => null);
try {

    $ruta = $_POST["ruta"];
    $contadorLineaArchivo = 0;
    $contador = 0;
    foreach (file($ruta) as $fila) {
        $contenido = explode("\t", $fila);
        if ($contadorLineaArchivo >= 1) {//No se fija en el encabezado del archivo excel
            if($contenido[0] != "" && $contenido[0] != null && $contenido[0] != "null") {
                //Obtenemos los valores
                $arrServicio = explode("-", $contenido[0]);
                $arrDataMigracion[$contador]["servicio"] = utf8_encode($arrServicio[1]);
                $arrDataMigracion[$contador]["codigoServicio"] = utf8_encode($arrServicio[0]);
                $arrDataMigracion[$contador]["direccionSucursal"] = utf8_encode($arrServicio[2]);
                
                $arrCliente = explode("-", $contenido[1]);
                $arrDataMigracion[$contador]["nit"] = utf8_encode($arrCliente[0]);
                $arrDataMigracion[$contador]["cliente"] = utf8_encode($arrCliente[1]);
                
                $arrDataMigracion[$contador]["fechaInicio"] = utf8_encode(str_replace("/", "-",$contenido[2]));
                $arrDataMigracion[$contador]["fechaFin"] = utf8_encode(str_replace("/", "-", $contenido[3]));
                $arrDataMigracion[$contador]["valor"] = utf8_encode(str_replace(".", "", $contenido[4]));  
                

                $contador++;
            }
        } else {
            $arrEncabezadoTabla[0] = utf8_encode($contenido[0]);
            $arrEncabezadoTabla[1] = utf8_encode($contenido[1]);
            $arrEncabezadoTabla[2] = utf8_encode($contenido[2]);
            $arrEncabezadoTabla[3] = utf8_encode($contenido[3]);
            $arrEncabezadoTabla[4] = utf8_encode($contenido[4]);
        }
        $contadorLineaArchivo++;
    }

    $retorno['tTable'] = $arrEncabezadoTabla;
    $retorno["data"] = $arrDataMigracion;
} catch (Exception $ex) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $ex->getMessage();
}

echo json_encode($retorno);
?>