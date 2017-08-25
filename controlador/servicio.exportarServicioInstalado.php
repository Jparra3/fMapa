<?php

session_start();
require_once '../../Plantillas/lib/phpJasper/PHPExcel.php';
require_once '../../Plantillas/lib/phpJasper/PHPExcel/Cell/AdvancedValueBinder.php';
$retorno = array("exito" => 1, "mensaje" => "", "ruta" => null);
try {
    $arrInformacionExportar = json_decode($_POST['arrInformacionExportar']);
    $localStorage = $_POST['localStorage'];

    $dia = date("d");
    $mes = (int) date("m") - 1;
    $anio = date("Y");
    $hora = date("H");
    $minuto = date("i");
    $segundo = date("s");

    date_default_timezone_set('America/Bogota');
    $fechaActual = date("Y-m-d");
//VARIABLES DE PHP
    $objPHPExcel = new PHPExcel();
    $cabeceraT = array("#", "Servicio", "Cliente - Sucursal", "($)Valor servicio", "Tipo servicio", "Fecha inicial", "Fecha final", "Municipio", "Vendedor");


// Propiedades de archivo Excels
    $objPHPExcel->getProperties()->setCreator("Ingesoftware")
            ->setLastModifiedBy("Ingesoftware")
            ->setTitle("Reporte de Servicios Instalados")
            ->setSubject("Reporte de Servicios Instalados")
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

    $estiloTextoFijo = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
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

    $estiloNumero = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
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
    $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->applyFromArray($estiloEncabezado);

    $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFont()->setSize(12);

//PROPIEDADES DEL  LA CELDA
    $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
    $objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);

    /* --------------------------Ajusta la celda------------------------------ */

    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);


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
            ->setCellValue("H" . $y, $cabeceraT[7])
            ->setCellValue("I" . $y, $cabeceraT[8]);



    $objPHPExcel->getActiveSheet()
            ->getStyle('A1:I1')
            ->getFill()
            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFEEEEEE');

    $objPHPExcel->getActiveSheet()
            ->getStyle('A1:I1')
            ->applyFromArray($borders);
    $contador = 1;
    for ($i = 0; $i < count($arrInformacionExportar); $i++) {
        $y++;

        $valor = (int) $arrInformacionExportar[$i]->valor;

        //alinemaos numeros
        $objPHPExcel->getActiveSheet()
            ->getStyle('D'.$y)
            ->applyFromArray($estiloNumero);
        
        //alinemaos texto fijo
        $objPHPExcel->getActiveSheet()
            ->getStyle('F'.$y.':G'.$y)
            ->applyFromArray($estiloTextoFijo);
        
        $objPHPExcel->getActiveSheet()
            ->getStyle('A'.$y)
            ->applyFromArray($estiloTextoFijo);
        
        //BORDE DE LA CELDA
        $objPHPExcel->setActiveSheetIndex(0)
                ->getStyle('A' . $y . ":I" . $y)
                ->applyFromArray($borders);
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A" . $y, $contador)
                ->setCellValue("B" . $y, $arrInformacionExportar[$i]->producto)
                ->setCellValue("C" . $y, $arrInformacionExportar[$i]->cliente)
                ->setCellValue("D" . $y, number_format($valor))
                ->setCellValue("E" . $y, $arrInformacionExportar[$i]->tipoServicio)
                ->setCellValue("F" . $y, $arrInformacionExportar[$i]->fechaInicial)
                ->setCellValue("G" . $y, $arrInformacionExportar[$i]->fechaFinal)
                ->setCellValue("H" . $y, $arrInformacionExportar[$i]->municipio)
                ->setCellValue("I" . $y, $arrInformacionExportar[$i]->vendedor);
        $contador++;
    }


    $retorno["ruta"] = "../../archivos" . $localStorage . "reporteServiciosInstalados/";

    //Creamos la ruta si no existe
    if (!file_exists($retorno["ruta"])) {
        mkdir($retorno["ruta"], 0777, true);
    }
    $extencion = ".xls";
    $archivo = "ListadoServiciosInstalados";
    $retorno["ruta"] .= $archivo . "_" . $dia . "-" . $mes . "-" . $anio . "_" . $hora . "-" . $minuto . $extencion;
//DATOS DE LA SALIDA DEL EXCEL
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="' . $retorno["ruta"] . '"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save($retorno["ruta"]);
} catch (Exception $ex) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $ex->getMessage();
}

echo json_encode($retorno);
?>