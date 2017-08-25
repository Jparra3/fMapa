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
                $arrDataMigracion[$contador]["producto"] = utf8_encode($contenido[0]);
                $arrDataMigracion[$contador]["codigo"] = utf8_encode($contenido[1]);

                if ($contenido[2] != "" && $contenido[2] != "null" && $contenido[2] != null) {
                    $arrDataMigracion[$contador]["tangible"] = "true";
                } else {
                    $arrDataMigracion[$contador]["tangible"] = "false";
                }

                $arrDataMigracion[$contador]["unidadMedida"] = utf8_encode($contenido[3]);
                $arrDataMigracion[$contador]["lineaProducto"] = utf8_encode($contenido[4]);
                if ($contenido[5] != "" && $contenido[5] != "null" && $contenido[5] != null) {
                    $arrDataMigracion[$contador]["manejaInventario"] = "true";
                } else {
                    $arrDataMigracion[$contador]["manejaInventario"] = "false";
                }
                $arrDataMigracion[$contador]["valorImpuesto"] = str_replace(".", "", utf8_encode($contenido[6]));
                $arrDataMigracion[$contador]["valorCompraConIva"] = str_replace(".", "", utf8_encode($contenido[7]));
                $arrDataMigracion[$contador]["valorSalidaConIva"] = str_replace(".", "", utf8_encode($contenido[8]));
                $arrDataMigracion[$contador]["serial"] = utf8_encode($contenido[10]);
                $arrDataMigracion[$contador]["cantidad"] = utf8_encode($contenido[12]);

                $contador++;
            }
        } else {
            $arrEncabezadoTabla[0] = utf8_encode($contenido[0]);
            $arrEncabezadoTabla[1] = utf8_encode($contenido[1]);
            $arrEncabezadoTabla[2] = utf8_encode($contenido[2]);
            $arrEncabezadoTabla[3] = utf8_encode($contenido[3]);
            $arrEncabezadoTabla[4] = utf8_encode($contenido[4]);
            $arrEncabezadoTabla[5] = utf8_encode($contenido[5]);
            $arrEncabezadoTabla[6] = utf8_encode($contenido[6]);
            $arrEncabezadoTabla[7] = utf8_encode($contenido[7]);
            $arrEncabezadoTabla[8] = utf8_encode($contenido[8]);
            $arrEncabezadoTabla[9] = utf8_encode($contenido[10]);
            $arrEncabezadoTabla[10] = utf8_encode($contenido[12]);
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