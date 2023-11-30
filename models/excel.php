<?php

  date_default_timezone_set('Indian/Antananarivo');
  setlocale(LC_ALL, "fr_FR.utf8");

require_once dirname(__FILE__) . '/../classes/php_classes/PHPExcel.php';


	$objPHPExcel 	= new PHPExcel();
    $etatTitle 		= "Gestion pointage RH";

    $titre_			= 'Gestion pointage RH:   '.$num.' du '.date('d-m-Y',strtotime($date_debut)).' au '.date('d-m-Y',strtotime($date_fin));
    
    $objPHPExcel->getProperties()->setCreator("SANIFER - Etats informatisés")
            ->setLastModifiedBy("SANIFER - Etats informatisés")
            ->setTitle($etatTitle)
            ->setSubject($etatTitle)
            ->setDescription($etatTitle);

    $xlsxSheet 		= $objPHPExcel->setActiveSheetIndex(0);   
    $lin = 2;

    foreach ($el_ as $val) {
    	$colWidth = 'M';
        if ($lin == 2) {
            for ($col = 'A'; $col < $colWidth; ++$col) {
                $xlsxSheet->setCellValue($col . $lin, key($val));
                next($val);
            }
            ++$lin;
        }
        reset($val);
        if ($lin > 2) {
            for ($col = 'A'; $col < $colWidth; ++$col) {
                $xlsxSheet->setCellValue($col . $lin, current($val));
                next($val);
            }
        }
        reset($val);
        ++$lin;
    }
        $globalStyleArray = array(
	        'borders' => array(
	            'allborders' => array(
	                'style' => PHPExcel_Style_Border::BORDER_THIN,
	            ),
	            'font' => array(
	                'size' => 10,
	            )
	        )
	    );
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:M1');
        $xlsxSheet->setCellValue('A1', $titre_);
	    $xlsxSheet->getStyle('A2:' . $xlsxSheet->getHighestColumn() . $xlsxSheet->getHighestRow())->applyFromArray($globalStyleArray);

	    $xlsxSheet
	            ->getStyle($xlsxSheet->calculateWorksheetDimension())
	            ->getAlignment()
	            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	    $xlsxSheet
	            ->getStyle($xlsxSheet->calculateWorksheetDimension())
	            ->getAlignment()
	            ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);


	    $header_bg_color = "7aa2e2";

	    $headerStyleArray = array(
	        'fill' => array(
	            'type' => PHPExcel_Style_Fill::FILL_SOLID,
	            'color' => array('rgb' => $header_bg_color),
	        ),
	        'font' => array(
	            'bold' => true,
	            'color' => array('rgb' => 'FFFFFF'),
	            'size' => 12,
	        )
	    );

	    $headerStyleArray2 = array(
	        'fill' => array(
	            'type' => PHPExcel_Style_Fill::FILL_SOLID
	        ),
	        'font' => array(
	            'bold' => true,
	            'color' => array('rgb' => '000000'),
	            'size' => 18,
	        )
	    );

	    $xlsxSheet->getStyle('A1:' . $xlsxSheet->getHighestColumn() . '1')->applyFromArray($headerStyleArray2);
	    $xlsxSheet->getStyle('A2:' . $xlsxSheet->getHighestColumn() . '2')->applyFromArray($headerStyleArray);

	    
	    $xlsxSheet->getStyle('A3:A' . $lin)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
	    $xlsxSheet->getStyle('B3:B' . $lin)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
	    $xlsxSheet->getStyle('C3:C' . $lin)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
	    $xlsxSheet->getStyle('D3:D' . $lin)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
	    $xlsxSheet->getStyle('E3:E' . $lin)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
	    $xlsxSheet->getStyle('F3:F' . $lin)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
	    $xlsxSheet->getStyle('G3:G' . $lin)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
	    $xlsxSheet->getStyle('H3:H' . $lin)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
	    $xlsxSheet->getStyle('I3:I' . $lin)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
	    $xlsxSheet->getStyle('J3:J' . $lin)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
	    $xlsxSheet->getStyle('K3:K' . $lin)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
	    $xlsxSheet->getStyle('L3:L' . $lin)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);

	    for ($col = 'A'; $col < $colWidth; ++$col)
	        $xlsxSheet->getColumnDimension($col)->setAutoSize(true);
	  
	    $sheetName = 'Export horaire du '.date('d-m-Y');
	    
	    $xlsxSheet->setTitle($sheetName);

	    $xlsxSheet->freezePane('A3');

	    $objPHPExcel->setActiveSheetIndex(0);

	    $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
	    $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
	    $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);
	    $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
	    $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);

	    $objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter($etatTitle . ' - Page &P / &N');
	    $objPHPExcel->getActiveSheet()->getHeaderFooter()->setEvenFooter($etatTitle . ' - Page &P / &N');


	    $excelFileName = "gestion_pointage_rh_".date('d-m-Y').".xlsx";

	  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	  header('Content-Disposition: attachment;filename="' . $excelFileName . '"');
	  header('Cache-Control: max-age=0');
	  header('Cache-Control: max-age=1');

	  header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); 
	  header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); 
	  header ('Cache-Control: cache, must-revalidate');
	  header ('Pragma: public'); 

	  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	  $objWriter->save('php://output');
	  exit;
	

?>