<?php
require_once '../entorno/configuracion.php';
require_once '../entidad/OrdenTrabajoCliente.php';
require_once '../modelo/OrdenTrabajoCliente.php';
include_once '../../Plantillas/lib/phpJasper/tcpdf/tcpdf.php';
include_once '../../Plantillas/lib/phpJasper/PHPJasperXML.inc.php';
    
    //display errors should be off in the php.ini file
    ini_set('display_errors', 0);
    
    $idOrdenTrabajoCliente = $_REQUEST["idOrdenTrabajoCliente"];
    $idOrdenTrabajo = $_REQUEST["idOrdenTrabajo"];
    
    if($idOrdenTrabajo != "" && $idOrdenTrabajo != null){
        $ordenTrabajoClienteE = new \entidad\OrdenTrabajoCliente();
        
        $ordenTrabajoClienteM =  new \modelo\OrdenTrabajoCliente($ordenTrabajoClienteE);
        $idOrdenTrabajoCliente = $ordenTrabajoClienteM->consultarIdOrdenTrabajoCliente($idOrdenTrabajo);            
    }
    
    $PHPJasperXML = new PHPJasperXML();

    $PHPJasperXML->arrayParameter=array("idOrdenTrabajoCliente"=>$idOrdenTrabajoCliente);

    $nombreArchivo = "OrdenServicio_".$idOrdenTrabajo."_".$idOrdenTrabajoCliente.".pdf";
    $PHPJasperXML->load_xml_file("../reportes/ordenServicioXCliente.jrxml");

    $ruta = "../../archivos/PublicServices/ordenServicio";
    if (!file_exists($ruta)) {
        mkdir($ruta, 0777, true);
    }
    
    $fechaActual = date("Y-m-d");
    $rutaFinal = "../../archivos/PublicServices/ordenServicio/".$nombreArchivo;

    global $pgport;
    $pgport = PORT_DATABASE;
    $PHPJasperXML->transferDBtoArray(SERVER_NAME_DATABASE,USER_DATABASE,PASSWORD_DATABASE,DATABASE,"psql");
    try {
        ob_end_clean();
        $PHPJasperXML->outpage("F",$rutaFinal);
        $PHPJasperXML->outpage("I",$nombreArchivo);
    } catch (Exception $exc) {
        echo $exc->getTraceAsString();
    }
?>

