<?php
session_start();
require_once '../entidad/Cliente.php';
require_once '../modelo/Cliente.php';
$retorno = array("exito"=>1, "mensaje"=>"", "data"=>null, "numeroRegistros"=>0);
try{
    $idCliente = $_POST['idCliente'];
    $idProducto = $_POST['idProducto'];
    $idServicio = $_POST['idServicio'];
    $idBodega = $_POST['idBodega'];
    
    $arrParametros[0]["valor"] = $idCliente;
    $arrParametros[1]["valor"] = $idProducto;
    $arrParametros[2]["valor"] = $idServicio;
    $arrParametros[3]["valor"] = $idBodega;
    
    $arrParametros[0]["relacion"] = " c.id_cliente = ";
    $arrParametros[1]["relacion"] = " p.id_producto IN (";
    $arrParametros[2]["relacion"] = " servicio.id_producto IN (";
    $arrParametros[3]["relacion"] = " csp.id_bodega IN (";
    
    $clienteE = new \entidad\Cliente();
    $clienteM = new \modelo\Cliente($clienteE);
    $retorno["data"] = $clienteM -> consultarInventarioInstalado($arrParametros);
    $retorno["numeroRegistros"] = $clienteM -> conexion -> obtenerNumeroRegistros();
} catch (Exception $ex) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $ex -> getMessage();
}
echo json_encode($retorno);
?>

