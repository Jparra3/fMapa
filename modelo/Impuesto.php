<?php
namespace modelo;
session_start();
require_once($_SESSION['ruta'].'entorno/Conexion.php');
class Impuesto {
    public $conexion;
    
    private $idImpuesto;
    private $impuesto;
    private $idTipoImpuesto;
    
    public function __construct(\entidad\Impuesto $impuesto) {
        $this->idImpuesto = $impuesto->getIdImpuesto();
        $this->impuesto = $impuesto->getImpuesto();
        $this->idTipoImpuesto = $impuesto->getIdTipoImpuesto();
        
        $this->conexion = new \Conexion();
    }
    
    public function consultar() {
        $sentenciaSql = "
                        SELECT
                            i.id_impuesto
                            , i.impuesto
                            , i.id_tipo_impuesto
                            , ti.tipo_impuesto
                        FROM
                            facturacion_inventario.impuesto AS i
                            INNER JOIN facturacion_inventario.tipo_impuesto AS ti ON ti.id_tipo_impuesto = i.id_tipo_impuesto
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        $contador = 0;
        while ($fila = $this->conexion->obtenerObjeto()) {
            $retorno[$contador]["idImpuesto"] = $fila->id_impuesto;
            $retorno[$contador]["impuesto"] = $fila->impuesto;
            $retorno[$contador]["idTipoImpuesto"] = $fila->id_tipo_impuesto;
            $retorno[$contador]["tipoImpuesto"] = $fila->tipo_impuesto;
            $contador++;
        }
        return $retorno;
    }
}