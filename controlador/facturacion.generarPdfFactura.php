<?php
session_start();
date_default_timezone_set("America/Bogota");

require_once '../../Plantillas/lib/tcpdf/tcpdf.php';
require_once '../entidad/Transaccion.php';
require_once '../modelo/Transaccion.php';
require_once '../entidad/TransaccionProducto.php';
require_once '../modelo/TransaccionProducto.php';
require_once '../entidad/TransaccionCruce.php';
require_once '../modelo/TransaccionCruce.php';
require_once '../comunes/funciones.php';
require_once '../entidad/TransaccionImpuesto.php';
require_once '../modelo/TransaccionImpuesto.php';

$retorno = array('exito' => 1, 'mensaje' => '', 'ruta' => '');

try {
    
    $objTransaccion = new \modelo\Transaccion(new \entidad\Transaccion());
    $arrEstilosFactura = $objTransaccion->obtenerEstilosFactura();
    if ($arrEstilosFactura["exito"] == 0) {
        throw new Exception($arrEstilosFactura["mensaje"]);
    }
    
    //Necesarias
    $contenidoArchivo = "";
    $arrMes = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    $dia = date("d");
    $mes = (int) date("m") - 1;
    $anio = date("Y");
    $hora = date("H");
    $minuto = date("i");
    $segundo = date("s");
    $ampm = strtoupper(date("a"));
    $rutaLogo = $arrEstilosFactura["data"]["rutaLogoEmpresa"];
    $fondoTitulos = $arrEstilosFactura["data"]["colorFondoTitulos"];
    $fondoContenido = $arrEstilosFactura["data"]["colorFondoContenido"];
    $colorTexto = $arrEstilosFactura["data"]["colorTextoContenido"];
    $colorTextoTitulo = $arrEstilosFactura["data"]["colorTextoTitulos"];

    //variables POST
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

    $retorno["ruta"] .= "factura_" . $arrInfoFactura["numeroFactura"] . ".pdf";
    //Se crea una nueva instancia de la libreria
    $orientacionHoja = 'P'; //P -> Portrait; L-> Landscape
    $pdf = new TCPDF($orientacionHoja, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    ob_clean();
    $pdf->SetFont('helvetica', '', 7, '', true);

    //Adiciona una página
    $pdf->AddPage();

    //Encabezado
    $html = "";
    $html .= '<div style="width:100%;">';
    $html .= '<table style="width:100%;">';
	$html .= '<tr><td>&nbsp;</td></tr>';
    $html .= '<tr>';
    $html .= '<td style="width:30%">';
    $html .= '<img src="'.$rutaLogo.'">';
    $html .= '</td>';
    $html .= '<td style="width:40%;text-align:center;">';
    $html .= $arrInfoEmpresa["empresa"];
    $html .= '<br>';
    $html .= 'NIT ' . $arrInfoEmpresa["nit"] . ' - ' . $arrInfoEmpresa["digitoVerificacion"];
    $html .= '<br>';
    $html .= $arrInfoEmpresa["direccion"];
    $html .= '<br>';
    $html .= 'Tel. ' . $arrInfoEmpresa["telefono"];
    $html .= '<br>';
    $html .= $arrInfoEmpresa["email"];
    $html .= '<br>';
    $html .= '</td>';
    $html .= '<td style="width:30%;">';
    $html .= '<table style="width:100%;">';
    $html .= '<tr>';
    
    if($arrInfoCajero["prefijo"] != null && $arrInfoCajero["prefijo"] != '' && $arrInfoCajero["prefijo"] != 'null'){
        $html .= '<td><b>Factura de venta ' . $arrInfoCajero["prefijo"] . ' - ' . str_pad($arrInfoFactura["numeroFactura"], 40, " ", STR_PAD_BOTH) . '</b></td>';
    }else{
        $html .= '<td><b>Factura de venta ' . str_pad($arrInfoFactura["numeroFactura"], 40, " ", STR_PAD_BOTH) . '</b></td>';
    }
    
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<td><b>Fecha:</b> ' . $arrMes[$mes] . ' ' . $dia . ' de ' . $anio . ' ' . $hora . ':' . $minuto . ':' . $segundo . ' ' . $ampm . '</td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<td><b>Cajero:</b> ' . $arrInfoCajero["usuario"] . '</td>';
    $html .= '</tr>';
	$html .= '<tr><td>&nbsp;</td></tr>';
    $html .= '</table>';
    $html .= '</td>';
    $html .= '</tr>';
    $html .= '</table>';

    //Info cliente
    $html .= '<table style="width:100%;">';
    $html .= '<tr>';
    $html .= '<td style="width: 15%; background-color:'.$fondoTitulos.';color:'.$colorTextoTitulo.';border:0.7px #8000FF solid;"> CLIENTE </td>';
    $html .= '<td style="width: 55%; border:0.7px #E6E7E8 solid;color:' . $colorTexto . ';"> ' . $arrInfoCliente["cliente"] . ' </td>';
    $html .= '<td style="width: 10%; background-color:'.$fondoTitulos.';color:'.$colorTextoTitulo.';border:0.7px #E6E7E8 solid;"> NIT </td>';
    $html .= '<td style="width: 20%; border:0.7px #E6E7E8 solid;color:' . $colorTexto . ';"> ' . $arrInfoCliente["nit"] . ' </td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<td style="width: 15%; background-color:'.$fondoTitulos.';color:'.$colorTextoTitulo.';border:0.7px #8000FF solid;"> DIRECCIÓN </td>';
    $html .= '<td style="width: 55%; border:0.7px #E6E7E8 solid;color:' . $colorTexto . ';"> ' . $arrInfoCliente["direccion"] . ' </td>';
    $html .= '<td style="width: 10%; background-color:'.$fondoTitulos.';color:'.$colorTextoTitulo.';border:0.7px #E6E7E8 solid;"> TELÉFONO </td>';
    $html .= '<td style="width: 20%; border:0.7px #E6E7E8 solid;color:' . $colorTexto . ';"> ' . $arrInfoCliente["telefono"] . ' </td>';
    $html .= '</tr>';

    //Productos
    $html .= '<tr><td colspan="5">&nbsp;</td></tr>';
    $html .= '<tr>';
    $html .= '<td style="width: 10%; background-color:'.$fondoTitulos.';color:'.$colorTextoTitulo.'; text-align:center;border:0.7px #E6E7E8 solid;"> COD. </td>';
    $html .= '<td style="width: 50%; background-color:'.$fondoTitulos.';color:'.$colorTextoTitulo.'; text-align:center;border:0.7px #E6E7E8 solid;"> PRODUCTO </td>';
    $html .= '<td style="width: 8%; background-color:'.$fondoTitulos.';color:'.$colorTextoTitulo.'; text-align:center;border:0.7px #E6E7E8 solid;"> CANT. </td>';
    $html .= '<td style="width: 12%; background-color:'.$fondoTitulos.';color:'.$colorTextoTitulo.'; text-align:center;border:0.7px #E6E7E8 solid;"> VALOR </td>';
    $html .= '<td style="width: 12%; background-color:'.$fondoTitulos.';color:'.$colorTextoTitulo.'; text-align:center;border:0.7px #E6E7E8 solid;"> TOTAL </td>';
    $html .= '<td style="width: 8%; background-color:'.$fondoTitulos.';color:'.$colorTextoTitulo.'; text-align:center;border:0.7px #E6E7E8 solid;"> SIMB. </td>';
    $html .= '</tr>';

    $contador = 0;
    while ($contador < count($arrInfoProductos)) {

        $html .= '<tr>';
        $html .= '<td style="background-color:'.$fondoContenido.';border:0.7px #E6E7E8 solid;color:' . $colorTexto . ';text-align:center;">' . $arrInfoProductos[$contador]["codigo"] . '</td>';
        $html .= '<td style="background-color:'.$fondoContenido.';border:0.7px #E6E7E8 solid;color:' . $colorTexto . ';">' . $arrInfoProductos[$contador]["producto"] . '</td>';
        $html .= '<td style="background-color:'.$fondoContenido.';border:0.7px #E6E7E8 solid;color:' . $colorTexto . ';text-align:right;">' . $arrInfoProductos[$contador]["cantidad"] . '</td>';
        $html .= '<td style="background-color:'.$fondoContenido.';border:0.7px #E6E7E8 solid;color:' . $colorTexto . ';text-align:right;">' . number_format($arrInfoProductos[$contador]["valorUnitaSalidConImpue"], 0, '', '.') . '</td>';
        $html .= '<td style="background-color:'.$fondoContenido.';border:0.7px #E6E7E8 solid;color:' . $colorTexto . ';text-align:right;">' . number_format(($arrInfoProductos[$contador]["valorUnitaSalidConImpue"] * $arrInfoProductos[$contador]["cantidad"]), 0, '', '.') . '</td>';
        $html .= '<td style="background-color:'.$fondoContenido.';border:0.7px #E6E7E8 solid;color:' . $colorTexto . ';text-align:center;">' . $arrInfoProductos[$contador]["simbolo"] . '</td>';
        $html .= '</tr>';

        $contador++;
    }

    $html .= '<tr><td colspan="5">&nbsp;</td></tr>';
    
    
    $html .= '<tr>';
    
    //Totales
    //Impuestos
    $html .= '<td style="width:50%">';
    $html .= '<table>';
    $html .= '<tr>';
    $html .= '<td style="width: 10%; background-color:'.$fondoTitulos.';color:'.$colorTextoTitulo.'; text-align:center;border:0.7px #E6E7E8 solid;"> SIMB. </td>';
    $html .= '<td style="width: 30%; background-color:'.$fondoTitulos.';color:'.$colorTextoTitulo.'; text-align:center;border:0.7px #E6E7E8 solid;"> IMPUESTO </td>';
    $html .= '<td style="width: 30%; background-color:'.$fondoTitulos.';color:'.$colorTextoTitulo.'; text-align:center;border:0.7px #E6E7E8 solid;"> VR. BASE </td>';
    $html .= '<td style="width: 30%; background-color:'.$fondoTitulos.';color:'.$colorTextoTitulo.'; text-align:center;border:0.7px #E6E7E8 solid;"> VR. IMPTO </td>';
    $html .= '</tr>';

    $contador = 0;
    while ($contador < count($arrInfoImpuestos)) {

        $html.='<tr>';
        $html .= '<td style="background-color:'.$fondoContenido.';border:0.7px #E6E7E8 solid;color:' . $colorTexto . ';text-align:center;">' . $arrInfoImpuestos[$contador]["simbolo"] . '</td>';
        $html .= '<td style="background-color:'.$fondoContenido.';border:0.7px #E6E7E8 solid;color:' . $colorTexto . ';">' . $arrInfoImpuestos[$contador]["impuesto"] . '</td>';
        $html .= '<td style="background-color:'.$fondoContenido.';border:0.7px #E6E7E8 solid;color:' . $colorTexto . ';text-align:right;">' . number_format($arrInfoImpuestos[$contador]["totalBase"], 0, '', '.') . '</td>';
        $html .= '<td style="background-color:'.$fondoContenido.';border:0.7px #E6E7E8 solid;color:' . $colorTexto . ';text-align:right;">' . number_format($arrInfoImpuestos[$contador]["totalImpuesto"], 0, '', '.') . '</td>';
        $html .='</tr>';

        $contador++;
    }
    $html .= '</table>';
    $html .= '</td>';
    
    //Formas de pago 
    $html .= '<td style="width:25%">';
    $html .= '<table>';
    $html .= '<tr>';
    $html .= '<td style="width: 50%; background-color:'.$fondoTitulos.';color:'.$colorTextoTitulo.'; text-align:center;border:0.7px #E6E7E8 solid;"> FORMA DE PAGO </td>';
    $html .= '<td style="width: 50%; background-color:'.$fondoTitulos.';color:'.$colorTextoTitulo.'; text-align:center;border:0.7px #E6E7E8 solid;"> VALOR </td>';
    $html .= '</tr>';

    $contador = 0;
    while ($contador < count($arrInfoFormasPago)) {
        $html.='<tr>';
        $html .= '<td style="background-color:'.$fondoContenido.';border:0.7px #E6E7E8 solid;color:' . $colorTexto . ';text-align:center;text-align:left;">' . $arrInfoFormasPago[$contador]["formaPago"] . '</td>';
        $html .= '<td style="background-color:'.$fondoContenido.';border:0.7px #E6E7E8 solid;color:' . $colorTexto . ';text-align:center;text-align:right;">' . number_format($arrInfoFormasPago[$contador]["valor"], 0, '', '.') . '</td>';
        $contador++;
        $html.='</tr>';
    }
    $html .= '</table>';
    $html .= '</td>';
    
    $html .= '<td style="width:25%">';
    $html .= '<table>';
    $html .= '<tr>';
    $html .= '<td style="width: 50%; background-color:'.$fondoTitulos.';color:'.$colorTextoTitulo.'; border:0.7px #E6E7E8 solid;"> TOTAL PAGADO </td>';
    $html .= '<td style="width: 50%; background-color:'.$fondoContenido.';border:0.7px #E6E7E8 solid;color:' . $colorTexto . ';text-align:right;">' . number_format($valorTotalPagado, 0, '', '.') . '</td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<td style="width: 50%; background-color:'.$fondoTitulos.';color:'.$colorTextoTitulo.'; border:0.7px #E6E7E8 solid;"> TOTAL A PAGAR </td>';
    $html .= '<td style="width: 50%; background-color:'.$fondoContenido.';border:0.7px #E6E7E8 solid;color:' . $colorTexto . ';text-align:right;">' . number_format($valorTotalPagar, 0, '', '.') . '</td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<td style="width: 50%; background-color:'.$fondoTitulos.';color:'.$colorTextoTitulo.'; border:0.7px #E6E7E8 solid;"> CAMBIO </td>';
    $html .= '<td style="width: 50%; background-color:'.$fondoContenido.';border:0.7px #E6E7E8 solid;color:' . $colorTexto . ';text-align:right;">' . number_format(($valorTotalPagado - $valorTotalPagar), 0, '', '.') . '</td>';
    $html .= '</tr>';
    $html .= '</table>';
    $html .= '</td>';
    $html .= '</tr>';   

    

    //Letra del total a pagar
    $letraTotalPagar = obtenerTextoNumero($valorTotalPagar);
    $html .= '</table>';
    $html .= '<br>';
    $html .= '<br>';
    $html .= '<hr style="height: .7px;">';
    $html .= '<table style="width:100%;border-collapse: collapse;">';
    $html .= '<tr>';
    $html .= '<td style="width:80%;color:' . $colorTexto . ';"><b>SON </b> ' . $letraTotalPagar . ' PESOS MCTE </td>';
    $html .= '<td style="text-align:right;width:10%;color:' . $colorTexto . ';"> <b>TOTAL</b></td>';
    $html .= '<td style="text-align:right;width:10%;color:' . $colorTexto . ';"> $' . number_format($valorTotalPagar, 0, '', '.') . '</td>';
    $html .= '</tr>';
    $html .= '</table>';

    //Resolución DIAN
    $html .= '<br><br>';
    $html .= '<table style="width:100%;border-collapse: collapse;">';
    $html .= '<tr>';
    
    $html .= '<td>';
    $html .= '<table style="width:100%;border-collapse: collapse;">';
    $html .= '<tr>';
    $html .= '<td style="text-align:center;width:100%;color:' . $colorTexto . ';">REGIMEN '. strtoupper($arrInfoEmpresa["tipoRegimen"]) .'</td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<td style="text-align:center;width:100%;color:' . $colorTexto . ';">RESOLUCIÓN DIAN: ' . $arrInfoCajero["numeroResolucion"] . '</td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<td style="text-align:center;width:100%;color:' . $colorTexto . ';">FECHA DE RESOLUCIÓN: ' . $arrInfoCajero["fechaExpedicionResolucion"] . '</td>';
    $html .= '</tr>';
    $html .= '<tr>';
    
    $arrInfoCajero["prefijo"] = trim($arrInfoCajero["prefijo"]);
    if ($arrInfoCajero["prefijo"] != null && $arrInfoCajero["prefijo"] != '' && $arrInfoCajero["prefijo"] != 'null') {
        $html .= '<td style="text-align:center;width:100%;color:' . $colorTexto . ';">PREFIJO: ' . $arrInfoCajero["prefijo"] . ' NUMERACIÓN DESDE 00' . $arrInfoCajero["numeroMinimo"] . ' HASTA ' . $arrInfoCajero["numeroMaximo"] . '</td>';
    } else {
        $html .= '<td style="text-align:center;width:100%;color:' . $colorTexto . ';">NUMERACIÓN DESDE 00' . $arrInfoCajero["numeroMinimo"] . ' HASTA ' . $arrInfoCajero["numeroMaximo"] . '</td>';
    }
    
    $html .= '</tr>';
    $html .= '</table>';
    $html .= '</td>';
    
    $html .= '<td>';
    $html .= '<table style="width:100%;border-collapse: collapse;">';
    $html .= '<tr>';
    $html .= '<td style="text-align:center;width:100%;color:' . $colorTexto . ';">FIRMA Y SELLO DEL CLIENTE</td>';
    $html .= '</tr>';
    $html .= '<tr><td>&nbsp;</td></tr>';
    $html .= '<tr>';
    $html .= '<td style="text-align:center;width:100%;">________________________________________</td>';
    $html .= '</tr>';
    //$html .= '<tr><td>&nbsp;</td></tr>';
    //$html .= '<tr>';
    //$html .= '<td style="text-align:center;width:100%;">C.C. ________________</td>';
    //$html .= '</tr>';
    $html .= '</table>';
    $html .= '</td>';
    
    $html .= '<td>';
    $html .= '<table style="width:100%;border-collapse: collapse;">';
    $html .= '<tr>';
    $html .= '<td style="text-align:center;width:100%;color:' . $colorTexto . ';">FIRMA Y SELLO DEL VENDEDOR</td>';
    $html .= '</tr>';
    $html .= '<tr><td>&nbsp;</td></tr>';
    $html .= '<tr>';
    $html .= '<td style="text-align:center;width:100%;">________________________________________</td>';
    $html .= '</tr>';
    //$html .= '<tr><td>&nbsp;</td></tr>';
    //$html .= '<tr>';
    //$html .= '<td style="text-align:center;width:100%;">C.C. ________________</td>';
    //$html .= '</tr>';
    $html .= '</table>';
    $html .= '</td>';
    
    $html .= '</tr>';
    $html .= '</table>';
    

    //Nombre software
    $html .= '<br>';
    $html .= '<br>';
    $html .= '<hr style="height: .3px;">';
    $html .= '<table style="width:100%;border-collapse: collapse;">';
    $html .= '<tr>';
    //$html .= '<td style="text-align:center;width:100%;">'. obtenerNombreSoftware() .'</td>';
    $html .= '<td style="text-align:center;width:50%;"><img src="../imagenes/logo_delta_color.png"></td>';
    $html .= '<td style="text-align:center;width:50%;">Desarrollado por <img src="../imagenes/logo_iss.png"></td>';
    $html .= '</tr>';
    $html .= '</table>';

    $html .= '</div>';

    //Guardamos el archivo
    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output($retorno["ruta"], 'F');
} catch (Exception $ex) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $ex->getMessage();
}
echo json_encode($retorno);
?>