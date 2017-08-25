<?php
require_once '../entorno/configuracion.php';
include_once '../../Plantillas/lib/phpJasper/tcpdf/tcpdf.php';
include_once '../../Plantillas/lib/phpJasper/PHPJasperXML.inc.php';
    
    //display errors should be off in the php.ini file
    ini_set('display_errors', 0);
    
    $idOrdenTrabajo = $_REQUEST["idOrdenTrabajo"];
    $numeroOrden = $_REQUEST["numeroOrden"];
    
    $PHPJasperXML = new PHPJasperXML();

    $PHPJasperXML->arrayParameter=array("idOrdenTrabajo"=>$idOrdenTrabajo);

    $nombreArchivo = "OrdenTrabajo_".$numeroOrden.".pdf";
    $PHPJasperXML->load_xml_file("../reportes/rptOrdenTrabajo.jrxml");

    $fechaActual = date("Y-m-d");
    $rutaFinal = "../../archivos/PublicServices/ordenTrabajo/".$nombreArchivo;

    global $pgport;
    $pgport = PORT_DATABASE;
    $PHPJasperXML->transferDBtoArray(SERVER_NAME_DATABASE,USER_DATABASE,PASSWORD_DATABASE,DATABASE,"psql");
    try {
        $PHPJasperXML->outpage("F",$rutaFinal);
        $PHPJasperXML->outpage("D",$nombreArchivo);
    } catch (Exception $exc) {
        echo $exc->getTraceAsString();
    }
?>

