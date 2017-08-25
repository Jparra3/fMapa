<?php
$retorno = array('exito'=>1, 'mensaje'=>'La imagen se subió correctamente', 'ruta'=>'');
try {
    foreach ($_FILES as $key){
        $nombre = $key['name'];
        $temporal = $key['tmp_name'];
        $tamano = $key['size'];
        $extension = substr(strrchr($nombre, "."), 1);
        $ruta = '../../archivos/PublicServices/productos/temporal.'.$extension;
        $retorno['ruta'] = $ruta;
        if($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png' || $extension == 'gif' || $extension == 'JPG' || $extension == 'JPEG' || $extension == 'PNG' || $extension == 'GIF'){
            if(!move_uploaded_file($temporal, $ruta)){
                $retorno['exito'] = 0;
                $retorno['mensaje'] = 'Ocurrió un error subiendo el archivo.';
            }
        }else{
            $retorno['exito'] = 0;
            $retorno['mensaje'] = 'Extensión inválida';
        }
    }
} catch (Exception $exc) {
    $retorno['exito'] = 0;
    $retorno['mensaje'] = $exc->getMessage();
}
echo json_encode($retorno);
?>