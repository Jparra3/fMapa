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

    $nombreArchivo = "OrdenMantenimiento.pdf";
    $PHPJasperXML->load_xml_file("../reportes/rptOrdenMantenimiento.jrxml");

    $fechaActual = date("Y-m-d");
    
    //Creamos la ruta si no existe
    $ruta = "../../archivos/PublicServices/ordenServicio";
    if (!file_exists($ruta)) {
        mkdir($ruta, 0777, true);
    }
    $rutaFinal = "../../archivos/PublicServices/ordenServicio/".$nombreArchivo;

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

