<?php
session_start();
//require_once '../entidad/ArchivoMigracion.php';
$retorno = array('exito' => 1, 'mensaje' => 'El archivo se subio correctamente', 'ruta' => '', "nombreArchivo"=>null);
try {
   
    foreach ($_FILES as $key) {
        $nombre = $key['name'];
        $temporal = $key['tmp_name'];
        $tamano = $key['size'];
        $extension = substr(strrchr($nombre, "."), 1);
        if($extension != "TXT" && $extension != "txt"){
            throw new Exception('Extención de archivo inválida.');
        }
        $ruta = '../../archivos/PublicServices/archivo_migracion/';
        if (!file_exists($ruta)) {
            mkdir($ruta, 0777, true);
        }
        $ruta .= 'migracion_' . $_SESSION['idUsuario'] .'.' . $extension;
        $retorno['ruta'] = $ruta;
        $retorno['extension'] = $extension;
        $retorno['nombreArchivo'] = $nombre;
        if ($extension == 'txt' || $extension == 'TXT') {
            if (!move_uploaded_file($temporal, $ruta)) {
                $retorno['exito'] = 0;
                $retorno['mensaje'] = 'Ocurrio un error subiendo el archivo.';
            }
        } else {
            $retorno['exito'] = 0;
            $retorno['mensaje'] = 'Extensión inválida';
        }
    }
    
    if($retorno['ruta'] == ''){
        throw new Exception('Extención de archivo inválida o archivo incorrecto.');
    }
    
} catch (Exception $exc) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $exc->getMessage();
}
echo json_encode($retorno);
?>