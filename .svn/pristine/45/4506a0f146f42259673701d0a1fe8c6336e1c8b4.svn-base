<?php
namespace modelo;
session_start();
require_once($_SESSION['ruta'].'entorno/Conexion.php');
class Ordenador {
    public $conexion;
    private $condicion;
    private $whereAnd;
    
    private $idOrdenador;
    private $empleado;
    private $estado;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    
    public function __construct(\entidad\Ordenador $ordenador) {
        
        $this->idOrdenador = $ordenador->getIdOrdenador();
        $this->empleado = $ordenador->getEmpleado() != "" ? $ordenador->getEmpleado() : new \entidad\Empleado();
        $this->estado = $ordenador->getEstado();
        $this->idUsuarioCreacion = $ordenador->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $ordenador->getIdUsuarioModificacion();
        
        
        $this->conexion = new \Conexion();
    }
    
    public function adicionar() {
        $sentenciaSql = "
            INSERT INTO publics_services.ordenador
                (
                      id_empleado
                    , estado
                    , id_usuario_creacion
                    , id_usuario_modificacion
                    , fecha_creacion
                    , fecha_modificacion
                )
            VALUES
                (
                      ".$this->empleado->getIdEmpleado()."
                    , $this->estado
                    , $this->idUsuarioCreacion
                    , $this->idUsuarioModificacion
                    , NOW()
                    , NOW()
                )
        ";
        $this->conexion->ejecutar($sentenciaSql);
        return $this->obtenerMaximo();
    }
    
    public function obtenerMaximo(){
        $sentenciaSql = "
            SELECT
                MAX(id_ordenador) AS id_ordenador
            FROM
                publics_services.ordenador
        ";
        $this->conexion->ejecutar($sentenciaSql);
        $fila = $this->conexion->obtenerObjeto();
        return $fila->id_ordenador;
    }
    
    public function modificar(){
        $sentenciaSql = "
            UPDATE
                  publics_services.ordenador
            SET
                  estado = $this->estado
                , id_usuario_modificacion = $this->idUsuarioModificacion
                , fecha_modificacion = NOW()    
            WHERE
                id_ordenador = $this->idOrdenador    
        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    
    public function consultar() {
        $this->obtenerCondicion();
        $sentenciaSql = "
                        SELECT
                              o.id_ordenador
                            , CONCAT_WS(' ', p.primer_nombre, p.segundo_nombre, p.primer_apellido, p.segundo_apellido) as ordenador
                            , p.numero_identificacion
                            , arl.id_arl
                            , arl.arl
                            , eps.id_eps
                            , eps.eps
                            , p.celular
                            , p.correo_electronico
                            , p.direccion
                            , p.id_persona
                            , c.id_cargo
                            , c.cargo
                            , e.id_empleado
                            , o.estado
                        FROM
                            nomina.empleado AS e
                            INNER JOIN general.persona AS p ON p.id_persona = e.id_persona
                            INNER JOIN nomina.arl AS arl ON arl.id_arl = e.id_arl
                            INNER JOIN nomina.eps AS eps ON eps.id_eps = e.id_eps
                            INNER JOIN general.cargo AS c ON c.id_cargo = e.id_cargo
                            INNER JOIN publics_services.ordenador AS o ON o.id_empleado = e.id_empleado
                            $this->condicion
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["idOrdenador"] = $fila->id_ordenador;
            $retorno[$contador]["ordenador"] = $fila->ordenador;
            $retorno[$contador]["numeroIdentificacion"] = $fila->numero_identificacion;
            $retorno[$contador]["arl"] = $fila->arl;
            $retorno[$contador]["eps"] = $fila->eps;
            $retorno[$contador]["celular"] = $fila->celular;
            $retorno[$contador]["correo"] = $fila->correo_electronico;
            $retorno[$contador]["direccion"] = $fila->direccion;
            $retorno[$contador]["idPersona"] = $fila->id_persona;
            $retorno[$contador]["cargo"] = $fila->cargo;
            $retorno[$contador]["idEmpleado"] = $fila->id_empleado;
            $retorno[$contador]["idEps"] = $fila->id_eps;
            $retorno[$contador]["idArl"] = $fila->id_arl;
            $retorno[$contador]["idCargo"] = $fila->id_cargo;
            $retorno[$contador]["estado"] = $fila->estado;
            $contador++;
        }
        return $retorno;
    }
    
    function obtenerCondicion(){
        $this->condicion = "";
        $this->whereAnd = " WHERE ";
        
        if($this->codigo != null && $this->codigo != "null" && $this->codigo != ""){
            $this->condicion = $this->condicion.$this->whereAnd." o.codigo = '".$this->codigo."'";
            $this->whereAnd = " AND ";
        }
        
        if($this->estado != null && $this->estado != "null" && $this->estado != ""){
            $this->condicion = $this->condicion.$this->whereAnd." o.estado = ".$this->estado;
            $this->whereAnd = " AND ";
        }
    }
    
    public function buscarOrdenador($ordenador) {
        $sentenciaSql = "SELECT
                              o.id_ordenador
                            , CONCAT_WS(' ', p.primer_nombre, p.segundo_nombre, p.primer_apellido, p.segundo_apellido) as nombre_completo
                            , p.numero_identificacion
                         FROM
                            publics_services.ordenador AS o
                            INNER JOIN nomina.empleado AS e ON e.id_empleado = o.id_empleado
                            INNER JOIN general.persona p ON p.id_persona = e.id_persona
                         WHERE
                            CONCAT_WS(' ', p.primer_nombre, p.segundo_nombre, p.primer_apellido, p.segundo_apellido) ILIKE '%$ordenador%'
                               ";
       $this->conexion->ejecutar($sentenciaSql);
       while($fila = $this->conexion->obtenerObjeto()){
           $datos[] = array("value" => $fila->nombre_completo,
                           "idOrdenador" => $fila->id_ordenador,
                           "numeroIdentificacion" => $fila->numero_identificacion
                           );
       }
       return $datos;
    }
    
}
