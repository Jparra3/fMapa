<?php
session_start();
require_once 'Conexion.php';
//Establezco la zona horaria por defecto para todas las funciones de fecha y hora utilizadas en el script
date_default_timezone_set('America/Bogota');
$conexion = new Conexion();
$retorno = array();
$_SESSION['autenticado'] = 0;
$usuarioEvaluar = $_POST['Usuario'];
$usuario = strtolower($usuarioEvaluar);
$contrasena = md5($_POST['Contrasena']);
//Obtengo la fecha actual
$fechaActual = date("Y-m-d");
//llamo el procedimiento almacenado que me consulta el usuario
$sentenciaSql = "
                SELECT
                   u.*
                    ,to_char(u.fecha_activacion, 'yyyy-mm-dd') AS fecha_activacion
                    ,to_char(u.fecha_expiracion, 'yyyy-mm-dd') AS fecha_expiracion
                    , p.primer_nombre AS nombre
                    , p.primer_apellido AS apellidos
                    , p.foto
                    , u.id_empresa
                FROM
                    seguridad.usuario AS u
                    INNER JOIN general.persona AS p ON p.id_persona = u.id_persona
                WHERE
                    usuario =  '$usuario';
                ";
$conexion->ejecutar($sentenciaSql);

//Valido si el usuario está correcto
if($conexion->obtenerNumeroRegistros()  == 1){
    $fila = $conexion->obtenerObjeto();

    //Valido si la contraseña está correcta
    if($fila->contrasenia == $contrasena){
        //Valido si el usuario está activo
        if($fila->estado == true){
            if ($fila->fecha_activacion <= $fechaActual) {
                //Valido que el usuario no haya expirado
                if ($fechaActual <= $fila->fecha_expiracion) {
                    $sentenciaSql = "
                                    SELECT
                                       id_tercero
                                    FROM
                                        contabilidad.empresa AS e
                                    WHERE
                                        id_empresa =  $fila->id_empresa;
                                    ";
                    $conexion->ejecutar($sentenciaSql);

                    $filaDos = $conexion->obtenerObjeto();
                    
                    $_SESSION['idEmpresa'] = $fila->id_empresa;
                    $_SESSION['idTercero'] = $filaDos->id_tercero;
                    $_SESSION['autenticado'] = 1;
                    $_SESSION['nombre'] = $fila->nombre;
                    $_SESSION['idUsuario'] = $fila->id_usuario;
                    $_SESSION['foto'] = $fila->foto;
                    $_SESSION['apellido'] = $fila->apellidos;
                    $_SESSION['usuario'] = $usuario;
                    $retorno['exito'] = 1;
                }else{
                    $retorno['exito'] = 0;
                    $retorno['mensaje'] = 'El usuario ha expirado';
                }
            }else{
                $retorno['exito'] = 0;
                $retorno['mensaje'] = 'El usuario se encuentra inactivo';
            }
        }else{
            $retorno['exito'] = 0;
            $retorno['mensaje'] = 'El usuario se encuentra inactivo';
        }
    }else{
        $retorno['exito'] = 0;
        $retorno['mensaje'] = 'Usuario y/o contraseña inválida.';
    }
}else{
    $retorno['exito'] = 0;
    $retorno['mensaje'] = 'Usuario y/o contraseña inválida';
}
echo json_encode($retorno);
?>