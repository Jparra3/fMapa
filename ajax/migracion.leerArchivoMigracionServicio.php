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
                $arrDataMigracion[$contador]["nombreServicio"] = utf8_encode($contenido[0]);
                $arrDataMigracion[$contador]["codigoServicio"] = utf8_encode($contenido[1]);
                $arrDataMigracion[$contador]["valorServicio"] = utf8_encode(str_replace(".", "", $contenido[2]));
                $contador++;
            }
        } else {
            $arrEncabezadoTabla[0] = utf8_encode($contenido[0]);
            $arrEncabezadoTabla[1] = utf8_encode($contenido[1]);
            $arrEncabezadoTabla[2] = utf8_encode($contenido[2]);
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