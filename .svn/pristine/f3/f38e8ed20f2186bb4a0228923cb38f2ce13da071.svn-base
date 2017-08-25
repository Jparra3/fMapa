<?php
session_start();
date_default_timezone_set("America/Bogota");

require_once '../entidad/Cliente.php';
require_once '../modelo/Cliente.php';
require_once '../entidad/Concepto.php';
require_once '../modelo/Concepto.php';
require_once '../comunes/funciones.php';
require_once '../entidad/Transaccion.php';
require_once '../modelo/Transaccion.php';

$retorno = array('exito'=>1, 'mensaje'=>'La factura se generó correctamente.', 'ruta'=>'');

try {
    
    //Necearias
    $contenidoArchivo = "";
    $fechaActual = date("Y/m/d H:i:s");
    
    //Post
    $idTercero = $_POST["idTercero"];
    $idCliente = $_POST["idClienteProveedor"];
    $nota = $_POST["nota"];
    $tipoClienteProveedor = $_POST["tipoClienteProveedor"];
    $idTransaccion = $_POST["idTransaccion"];
    $idCaja = $_POST["idCaja"];
    $arrInfoFormasPago = json_decode($_POST["dataFormasPago"]);
    $idUsuario = $_SESSION["idUsuario"];
    $arrConceptoPago = json_decode($_POST["arrConceptoPago"]);
    $localStorage = $_POST["localStorage"];
    
    $retorno["ruta"] = "../../archivos".$localStorage."reciboCaja/";
    
    //Creamos la ruta si no existe
    if (!file_exists($retorno["ruta"])) {
        mkdir($retorno["ruta"], 0777, true);
    }
    
    $transaccionE = new \entidad\Transaccion();
    $transaccionE->setIdTransaccion($idTransaccion);
    
    $transaccionM = new \modelo\Transaccion($transaccionE);
    $informacionTransaccion = $transaccionM->consultarInformacionFactura();
    
    //Creamos el id unico del recibo
    $retorno["ruta"] .= "reciboCaja_" . $informacionTransaccion[0]["numeroTipoDocumento"] . ".txt";
    
    //Intanciamos Cliente
    $clienteE = new \entidad\Cliente();
    $clienteE ->setIdCliente($idCliente);
    $clienteE ->setTipoClienteProveedor($tipoClienteProveedor);
    
    $clienteM = new \modelo\Cliente($clienteE);
    $arrCliente = $clienteM -> consultar();
    
    
    $contenidoArchivo .= str_pad(substr("COMPROBANTE NRO. ".strtoupper($informacionTransaccion[0]["numeroTipoDocumento"]), 0, 40), 40, " ", STR_PAD_BOTH)."\r\n";
    $contenidoArchivo .= str_pad(substr("CLIENTE: ".strtoupper($arrCliente[0]["tercero"]), 0, 40), 40, " ", STR_PAD_BOTH)."\r\n";
    $contenidoArchivo .= str_pad(substr("NIT: ".strtoupper($arrCliente[0]["nit"]), 0, 40), 40, " ", STR_PAD_BOTH)."\r\n";
    $contenidoArchivo .= "\r\n";
    
    //
    $contenidoArchivo .= "\r\n";
    $contenidoArchivo .= str_pad("COD", 10, " ", STR_PAD_RIGHT);
    $contenidoArchivo .= str_pad("CONCEPTO", 20, " ", STR_PAD_RIGHT);
    $contenidoArchivo .= str_pad("VALOR", 10, " ", STR_PAD_LEFT);
    $contenidoArchivo .= "\r\n";
    
    //Conceptos
    $contador=0;
    while($contador<  count($arrConceptoPago)){
        
        $conceptoE = new \entidad\Concepto();
        $conceptoE ->setIdConcepto($arrConceptoPago[$contador]->idConcepto);

        $conceptoM = new \modelo\Concepto($conceptoE);
        $arrConcepto = $conceptoM -> consultar($parametros);
        
        $valor = number_format($arrConceptoPago[$contador]->valorPago, 0, '', '.');
        
        $contenidoArchivo .= str_pad(substr($arrConcepto[0]["codigo"], 0, 5), 10, " ", STR_PAD_RIGHT);
        $contenidoArchivo .= str_pad(substr(strtoupper($arrConcepto[0]["tipoDocumento"]), 0, 20), 20, " ", STR_PAD_RIGHT);
        $contenidoArchivo .= str_pad($valor, 10, " ", STR_PAD_LEFT);
        $contenidoArchivo .= "\r\n";
        $contador++;
    }
    
    //---------------------FORMAS DE PAGO------------------------
    $contenidoArchivo .= "\r\n";
    $contenidoArchivo .= str_pad("FORMA PAGO", 20, " ", STR_PAD_RIGHT);
    $contenidoArchivo .= str_pad("VALOR", 20, " ", STR_PAD_LEFT)."\r\n";
    $contador = 0;
    $totalPagar = 0;
    while ($contador < count($arrInfoFormasPago)) {
        $contenidoArchivo .= str_pad(strtoupper($arrInfoFormasPago[$contador]->formaPago), 20, " ", STR_PAD_RIGHT);
        $contenidoArchivo .= str_pad(number_format($arrInfoFormasPago[$contador]->valor, 0, '', '.'), 20, " ", STR_PAD_LEFT)."\r\n";
        $totalPagar = (int)$totalPagar+(int)$arrInfoFormasPago[$contador]->valor;
        $contador++;
    }
    
    //-------------------------TOTALES------------------------------------------
    $contenidoArchivo .= str_pad("--", 40, "--", STR_PAD_RIGHT)."\r\n";
    $contenidoArchivo .= str_pad("TOTAL PAGADO", 20, " ", STR_PAD_RIGHT);
    $contenidoArchivo .= str_pad(number_format($totalPagar, 0, '', '.'), 20, " ", STR_PAD_LEFT)."\r\n";
    $contenidoArchivo .= str_pad("TOTAL A PAGAR", 20, " ", STR_PAD_RIGHT);
    $contenidoArchivo .= str_pad(number_format($totalPagar, 0, '', '.'), 20, " ", STR_PAD_LEFT)."\r\n";
    $contenidoArchivo .= str_pad("CAMBIO", 20, " ", STR_PAD_RIGHT);
    $contenidoArchivo .= str_pad(number_format(($totalPagar - $totalPagar), 0, '', '.'), 20, " ", STR_PAD_LEFT)."\r\n";
    $contenidoArchivo .= "\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n";
    
    $ar = fopen($retorno["ruta"], "w+");
    fwrite($ar, $contenidoArchivo);
    fclose($ar);
    
//-------------------------------------IMPRESIÓN DE LA FACTURA---------------------------------------------------
    $ubicacionJar = "C:\\Users\\David\\Dropbox\\Desarrollos\\PHP\\PHP56\\Financiera\\ticket_jar\\ImprimirTicket.jar";
    $archivo = $retorno["ruta"];
    $servidorImpresora = "localhost";
    $nombreImpresora = "BIXOLON-SRP-270";
    $ruta = "C:\\Users\\David\\Dropbox\\Desarrollos\\PHP\\PHP56\\Financiera\\ticket_jar\\";
    $parametros = $archivo." ".$servidorImpresora." ".$nombreImpresora." ".$ruta;
    $comando = " java -jar ".$ubicacionJar." ".$parametros;
} catch (Exception $exc) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $exc->getMessage();
}
echo json_encode($retorno);
