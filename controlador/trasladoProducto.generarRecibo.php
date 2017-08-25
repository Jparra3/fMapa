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
//require_once '../comunes/funciones.php';
require_once '../entidad/UsuarioTipoDocumento.php';
require_once '../modelo/UsuarioTipoDocumento.php';

$retorno = array('exito' => 1, 'mensaje' => '', 'ruta' => '');
$headerPdf;

class mipdf extends TCPDF {

    //Header personalizado
    public function Header() {
        $this->SetMargins(20, 27, 10, 10);
        $this->SetFont('helvetica', '', 8);
        $this->writeHTML($_SESSION['header'], true, false, true, false, '');
    }

    //footer personalizado
    public function Footer() {
        // posicion
        $this->SetY(-70);
        // fuente
        $this->SetFont('helvetica', '', 7);
        // numero de pagina
        $this->writeHTML($_SESSION['footer'], true, false, true, false, '');
        //$this->Cell(0, 10,$_SESSION['footer'] /*'Página '.$this->getAliasNumPage().' de '.$this->getAliasNbPages()*/, 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }

}

class ObtenerValor {

    public static $html;

}

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
    $idUsuario = $_SESSION['idUsuario'];

    $usuarioTipoDocumentoE = new \entidad\UsuarioTipoDocumento();
    $usuarioTipoDocumentoE ->setIdUsuario($idUsuario);
    
    $usuarioTipoDocumentoM = new \modelo\UsuarioTipoDocumento($usuarioTipoDocumentoE);
    $arrInformacionUsuario = $usuarioTipoDocumentoM->consultarInformacionUsuario();
    
    $localStorage = $_POST["localStorage"];
    
    $idTransaccion[0] = $_POST["idTransaccionEntrada"];//Entrada
    $idTransaccion[1] = $_POST["idTransaccionSalida"];//Salida
    //variables POST
    for($i = 0; $i < count($idTransaccion); $i++){
        $transaccionE = new \entidad\Transaccion();
        $transaccionE->setIdTransaccion($idTransaccion[$i]);

        $transaccionProductoE = new \entidad\TransaccionProducto();
        $transaccionProductoE->setTransaccion($transaccionE);
        $transaccionProductoM = new \modelo\TransaccionProducto($transaccionProductoE);
        if($i == 0){
            $arrInfoTransDesti = $transaccionProductoM ->consultarProductosFactura();
        }else{
            $arrInfoTransOrige = $transaccionProductoM ->consultarProductosFactura();
        }
    }

    //Obtener informacion usuario traslado
    $transaccionE->setIdTransaccion($idTransaccion[1]);
    $transaccionM = new \modelo\Transaccion($transaccionE);
    $arrInfoEmpresa = $transaccionM->obtenerInfoEmpresaUsuario();
    
    //ruta
    $retorno["ruta"] = "../../archivos" . $localStorage . "reciboTrasladoProducto/";

    //Creamos la ruta si no existe
    if (!file_exists($retorno["ruta"])) {
        mkdir($retorno["ruta"], 0777, true);
    }

    $retorno["ruta"] .= "reciboTrasladoProducto_" . $idTransaccion[1] . ".pdf";
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
    $htmlHead .= '<tr>';
    $htmlHead .= '<td style="width:25%">';
    $htmlHead .= '<img src="' . $rutaLogo . '">';
    $htmlHead .= '</td>';
    $htmlHead .= '<td style="width:40%;text-align:center;">';
    $htmlHead .= '<br>';
    $htmlHead .= '<br>';
    $htmlHead .= $arrInfoEmpresa["empresa"];
    $htmlHead .= '<br>';
    $htmlHead .= 'NIT ' . $arrInfoEmpresa["nit"] . ' - ' . $arrInfoEmpresa["digitoVerificacion"];
    $htmlHead .= '<br>';
    $htmlHead .= '';//$arrInfoEmpresa["direccion"] . ' / Tel. ' . $arrInfoEmpresa["telefono"];
    $htmlHead .= '</td>';
    $htmlHead .= '<td style="width:30%;">';
    $htmlHead .= '<table style="width:100%;">';
    $htmlHead .= '<tr>';
    $htmlHead .= '<td>';
    $htmlHead .= '<br>';
    $htmlHead .= '<br>';
    $htmlHead .= '<b>Fecha:</b> ' . $arrMes[$mes] . ' ' . $dia . ' de ' . $anio . ' ' . $hora . ':' . $minuto . ':' . $segundo . ' ' . $ampm . '</td>';
    $htmlHead .= '</tr>';
    $htmlHead .= '</table>';
    $htmlHead .= '</td>';
    $htmlHead .= '</tr>';
    $htmlHead .= '</table>';
    
    $_SESSION['header'] = $htmlHead;
    $_SESSION['footer'] = "";
    $pdfClass = new mipdf(PDF_PAGE_ORIENTATION, 'mm', 'Letter', true, 'UTF-8', false);
    
    //Adiciona una página
    $pdf->AddPage();
    //Info cliente
    $html .= '<table style="width:100%;">';
    $html .= '<tr>';
    $html .= '<td style="background-color:' . $fondoTitulos . ';color:' . $colorTextoTitulo . ';border:0.7px #8000FF solid;"> USUARIO TRASLADO </td>';
    $html .= '<td colspan="3" style="width: 75%; border:0.7px #E6E7E8 solid;"> ' . $arrInformacionUsuario[0]["usuario"] . ' </td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<td style="background-color:' . $fondoTitulos . ';color:' . $colorTextoTitulo . ';border:0.7px #8000FF solid;"> BODEGA ORIGEN </td>';
    $html .= '<td style="border:0.7px #E6E7E8 solid;"> ' . $arrInfoTransOrige[0]["bodega"] . ' </td>';
    $html .= '<td style="background-color:' . $fondoTitulos . ';color:' . $colorTextoTitulo . ';border:0.7px #8000FF solid;"> BODEGA DESTINO </td>';
    $html .= '<td style="border:0.7px #E6E7E8 solid;"> ' . $arrInfoTransDesti[0]["bodega"] . ' </td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<td style="background-color:' . $fondoTitulos . ';color:' . $colorTextoTitulo . ';border:0.7px #8000FF solid;"> TIPO DOCUMENTO </td>';
    $html .= '<td style="border:0.7px #E6E7E8 solid;"> ' . $arrInfoTransOrige[0]["tipoDocumento"] . ' </td>';
    $html .= '<td style="background-color:' . $fondoTitulos . ';color:' . $colorTextoTitulo . ';border:0.7px #8000FF solid;"> TIPO DOCUMENTO </td>';
    $html .= '<td style="border:0.7px #E6E7E8 solid;"> ' . $arrInfoTransDesti[0]["tipoDocumento"] . ' </td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<td style="background-color:' . $fondoTitulos . ';color:' . $colorTextoTitulo . ';border:0.7px #8000FF solid;"> NRO DOCUMENTO </td>';
    $html .= '<td style="border:0.7px #E6E7E8 solid;"> ' . $arrInfoTransOrige[0]["numeroTipoDocumento"] . ' </td>';
    $html .= '<td style="background-color:' . $fondoTitulos . ';color:' . $colorTextoTitulo . ';border:0.7px #8000FF solid;"> NRO DOCUMENTO </td>';
    $html .= '<td style="border:0.7px #E6E7E8 solid;"> ' . $arrInfoTransDesti[0]["numeroTipoDocumento"] . ' </td>';
    $html .= '</tr>';

    //Productos
    $html .= '<tr><td colspan="4">&nbsp;</td></tr>';
    $html .= '<tr>';
    $html .= '<td style="width: 10%; background-color:' . $fondoTitulos . ';color:' . $colorTextoTitulo . '; text-align:center;border:0.7px #E6E7E8 solid;"> COD. </td>';
    $html .= '<td style="width: 50%; background-color:' . $fondoTitulos . ';color:' . $colorTextoTitulo . '; text-align:center;border:0.7px #E6E7E8 solid;"> PRODUCTO </td>';
    $html .= '<td style="width: 40%; background-color:' . $fondoTitulos . ';color:' . $colorTextoTitulo . '; text-align:center;border:0.7px #E6E7E8 solid;"> CANTIDAD </td>';;
    $html .= '</tr>';

    $contador = 0;
    while ($contador < count($arrInfoTransOrige)) {

        $html .= '<tr>';
        $html .= '<td style="background-color:' . $fondoContenido . ';border:0.7px #E6E7E8 solid;text-align:center;">' . $arrInfoTransOrige[$contador]["codigo"] . '</td>';
        $html .= '<td style="background-color:' . $fondoContenido . ';border:0.7px #E6E7E8 solid;">' . $arrInfoTransOrige[$contador]["producto"] . '</td>';
        $html .= '<td style="background-color:' . $fondoContenido . ';border:0.7px #E6E7E8 solid;text-align:right;">' . $arrInfoTransOrige[$contador]["cantidad"] . '</td>';
        $html .= '</tr>';

        $contador++;
    }


    $html .= '</table>';

    $html .= '</div>';

//    $_SESSION['header'] = $htmlHead;
//    $_SESSION['footer'] = "";
//    $pdfClass = new mipdf(PDF_PAGE_ORIENTATION, 'mm', 'Letter', true, 'UTF-8', false);

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