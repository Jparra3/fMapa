<?php
namespace modelo;
session_start();
require_once($_SESSION['ruta'].'entorno/Conexion.php');
class Tecnico {
    public $conexion;
    private $condicion;
    private $whereAnd;
    
    private $idTecnico;
    private $empleado;
    private $estado;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    
    public function __construct(\entidad\Tecnico $tecnico) {
        $this->idTecnico = $tecnico->getIdTecnico();
        $this->empleado = $tecnico->getEmpleado() != "" ? $tecnico->getEmpleado() : new \entidad\Empleado();
        $this->estado = $tecnico->getEstado();
        $this->idUsuarioCreacion = $tecnico->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $tecnico->getIdUsuarioModificacion();
        
        $this->conexion = new \Conexion();
    }
    
    public function adicionar(){
        $sentenciaSql = "
            INSERT INTO publics_services.tecnico
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
    }

     public function modificar(){
            $sentenciaSql = "
                UPDATE
                    publics_services.tecnico
                SET
                      estado = $this->estado
                    , id_usuario_modificacion = $this->idUsuarioModificacion
                    , fecha_modificacion = NOW()    
                WHERE
                    id_tecnico = $this->idTecnico
                    
            ";
            $this->conexion->ejecutar($sentenciaSql);
        }
    
    public function consultar() {
        $this->obtenerCondicion();
        $sentenciaSql = "
                        SELECT
                              t.id_tecnico
                            , CONCAT_WS(' ', p.primer_nombre, p.segundo_nombre, p.primer_apellido, p.segundo_apellido) as tecnico
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
                            , t.estado
                        FROM
                            nomina.empleado AS e
                            INNER JOIN general.persona AS p ON p.id_persona = e.id_persona
                            INNER JOIN publics_services.tecnico AS t ON t.id_empleado = e.id_empleado
                            INNER JOIN nomina.arl AS arl ON arl.id_arl = e.id_arl
                            INNER JOIN nomina.eps AS eps ON eps.id_eps = e.id_eps
                            INNER JOIN general.cargo AS c ON c.id_cargo = e.id_cargo
                            $this->condicion
                            ".Tecnico::obtenerTecnicos('t.id_tecnico', $this->whereAnd, $this->conexion)."    
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["idTecnico"] = $fila->id_tecnico;
            $retorno[$contador]["tecnico"] = $fila->tecnico;
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
    
    public static function obtenerTecnicos($aliasTabla, $whereAnd, $conexion){
        
        
        $sentenciaSql = "
            SELECT
                t.id_tecnico
            FROM
                publics_services.tecnico AS t
                INNER JOIN nomina.empleado AS e ON e.id_empleado = t.id_empleado
                INNER JOIN general.persona AS p ON p.id_persona = e.id_persona
                INNER JOIN seguridad.usuario AS u ON u.id_persona = p.id_persona
            WHERE 
                u.id_usuario = " . $_SESSION['idUsuario'] . " 
        ";
        
        $conexion->ejecutar($sentenciaSql);
        
        $idTecnicos = array();        
        while($fila = $conexion->obtenerObjeto()){
            array_push($idTecnicos, $fila->id_tecnico);
        }
        
        //SE UNE POR UNA COMA        
        $retorno['conPermiso'] = implode(',', $idTecnicos); 
        
        if($retorno['conPermiso'] != ''){          
            //LOS TECNICOS A LOS CUALES TIENE PERMISO
            $condicionSql .= $whereAnd." $aliasTabla  IN(" . $retorno['conPermiso'] . ")";
                     
        }else{//SIN PERMISO
            $condicionSql = "";	
        }        
        return $condicionSql;
    }
    
    public function buscarTecnico($tecnico) {
        $sentenciaSql = "SELECT
                              t.id_tecnico
                            , CONCAT_WS(' ', p.primer_nombre, p.segundo_nombre, p.primer_apellido, p.segundo_apellido) as nombre_completo
                            , p.numero_identificacion
                         FROM
                            publics_services.tecnico t
                            INNER JOIN nomina.empleado AS e ON e.id_empleado = t.id_empleado
                            INNER JOIN general.persona p ON p.id_persona = e.id_persona
                         WHERE
                            CONCAT_WS(' ', p.primer_nombre, p.segundo_nombre, p.primer_apellido, p.segundo_apellido) ILIKE '%$tecnico%'
                               ";
       $this->conexion->ejecutar($sentenciaSql);
       while($fila = $this->conexion->obtenerObjeto()){
           $datos[] = array("value" => $fila->nombre_completo,
                           "idTecnico" => $fila->id_tecnico,
                           "numeroIdentificacion" => $fila->numero_identificacion
                           );
       }
       return $datos;
    }
    
    function obtenerCondicion(){
        $this->condicion = "";
        $this->whereAnd = " WHERE ";
        
        if($this->idTecnico != null && $this->idTecnico != "null" && $this->idTecnico != ""){
            $this->condicion = $this->condicion.$this->whereAnd." t.id_tecnico = ".$this->idTecnico;
            $this->whereAnd = " AND ";
        }
        
        if($this->estado != null && $this->estado != "null" && $this->estado != ""){
            $this->condicion = $this->condicion.$this->whereAnd." t.estado = ".$this->estado;
            $this->whereAnd = " AND ";
        }
    }
}
