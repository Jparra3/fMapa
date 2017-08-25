<?php
require_once '../comunes/funciones.php';
session_start();
$retorno = array("exito" => 1, "mensaje" => "", "data" => null, "tTable" => null, "error"=>null);
try {

    $ruta = $_POST["ruta"];
    $contadorLineaArchivo = 0;
    $contador = 0;
    foreach (file($ruta) as $fila) {
        $contenido = explode("\t", $fila);
        if ($contadorLineaArchivo >= 1) {//No se fija en el encabezado del archivo excel
            if($contenido[0] != "" && $contenido[0] != null && $contenido[0] != "null") {
                //Obtenemos los valores
                $arrDataMigracion[$contador]["nitProveedor"] = utf8_encode($contenido[0]);
                $arrDataMigracion[$contador]["proveedor"] = utf8_encode($contenido[1]);
                
                //Se obtiene el código y el nombre del producto
                $arrProducto = explode("-", $contenido[2]);
                $arrDataMigracion[$contador]["codigo"] = utf8_encode($arrProducto[0]);
                $arrDataMigracion[$contador]["producto"] = utf8_encode($arrProducto[1]);
                $arrDataMigracion[$contador]["cantidad"] = utf8_encode($contenido[3]);
                $arrDataMigracion[$contador]["bodega"] = utf8_encode($contenido[4]);
                $arrDataMigracion[$contador]["serial"] = limpiar_espacios(str_replace("'","",trim(utf8_encode($contenido[5]))));
                if($arrDataMigracion[$contador]["cantidad"]>1 && $arrDataMigracion[$contador]["serial"] != "" && $arrDataMigracion[$contador]["serial"] != "null" && $arrDataMigracion[$contador]["serial"] != null){
                    $retorno['error'] .="<br>El producto ".$arrDataMigracion[$contador]["producto"]." con el código ".$arrDataMigracion[$contador]["codigo"]." está asignando el serial ".$arrDataMigracion[$contador]["serial"]." mas de una vez.";
                }
                $arrDataMigracion[$contador]["serialInterno"] = limpiar_espacios(str_replace("'","",trim(utf8_encode($contenido[6]))));

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
