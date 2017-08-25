<?php
session_start();
date_default_timezone_set("America/Bogota");

require_once '../entidad/Transaccion.php';
require_once '../modelo/Transaccion.php';
require_once '../entidad/TransaccionProducto.php';
require_once '../modelo/TransaccionProducto.php';
require_once '../entidad/TransaccionCruce.php';
require_once '../modelo/TransaccionCruce.php';
require_once '../entidad/TransaccionImpuesto.php';
require_once '../modelo/TransaccionImpuesto.php';

$retorno = array('exito'=>1, 'mensaje'=>'La factura se generó correctamente.', 'ruta'=>'');

try {
    $contenidoArchivo = "";
    $fechaActual = date("Y/m/d H:i:s");
    $idTransaccion = $_POST["idTransaccion"];
    $valorTotalPagar = $_POST["valorTotalPagar"];
    $valorTotalPagado = $_POST["valorTotalPagado"];
    $localStorage = $_POST["localStorage"];
    
    $transaccionE = new \entidad\Transaccion();
    $transaccionE->setIdTransaccion($idTransaccion);
    $transaccionM = new \modelo\Transaccion($transaccionE);
    $arrInfoEmpresa = $transaccionM->obtenerInfoEmpresaUsuario();
    $arrInfoFactura = $transaccionM->obtenerInfoFactura();
    $arrInfoCajero = $transaccionM->obtenerInfoCajero();
    $arrInfoCliente = $transaccionM->obtenerInfoCliente();
    $arrInfoConcepto = $transaccionM->obtenerInfoConcepto();
    
    $transaccionProductoE = new \entidad\TransaccionProducto();
    $transaccionProductoE->setTransaccion($transaccionE);
    $transaccionProductoM = new \modelo\TransaccionProducto($transaccionProductoE);
    $arrInfoProductos = $transaccionProductoM->consultarProductosFactura();
    
    $transaccionImpuestoE = new \entidad\TransaccionImpuesto();
    $transaccionImpuestoE->setIdTransaccion($idTransaccion);
    $transaccionImpuestoM = new \modelo\TransaccionImpuesto($transaccionImpuestoE);
    $arrInfoImpuestos = $transaccionImpuestoM->consultar();
    
    $transaccionCruceE = new \entidad\TransaccionCruce();
    $transaccionCruceE->setIdTransaccionConceptoAfectado($arrInfoConcepto["idTransaccionConcepto"]);
    $transaccionCruceM = new \modelo\TransaccionCruce($transaccionCruceE);
    $arrInfoFormasPago = $transaccionCruceM->consultarFormasPagoFactura();
    
    //ruta
    $retorno["ruta"] = "../../archivos" . $localStorage . "facturas/";

    //Creamos la ruta si no existe
    if (!file_exists($retorno["ruta"])) {
        mkdir($retorno["ruta"], 0777, true);
    }
    $retorno["ruta"] .= "factura_" . $arrInfoFactura["numeroFactura"] . ".txt";
    
//-----------------------------------------------------CREACIÓN DEL ARCHIVO----------------------------------------------------------
    //-------------------INFORMACIÓN DE LA EMPRESA----------------------------------------------
    $contenidoArchivo .= str_pad(substr(strtoupper($arrInfoEmpresa["empresa"]), 0, 40), 40, " ", STR_PAD_BOTH)."\r\n";
    $contenidoArchivo .= str_pad("NIT: ".$arrInfoEmpresa["nit"], 40, " ", STR_PAD_BOTH)."\r\n";
    
    //-------------------INFORMACIÓN DE LA CAJA Y CAJERO----------------------------------------
    $contenidoArchivo .= "\r\n";
    $contenidoArchivo .= str_pad("No. CAJA: 00".$arrInfoCajero["numeroCaja"], 40, " ", STR_PAD_BOTH)."\r\n";
    $contenidoArchivo .= str_pad("No. FAC: ".$arrInfoFactura["numeroFactura"], 40, " ", STR_PAD_BOTH)."\r\n";
    $contenidoArchivo .= str_pad($fechaActual, 40, " ", STR_PAD_BOTH)."\r\n";
    $contenidoArchivo .= str_pad(substr("CAJERO(A): ".strtoupper($arrInfoCajero["cajero"]), 0, 40), 40, " ", STR_PAD_BOTH)."\r\n";
    
    //-------------------INFORMACIÓN DEL CLIENTE--------------------------------------------------
    $contenidoArchivo .= "\r\n";
    $contenidoArchivo .= str_pad(utf8_decode("CÉDULA: ").$arrInfoCliente["nit"], 40, " ", STR_PAD_RIGHT)."\r\n";
    $contenidoArchivo .= str_pad(substr("CLIENTE: ".strtoupper($arrInfoCliente["cliente"]), 0, 40), 40, " ", STR_PAD_RIGHT)."\r\n";
    
    //-------------------PRODUCTOS FACTURADOS-----------------------------------------------------
    $contenidoArchivo .= "\r\n";
    $contenidoArchivo .= str_pad("COD", 5, " ", STR_PAD_RIGHT);
    $contenidoArchivo .= str_pad("PRODUCTO", 20, " ", STR_PAD_RIGHT);
    $contenidoArchivo .= str_pad("CANT", 5, " ", STR_PAD_BOTH);
    $contenidoArchivo .= str_pad("VALOR", 7, " ", STR_PAD_LEFT);
    $contenidoArchivo .= str_pad(" ", 3, " ", STR_PAD_LEFT);
    $contenidoArchivo .= "\r\n";

    $contador = 0;
    $total = 0;
    while ($contador < count($arrInfoProductos)) {        
        $contenidoArchivo .= str_pad(substr($arrInfoProductos[$contador]["codigo"], 0, 5), 5, " ", STR_PAD_RIGHT);
        $contenidoArchivo .= str_pad(substr(strtoupper($arrInfoProductos[$contador]["producto"]), 0, 20), 20, " ", STR_PAD_RIGHT);
        $contenidoArchivo .= str_pad($arrInfoProductos[$contador]["cantidad"], 5, " ", STR_PAD_BOTH);
        $contenidoArchivo .= str_pad($arrInfoProductos[$contador]["valorUnitaSalidConImpue"], 7, " ", STR_PAD_LEFT);
        $contenidoArchivo .= str_pad($arrInfoProductos[$contador]["simbolo"], 3, " ", STR_PAD_LEFT);
        $contenidoArchivo .= "\r\n";
        
        $total += ($arrInfoProductos[$contador]["valorUnitaSalidConImpue"] * $arrInfoProductos[$contador]["cantidad"]);
        $contador++;
    }
    
    //-------------------IMPUESTOS-----------------------
    $contenidoArchivo .= "\r\n";
    $contenidoArchivo .= str_pad("SIMB", 6, " ", STR_PAD_RIGHT);
    $contenidoArchivo .= str_pad("IMPUESTO", 11, " ", STR_PAD_RIGHT);
    $contenidoArchivo .= str_pad("VR. BASE", 11, " ", STR_PAD_LEFT);
    $contenidoArchivo .= str_pad("VR. IMPTO", 12, " ", STR_PAD_LEFT);
    $contenidoArchivo .= "\r\n";
    $contador = 0;
    while ($contador < count($arrInfoImpuestos)) {
        $contenidoArchivo .= str_pad($arrInfoImpuestos[$contador]["simbolo"], 6, " ", STR_PAD_BOTH);
        $contenidoArchivo .= str_pad(substr(strtoupper($arrInfoImpuestos[$contador]["impuesto"]), 0, 11), 11, " ", STR_PAD_RIGHT);
        $contenidoArchivo .= str_pad(number_format($arrInfoImpuestos[$contador]["totalBase"], 0, '', '.'), 11, " ", STR_PAD_LEFT);
        $contenidoArchivo .= str_pad(number_format($arrInfoImpuestos[$contador]["totalImpuesto"], 0, '', '.'), 12, " ", STR_PAD_LEFT);
        $contenidoArchivo .= "\r\n";
        $contador++;
    }
    
    //---------------------FORMAS DE PAGO------------------------
    $contenidoArchivo .= "\r\n";
    $contenidoArchivo .= str_pad("FORMA PAGO", 20, " ", STR_PAD_RIGHT);
    $contenidoArchivo .= str_pad("VALOR", 20, " ", STR_PAD_LEFT)."\r\n";
    $contador = 0;
    while ($contador < count($arrInfoFormasPago)) {
        $contenidoArchivo .= str_pad(strtoupper($arrInfoFormasPago[$contador]["formaPago"]), 20, " ", STR_PAD_RIGHT);
        $contenidoArchivo .= str_pad(number_format($arrInfoFormasPago[$contador]["valor"], 0, '', '.'), 20, " ", STR_PAD_LEFT)."\r\n";
        $contador++;
    }
    
    //-------------------------TOTALES------------------------------------------
    $contenidoArchivo .= str_pad("--", 40, "--", STR_PAD_RIGHT)."\r\n";
    $contenidoArchivo .= str_pad("TOTAL PAGADO", 20, " ", STR_PAD_RIGHT);
    $contenidoArchivo .= str_pad(number_format($valorTotalPagado, 0, '', '.'), 20, " ", STR_PAD_LEFT)."\r\n";
    $contenidoArchivo .= str_pad("TOTAL A PAGAR", 20, " ", STR_PAD_RIGHT);
    $contenidoArchivo .= str_pad(number_format($valorTotalPagar, 0, '', '.'), 20, " ", STR_PAD_LEFT)."\r\n";
    $contenidoArchivo .= str_pad("CAMBIO", 20, " ", STR_PAD_RIGHT);
    $contenidoArchivo .= str_pad(number_format(($valorTotalPagado - $valorTotalPagar), 0, '', '.'), 20, " ", STR_PAD_LEFT)."\r\n";
    
    
    //-----------------------------RESOLUCIÓN DIAN--------------------------------------
    $contenidoArchivo .= "\r\n\r\n";
    $contenidoArchivo .= str_pad("SOMOS GRANDES CONTRIBUYENTES", 40, "--", STR_PAD_BOTH) . "\r\n";
    $contenidoArchivo .= str_pad(utf8_decode("RESOLUCIÓN DIAN: ") . $arrInfoCajero["numeroResolucion"], 40, " ", STR_PAD_BOTH) . "\r\n";
    $contenidoArchivo .= str_pad(utf8_decode("FECHA DE RESOLUCIÓN: ") . $arrInfoCajero["fechaExpedicionResolucion"], 40, " ", STR_PAD_BOTH) . "\r\n";
    $contenidoArchivo .= str_pad("PREFIJO: " . $arrInfoCajero["prefijo"] . " DESDE " . $arrInfoCajero["numeroMinimo"] . " HASTA " . $arrInfoCajero["numeroMaximo"], 40, " ", STR_PAD_BOTH) . "\r\n";
    $contenidoArchivo .= "\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n";
    
//-----------------------------------------------------------------------------------------------------------------------------------
    //Se crea el archivo que se va a guardar de respaldo
    $ar = fopen($retorno["ruta"], "w+");
    fwrite($ar, $contenidoArchivo);
    fclose($ar);
    
    //Se crea el archivo que se va a imprimir
    $ar = fopen("../facturas/factura.txt", "w+");
    fwrite($ar, $contenidoArchivo);
    fclose($ar);
//-------------------------------------IMPRESIÓN DE LA FACTURA---------------------------------------------------
    $ubicacionJar = "C:\\Users\\JUANSE\\Dropbox\\Desarrollos\\PHP\\PHP56\\Faiho\\facturas\\ImprimirTicket.jar";
    $archivo = "factura.txt";
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
