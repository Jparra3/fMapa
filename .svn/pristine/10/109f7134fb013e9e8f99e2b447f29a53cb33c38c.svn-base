<?php
namespace modelo;
session_start();
require_once($_SESSION['ruta'].'entorno/Conexion.php');
class ClienteServicioArchivo {
    public $conexion;
    private $condicion;
    private $whereAnd;
            
    private $idClienteServicioArchivo;
    private $clienteServicio;
    private $ordenTrabajoCliente;
    private $etiqueta;
    private $rutaArchivo;
    private $observacion;
    private $estado;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    
    public function __construct(\entidad\ClienteServicioArchivo $clienteServicio) {
        $this->idClienteServicioArchivo = $clienteServicio->getIdClienteServicioArchivo();
        $this->clienteServicio = $clienteServicio->getClienteServicio() != "" ? $clienteServicio->getClienteServicio(): new \entidad\ClienteServicio();
        $this->ordenTrabajoCliente = $clienteServicio->getOrdenTrabajoCliente() != "" ? $clienteServicio->getOrdenTrabajoCliente(): new \entidad\OrdenTrabajoCliente();
        $this->etiqueta = $clienteServicio->getEtiqueta();
        $this->rutaArchivo = $clienteServicio->getRutaArchivo();
        $this->observacion = $clienteServicio->getObservacion();
        $this->estado = $clienteServicio->getEstado();
        $this->idUsuarioCreacion = $clienteServicio->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $clienteServicio->getIdUsuarioCreacion();
        
        $this->conexion = new \Conexion();
    }
    public function inactivar(){
        $sentenciaSql = "
                            UPDATE
                                publics_services.cliente_servicio_archivo
                            SET 
                                estado = FALSE
                            WHERE 
                                id_cliente_servicio_archivo = $this->idClienteServicioArchivo
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    public function adicionar(){
        $sentenciaSql = "
                            INSERT INTO 
                                publics_services.cliente_servicio_archivo
                            (
                                id_cliente_servicio
                                ,id_orden_trabajo_cliente
                                ,etiqueta
                                ,ruta_archivo
                                ,observacion
                                ,estado
                                ,id_usuario_creacion
                                ,id_usuario_modificacion
                                ,fecha_creacion
                                ,fecha_modificacion
                            )
                            VALUES
                            (
                                ".$this->clienteServicio->getIdClienteServicio()."    
                                ,".$this->ordenTrabajoCliente->getIdOrdenTrabajoCliente()."    
                                ,'".$this->etiqueta."'
                                ,'".$this->rutaArchivo."'
                                ,'".$this->observacion."'
                                ,".$this->estado."
                                ,$this->idUsuarioCreacion
                                ,$this->idUsuarioModificacion
                                ,NOW()
                                ,NOW()
                            )
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    public function modificar(){
        $sentenciaSql = "
                            UPDATE
                                publics_services.cliente_servicio_archivo
                            SET 
                                id_cliente_servicio = ".$this->clienteServicio->getIdClienteServicio()."
                                ,id_orden_trabajo_cliente = ".$this->ordenTrabajoCliente->getIdOrdenTrabajoCliente()."    
                                ,etiqueta = '".$this->etiqueta."'
                                ,ruta_archivo = '".$this->rutaArchivo."'
                                ,observacion = '".$this->observacion."'
                                ,estado = ".$this->estado."
                                ,id_usuario_modificacion = $this->idUsuarioModificacion
                                ,fecha_modificacion = NOW()
                            WHERE 
                                id_cliente_servicio_archivo = $this->idClienteServicioArchivo
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    
    public function consultar(){
        $this->obtenerCondicion();
        $sentenciaSql = "
            SELECT
                csa.id_cliente_servicio_archivo
                , csa.etiqueta
                , csa.fecha_creacion
                , csa.ruta_archivo
                , csa.observacion
                , p.persona
            FROM
                publics_services.cliente_servicio_archivo AS csa
                INNER JOIN seguridad.usuario AS u ON u.id_usuario = csa.id_usuario_creacion
                INNER JOIN general.persona AS p ON p.id_persona = u.id_persona
            $this->condicion    
        ";
        $this->conexion->ejecutar($sentenciaSql);
        $conatador = 0;
        while ($fila = $this->conexion->obtenerObjeto()){
            $retorno[$conatador]['idClienteServicioArchivo'] = $fila -> id_cliente_servicio_archivo;
            $retorno[$conatador]['etiqueta'] = $fila -> etiqueta;
            $retorno[$conatador]['fechaCreacion'] = $fila -> fecha_creacion;
            $retorno[$conatador]['ruta'] = $fila -> ruta_archivo;
            $retorno[$conatador]['persona'] = $fila -> persona;
            $retorno[$conatador]['observacion'] = $fila -> observacion;
            
            $conatador++;
        }
        return $retorno;
    }
    
    public function obtenerCondicion(){
        $this->condicion = "";
        $this->whereAnd = " WHERE ";
        
        if($this->idClienteServicioArchivo != "" && $this->idClienteServicioArchivo != "null" && $this->idClienteServicioArchivo != null){
            $this->condicion .= $this->whereAnd . " csa.id_cliente_servicio_archivo = ".$this->idClienteServicioArchivo;
            $this->whereAnd = " AND ";
        }
        
        if($this->ordenTrabajoCliente != "" && $this->ordenTrabajoCliente != "null" && $this->ordenTrabajoCliente != null){
            $this->condicion .= $this->whereAnd . " csa.id_orden_trabajo_cliente = ".$this->ordenTrabajoCliente->getIdOrdenTrabajoCliente();
            $this->whereAnd = " AND ";
        }
        
        if($this->estado != "" && $this->estado != "null" && $this->estado != null){
            $this->condicion .= $this->whereAnd . " csa.estado = ".$this->estado;
            $this->whereAnd = " AND ";
        }
        
    }
    
}
