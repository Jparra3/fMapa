<?php
session_start();
require_once '../../Plantillas/lib/phpJasper/PHPExcel.php';
require_once '../../Plantillas/lib/phpJasper/PHPExcel/Cell/AdvancedValueBinder.php';
require_once '../../Seguridad/entidad/Usuario.php';
require_once '../../Seguridad/modelo/Usuario.php';
$retorno = array("exito" => 1, "mensaje" => "Se generó el reporte correctamente.", "ruta"=>null);
try{
    
    if($_POST["fechaInicio"] != "" && $_POST["fechaInicio"] != null && $_POST["fechaInicio"] != "null"){
        if($_POST["fechaFin"] != "" && $_POST["fechaFin"] != null && $_POST["fechaFin"] != "null"){
            $fechaInicio = utf8_decode($_POST["fechaInicio"]);
            $fechaFin = utf8_decode($_POST["fechaFin"]);
        }else{
            $fechaInicio = utf8_decode($_POST["fechaInicio"]);
            $fechaFin = utf8_decode($_POST["fechaInicio"]);
        }
    }else{
        if($_POST["fechaFin"] != "" && $_POST["fechaFin"] != null && $_POST["fechaFin"] != "null"){
            $fechaInicio = utf8_decode($_POST["fechaFin"]);
            $fechaFin = utf8_decode($_POST["fechaFin"]);
        }
    }    
    $idUsuario = $_SESSION["idUsuario"];
    $mes = date("m")-1;
    $anio = date("Y");
    $dia = date("d");
    $data = $_POST["data"]; 
    $arrMes = array("Enero","Febrero","Marzo","Abrril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
    $nombreMes = $arrMes[$mes];
    //VARIABLES DE PHP
    $objPHPExcel = new PHPExcel();
    $archivo = "caja_".$dia."_".$nombreMes."_".$anio."_".$idUsuario.".xls";
    $cabeceraT = array("Ítem","Cédula y nombre","Fecha","Concepto","Valor");
    $localStorage = $_POST["localStorage"];
    
    $rutaArchivo ="../../archivos".$localStorage."reporteCaja/";

    if (!file_exists($rutaArchivo)) {
        mkdir($rutaArchivo, 0777, true);
    }
    
    $rutaArchivo .= $archivo;
    
    //Instanciamos usuario
    $usuarioE = new \entidad\Usuario();
    $usuarioE ->setIdUsuario($idUsuario);
    
    $usuarioM = new \modelo\Usuario($usuarioE);
    $arrInforEmpresa = $usuarioM ->consultarEmpresaUsuario();
    
    //Creamos encabezado
    $objDrawing = new PHPExcel_Worksheet_Drawing();
    $objDrawing->setName('logo');
    $objDrawing->setDescription('Logo');
    $objDrawing->setPath('../imagenes/banner/1.png');       // filesystem reference for the image file
    $objDrawing->setHeight(110);
    $objDrawing->setWidth(120);
    $objDrawing->setCoordinates('A1'); 
    $objDrawing->setOffsetX(250); 
    $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

    // Propiedades de archivo Excels
    $objPHPExcel->getProperties()->setCreator("Ingesoftware")
        ->setLastModifiedBy("Ingesoftware")
        ->setTitle("Generación del listado para descuentos de nómina")
        ->setSubject("Generación del listado para descuentos de nómina")
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
    
    $estiloLeft = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
        )
    );
    $estiloRight = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
        )
    );
    
    $estiloCenter = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
    );

    $objPHPExcel->getActiveSheet()->getStyle('B3:B9')->applyFromArray($estiloRight);
    
    
    $objPHPExcel->getActiveSheet()->getStyle('B1:E1')->applyFromArray($borders);
    $objPHPExcel->getActiveSheet()->getStyle('B1')->applyFromArray($estiloEncabezado);
    $objPHPExcel->getActiveSheet()->getStyle('A3:A9')->applyFromArray($estiloEncabezado);
    $objPHPExcel->getActiveSheet()->getStyle('A11:E11')->applyFromArray($estiloEncabezado);

    $objPHPExcel->getActiveSheet()->getStyle('A2:E2')->getFont()->setSize(12);
    $objPHPExcel->getActiveSheet()->mergeCells('A2:E2');
    $objPHPExcel->getActiveSheet()->mergeCells('A10:E10');

    //PROPIEDADES DEL  LA CELDA
    $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
    $objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
    $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(80);
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);

    /*--------------------------Ajusta la celda------------------------------*/

    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);


    //CABECERA DE LA CONSULTA
    $letraCabezera = "A";
    $y = 11;
    for($i=0;$i<count($cabeceraT);$i++){
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue($letraCabezera.$y, $cabeceraT[$i]);
        $letraCabezera++;
    }


    $objPHPExcel->getActiveSheet()->mergeCells('B1:E1');


    $objPHPExcel->getActiveSheet()
                ->getStyle('A11:E11')
                ->getFill()
                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFEEEEEE');

    $objPHPExcel->getActiveSheet()
            ->getStyle('A11:E11')
            ->applyFromArray($borders);
    $objPHPExcel->getActiveSheet()
            ->getStyle('C1')
            ->applyFromArray($estiloEncabezado);
    
    $contadorWhile =0;
    $contador = 0;
    $totalPagos = 0;
    $arrCampos = $data["data"];
    while($contadorWhile<count($arrCampos)){
        $y++;
        $contador++;
        //BORDE DE LA CELDA
        $objPHPExcel->setActiveSheetIndex(0)
            ->getStyle('A'.$y.":E".$y)
            ->applyFromArray($borders);
        
        $objPHPExcel->getActiveSheet()->getStyle('E'.$y.':E'.$y)->applyFromArray($estiloRight);
        $objPHPExcel->getActiveSheet()->getStyle('C'.$y.':C'.$y)->applyFromArray($estiloCenter);
        
        $valor = number_format($arrCampos[$contadorWhile]["valor"], 0, '', ',');
        
        //MOSTRAMOS LOS VALORES
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A".$y, $contador)
            ->setCellValue("B".$y, $arrCampos[$contadorWhile]["nit"]." - ".$arrCampos[$contadorWhile]["tercero"])
            ->setCellValue("C".$y, $arrCampos[$contadorWhile]["fecha"])
            ->setCellValue("D".$y, $arrCampos[$contadorWhile]["tipoDocumento"])
            ->setCellValue("E".$y, $valor);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B'.$Y)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C'.$Y)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D'.$Y)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E'.$Y)->setAutoSize(true);
        $totalPagos = $totalPagos + (int)$arrCampos[$contadorWhile]["valor"];
        $contadorWhile++;
    }
    //
    //Cuadramos valores de encabezado
    $objPHPExcel->getActiveSheet()->setCellValue('B1', $arrInforEmpresa[0]["empresa"]."\r REPORTE DE PAGOS REALIZADOS EN CAJA");
    $objPHPExcel->getActiveSheet()->getStyle('B1')->getAlignment()->setWrapText(true);

    
    
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A3", "Nit pagador")
            ->setCellValue("A4", "Año")
            ->setCellValue("A5", "Mes")
            ->setCellValue("A6", "Fecha inicio")
            ->setCellValue("A7", "Fecha fin")
            ->setCellValue("A8", "# Pagos")
            ->setCellValue("A9", "Total pagos");
    $totalPagos = number_format($totalPagos, 0, '', '.');
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("B3", "")
            ->setCellValue("B4", $anio)
            ->setCellValue("B5", $nombreMes)
            ->setCellValue("B6", $fechaInicio)
            ->setCellValue("B7", $fechaFin)
            ->setCellValue("B8", $contador)
            ->setCellValue("B9", $totalPagos);
    $objPHPExcel->setActiveSheetIndex(0)
            ->getStyle("A3:B9")
            ->applyFromArray($borders);

    $retorno["totalPagos"] = $totalPagos;

    //DATOS DE LA SALIDA DEL EXCEL
    $rutaArchivo = utf8_decode($rutaArchivo);
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="'.$archivo.'"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save($rutaArchivo);
    
    $retorno["ruta"] = $rutaArchivo;
} catch (Exception $ex) {
    $retorno["exito"] = 0;
    $retorno["mensaje"] = $ex -> getMessage();
}
echo json_encode($retorno);
?>
