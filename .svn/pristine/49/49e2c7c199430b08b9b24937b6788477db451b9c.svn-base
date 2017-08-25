<?php
session_start();
date_default_timezone_set("America/Bogota");

require_once '../entidad/Pedido.php';
require_once '../modelo/Pedido.php';
require_once '../entidad/PedidoProducto.php';
require_once '../modelo/PedidoProducto.php';

$retorno = array('exito'=>1, 'mensaje'=>'La factura se generó correctamente.', 'ruta'=>'');

try {
    $contenidoArchivo = "";
    $fechaActual = date("Y/m/d H:i:s");
    $retorno["ruta"] = "../facturas/factura_pedido.txt";
    $idPedido = $_POST["idPedido"];
    
    $pedidoE = new \entidad\Pedido();
    $pedidoE->setIdPedido($idPedido);
    $pedidoM = new \modelo\Pedido($pedidoE);
    $arrInfoEmpresa = $pedidoM->obtenerInfoEmpresaUsuario();
    $arrInfoPedido = $pedidoM->obtenerInfoPedido();
    
    $pedidoProductoE = new \entidad\PedidoProducto();
    $pedidoProductoE->setPedido($pedidoE);
    $pedidoProductoM = new \modelo\PedidoProducto($pedidoProductoE);
    $arrInfoProductos = $pedidoProductoM->consultarBandejaEntrada();
    
//-----------------------------------------------------CREACIÓN DEL ARCHIVO----------------------------------------------------------
    //-------------------INFORMACIÓN DE LA EMPRESA----------------------------------------------
    $contenidoArchivo .= str_pad(substr(strtoupper($arrInfoEmpresa["empresa"]), 0, 40), 40, " ", STR_PAD_BOTH)."\r\n";
    $contenidoArchivo .= str_pad("NIT: ".$arrInfoEmpresa["nit"], 40, " ", STR_PAD_BOTH)."\r\n";
    
    //-------------------INFORMACIÓN DEL PEDIDO--------------------------------------------------
    $contenidoArchivo .= "\r\n";
    $contenidoArchivo .= str_pad(substr("COD. PEDIDO: ".strtoupper($arrInfoPedido["idPedido"]), 0, 40), 40, " ", STR_PAD_RIGHT)."\r\n";
    $contenidoArchivo .= str_pad(substr("CLIENTE: ".strtoupper($arrInfoPedido["cliente"]), 0, 40), 40, " ", STR_PAD_RIGHT)."\r\n";
    $contenidoArchivo .= str_pad(substr("FECHA: ".strtoupper($arrInfoPedido["fecha"]), 0, 40), 40, " ", STR_PAD_RIGHT)."\r\n";
    $contenidoArchivo .= str_pad(substr("BARRIO: ".strtoupper($arrInfoPedido["barrio"]), 0, 40), 40, " ", STR_PAD_RIGHT)."\r\n";
    $contenidoArchivo .= str_pad(substr("DIRECCIÓN: ".strtoupper($arrInfoPedido["direccion"]), 0, 40), 40, " ", STR_PAD_RIGHT)."\r\n";
    
    //-------------------PRODUCTOS DEL PEDIDO-----------------------------------------------------
    $contenidoArchivo .= "\r\n";
    $contenidoArchivo .= str_pad("PRODUCTO", 28, " ", STR_PAD_RIGHT);
    $contenidoArchivo .= str_pad("CANT", 5, " ", STR_PAD_BOTH);
    $contenidoArchivo .= str_pad("VALOR", 7, " ", STR_PAD_LEFT);
    $contenidoArchivo .= "\r\n";

    $contador = 0;
    while ($contador < count($arrInfoProductos["detalle"])) {        
        $contenidoArchivo .= str_pad(substr(strtoupper($arrInfoProductos["detalle"][$contador]["producto"]), 0, 28), 28, " ", STR_PAD_RIGHT);
        $contenidoArchivo .= str_pad($arrInfoProductos["detalle"][$contador]["cantidad"], 5, " ", STR_PAD_BOTH);
        $contenidoArchivo .= str_pad($arrInfoProductos["detalle"][$contador]["valorUnitaConImpue"], 7, " ", STR_PAD_LEFT);
        $contenidoArchivo .= "\r\n";
        $contador++;
    }
    
    //-------------------------VALOR DOMICILIO--------------------------------
    $contenidoArchivo .= str_pad("DOMICILIO", 20, " ", STR_PAD_RIGHT);
    $contenidoArchivo .= str_pad($arrInfoProductos["valorDomicilio"], 20, " ", STR_PAD_LEFT)."\r\n";
    
    //-------------------------TOTALES------------------------------------------
    $contenidoArchivo .= str_pad("--", 40, "--", STR_PAD_RIGHT)."\r\n";
    $contenidoArchivo .= str_pad("TOTAL", 20, " ", STR_PAD_RIGHT);
    $contenidoArchivo .= str_pad(number_format($arrInfoProductos["total"], 0, '', '.'), 20, " ", STR_PAD_LEFT)."\r\n";
    $contenidoArchivo .= "\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n";
//-----------------------------------------------------------------------------------------------------------------------------------
    
    $ar = fopen($retorno["ruta"], "w+");
    fwrite($ar, $contenidoArchivo);
    fclose($ar);
    
//-------------------------------------IMPRESIÓN DE LA FACTURA---------------------------------------------------
    $ubicacionJar = "C:\\Users\\JUANSE\\Dropbox\\Desarrollos\\PHP\\PHP56\\Faiho\\facturas\\ImprimirTicket.jar";
    $archivo = "factura_pedido.txt";
    $servidorImpresora = "localhost";
    $nombreImpresora = "BIXOLON-SRP-270";
    $ruta = "C:\\Users\\JUANSE\\Dropbox\\Desarrollos\\PHP\\PHP56\\Faiho\\facturas\\";
    $parametros = $archivo." ".$servidorImpresora." ".$nombreImpresora." ".$ruta;
    $comando = " java -jar ".$ubicacionJar." ".$parametros;
    //shell_exec($comando);
} catch (Exception $exc) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $exc->getMessage();
}
echo json_encode($retorno);
