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

$retorno = array('exito' => 1, 'mensaje' => '', 'ruta' => '');
$headerPdf;

class mipdf extends TCPDF {

    //Header personalizado
    public function Header() {
        $this->SetMargins(10, 20, 10, 10);
        $this->SetFont('helvetica', '', 8);
        $this->writeHTML($_SESSION['header'], true, false, true, false, '');
    }

    //footer personalizado
    public function Footer() {
        // posicion
        $this->SetY($_SESSION['setY']);
        // fuente
        $this->SetFont('helvetica', '', 7);
        // numero de pagina
        $this->writeHTML($_SESSION['footer'], true, false, true, false, '');
        //$this->Cell(0, 10,$_SESSION['footer'] /*'Página '.$this->getAliasNumPage().' de '.$this->getAliasNbPages()*/, 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }

}

try {
    //Margenes para el footer
    $_SESSION['setY'] = -65;
    
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
    $rutaLogo = "../imagenes/logo_llaber.png";
    $fondoTitulos = "#25006D";
    $fondoContenido = "#FFFFFF";
    $colorTexto = "#000000";
    $colorTextoTitulo = "#FFFFFF";

    //variables POST
    $idTransaccion = $_POST["idTransaccion"];
    $valorTotalPagar = ceiling($_POST["valorTotalPagar"], 50);
    $valorTotalPagado = ceiling($_POST["valorTotalPagado"], 50);
    $localStorage = $_POST["localStorage"];

    $transaccionE = new \entidad\Transaccion();
    $transaccionE->setIdTransaccion($idTransaccion);
    $transaccionM = new \modelo\Transaccion($transaccionE);
    $arrInfoEmpresa = $transaccionM->obtenerInfoEmpresaUsuario();
    $arrInfoFactura = $transaccionM->obtenerInfoFactura();
    $arrInfoCajero = $transaccionM->obtenerInfoCajero();
    $arrInfoCliente = $transaccionM->obtenerInfoCliente();

    $transaccionProductoE = new \entidad\TransaccionProducto();
    $transaccionProductoE->setTransaccion($transaccionE);
    $transaccionProductoM = new \modelo\TransaccionProducto($transaccionProductoE);
    $arrInfoProductos = $transaccionProductoM->consultarProductosFactura();
    $arrInfoImpuestos = $transaccionProductoM->consuTotalImpueFactu();

    $transaccionCruceE = new \entidad\TransaccionCruce();
    $transaccionCruceE->setIdTransaccionAfectado($idTransaccion);
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
    //iniciando un nuevo pdf
    $pdf = new mipdf(PDF_PAGE_ORIENTATION, 'mm', 'Letter', true, 'UTF-8', false);
    ob_clean();
    $pdf->SetFont('helvetica', '', 7, '', true);

    //Encabezado
    $html = "";
    $html .= '<div style="width:100%;">';
    $htmlHead .= '<table style="width:100%;">';
    $htmlHead .= '<tr><td>&nbsp;</td></tr>';
    $htmlHead .= '<tr>';
    $htmlHead .= '<td style="width:30%">';
    $htmlHead .= '<img src="' . $rutaLogo . '">';
    $htmlHead .= '</td>';
    $htmlHead .= '<td style="width:40%;text-align:center;">';
    $htmlHead .= $arrInfoEmpresa["empresa"];
    $htmlHead .= '<br>';
    $htmlHead .= 'NIT ' . $arrInfoEmpresa["nit"] . ' - ' . $arrInfoEmpresa["digitoVerificacion"];
    $htmlHead .= '<br>';
    $htmlHead .= $arrInfoEmpresa["direccion"] . ' / Tel. ' . $arrInfoEmpresa["telefono"];
    $htmlHead .= '</td>';
    $htmlHead .= '<td style="width:30%;">';
    $htmlHead .= '<table style="width:100%;">';
    $htmlHead .= '<tr>';

    if ($arrInfoCajero["prefijo"] != null && $arrInfoCajero["prefijo"] != '' && $arrInfoCajero["prefijo"] != 'null') {
        $htmlHead .= '<td><b>Factura ' . $arrInfoCajero["prefijo"] . ' - ' . str_pad($arrInfoFactura["numeroFactura"], 40, " ", STR_PAD_BOTH) . '</b></td>';
    } else {
        $htmlHead .= '<td><b>Factura ' . str_pad($arrInfoFactura["numeroFactura"], 40, " ", STR_PAD_BOTH) . '</b></td>';
    }

    $htmlHead .= '</tr>';
    $htmlHead .= '<tr>';
    $htmlHead .= '<td><b>Fecha:</b> ' . $arrMes[$mes] . ' ' . $dia . ' de ' . $anio . ' ' . $hora . ':' . $minuto . ':' . $segundo . ' ' . $ampm . '</td>';
    $htmlHead .= '</tr>';
    $htmlHead .= '<tr>';
    $htmlHead .= '<td><b>Cajero:</b> ' . $arrInfoCajero["cajero"] . '</td>';
    $htmlHead .= '</tr>';
    $htmlHead .= '<tr><td>&nbsp;</td></tr>';
    $htmlHead .= '</table>';
    $htmlHead .= '</td>';
    $htmlHead .= '</tr>';
    $htmlHead .= '</table>';

    $_SESSION['header'] = $htmlHead;
    $_SESSION['footer'] = $htmlFooter;
    $pdfClass = new mipdf(PDF_PAGE_ORIENTATION, 'mm', 'Letter', true, 'UTF-8', false);

    //Adiciona una página
    $pdf->AddPage();
    //Info cliente
    $html .= '<table style="width:100%;">';
    $html .= '<tr>';
    $html .= '<td style="width: 15%; background-color:' . $fondoTitulos . ';color:' . $colorTextoTitulo . ';border:0.7px #8000FF solid;"> CLIENTE </td>';
    $html .= '<td style="width: 55%; border:0.7px #E6E7E8 solid;"> ' . $arrInfoCliente["cliente"] . ' </td>';
    $html .= '<td style="width: 10%; background-color:' . $fondoTitulos . ';color:' . $colorTextoTitulo . ';border:0.7px #E6E7E8 solid;"> NIT </td>';
    $html .= '<td style="width: 20%; border:0.7px #E6E7E8 solid;"> ' . $arrInfoCliente["nit"] . ' </td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<td style="width: 15%; background-color:' . $fondoTitulos . ';color:' . $colorTextoTitulo . ';border:0.7px #8000FF solid;"> DIRECCIÓN </td>';
    $html .= '<td style="width: 55%; border:0.7px #E6E7E8 solid;"> ' . $arrInfoCliente["direccion"] . ' </td>';
    $html .= '<td style="width: 10%; background-color:' . $fondoTitulos . ';color:' . $colorTextoTitulo . ';border:0.7px #E6E7E8 solid;"> TELÉFONO </td>';
    $html .= '<td style="width: 20%; border:0.7px #E6E7E8 solid;"> ' . $arrInfoCliente["telefono"] . ' </td>';
    $html .= '</tr>';
    $html .= '</table>';

    //Productos
    //$html .= '<tr><td colspan="5">&nbsp;</td></tr>';
    $html .= '<br>';
    $html .= '<br>';
    $html .= '<div>';
    $html .= '<table style="width: 100%">';
    $html .= '<tr>';
    $html .= '<td style="width: 5%; background-color:' . $fondoTitulos . ';color:' . $colorTextoTitulo . '; text-align:center;border:0.7px #E6E7E8 solid;"> COD. </td>';
    $html .= '<td style="width: 40%; background-color:' . $fondoTitulos . ';color:' . $colorTextoTitulo . '; text-align:center;border:0.7px #E6E7E8 solid;"> PRODUCTO </td>';
    $html .= '<td style="width: 30%; background-color:' . $fondoTitulos . ';color:' . $colorTextoTitulo . '; text-align:center;border:0.7px #E6E7E8 solid;"> NOTA </td>';
    $html .= '<td style="width: 8%; background-color:' . $fondoTitulos . ';color:' . $colorTextoTitulo . '; text-align:center;border:0.7px #E6E7E8 solid;"> CANT. </td>';
    $html .= '<td style="width: 10%; background-color:' . $fondoTitulos . ';color:' . $colorTextoTitulo . '; text-align:center;border:0.7px #E6E7E8 solid;"> VALOR </td>';
    $html .= '<td style="width: 7%; background-color:' . $fondoTitulos . ';color:' . $colorTextoTitulo . '; text-align:center;border:0.7px #E6E7E8 solid;"> IVA </td>';
    $html .= '</tr>';

    $contador = 0;
    while ($contador < count($arrInfoProductos)) {

        $html .= '<tr>';
        $html .= '<td style="background-color:' . $fondoContenido . ';border:0.7px #E6E7E8 solid;text-align:center;">' . $arrInfoProductos[$contador]["codigo"] . '</td>';
        $html .= '<td style="background-color:' . $fondoContenido . ';border:0.7px #E6E7E8 solid;">' . $arrInfoProductos[$contador]["producto"] . '</td>';
        if ($arrInfoProductos[$contador]["nota"] != null && $arrInfoProductos[$contador]["nota"] != 'null' && $arrInfoProductos[$contador]["nota"] != '') {
            $html .= '<td style="background-color:' . $fondoContenido . ';border:0.7px #E6E7E8 solid;">' . $arrInfoProductos[$contador]["nota"] . '</td>';
        } else {
            $html .= '<td style="background-color:' . $fondoContenido . ';border:0.7px #E6E7E8 solid;"> </td>';
        }
        $html .= '<td style="background-color:' . $fondoContenido . ';border:0.7px #E6E7E8 solid;text-align:right;">' . $arrInfoProductos[$contador]["cantidad"] . '</td>';
        $html .= '<td style="background-color:' . $fondoContenido . ';border:0.7px #E6E7E8 solid;text-align:right;">' . number_format($arrInfoProductos[$contador]["valorUnitaSalidConImpue"], 0, '', '.') . '</td>';
        if ($arrInfoProductos[$contador]['valorImpuesto'] != null && $arrInfoProductos[$contador]['valorImpuesto'] != 'null' && $arrInfoProductos[$contador]['valorImpuesto'] != '') {
            $html .= '<td style="background-color:' . $fondoContenido . ';border:0.7px #E6E7E8 solid;text-align:center;">' . $arrInfoProductos[$contador]['valorImpuesto'] . $arrInfoProductos[$contador]["simbolo"] . '</td>';
        } else {
            $html .= '<td style="background-color:' . $fondoContenido . ';border:0.7px #E6E7E8 solid;text-align:center;">0</td>';
        }
        $html .= '</tr>';

        $contador++;
    }
    $html .= '</table>';
    $htmlFooter .= '</div>';
    
    //Letra del total a pagar
    $letraTotalPagar = obtenerTextoNumero($valorTotalPagar);

    $htmlFooter .= '<table>';
    $htmlFooter .= '<tr>';
    $htmlFooter .= '<td style="background-color:' . $fondoTitulos . ';color:' . $colorTextoTitulo . '; text-align:center;border:0.7px #E6E7E8 solid;"> IMPUESTO </td>';
    $htmlFooter .= '<td style="background-color:' . $fondoTitulos . ';color:' . $colorTextoTitulo . '; text-align:center;border:0.7px #E6E7E8 solid;"> VR. BASE </td>';
    $htmlFooter .= '<td style="background-color:' . $fondoTitulos . ';color:' . $colorTextoTitulo . '; text-align:center;border:0.7px #E6E7E8 solid;"> VR. IMPTO </td>';
    $htmlFooter .= '</tr>';

    $contador = 0;
    while ($contador < count($arrInfoImpuestos)) {

        $htmlFooter.='<tr>';
        $htmlFooter .= '<td style="background-color:' . $fondoContenido . ';border:0.7px #E6E7E8 solid;">' . $arrInfoImpuestos[$contador]["impuesto"] . '</td>';
        $htmlFooter .= '<td style="background-color:' . $fondoContenido . ';border:0.7px #E6E7E8 solid;text-align:right;">' . number_format($arrInfoImpuestos[$contador]["totalBase"], 0, '', '.') . '</td>';
        $htmlFooter .= '<td style="background-color:' . $fondoContenido . ';border:0.7px #E6E7E8 solid;text-align:right;">' . number_format($arrInfoImpuestos[$contador]["totalImpuesto"], 0, '', '.') . '</td>';
        $htmlFooter .='</tr>';

        $contador++;
        $_SESSION['setY'] = (int)$_SESSION['setY']-2;
    }
    $htmlFooter .= '</table>';
    
    $htmlFooter .= '<br>';
    $htmlFooter .= '<br>';
    $htmlFooter .= '<hr style="height: .7px;">';
    $htmlFooter .= '<table style="width:100%;border-collapse: collapse;">';
    $htmlFooter .= '<tr>';
    $htmlFooter .= '<td style="width:80%"><b>SON </b> ' . $letraTotalPagar . ' PESOS MCTE </td>';
    $htmlFooter .= '<td style="text-align:right;width:10%"> <b>TOTAL</b></td>';
    $htmlFooter .= '<td style="text-align:right;width:10%"> $' . number_format($valorTotalPagar, 0, '', '.') . '</td>';
    $htmlFooter .= '</tr>';
    $htmlFooter .= '</table>';

    //Resolución DIAN
    $htmlFooter .= '<br><br>';
    $htmlFooter .= '<table style="width:100%;border-collapse: collapse;">';
    $htmlFooter .= '<tr>';

    $htmlFooter .= '<td>';
    $htmlFooter .= '<table style="width:100%;border-collapse: collapse;">';
    $htmlFooter .= '<tr>';
    $htmlFooter .= '<td style="text-align:center;width:100%;">REGIMEN ' . strtoupper($arrInfoEmpresa["tipoRegimen"]) . '</td>';
    $htmlFooter .= '</tr>';
    $htmlFooter .= '<tr>';
    $htmlFooter .= '<td style="text-align:center;width:100%;">RESOLUCIÓN DIAN: ' . $arrInfoCajero["numeroResolucion"] . '</td>';
    $htmlFooter .= '</tr>';
    $htmlFooter .= '<tr>';
    $htmlFooter .= '<td style="text-align:center;width:100%;">FECHA DE RESOLUCIÓN: ' . $arrInfoCajero["fechaExpedicionResolucion"] . '</td>';
    $htmlFooter .= '</tr>';
    $htmlFooter .= '<tr>';

    if ($arrInfoCajero["prefijo"] != null && $arrInfoCajero["prefijo"] != '' && $arrInfoCajero["prefijo"] != 'null') {
        $htmlFooter .= '<td style="text-align:center;width:100%;">PREFIJO: ' . $arrInfoCajero["prefijo"] . ' DESDE ' . $arrInfoCajero["numeroMinimo"] . ' HASTA ' . $arrInfoCajero["numeroMaximo"] . '</td>';
    } else {
        $htmlFooter .= '<td style="text-align:center;width:100%;">DESDE 00' . $arrInfoCajero["numeroMinimo"] . ' HASTA ' . $arrInfoCajero["numeroMaximo"] . '</td>';
    }

    $htmlFooter .= '</tr>';
    $htmlFooter .= '</table>';
    $htmlFooter .= '</td>';

    $htmlFooter .= '<td>';
    $htmlFooter .= '<table style="width:100%;border-collapse: collapse;">';
    $htmlFooter .= '<tr>';
    $htmlFooter .= '<td style="text-align:left;width:100%;">FIRMA Y SELLO DEL CLIENTE</td>';
    $htmlFooter .= '</tr>';
    $htmlFooter .= '<tr><td>&nbsp;</td></tr>';
    $htmlFooter .= '<tr>';
    $htmlFooter .= '<td style="text-align:left;width:100%;">________________________________________</td>';
    $htmlFooter .= '</tr>';
    $htmlFooter .= '<tr><td>&nbsp;</td></tr>';
    $htmlFooter .= '<tr>';
    $htmlFooter .= '<td style="text-align:left;width:100%;">C.C. ________________</td>';
    $htmlFooter .= '</tr>';
    $htmlFooter .= '</table>';
    $htmlFooter .= '</td>';

    $htmlFooter .= '<td>';
    $htmlFooter .= '<table style="width:100%;border-collapse: collapse;">';
    $htmlFooter .= '<tr>';
    $htmlFooter .= '<td style="text-align:left;width:100%;">FIRMA Y SELLO DEL VENDEDOR</td>';
    $htmlFooter .= '</tr>';
    $htmlFooter .= '<tr><td>&nbsp;</td></tr>';
    $htmlFooter .= '<tr>';
    $htmlFooter .= '<td style="text-align:left;width:100%;">________________________________________</td>';
    $htmlFooter .= '</tr>';
    $htmlFooter .= '<tr><td>&nbsp;</td></tr>';
    $htmlFooter .= '<tr>';
    $htmlFooter .= '<td style="text-align:left;width:100%;">C.C. ________________</td>';
    $htmlFooter .= '</tr>';
    $htmlFooter .= '</table>';
    $htmlFooter .= '</td>';

    $htmlFooter .= '</tr>';
    $htmlFooter .= '</table>';


    //Nombre software
    $htmlFooter .= '<br>';
    $htmlFooter .= '<br>';
    $htmlFooter .= '<hr style="height: .3px;">';
    $htmlFooter .= '<table style="width:100%;border-collapse: collapse;">';
    //$htmlFooter .= '<td style="text-align:center;width:100%;">'. obtenerNombreSoftware() .'</td>';
    $htmlFooter .= '<tr>';
    $htmlFooter .= '<td style="width:50%;"> Desarrollado por: </td>';
    $htmlFooter .= '<td colspan="7"> Nuestros productos: </td>';
    $htmlFooter .= '</tr>';
    $htmlFooter .= '<tr>';
    $htmlFooter .= '<td valign="middle" style="width:50%;"><img style="width:1500%; height:380%;" src="../imagenes/logo_iss_2.png"></td>';
    $htmlFooter .= '<td style="width:7%;">';
    $htmlFooter .= '<img style="width:400%;" src="../imagenes/productos/Horus.png">';
    $htmlFooter .= '</td>';
    $htmlFooter .= '<td style="width:7%;">';
    $htmlFooter .= '<img style="width:400%;" src="../imagenes/productos/Faiho.png">';
    $htmlFooter .= '</td>';
    $htmlFooter .= '<td style="width:7%;">';
    $htmlFooter .= '<img style="width:400%;" src="../imagenes/productos/LawyersCases.png">';
    $htmlFooter .= '</td>';
    $htmlFooter .= '<td style="width:7%;">';
    $htmlFooter .= '<img style="width:500%;" src="../imagenes/productos/LogoFastServices.png">';
    $htmlFooter .= '</td>';
    $htmlFooter .= '<td style="width:7%;">';
    $htmlFooter .= '<img style="width:450%;height: 400%;" src="../imagenes/productos/SendMessage.png">';
    $htmlFooter .= '</td>';
    $htmlFooter .= '<td style="width:7%;">';
    $htmlFooter .= '<img style="width:400%;height: 400%;" src="../imagenes/productos/LogoR.png">';
    $htmlFooter .= '</td>';
    $htmlFooter .= '<td style="width:7%;">';
    $htmlFooter .= '<img style="width:400%;" src="../imagenes/productos/AllParking.png">';
    $htmlFooter .= '</td>';
    $htmlFooter .= '</tr>';
    $htmlFooter .= '</table>';
    $htmlFooter .= '<table style="width:100%;">';
    $htmlFooter .= '<tr>';
    $htmlFooter .= '<td style="width:25%;"></td>';
    $htmlFooter .= '<td style="width:50%;">';
    $htmlFooter .= '<table>';
    $htmlFooter .= '<tr>';
    $htmlFooter .= '<td><img style="width:200%;" src="../imagenes/RedesSociales/Facebook.png">ingesoftwareTIC</td>';
    $htmlFooter .= '<td><img style="width:200%;" src="../imagenes/RedesSociales/GooglePlus.png">ingesoftwareTIC</td>';
    $htmlFooter .= '<td><img style="width:200%;" src="../imagenes/RedesSociales/Twitter.png">@ingesoftwareTIC</td>';
    $htmlFooter .= '</tr>';
    $htmlFooter .= '</table>';
    $htmlFooter .= '</td>';
    $htmlFooter .= '<td style="width:25%;"></td>';
    $htmlFooter .= '</tr>';
    $htmlFooter .= '</table>';

    $html .= '</div>';

    $_SESSION['header'] = $htmlHead;
    $_SESSION['footer'] = $htmlFooter;
    $pdfClass = new mipdf(PDF_PAGE_ORIENTATION, 'mm', 'Letter', true, 'UTF-8', false);

    //Guardamos el archivo
    $pdf->writeHTML($html, true, false, true, false, '');
    //$pdf->AddPage();
    $pdf->Output($retorno["ruta"], 'F');
} catch (Exception $ex) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $ex->getMessage();
}
echo json_encode($retorno);
?>