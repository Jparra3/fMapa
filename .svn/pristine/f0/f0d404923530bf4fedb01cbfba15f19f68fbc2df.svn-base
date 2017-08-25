<?php
namespace modelo;
session_start();
require_once($_SESSION['ruta'].'entorno/Conexion.php');
class Cierre {
    public $conexion = null;
    
    private $idCierre;
    private $periodo;
    private $fechaInicial;
    private $fechaFinal;
    private $estado;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    
    public function __construct(\entidad\Cierre $cierre) {
        $this->idCierre = $cierre->getIdCierre();
        $this->periodo = $cierre->getPeriodo();
        $this->fechaInicial = $cierre->getFechaInicial();
        $this->fechaFinal = $cierre->getFechaFinal();
        $this->estado = $cierre->getEstado();
        $this->idUsuarioCreacion = $cierre->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $cierre->getIdUsuarioModificacion();
        $this->fechaCreacion = $cierre->getFechaCreacion();
        $this->fechaModificacion = $cierre->getFechaModificacion();
        
        $this->conexion = new \Conexion();
    }
    
    public function adicionar(){
        $sentenciaSql = "
                        INSERT INTO
                            facturacion_inventario.cierre
                        (
                            periodo
                            , fecha_inicial
                            , fecha_final
                            , id_usuario_creacion
                            , id_usuario_modificacion
                            , fecha_creacion
                            , fecha_modificacion
                            , estado
                        )
                        VALUES
                        (
                        '$this->periodo'
                        , '$this->fechaInicial'
                        , '$this->fechaFinal'
                        , $this->idUsuarioCreacion
                        , $this->idUsuarioModificacion    
                        , NOW()
                        , NOW()
                        , TRUE
                        )
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    
    public function consultar() {
        $sentenciaSql = "
                        SELECT
                            id_cierre
                            , periodo
                        FROM
                            facturacion_inventario.cierre
                        WHERE
                            estado = true
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["idCierre"] = $fila->id_cierre;
            $retorno[$contador]["periodo"] = $fila->periodo;
            $contador++;
        }
        return $retorno;
    }
    
    public function obtenerUltimoCierre(){
        $retorno = array("fechaInicial"=>"", "fechaFinal"=>"");
        $sentenciaSql = "
                        SELECT
                            CAST(fecha_inicial AS DATE) AS fecha_inicial
                            , CAST(fecha_final AS DATE) AS fecha_final
                        FROM
                            facturacion_inventario.cierre
                        WHERE 	
                            id_cierre = (SELECT MAX(id_cierre) FROM facturacion_inventario.cierre)
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $fila = $this->conexion->obtenerObjeto();
        $retorno["fechaInicial"] = $fila->fecha_inicial;
        $retorno["fechaFinal"] =   $fila->fecha_final;      
        return $retorno;
    }
    
    public function obtenerMaximo(){
        $sentenciaSql = "
                        SELECT
                            MAX(id_cierre) AS maximo
                        FROM
                            facturacion_inventario.cierre
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $fila = $this->conexion->obtenerObjeto();
        return $fila->maximo;
    }
    
    public function obtenerUltimoCierreFecha($fecha){
        $retorno = array("fechaInicial"=>"", "fechaFinal"=>"");
        $sentenciaSql = "
                        SELECT
                            id_cierre
                            , periodo
                            , CAST(fecha_inicial AS DATE) AS fecha_inicial
                            , CAST(fecha_final AS DATE) AS fecha_final
                        FROM
                            facturacion_inventario.cierre
                        WHERE
                            fecha_final < '$fecha'
                            AND estado = TRUE
                        ORDER BY fecha_final DESC
                        LIMIT 1
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $fila = $this->conexion->obtenerObjeto();
        $retorno["fechaInicial"] = $fila->fecha_inicial;
        $retorno["fechaFinal"] = $fila->fecha_final;
        return $retorno;
    }
    
    function obtenerNombreMes($mes){
        setlocale(LC_TIME, 'spanish');  
        $nombre = strftime("%B",mktime(0, 0, 0, $mes, 1, 2000)); 
        return $nombre;
    }
    
    public function obtenerPrimerDiaMes($fecha){
        $objFecha = new \DateTime($fecha);
        $objFecha->modify('first day of this month');
        return $objFecha->format('Y-m-d');
    }
    
    public function obtenerUltimoDiaMes($fecha){
        $objFecha = new \DateTime($fecha);
        $objFecha->modify('last day of this month');
        return $objFecha->format('Y-m-d');
    }
}
