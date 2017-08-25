<?php
namespace modelo;
session_start();
require_once($_SESSION['ruta'].'entorno/Conexion.php');
class TransaccionProductoImpuesto {
    public $conexion;
    
    private $idTransaccionProductoImpuesto;
    private $idTransaccionProducto;
    private $idImpuesto;
    private $impuestoValor;
    private $valor;
    private $base;
    private $idUsuarioCreacion;
    private $idUsuarioModificacion;
    private $fechaCreacion;
    private $fechaModificacion;
    
    public function __construct(\entidad\TransaccionProductoImpuesto $transaccionProductoImpuesto) {
        $this->idTransaccionProductoImpuesto = $transaccionProductoImpuesto->getIdTransaccionProductoImpuesto();
        $this->idTransaccionProducto = $transaccionProductoImpuesto->getIdTransaccionProducto();
        $this->idImpuesto = $transaccionProductoImpuesto->getIdImpuesto();
        $this->impuestoValor = $transaccionProductoImpuesto->getImpuestoValor();
        $this->valor = $transaccionProductoImpuesto->getValor();
        $this->base = $transaccionProductoImpuesto->getBase();
        $this->idUsuarioCreacion = $transaccionProductoImpuesto->getIdUsuarioCreacion();
        $this->idUsuarioModificacion = $transaccionProductoImpuesto->getIdUsuarioModificacion();
        $this->fechaCreacion = $transaccionProductoImpuesto->getFechaCreacion();
        $this->fechaModificacion = $transaccionProductoImpuesto->getFechaModificacion();
        
        $this->conexion = new \Conexion();
    }
    public function adicionar() {
        $sentenciaSql = "
                        INSERT INTO
                            facturacion_inventario.transaccion_producto_impuesto
                        (
                            id_transaccion_producto
                            , id_impuesto 
                            , impuesto_valor
                            , valor
                            , base
                            , id_usuario_creacion
                            , id_usuario_modificacion
                            , fecha_creacion
                            , fecha_modificacion
                        )
                        VALUES
                        (
                            $this->idTransaccionProducto
                            , $this->idImpuesto
                            , $this->impuestoValor
                            , $this->valor
                            , $this->base
                            , $this->idUsuarioCreacion
                            , $this->idUsuarioModificacion
                            , NOW()
                            , NOW()
                        )
                        ";
        $this->conexion->ejecutar($sentenciaSql);
    }
    
    public function guardDatosTransProduImpue($idTransaccionProducto, $idProducto, $cantidad) {
        $sentenciaSql = "
                        SELECT
                            pi.id_impuesto
                            , i.id_tipo_impuesto
                            , pi.valor  AS valor_impuesto
                            , p.valor_salid_con_impue AS valor_producto
                        FROM
                            facturacion_inventario.producto_impuesto AS pi
                            INNER JOIN facturacion_inventario.impuesto AS i ON i.id_impuesto = pi.id_impuesto
                            INNER JOIN facturacion_inventario.producto AS p ON p.id_producto = pi.id_producto
                        WHERE
                            pi.id_producto = $idProducto
                        ";
        $this->conexion->ejecutar($sentenciaSql);
        
        while ($fila = $this->conexion->obtenerObjeto()) {
            $idImpuesto = $fila->id_impuesto;
            $idTipoImpuesto = $fila->id_tipo_impuesto;
            $valorImpuesto = $fila->valor_impuesto;
            $valorProducto = $fila->valor_producto;
            $totalConImpuesto = $valorProducto * $cantidad;
            $valor = 0;
            $valorBase = 0;
            
            if($idTipoImpuesto == "1"){//FIJO
                $valor = $valorImpuesto * $cantidad;
                $valorBase = $totalConImpuesto - $valor;
            }elseif($idTipoImpuesto == "2"){//PORCENTUAL
                $valorBase = $totalConImpuesto / (($valorImpuesto / 100) + 1); //SE CALCULA LA BASE
                $valor = $totalConImpuesto - $valorBase;//SE OBTIENE EL IMPUESTO
            }
            
            //GUARDO EN TRANSACCION PRODUCTO IMPUESTO
            $transaccionProductoImpuestoE = new \entidad\TransaccionProductoImpuesto();
            $transaccionProductoImpuestoE->setIdImpuesto($idImpuesto);
            $transaccionProductoImpuestoE->setIdTransaccionProducto($idTransaccionProducto);
            $transaccionProductoImpuestoE->setImpuestoValor($valorImpuesto);
            $transaccionProductoImpuestoE->setValor($valor);
            $transaccionProductoImpuestoE->setBase($valorBase);
            $transaccionProductoImpuestoE->setIdUsuarioCreacion($_SESSION["idUsuario"]);
            $transaccionProductoImpuestoE->setIdUsuarioModificacion($_SESSION["idUsuario"]);
            
            $transaccionProductoImpuestoM = new \modelo\TransaccionProductoImpuesto($transaccionProductoImpuestoE);
            $transaccionProductoImpuestoM->adicionar();
        }
    }
}
