<?php
session_start();
require_once '../../Plantillas/lib/phpJasper/PHPExcel.php';
require_once '../../Plantillas/lib/phpJasper/PHPExcel/Cell/AdvancedValueBinder.php';

$retorno = $_SESSION['arregloEvaluar'];

date_default_timezone_set('America/Bogota');
$fechaActual = date("Y-m-d");

//VARIABLES DE PHP
$objPHPExcel = new PHPExcel();
$cabeceraT = array("Cod. Pedido", "Cliente", "Barrio", "Fecha", "Producto", "Cantidad", "Precio", "Total", "Nota", "Estado");
$archivo = "rptPedidos.xls";
$fondoEncabezado = '3F51B5';

// Propiedades de archivo Excels
$objPHPExcel->getProperties()->setCreator("Ingesoftware Soluciones SAS")
    ->setLastModifiedBy("Ingesoftware Soluciones SAS")
    ->setTitle("Reporte de Pedidos")
    ->setSubject("Reporte de Pedidos")
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
$objPHPExcel->getActiveSheet()->getStyle('A1:J1')->applyFromArray($estiloEncabezado);

$objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFont()->setSize(12);

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
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);


//CABECERA DE LA CONSULTA
$y = 1;
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue("A".$y, $cabeceraT[0])
->setCellValue("B".$y,$cabeceraT[1])
->setCellValue("C".$y, $cabeceraT[2])
->setCellValue("D".$y, $cabeceraT[3])
->setCellValue("E".$y, $cabeceraT[4])
->setCellValue("F".$y, $cabeceraT[5])
->setCellValue("G".$y, $cabeceraT[6])
->setCellValue("H".$y, $cabeceraT[7])
->setCellValue("I".$y, $cabeceraT[8])
->setCellValue("J".$y, $cabeceraT[9]);

$objPHPExcel->getActiveSheet()
            ->getStyle('A1:J1')
            ->getFill()
            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()->setRGB($fondoEncabezado);

$objPHPExcel->getActiveSheet()
        ->getStyle('A1:J1')
        ->applyFromArray($borders);

$contador = 0;
while ($contador < count($retorno["detalle"])){
    
    $y++;
    
    if($retorno["detalle"][$contador]['nota'] == "" || $retorno["detalle"][$contador]['nota'] == null)
        $nota = "";
    else
        $nota = $retorno["detalle"][$contador]['nota'];
    
    //BORDE DE LA CELDA
    $objPHPExcel->setActiveSheetIndex(0)
        ->getStyle('A'.$y.":J".$y)
        ->applyFromArray($borders);

    //MOSTRAMOS LOS VALORES
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("A".$y, $retorno["detalle"][$contador]['idPedido'])
        ->setCellValue("B".$y, $retorno["detalle"][$contador]['cliente'])
        ->setCellValue("C".$y, $retorno["detalle"][$contador]['barrio'])
        ->setCellValue("D".$y, $retorno["detalle"][$contador]['fecha'])
        ->setCellValue("E".$y, $retorno["detalle"][$contador]['producto'])
        ->setCellValue("F".$y, $retorno["detalle"][$contador]['cantidad'])
        ->setCellValue("G".$y, $retorno["detalle"][$contador]['valorUnitaConImpue'])
        ->setCellValue("H".$y, $retorno["detalle"][$contador]['total'])
        ->setCellValue("I".$y, $nota)
        ->setCellValue("J".$y, $retorno["detalle"][$contador]['estadoPedido']);
    $contador++;
}

$posTotal = ($y + 1);

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A".$posTotal, "Total");

$objPHPExcel->setActiveSheetIndex(0)->mergeCells("A".$posTotal.":G".$posTotal);
$objPHPExcel->getActiveSheet()->getStyle("A".$posTotal.":G".$posTotal)->applyFromArray($estiloEncabezado);

$objPHPExcel->getActiveSheet()
                        ->getStyle("A".$posTotal.":G".$posTotal)
                        ->getFill()
                        ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
                        ->getStartColor()->setRGB($fondoEncabezado);

//BORDE DE LA CELDA
    $objPHPExcel->setActiveSheetIndex(0)
        ->getStyle('A'.$posTotal.":J".$posTotal)
        ->applyFromArray($borders);

$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("H".$posTotal, $retorno["total"]);

//DATOS DE LA SALIDA DEL EXCEL
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="'.$archivo.'"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;