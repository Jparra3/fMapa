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
class mipdf extends TCPDF{  
  //Header personalizado
  public function Header() {
    $this->SetMargins(10, 20, 40, 10);
    $this->SetFont('helvetica', "", 8);
    $this->writeHTML($_SESSION['header'], true, false, true, false, '');
  }
  
  //footer personalizado
  public function Footer() {
    // posicion
    $this->SetY(-30);
    // fuente
    $this->SetFont('helvetica', 'I', 8);
    // numero de pagina
    $this->writeHTML($_SESSION['footer'], true, false, true, false, '');
    //$this->Cell(0, 10,$_SESSION['footer'] /*'Página '.$this->getAliasNumPage().' de '.$this->getAliasNbPages()*/, 0, false, 'C', 0, '', 0, false, 'T', 'M');
  }
}

//class ObtenerValor{
//    public static $html;
//}

try {
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
    $rutaLogo = "../imagenes/logo.png";
    $fondoTitulos = "#25006D";
    $fondoContenido = "#FFFFFF";
    $colorTexto = "#000000";
    $colorTextoTitulo = "#FFFFFF";

    //variables POST
    $idTransaccion = $_POST["idTransaccion"];
    $idTipoDocumento = $_POST["idTipoDocumento"];
    $idProveedor = $_POST["idProveedor"];
    $idOficina = $_POST["idOficina"];
    
    $localStorage = $_POST["localStorage"];

    $transaccionE = new \entidad\Transaccion();
    $transaccionE->setIdTransaccion($idTransaccion);
    $transaccionM = new \modelo\Transaccion($transaccionE);
    $arrInfoEmpresa = $transaccionM->obtenerInfoEmpresaUsuario();
    $parametros = array("idProveedor"=>$idProveedor);
    $arrInfoTransaccion = $transaccionM->consultarDetalleTransaccion($parametros);
//    $arrInfoCajero = $transaccionM->obtenerInfoCajero();
//    $arrInfoCliente = $transaccionM->obtenerInfoCliente();

    $transaccionProductoE = new \entidad\TransaccionProducto();
    $transaccionProductoE->setTransaccion($transaccionE);
    $transaccionProductoM = new \modelo\TransaccionProducto($transaccionProductoE);
    $arrInfoProductos = $transaccionProductoM->consultarProductosFactura();
    $arrInfoImpuestos = $transaccionProductoM->consuTotalImpueFactu();

//    $transaccionCruceE = new \entidad\TransaccionCruce();
//    $transaccionCruceE->setIdTransaccionAfectado($idTransaccion);
//    $transaccionCruceM = new \modelo\TransaccionCruce($transaccionCruceE);
//    $arrInfoFormasPago = $transaccionCruceM->consultarFormasPagoFactura();

    //ruta
    $retorno["ruta"] = "../../archivos" . $localStorage . "reciboInventario/";

    //Creamos la ruta si no existe
    if (!file_exists($retorno["ruta"])) {
        mkdir($retorno["ruta"], 0777, true);
    }

    $retorno["ruta"] .= "reciboInventario_" . $idTransaccion . ".pdf";
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
    $htmlHead .= '<td style="width:20%">';
    $htmlHead .= '<img src="'.$rutaLogo.'">';
    $htmlHead .= '</td>';
    $htmlHead .= '<td style="width:70%;text-align:center;">';
    $htmlHead .= $arrInfoEmpresa["empresa"];
    $htmlHead .= '<br>';
    $htmlHead .= 'NIT ' . $arrInfoEmpresa["nit"] . ' - ' . $arrInfoEmpresa["digitoVerificacion"];
//    $htmlHead .= '<br>';
//    $htmlHead .= $arrInfoEmpresa["direccion"] . ' / Tel. ' . $arrInfoEmpresa["telefono"];
    $htmlHead .= '</td>';
    $htmlHead .= '<td style="width:30%;">';
//    $htmlHead .= '<table style="width:100%;">';
//    $htmlHead .= '<tr>';
//    
//    if($arrInfoCajero["prefijo"] != null && $arrInfoCajero["prefijo"] != '' && $arrInfoCajero["prefijo"] != 'null'){
//        $htmlHead .= '<td><b>Factura ' . $arrInfoCajero["prefijo"] . ' - ' . str_pad($arrInfoFactura["numeroFactura"], 40, " ", STR_PAD_BOTH) . '</b></td>';
//    }else{
//        $htmlHead .= '<td><b>Factura ' . str_pad($arrInfoFactura["numeroFactura"], 40, " ", STR_PAD_BOTH) . '</b></td>';
//    }
//    
//    $htmlHead .= '</tr>';
//    $htmlHead .= '<tr>';
      $htmlHead .= '<b>Fecha:</b> ' . $arrMes[$mes] . ' ' . $dia . ' de ' . $anio . ' ' . $hora . ':' . $minuto . ':' . $segundo . ' ' . $ampm . '';
//    $htmlHead .= '</tr>';
//    $htmlHead .= '<tr>';
//    $htmlHead .= '<td><b>Cajero:</b> ' . $arrInfoCajero["cajero"] . '</td>';
//    $htmlHead .= '</tr>';
//    $htmlHead .= '<tr><td>&nbsp;</td></tr>';
//    $htmlHead .= '</table>';
    $htmlHead .= '</td>';
    $htmlHead .= '</tr>';
    $htmlHead .= '</table>';

    $_SESSION['header'] = $htmlHead;
    $_SESSION['footer'] = $htmlFooter;
    $pdfClass = new mipdf(PDF_PAGE_ORIENTATION, 'mm', 'Letter', true, 'UTF-8', false);
    
    //Adiciona una página
    $pdf->AddPage();
    $html .= '<br>';
    $html .= '<br>';
    //Info cliente
    $html .= '<table style="width:100%;">';
    $html .= '<tr>';
    $html .= '<td style="width: 15%; background-color:'.$fondoTitulos.';color:'.$colorTextoTitulo.';border:0.7px #8000FF solid;"> PROVEEDOR </td>';
    $html .= '<td style="width: 55%; border:0.7px #E6E7E8 solid;"> ' . $arrInfoTransaccion[0]["nombreProveedor"] . ' </td>';
    $html .= '<td style="width: 10%; background-color:'.$fondoTitulos.';color:'.$colorTextoTitulo.';border:0.7px #E6E7E8 solid;"> NIT </td>';
    $html .= '<td style="width: 20%; border:0.7px #E6E7E8 solid;"> ' . $arrInfoTransaccion[0]["nitProveedor"] . ' </td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<td style="width: 15%; background-color:'.$fondoTitulos.';color:'.$colorTextoTitulo.';border:0.7px #8000FF solid;"> OFICINA </td>';
    $html .= '<td style="width: 55%; border:0.7px #E6E7E8 solid;"> ' . $arrInfoTransaccion[0]["oficina"] . ' </td>';
    $html .= '<td style="width: 10%; background-color:'.$fondoTitulos.';color:'.$colorTextoTitulo.';border:0.7px #E6E7E8 solid;"> TIPO DOCUMENTO </td>';
    $html .= '<td style="width: 20%; border:0.7px #E6E7E8 solid;"> ' . $arrInfoTransaccion[0]["tipoDocumento"] . ' </td>';
    $html .= '</tr>';

    //Productos
    $html .= '<tr><td colspan="5">&nbsp;</td></tr>';
    $html .= '<tr>';
    $html .= '<td style="width: 10%; background-color:'.$fondoTitulos.';color:'.$colorTextoTitulo.'; text-align:center;border:0.7px #E6E7E8 solid;"> COD. </td>';
    $html .= '<td style="width: 55%; background-color:'.$fondoTitulos.';color:'.$colorTextoTitulo.'; text-align:center;border:0.7px #E6E7E8 solid;"> PRODUCTO </td>';
    $html .= '<td style="width: 8%; background-color:'.$fondoTitulos.';color:'.$colorTextoTitulo.'; text-align:center;border:0.7px #E6E7E8 solid;"> CANT. </td>';
    $html .= '<td style="width: 15%; background-color:'.$fondoTitulos.';color:'.$colorTextoTitulo.'; text-align:center;border:0.7px #E6E7E8 solid;"> VALOR </td>';
    $html .= '<td style="width: 12%; background-color:'.$fondoTitulos.';color:'.$colorTextoTitulo.'; text-align:center;border:0.7px #E6E7E8 solid;"> SUBTOTAL </td>';
    $html .= '</tr>';

    $contador = 0;
    while ($contador < count($arrInfoProductos)) {

        $html .= '<tr>';
        $html .= '<td style="background-color:'.$fondoContenido.';border:0.7px #E6E7E8 solid;text-align:center;">' . $arrInfoProductos[$contador]["codigo"] . '</td>';
        $html .= '<td style="background-color:'.$fondoContenido.';border:0.7px #E6E7E8 solid;">' . $arrInfoProductos[$contador]["producto"] . '</td>';
        $html .= '<td style="background-color:'.$fondoContenido.';border:0.7px #E6E7E8 solid;text-align:right;">' . $arrInfoProductos[$contador]["cantidad"] . '</td>';
        $html .= '<td style="background-color:'.$fondoContenido.';border:0.7px #E6E7E8 solid;text-align:right;">' . number_format($arrInfoProductos[$contador]["valorUnitaSalidConImpue"], 0, '', '.') . '</td>';
        $subTotal = (int)$arrInfoProductos[$contador]["cantidad"] * floatval($arrInfoProductos[$contador]["valorUnitaSalidConImpue"]);
        $html .= '<td style="background-color:'.$fondoContenido.';border:0.7px #E6E7E8 solid;text-align:center;">' . number_format($subTotal, 0, '', '.') . '</td>';
        $html .= '</tr>';
        
        $valorTotalPagar += floatval($subTotal);
        $contador++;
    }

	
	
    $html .= '<tr><td colspan="5">&nbsp;</td></tr>';
    
    
    //$html .= '<tr>';
    
    //Totales
    //$html .= '<td style="width:25%">';
    /*$html .= '<table>';
    $html .= '<tr>';
    $html .= '<td style="width: 50%; background-color:'.$fondoTitulos.';color:'.$colorTextoTitulo.'; border:0.7px #E6E7E8 solid;"> TOTAL PAGADO </td>';
    $html .= '<td style="width: 50%; background-color:'.$fondoContenido.';border:0.7px #E6E7E8 solid;text-align:right;">' . number_format($valorTotalPagado, 0, '', '.') . '</td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<td style="width: 50%; background-color:'.$fondoTitulos.';color:'.$colorTextoTitulo.'; border:0.7px #E6E7E8 solid;"> TOTAL A PAGAR </td>';
    $html .= '<td style="width: 50%; background-color:'.$fondoContenido.';border:0.7px #E6E7E8 solid;text-align:right;">' . number_format($valorTotalPagar, 0, '', '.') . '</td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<td style="width: 50%; background-color:'.$fondoTitulos.';color:'.$colorTextoTitulo.'; border:0.7px #E6E7E8 solid;"> CAMBIO </td>';
    $html .= '<td style="width: 50%; background-color:'.$fondoContenido.';border:0.7px #E6E7E8 solid;text-align:right;">' . number_format(($valorTotalPagado - $valorTotalPagar), 0, '', '.') . '</td>';
    $html .= '</tr>';
    $html .= '</table>';*/
    //$html .= '</td>';
    
    //Impuestos
    //$html .= '<td style="width:0%" colspan="3">';
//    $html .= '<table>';
//    $html .= '<tr>';
//    $html .= '<td style="width: 10%; background-color:'.$fondoTitulos.';color:'.$colorTextoTitulo.'; text-align:center;border:0.7px #E6E7E8 solid;"> SIMB. </td>';
//    $html .= '<td style="width: 30%; background-color:'.$fondoTitulos.';color:'.$colorTextoTitulo.'; text-align:center;border:0.7px #E6E7E8 solid;"> IMPUESTO </td>';
//    $html .= '<td style="width: 30%; background-color:'.$fondoTitulos.';color:'.$colorTextoTitulo.'; text-align:center;border:0.7px #E6E7E8 solid;"> VR. BASE </td>';
//    $html .= '<td style="width: 30%; background-color:'.$fondoTitulos.';color:'.$colorTextoTitulo.'; text-align:center;border:0.7px #E6E7E8 solid;"> VR. IMPTO </td>';
//    $html .= '</tr>';
//
//    $contador = 0;
//    while ($contador < count($arrInfoImpuestos)) {
//
//        $html.='<tr>';
//        $html .= '<td style="background-color:'.$fondoContenido.';border:0.7px #E6E7E8 solid;text-align:center;">' . $arrInfoImpuestos[$contador]["simbolo"] . '</td>';
//        $html .= '<td style="background-color:'.$fondoContenido.';border:0.7px #E6E7E8 solid;">' . $arrInfoImpuestos[$contador]["impuesto"] . '</td>';
//        $html .= '<td style="background-color:'.$fondoContenido.';border:0.7px #E6E7E8 solid;text-align:right;">' . number_format($arrInfoImpuestos[$contador]["totalBase"], 0, '', '.') . '</td>';
//        $html .= '<td style="background-color:'.$fondoContenido.';border:0.7px #E6E7E8 solid;text-align:right;">' . number_format($arrInfoImpuestos[$contador]["totalImpuesto"], 0, '', '.') . '</td>';
//        $html .='</tr>';
//
//        $contador++;
//    }
//    $html .= '</table>';
    //$html .= '</td>';
    
    //Formas de pago 
    //$html .= '<td style="width:25%">';
    /*$html .= '<table>';
    $html .= '<tr>';
    $html .= '<td style="width: 50%; background-color:'.$fondoTitulos.';color:'.$colorTextoTitulo.'; text-align:center;border:0.7px #E6E7E8 solid;"> TIPO PAGO </td>';
    $html .= '<td style="width: 50%; background-color:'.$fondoTitulos.';color:'.$colorTextoTitulo.'; text-align:center;border:0.7px #E6E7E8 solid;"> VALOR </td>';
    $html .= '</tr>';

    $contador = 0;
    while ($contador < count($arrInfoFormasPago)) {
        $html.='<tr>';
        $html .= '<td style="background-color:'.$fondoContenido.';border:0.7px #E6E7E8 solid;text-align:center;text-align:left;">' . $arrInfoFormasPago[$contador]["formaPago"] . '</td>';
        $html .= '<td style="background-color:'.$fondoContenido.';border:0.7px #E6E7E8 solid;text-align:center;text-align:right;">' . number_format(ceiling($arrInfoFormasPago[$contador]["valor"], 50), 0, '', '.') . '</td>';
        $contador++;
        $html.='</tr>';
    }
    $html .= '</table>';*/
    //$html .= '</td>';
    
    //$html .= '</tr>';   

	
	
    //Letra del total a pagar
    $letraTotalPagar = obtenerTextoNumero($valorTotalPagar);
    $html .= '</table>';
    $html .= '<br>';
    $html .= '<br>';
    $html .= '<hr style="height: .7px;">';
    $html .= '<table style="width:100%;border-collapse: collapse;">';
    $html .= '<tr>';
    $html .= '<td style="width:80%"><b>SON </b> ' . $letraTotalPagar . ' PESOS MCTE </td>';
    $html .= '<td style="text-align:right;width:10%"> <b>TOTAL</b></td>';
    $html .= '<td style="text-align:right;width:10%"> $' . number_format($valorTotalPagar, 0, '', '.') . '</td>';
    $html .= '</tr>';
    $html .= '</table>';

//    //Resolución DIAN
//    $html .= '<br><br>';
//    $html .= '<table style="width:100%;border-collapse: collapse;">';
//    $html .= '<tr>';
//    
//    $html .= '<td>';
////    $html .= '<table style="width:100%;border-collapse: collapse;">';
////    $html .= '<tr>';
////    $html .= '<td style="text-align:center;width:100%;">REGIMEN '. strtoupper($arrInfoEmpresa["tipoRegimen"]) .'</td>';
////    $html .= '</tr>';
////    $html .= '<tr>';
////    $html .= '<td style="text-align:center;width:100%;">RESOLUCIÓN DIAN: ' . $arrInfoCajero["numeroResolucion"] . '</td>';
////    $html .= '</tr>';
////    $html .= '<tr>';
////    $html .= '<td style="text-align:center;width:100%;">FECHA DE RESOLUCIÓN: ' . $arrInfoCajero["fechaExpedicionResolucion"] . '</td>';
////    $html .= '</tr>';
////    $html .= '<tr>';
////	
////	if($arrInfoCajero["prefijo"] != null && $arrInfoCajero["prefijo"] != '' && $arrInfoCajero["prefijo"] != 'null'){
////		$html .= '<td style="text-align:center;width:100%;">PREFIJO: ' . $arrInfoCajero["prefijo"] . ' DESDE ' . $arrInfoCajero["numeroMinimo"] . ' HASTA ' . $arrInfoCajero["numeroMaximo"] . '</td>';
////	}else{
////		$html .= '<td style="text-align:center;width:100%;">DESDE 00' . $arrInfoCajero["numeroMinimo"] . ' HASTA ' . $arrInfoCajero["numeroMaximo"] . '</td>';
////	}
////    
////    $html .= '</tr>';
////    $html .= '</table>';
//    $html .= '</td>';
//    
//    $html .= '<td>';
////    $html .= '<table style="width:100%;border-collapse: collapse;">';
////    $html .= '<tr>';
////    $html .= '<td style="text-align:left;width:100%;">FIRMA Y SELLO DEL CLIENTE</td>';
////    $html .= '</tr>';
////    $html .= '<tr><td>&nbsp;</td></tr>';
////    $html .= '<tr>';
////    $html .= '<td style="text-align:left;width:100%;">________________________________________</td>';
////    $html .= '</tr>';
////    $html .= '<tr><td>&nbsp;</td></tr>';
////    $html .= '<tr>';
////    $html .= '<td style="text-align:left;width:100%;">C.C. ________________</td>';
////    $html .= '</tr>';
////    $html .= '</table>';
//    $html .= '</td>';
//    
//    $html .= '<td>';
////    $html .= '<table style="width:100%;border-collapse: collapse;">';
////    $html .= '<tr>';
////    $html .= '<td style="text-align:left;width:100%;">FIRMA Y SELLO DEL VENDEDOR</td>';
////    $html .= '</tr>';
////    $html .= '<tr><td>&nbsp;</td></tr>';
////    $html .= '<tr>';
////    $html .= '<td style="text-align:left;width:100%;">________________________________________</td>';
////    $html .= '</tr>';
////    $html .= '<tr><td>&nbsp;</td></tr>';
////    $html .= '<tr>';
////    $html .= '<td style="text-align:left;width:100%;">C.C. ________________</td>';
////    $html .= '</tr>';
////    $html .= '</table>';
//    $html .= '</td>';
//    
//    $html .= '</tr>';
//    $html .= '</table>';
    

//    //Nombre software
//    $html .= '<br>';
//    $html .= '<br>';
//    $htmlFooter .= '<hr style="height: .3px;">';
//    $htmlFooter .= '<table style="width:100%;border-collapse: collapse;">';
//    //$htmlFooter .= '<td style="text-align:center;width:100%;">'. obtenerNombreSoftware() .'</td>';
//    $htmlFooter .= '<tr>';
//    $htmlFooter .= '<td style="width:50%;"> Desarrollado por: </td>';
//    $htmlFooter .= '<td colspan="7"> Nuestros productos: </td>';
//    $htmlFooter .= '</tr>';
//    $htmlFooter .= '<tr>';
//    $htmlFooter .= '<td valign="middle" style="width:50%;"><img style="width:1500%; height:380%;" src="../imagenes/logo_iss_2.png"></td>';
//    $htmlFooter .= '<td style="width:7%;">';
//    $htmlFooter .= '<img style="width:400%;" src="../imagenes/productos/Horus.png">';
//    $htmlFooter .= '</td>';
//    $htmlFooter .= '<td style="width:7%;">';
//    $htmlFooter .= '<img style="width:400%;" src="../imagenes/productos/Faiho.png">';
//    $htmlFooter .= '</td>';
//    $htmlFooter .= '<td style="width:7%;">';
//    $htmlFooter .= '<img style="width:400%;" src="../imagenes/productos/LawyersCases.png">';
//    $htmlFooter .= '</td>';
//    $htmlFooter .= '<td style="width:7%;">';
//    $htmlFooter .= '<img style="width:500%;" src="../imagenes/productos/LogoFastServices.png">';
//    $htmlFooter .= '</td>';
//    $htmlFooter .= '<td style="width:7%;">';
//    $htmlFooter .= '<img style="width:450%;height: 400%;" src="../imagenes/productos/SendMessage.png">';
//    $htmlFooter .= '</td>';
//    $htmlFooter .= '<td style="width:7%;">';
//    $htmlFooter .= '<img style="width:400%;height: 400%;" src="../imagenes/productos/LogoR.png">';
//    $htmlFooter .= '</td>';
//    $htmlFooter .= '<td style="width:7%;">';
//    $htmlFooter .= '<img style="width:400%;" src="../imagenes/productos/AllParking.png">';
//    $htmlFooter .= '</td>';
//    $htmlFooter .= '</tr>';
//    $htmlFooter .= '</table>';
//    $htmlFooter .= '<table style="width:100%;">';
//    $htmlFooter .= '<tr>';
//    $htmlFooter .= '<td style="width:25%;"></td>';
//    $htmlFooter .= '<td style="width:50%;">';
//    $htmlFooter .= '<table>';
//    $htmlFooter .= '<tr>';
//    $htmlFooter .= '<td><img style="width:200%;" src="../imagenes/RedesSociales/Facebook.png">ingesoftwareTIC</td>';
//    $htmlFooter .= '<td><img style="width:200%;" src="../imagenes/RedesSociales/GooglePlus.png">ingesoftwareTIC</td>';
//    $htmlFooter .= '<td><img style="width:200%;" src="../imagenes/RedesSociales/Twitter.png">@ingesoftwareTIC</td>';
//    $htmlFooter .= '</tr>';
//    $htmlFooter .= '</table>';
//    $htmlFooter .= '</td>';
//    $htmlFooter .= '<td style="width:25%;"></td>';
//    $htmlFooter .= '</tr>';
//    $htmlFooter .= '</table>';  

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

