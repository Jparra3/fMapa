<?php
session_start();
require_once '../../Plantillas/lib/phpJasper/PHPExcel.php';
require_once '../../Plantillas/lib/phpJasper/PHPExcel/Cell/AdvancedValueBinder.php';

$retorno = $_SESSION['arregloEvaluar'];

date_default_timezone_set('America/Bogota');
$fechaActual = date("Y-m-d");

//VARIABLES DE PHP
$objPHPExcel = new PHPExcel();
$cabeceraT = array("Tercero", "Tipo Documento", "Naturaleza", "Fecha", "Nota", "Valor");
$archivo = "rptDetalleMovimientosContables.xls";
$fondoEncabezado = '3F51B5';

// Propiedades de archivo Excels
$objPHPExcel->getProperties()->setCreator("Ingesoftware Soluciones SAS")
    ->setLastModifiedBy("Ingesoftware Soluciones SAS")
    ->setTitle("Reporte de Movimientos Contables")
    ->setSubject("Reporte de Movimientos Contables")
    ->setDescription("")
    ->setKeywords("")
    ->setCategory("");

//Estilo del titulo
$estiloEncabezado = array(
    'font' => array(
        'bold' => true,
        'color' => array('rgb' => 'FFFFFF'),
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

$borders = array(
      'borders' => array(
        'allborders' => array(
          'style' => PHPExcel_Style_Border::BORDER_THIN,
          'color' => array('argb' => 'FF000000'),
        )
      ),
 );
$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray($estiloEncabezado);

$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setSize(12);

//PROPIEDADES DEL  LA CELDA
$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
$objPHPExcel->getDefaultStyle()->getFont()->setSize(10);

/*--------------------------Ajusta la celda------------------------------*/
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);


//CABECERA DE LA CONSULTA
$y = 1;
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue("A".$y, $cabeceraT[0])
->setCellValue("B".$y,$cabeceraT[1])
->setCellValue("C".$y, $cabeceraT[2])
->setCellValue("D".$y, $cabeceraT[3])
->setCellValue("E".$y, $cabeceraT[4])
->setCellValue("F".$y, $cabeceraT[5]);

$objPHPExcel->getActiveSheet()
            ->getStyle('A1:F1')
            ->getFill()
            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()->setRGB($fondoEncabezado);

$objPHPExcel->getActiveSheet()
        ->getStyle('A1:F1')
        ->applyFromArray($borders);

$contador = 0;
while ($contador < count($retorno["detalle"])){
    
    $y++;
    
    if($retorno["detalle"][$contador]['fecha'] == "" || $retorno["detalle"][$contador]['fecha'] == null)
        $fecha = "";
    else
        $fecha = $retorno["detalle"][$contador]['fecha'];
    
    if($retorno["detalle"][$contador]['nota'] == "" || $retorno["detalle"][$contador]['nota'] == null)
        $nota = "";
    else
        $nota = $retorno["detalle"][$contador]['nota'];
    
    //BORDE DE LA CELDA
    $objPHPExcel->setActiveSheetIndex(0)
        ->getStyle('A'.$y.":F".$y)
        ->applyFromArray($borders);

    //MOSTRAMOS LOS VALORES
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("A".$y, $retorno["detalle"][$contador]['tercero'])
        ->setCellValue("B".$y, $retorno["detalle"][$contador]['tipoDocumento'])
        ->setCellValue("C".$y, $retorno["detalle"][$contador]['naturaleza'])
        ->setCellValue("D".$y, $fecha)
        ->setCellValue("E".$y, $nota)
        ->setCellValue("F".$y, $retorno["detalle"][$contador]['valor']);
    $contador++;
}

$posTotal = ($y + 1);

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A".$posTotal, "Total");

$objPHPExcel->setActiveSheetIndex(0)->mergeCells("A".$posTotal.":E".$posTotal);
$objPHPExcel->getActiveSheet()->getStyle("A".$posTotal.":E".$posTotal)->applyFromArray($estiloEncabezado);

$objPHPExcel->getActiveSheet()
                        ->getStyle("A".$posTotal.":E".$posTotal)
                        ->getFill()
                        ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
                        ->getStartColor()->setRGB($fondoEncabezado);

//BORDE DE LA CELDA
    $objPHPExcel->setActiveSheetIndex(0)
        ->getStyle('A'.$posTotal.":F".$posTotal)
        ->applyFromArray($borders);

$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("F".$posTotal, $retorno["total"]);

//DATOS DE LA SALIDA DEL EXCEL
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="'.$archivo.'"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;