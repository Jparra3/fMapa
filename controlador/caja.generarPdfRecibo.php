<?php
session_start();
date_default_timezone_set("America/Bogota");

require_once('../../Plantillas/lib/tcpdf/tcpdf.php');
require_once '../entidad/Cliente.php';
require_once '../modelo/Cliente.php';
require_once '../entidad/Concepto.php';
require_once '../modelo/Concepto.php';
require_once '../comunes/funciones.php';
require_once '../../Seguridad/entidad/Usuario.php';
require_once '../../Seguridad/modelo/Usuario.php';
require_once '../entidad/Transaccion.php';
require_once '../modelo/Transaccion.php';

$retorno = array('exito'=>1, 'mensaje'=>'', 'ruta'=>'');

try{
    
    $objTransaccion = new \modelo\Transaccion(new \entidad\Transaccion());
    $arrEstilosFactura = $objTransaccion->obtenerEstilosFactura();
    if ($arrEstilosFactura["exito"] == 0) {
        throw new Exception($arrEstilosFactura["mensaje"]);
    }
    
    //Necearias
    $contenidoArchivo = "";
    $fechaActual = date("Y/m/d H:i:s");
    $arrMes = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
    $dia = date("d");
    $mes = (int)date("m")-1;
    $anio = date("Y");
    $rutaLogo = $arrEstilosFactura["data"]["rutaLogoEmpresa"];
    $fondoTitulos = $arrEstilosFactura["data"]["colorFondoTitulos"];
    $fondoContenido = $arrEstilosFactura["data"]["colorFondoContenido"];
    $colorTexto = $arrEstilosFactura["data"]["colorTextoContenido"];
    $colorTextoTitulo = $arrEstilosFactura["data"]["colorTextoTitulos"];
    
    //Post
    $idTercero = $_POST["idTercero"];
    $idCliente = $_POST["idClienteProveedor"];
    $idTransaccion = $_POST["idTransaccion"];
    $nota = $_POST["nota"];
    $tipoClienteProveedor = $_POST["tipoClienteProveedor"];
    $arrInfoFormasPago = json_decode($_POST["dataFormasPago"]);
    $idUsuario = $_SESSION["idUsuario"];
    $arrConceptoPago = json_decode($_POST["arrConceptoPago"]);
    $localStorage = $_POST["localStorage"];
    
    //ruta
    $retorno["ruta"] = "../../archivos".$localStorage."reciboCaja/";
    
    //Creamos la ruta si no existe
    if (!file_exists($retorno["ruta"])) {
        mkdir($retorno["ruta"], 0777, true);
    }
    
    $transaccionE = new \entidad\Transaccion();
    $transaccionE->setIdTransaccion($idTransaccion);
    
    $transaccionM = new \modelo\Transaccion($transaccionE);
    $informacionTransaccion = $transaccionM->consultarInformacionFactura();
    $arrInfoEmpresa = $transaccionM->obtenerInfoEmpresaUsuario();
    
    $retorno["ruta"] .= "reciboCaja_" . $informacionTransaccion[0]["numeroTipoDocumento"] . ".pdf";
    
    //Intanciamos Cliente
    $clienteE = new \entidad\Cliente();
    $clienteE ->setIdCliente($idCliente);
    $clienteE ->setTipoClienteProveedor($tipoClienteProveedor);
    //
    $clienteM = new \modelo\Cliente($clienteE);
    $arrCliente = $clienteM -> consultar();
    
    /*//Instanciamos usuario
    $usuarioE = new \entidad\Usuario();
    $usuarioE ->setIdUsuario($idUsuario);
    
    $usuarioM = new \modelo\Usuario($usuarioE);
    $arrInforEmpresa = $usuarioM ->consultarEmpresaUsuario();*/
    
    //Se crea una nueva instancia de la libreria
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    ob_clean();
    $pdf->SetFont('helvetica', '', 9, '', true);

    //Adiciona una página
    $pdf->AddPage(); 
    //$pdf->SetMargins(30,0,30,0);
    
    
    $html ="";
//$empresa = str_replace(" ", "<br>", $arrEmpresa[0]["tercero"]);
$html .= '<br>';
$html .= '<div style="width:100%;">';
$html .= '<table style="width:100%;">';
$html .= '<tr>';
$html .= '<td>';
$html .= '<img src="'.$rutaLogo.'">';
$html .= '</td>';
$html .= '<td style="width:34%;text-align:center;">';
$html .= $arrInfoEmpresa["empresa"];
$html .= '<br>';
$html .= 'NIT ' . $arrInfoEmpresa["nit"] . ' - ' . $arrInfoEmpresa["digitoVerificacion"];
$html .= '<br>';
$html .= $arrInfoEmpresa["direccion"];
$html .= '<br>';
$html .= 'Tel. ' . $arrInfoEmpresa["telefono"];
$html .= '<br>';
$html .= $arrInfoEmpresa["email"];
    $html .= '</td>';
$html .= '<td style="text-align:center">';
$html .= '<b>COMPROBANTE NRO. '.str_pad($informacionTransaccion[0]["numeroTipoDocumento"], 4, "0", STR_PAD_LEFT).'</b><br>';
$html .= '<b>Fecha:</b> '.$arrMes[$mes].' '.$dia.' de '.$anio.'<br>';
$html .= '<b>Usuario :</b> '.$informacionTransaccion[0]["usuario"].'<br>';
$html .= '</td>';
$html .= '</tr>	';
$html .= '</table>';
$html .= '<br><br>';
$html .= '<table style="width:150%;">';
$html .= '<tr>';
$html .= '<td style="width: 70px;background-color:'.$fondoTitulos.';color:'.$colorTextoTitulo.';border:0.7px #8000FF solid;"> SEÑORES </td>';
$html .= '<td style="border:0.7px #8000FF solid;color:'.$colorTexto.';"> '.$arrCliente[0]["tercero"].' </td>';
$html .= '<td style="width: 70px;background-color:'.$fondoTitulos.';color:'.$colorTextoTitulo.';border:0.7px #8000FF solid;"> NIT </td>';
$html .= '<td style="border:0.7px #8000FF solid;color:'.$colorTexto.';"> '.$arrCliente[0]["nit"].' </td>';
$html .= '</tr>';
$html .= '<tr>';
$html .= '<td style="background-color:'.$fondoTitulos.';color:'.$colorTextoTitulo.';border:0.7px #8000FF solid;"> DIRECCION </td>';
$html .= '<td style="border:0.7px #8000FF solid;color:'.$colorTexto.';">'.$arrCliente[0]["terceroDireccion"].'</td>';
$html .= '<td style="background-color:'.$fondoTitulos.';color:'.$colorTextoTitulo.';border:0.7px #8000FF solid;"> TELEFONO </td>';
$html .= '<td style="border:0.7px #8000FF solid;color:'.$colorTexto.';">'.$arrCliente[0]["terceroTelefono"].'</td>';
$html .= '</tr>';
$html .= '<tr><td colspan="4"><p></p></td></tr>';
$html .= '<tr>';
$html .= '<td style="width:10%; background-color:'.$fondoTitulos.';color:'.$colorTextoTitulo.'; text-align:center;border:0.7px #8000FF solid;"> CÓD </td>';
$html .= '<td style="width:22.5%; background-color:'.$fondoTitulos.';color:'.$colorTextoTitulo.'; text-align:center;border:0.7px #8000FF solid;"> CONCEPTO </td>';
$html .= '<td style="width:25%; background-color:'.$fondoTitulos.';color:'.$colorTextoTitulo.'; text-align:center;border:0.7px #8000FF solid;"> NOTA </td>';
$html .= '<td style="width:10%; background-color:'.$fondoTitulos.';color:'.$colorTextoTitulo.'; text-align:center;border:0.7px #8000FF solid;"> VALOR </td>';
$html .= '</tr>';
//Conceptos
$contador=0;
while($contador<  count($arrConceptoPago)){
    $html.='<tr>';
    $conceptoE = new \entidad\Concepto();
    $conceptoE ->setIdConcepto($arrConceptoPago[$contador]->idConcepto);

    $conceptoM = new \modelo\Concepto($conceptoE);
    $arrConcepto = $conceptoM -> consultar($parametros);

    $valor = number_format($arrConceptoPago[$contador]->valorPago, 0, '', '.');
    
    $html .= '<td style="background-color:'.$fondoContenido.';color:'.$colorTexto.';text-align:right;">'.$arrConcepto[0]["codigo"].'</td>';
    $html .= '<td style="background-color:'.$fondoContenido.';color:'.$colorTexto.';">'.$arrConcepto[0]["tipoDocumento"].'</td>';
    $html .= '<td style="background-color:'.$fondoContenido.';color:'.$colorTexto.';">'.$arrConceptoPago[$contador]->nota.'</td>';
    $html .= '<td style="background-color:'.$fondoContenido.';color:'.$colorTexto.';text-align:right;">'.$valor.'</td>';
    $html .='</tr>';

    $contador++;
}

$html .= '<tr>';
$html .= '<td colspan="4"><p></p></td>';
$html .= '</tr>';
$html .= '<tr>';
$html .= '<td colspan="4"><p></p></td>';
$html .= '</tr>';
$html .= '<tr>';
$html .= '<td colspan="2" style="background-color:'.$fondoTitulos.';color:'.$colorTextoTitulo.'; text-align:center;border:0.7px #8000FF solid;"> FORMA DE PAGO </td>';
$html .= '<td colspan="2" style="background-color:'.$fondoTitulos.';color:'.$colorTextoTitulo.'; text-align:center;border:0.7px #8000FF solid;"> VALOR </td>';
$html .= '</tr>';

//Formas de pago 
    $contador = 0;
    $totalPagar = 0;
    while ($contador < count($arrInfoFormasPago)) {
        $totalTipoPago = number_format($arrInfoFormasPago[$contador]->valor, 0, '', '.');
        $html.='<tr>';
        $html .= '<td colspan="2" style="background-color:'.$fondoContenido.';color:'.$colorTexto.';text-align:center;text-align:left;">'.$arrInfoFormasPago[$contador]->formaPago.'</td>';
        $html .= '<td style="background-color:'.$fondoContenido.';color:'.$colorTexto.';text-align:center;"></td>';
        $html .= '<td colspan="2" style="background-color:'.$fondoContenido.';color:'.$colorTexto.';text-align:center;text-align:right;">'.$totalTipoPago.'</td>';
        $totalPagar = (int)$totalPagar+(int)$arrInfoFormasPago[$contador]->valor;
        $contador++;
        $html.='</tr>';
    }
$letraTotalPagar = obtenerTextoNumero($totalPagar);
$totalPagar = number_format($totalPagar, 0, '', '.');
$html .= '</table>';
$html .= '<br>';
$html .= '<br>';
$html .= '<hr style="height: .7px;">';
$html .= '<table style="width:100%;border-collapse: collapse;">';
$html .= '<tr>';
$html .= '<td style="width:75%;color:'.$colorTexto.';"><b>SON </b> '.$letraTotalPagar.' PESOS MTCE </td>';
$html .= '<td style="text-align:center;width:10%;color:'.$colorTexto.';"> <b>TOTAL</b></td>';
$html .= '<td style="text-align:right;width:10%;color:'.$colorTexto.';"> $'.$totalPagar.'</td>';
$html .= '</tr>';
$html .= '</table>';
$html .= '</div>';
    //Guardamos el archivo
    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output($retorno["ruta"], 'F');
    
    
} catch (Exception $ex) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $ex -> getMessage();
}

echo json_encode($retorno);
?>