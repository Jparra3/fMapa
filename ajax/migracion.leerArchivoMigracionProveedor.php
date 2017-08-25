<?php

require_once '../comunes/funciones.php';
session_start();
$retorno = array("exito" => 1, "mensaje" => "", "data" => null, "tTable" => null, "error" => null);
try {

    $ruta = $_POST["ruta"];
    $contadorLineaArchivo = 0;
    $contador = 0;
    foreach (file($ruta) as $fila) {
        $contenido = explode("\t", $fila);
        if ($contadorLineaArchivo >= 1) {//No se fija en el encabezado del archivo excel
            if ($contenido[0] != "" && $contenido[0] != null && $contenido[0] != "null") {
                //Obtenemos los valores
                $arrDataMigracion[$contador]["tipoDocumento"] = utf8_encode($contenido[0]);
                $arrDataMigracion[$contador]["nit"] = utf8_encode($contenido[1]);
                $arrDataMigracion[$contador]["digito"] = utf8_encode(trim($contenido[2]));
                $arrDataMigracion[$contador]["primerNombre"] = utf8_encode(trim($contenido[3]));
                $arrDataMigracion[$contador]["segundoNombre"] = utf8_encode(trim($contenido[4]));
                $arrDataMigracion[$contador]["primerApellido"] = utf8_encode(trim($contenido[5]));
                $arrDataMigracion[$contador]["segundoApellido"] = utf8_encode(trim($contenido[6]));


                $tercero = utf8_encode(trim($contenido[3])) . " " . utf8_encode(trim($contenido[4])) . " " . utf8_encode(trim($contenido[5])) . " " . utf8_encode(trim($contenido[6]));
                //$tercero = eregi_replace("[\n|\r|\n\r|\t||\x0B]", "",$tercero);
                $tercero = trim($tercero);
                $tercero = limpiar_espacios($tercero);
                $tercero = str_replace("'", '', $tercero);

                $arrDataMigracion[$contador]["tercero"] = $tercero;


                $direccion = utf8_encode($contenido[7]);
                $direccion = str_replace("'", '', $direccion);
                //$direccion = eregi_replace("[\n|\r|\n\r|\t||\x0B]", "",$direccion);
                $direccion = limpiar_espacios($direccion);

                $arrDataMigracion[$contador]["direccion"] = $direccion;
                $arrDataMigracion[$contador]["telefono"] = utf8_encode(trim($contenido[8]));
                $arrDataMigracion[$contador]["celular"] = utf8_encode(trim($contenido[9]));
                $arrDataMigracion[$contador]["email"] = utf8_encode(trim($contenido[10]));
                $arrDataMigracion[$contador]["municipio"] = utf8_encode(trim($contenido[11]));
                //$arrDataMigracion[$contador]["zona"] = utf8_encode(trim($contenido[11]));

                //Se obtiene el código y el nombre del producto
                $arrZona = explode("-", $contenido[13]);
                $arrDataMigracion[$contador]["zona"] = utf8_encode($arrZona[0]);
                $arrDataMigracion[$contador]["departamento"] = utf8_encode($arrZona[1]);

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
            $arrEncabezadoTabla[9] = utf8_encode($contenido[9]);
            $arrEncabezadoTabla[10] = utf8_encode($contenido[10]);
            $arrEncabezadoTabla[11] = utf8_encode($contenido[11]);
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