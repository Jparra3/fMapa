<?php
namespace modelo;
require_once '../entorno/Conexion.php';
class Cliente {
    public $conexion;
    
    private $idCliente;
    private $cliente;
    private $telefonos;
    private $correoElectronico;
    private $contrasena;
    private $direcciones;
    private $tipoLogueo;
    
    public function __construct(\entidad\Cliente $cliente) {
        $this->idCliente = $cliente->getIdCliente();
        $this->cliente = $cliente->getCliente();
        $this->telefonos = $cliente->getTelefonos();
        $this->correoElectronico = $cliente->getCorreoElectronico();
        $this->contrasena = $cliente->getContrasena();
        $this->direcciones = $cliente->getDirecciones();
        $this->tipoLogueo = $cliente->getTipoLogueo();
        
        $this->conexion = new \Conexion();
    }
    
    public function buscarCliente($cliente){
        $sentenciaSql = "SELECT
                            c.id_cliente
                            , c.cliente
                        FROM
                            pedido.cliente AS c
                        WHERE
                            c.cliente ILIKE '%$cliente%'
                        ";
       $this->conexion->ejecutar($sentenciaSql);
       while($fila = $this->conexion->obtenerObjeto()){
           $datos[] = array("value" => $fila->cliente,
                           "idCliente" => $fila->id_cliente
                            );
       }
       return $datos;
    }
}
