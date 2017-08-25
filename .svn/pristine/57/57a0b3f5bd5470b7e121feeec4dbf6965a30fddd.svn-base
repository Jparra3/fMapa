<?php
namespace modelo;
session_start();
require_once($_SESSION['ruta'].'entorno/Conexion.php');
class UsuarioTipoDocumento {
    public $conexion;
    
    private $idUsuarioTipoDocumento;
    private $idUsuario;
    private $idTipoDocumento;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    
    public function __construct(\entidad\UsuarioTipoDocumento $usuarioTipoDocumento) {
        $this->idUsuarioTipoDocumento = $usuarioTipoDocumento->getIdUsuarioTipoDocumento();
        $this->idUsuario = $usuarioTipoDocumento->getIdUsuario();
        $this->idTipoDocumento = $usuarioTipoDocumento->getIdTipoDocumento();
        $this->idUsuarioCreacion = $usuarioTipoDocumento->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $usuarioTipoDocumento->getIdUsuarioModificacion();
        $this->fechaCreacion = $usuarioTipoDocumento->getFechaCreacion();
        $this->fechaModificacion = $usuarioTipoDocumento->getFechaModificacion();
        
        $this->conexion = new \Conexion();
    }
    public function adicionar() {
        $sentenciaSql = "
                        INSERT INTO
                            contabilidad.usuario_tipo_documento
                        (
                            id_usuario
                            , id_tipo_documento
                            , id_usuario_creacion
                            , id_usuario_modificacion
                            , fecha_creacion
                            , fecha_modificacion
                        )
                        VALUES
                        (
                            $this->idUsuario
                            , $this->idTipoDocumento
                            , $this->idUsuarioCreacion
                            , $this->idUsuarioModificacion
                            , NOW()
                            , NOW()
                        )
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    public function consultar() {
        $sentenciaSql = "
                        SELECT
                            utd.id_usuario_tipo_documento
                            , utd.id_usuario
                            , CONCAT_WS(' - ', u.usuario, p.persona) AS usuario
                            , utd.id_tipo_documento
                            , td.tipo_documento
                            , n.naturaleza
                            , o.oficina
                        FROM
                            contabilidad.usuario_tipo_documento AS utd
                            INNER JOIN seguridad.usuario AS u ON u.id_usuario = utd.id_usuario
                            INNER JOIN general.persona AS p ON p.id_persona = u.id_persona
                            INNER JOIN contabilidad.tipo_documento AS td ON td.id_tipo_documento = utd.id_tipo_documento
                            INNER JOIN contabilidad.naturaleza AS n ON n.id_naturaleza = td.id_naturaleza
                            INNER JOIN contabilidad.oficina AS o ON o.id_oficina = td.id_oficina
                        WHERE
                            utd.id_usuario = ".$this->idUsuario;
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["idUsuarioTipoDocumento"] = $fila->id_usuario_tipo_documento;
            $retorno[$contador]["idUsuario"] = $fila->id_usuario;
            $retorno[$contador]["usuario"] = $fila->usuario;
            $retorno[$contador]["idTipoDocumento"] = $fila->id_tipo_documento;
            $retorno[$contador]["tipoDocumento"] = $fila->tipo_documento;
            $retorno[$contador]["naturaleza"] = $fila->naturaleza;
            $retorno[$contador]["oficina"] = $fila->oficina;
            $contador++;
        }
        return $retorno;
    }
    public function eliminar() {
        $sentenciaSql = "
                        DELETE FROM
                            contabilidad.usuario_tipo_documento
                        WHERE
                            id_usuario = $this->idUsuario
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    
    public function consultarUsuarios() {
        $sentenciaSql = "
                        SELECT
                            u.id_usuario
                            , u.usuario
                            , CONCAT_WS(' ', primer_nombre, segundo_nombre, primer_apellido, segundo_apellido) AS nombre_completo
                        FROM
                            seguridad.usuario AS u
                            INNER JOIN general.persona AS p ON p.id_persona = u.id_persona
                            LEFT OUTER JOIN contabilidad.empresa AS e ON e.id_empresa = u.id_empresa
                        WHERE
                            u.estado = TRUE
                            AND e.id_empresa = ".$_SESSION["idEmpresa"]."
                            AND u.id_usuario NOT IN (
                                                    SELECT
                                                        id_usuario
                                                    FROM
                                                        contabilidad.usuario_tipo_documento
                                                    )";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
	while ($fila =  $this->conexion->obtenerObjeto()) {
            $retorno[$contador]['idUsuario'] = $fila->id_usuario;
            $retorno[$contador]['usuario'] =$fila->usuario;
            $retorno[$contador]['nombreCompleto'] =$fila->nombre_completo;
            $contador++;
	}
	return $retorno;
    }
    
    //Solo para consultar el usuario
    function consultarInformacionUsuario(){
        $sentenciaSql = "
            SELECT
                  u.id_usuario
                , CONCAT_WS(' - ', u.usuario, p.persona) AS usuario
            FROM
                seguridad.usuario AS u
                INNER JOIN general.persona AS p ON p.id_persona = u.id_persona
            WHERE
              u.id_usuario = $this->idUsuario
        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["idUsuario"] = $fila->id_usuario;
            $retorno[$contador]["usuario"] = $fila->usuario;
            $contador++;
        }
        return $retorno;
    }
}