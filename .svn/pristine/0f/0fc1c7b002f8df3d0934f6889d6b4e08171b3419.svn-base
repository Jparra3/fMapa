<?php

session_start();
require_once '../../Plantillas/lib/phpJasper/PHPExcel.php';
require_once '../../Plantillas/lib/phpJasper/PHPExcel/Cell/AdvancedValueBinder.php';

require_once '../entidad/Ruta.php';
require_once '../modelo/Ruta.php';

date_default_timezone_set('America/Bogota');

$arrRetorno = array("exito"=>1, "mensaje"=>"El archivo se descargó correctamente.", "ruta"=>"");

try {
    $fechaActual = date("Y-m-d");
    $data = $_SESSION['arregloEvaluar'];
    $retorno = $data["data"];
    $infoRuta = $data["infoRuta"];

    //VARIABLES DE PHP
    $objPHPExcel = new PHPExcel();
    $cabeceraT = array("#", "Nit", "Cliente", "Fecha inicial", "Hora visita", "Tiempo visita", "Visita festivos", "Fecha visita");

    // Propiedades de archivo Excels
    $objPHPExcel->getProperties()->setCreator("Ingesoftware")
            ->setLastModifiedBy("Ingesoftware")
            ->setTitle("Listado de rutas")
            ->setSubject("Listado de rutas")
            ->setDescription("")
            ->setKeywords("")
            ->setCategory("");

    //Estilo del titulo
    $estiloEncabezado = array(
        'font' => array(
            'bold' => true,
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        ),
        'borders' => array(
            'top' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
            ),
        ),
    );

    $estiloTexto = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        ),
        'borders' => array(
            'top' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
            ),
        ),
    );

    $borders = array(
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('argb' => 'FF000000'),
            )
        ),
    );
    $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($estiloEncabezado);

    $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->setSize(12);

    //PROPIEDADES DEL  LA CELDA
    $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
    $objPHPExcel->getDefaultStyle()->getFont()->setSize(10);

    /* --------------------------Ajusta la celda------------------------------ */
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);

    //CABECERA DE LA CONSULTA
    $y = 1;
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A" . $y, $cabeceraT[0])
            ->setCellValue("B" . $y, $cabeceraT[1])
            ->setCellValue("C" . $y, $cabeceraT[2])
            ->setCellValue("D" . $y, $cabeceraT[3])
            ->setCellValue("E" . $y, $cabeceraT[4])
            ->setCellValue("F" . $y, $cabeceraT[5])
            ->setCellValue("G" . $y, $cabeceraT[6])
            ->setCellValue("H" . $y, $cabeceraT[7]);

    $objPHPExcel->getActiveSheet()
            ->getStyle('A1:H1')
            ->getFill()
            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFEEEEEE');

    $objPHPExcel->getActiveSheet()
            ->getStyle('A1:H1')
            ->applyFromArray($borders);
    $contador = 1;
    foreach ($retorno as $fila) {
        $y++;

        //BORDE DE LA CELDA
        $objPHPExcel->setActiveSheetIndex(0)
                ->getStyle('A' . $y . ":H" . $y)
                ->applyFromArray($borders);

        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A" . $y, ($contador))
                ->setCellValue("B" . $y, $fila['nit'])
                ->setCellValue("C" . $y, $fila['tercero'])
                ->setCellValue("D" . $y, $fila['fechaInicialVisita'])
                ->setCellValue("E" . $y, $fila['horaVisita'])
                ->setCellValue("F" . $y, $fila['tiempoVisita'] . ' min.')
                ->setCellValue("G" . $y, $fila['visitaFestivos'])
                ->setCellValue("H" . $y, $fila['fechaVisita']);
        $contador++;
    }

//----------------------------------SE ADICIONA LA RUTA------------------------------------
    $_POST['fechaHora'] = "NOW()";
    $_POST['idRutaEstado'] = "1";
    $_POST['idRutaPadre'] = "null";
    $_POST['idCliente'] = "-1";
    $_POST['idZona'] = $infoRuta["idZona"];
    $_POST['idRegional'] = $infoRuta["idRegional"];
    $_POST['archivo'] = "null";
    $_POST['jsonData'] = json_encode($data["data"]);

    include 'ruta.adicionar.php';

    if ($retorno["exito"] == 0) {
        throw new Exception("ERROR AL ADICIONAR LA RUTA -> ".$retorno["mensaje"]);
    }
    
    $idRuta = $retorno["idRuta"];
//-----------------------------------------------------------------------------------------

    //ruta
    $rutaArchivo = "../../archivos" . $infoRuta["modulo"] . "rutas/";

    //Creamos la ruta si no existe
    if (!file_exists($rutaArchivo)) {
        mkdir($rutaArchivo, 0777, true);
    }

    $rutaArchivo .= "ListadoRuta_" . $idRuta . ".xls";
    
    //---------GUARDAMOS EL ARCHIVO EN LA RUTA ESPECIFICADA------------
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save($rutaArchivo);
    $arrRetorno["ruta"] = $rutaArchivo;
    //-------------------------------------------------------------
    
    //--------ACTUALIZO LA RUTA DEL ARCHIVO EN LA BD-------
    $rutaE = new \entidad\Ruta();
    $rutaE->setIdRuta($idRuta);
    $rutaE->setArchivo($rutaArchivo);
    
    $rutaM = new \modelo\Ruta($rutaE);
    $rutaM->actualizarArchivo();
    //-------------------------------------------
    
} catch (Exception $exc) {
    $arrRetorno["exito"] = 0;
    $arrRetorno["mensaje"] = $exc->getMessage();
}

echo json_encode($arrRetorno);