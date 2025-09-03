<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* 
 *  ======================================= 
 *  Author     : Muhammad Surya Ikhsanudin 
 *  License    : Protected 
 *  Email      : mutofiyah@gmail.com 
 *   
 *  Dilarang merubah, mengganti dan mendistribusikan 
 *  ulang tanpa sepengetahuan Author 
 *  ======================================= 
 */  
require_once "third_party/PHPExcel.php"; 
 
class Excel extends PHPExcel { 
	protected $headerStyle = [];
	
	protected $file = 'report.xlsx';
	
	protected $dowloadType = 'file';
	
    public function __construct()
    { 
        parent::__construct();
        
        $this->setDefaultHeaderStyle();
    }
    
    protected function setDefaultHeaderStyle()
    {
    	$this->headerStyle = [
    		'font'      => ['bold' => true],
    		'alignment' => ['horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER],
    	];
    	
    	return $this;
    }
    
    public function setHeaderStyle(Array $style)
    {
    	$this->headerStyle += style;
    	return $this;
    }
    
    public function setFile($name)
    {
    	$this->file = $name;
    	return $this;
    }
    
    public function setDownloadType($type)
    {
    	$this->downloadType = $type;
    	return $this;
    }
    
	
    public function doExport(Array $data, Array $columns = [])
    {
    	//What to do with empty data & columns
    	if (empty($columns) && empty($data)) {
    		return false;
    	}
    	
    	//if empty, prepare header from data
    	if (empty($columns)) {
    		$columns = array_combine(
    			array_keys($data[0]), array_keys($data[0])
    		);
    	}
    	
    	// get Sheet
    	$sheet = $this->getSheet(0);
    	
    	//Set AutoSize
    	for($i=0; $i<count($columns); $i++) {
    		$lastCol = PHPExcel_Cell::stringFromColumnIndex($i);
    		$sheet->getColumnDimension($lastCol)->setAutoSize(true);
    	}
    	
    	//Apply header style
    	$sheet->getStyle('A1:'.$lastCol.'1')->applyFromArray($this->headerStyle);
    	
    	//headers
    	$sheet->fromArray($columns, ' ', 'A1');
    	
    	//Data
    	$i = 2;
    	foreach($data as $temp) {
    		$row = elements(array_keys($columns), $temp, NULL);
    		$sheet->fromArray($row, ' ', 'A' . $i);
    		$i++;
    	}
    	
    	//get the writer
    	$writer = \PHPExcel_IOFactory::createWriter($this, 'Excel2007');
    	
    	// Avoid fatel error for uncaught exception
    	try {
    		if ('direct' == $this->downloadType)
    		{
    			//Direct output
    			@ob_end_clean();
    			ob_start();
				$writer->save('php://output');
				$content = ob_get_contents();
				ob_end_clean();
				force_download(basename($this->file), $content);
    		}
    		else if('saveDownload' == $this->downloadType)
    		{
    			// File based
				/*
				 * TODO: need to delete the file after download.
				 * 1. Use /tmp dir (system may clear it automatically)
				 * 2. delete the files by cron
				*/
				$writer->save($this->file);
				force_download($this->file, NULL);
    		}else if('saveFile'== $this->downloadType){
				$writer->save($this->file); // SAVE THE FILE TO THE REPOSITORY
			}
		}catch(Exception $e) {
			log_message('debug', $e->getMessage());
			return false;
		}
    }


	// Custom Excel Function to Generate for Trasport Report Download.
	public function doExportCustom(Array $data, Array $columns = [], $headerRowData = null, $footerRowData = null)
    {
    	//What to do with empty data & columns
    	if (empty($columns) && empty($data)) {
    		return false;
    	}
    	
    	//if empty, prepare header from data
    	if (empty($columns)) {
    		$columns = array_combine(
    			array_keys($data[0]), array_keys($data[0])
    		);
    	}
    	
    	// get Sheet
    	$sheet = $this->getSheet(0);
    	
    	//Set AutoSize
    	for($i=0; $i<count($columns); $i++) {
    		$lastCol = PHPExcel_Cell::stringFromColumnIndex($i);
    		$sheet->getColumnDimension($lastCol)->setAutoSize(true);
    	}
    	
    	//Apply header style
    	$sheet->getStyle('A1:'.$lastCol.'1')->applyFromArray($this->headerStyle);
    	$sheet->getStyle('A4:'.$lastCol.'4')->applyFromArray($this->headerStyle);
    	

		$sheet->SetCellValue('A1', $headerRowData);

    	//headers
    	$sheet->fromArray($columns, ' ', 'A4');
    	
		
    	//Data
    	$i = 5;
    	foreach($data as $temp) {
    		$row = elements(array_keys($columns), $temp, NULL);
    		$sheet->fromArray($row, ' ', 'A' . $i);
    		$i++;
    	}

		$sheet->SetCellValue('A'.($i+1), $footerRowData);

    	
    	//get the writer
    	$writer = \PHPExcel_IOFactory::createWriter($this, 'Excel2007');
    	
    	// Avoid fatel error for uncaught exception
    	try {
    		if ('direct' == $this->downloadType)
    		{
    			//Direct output
    			@ob_end_clean();
    			ob_start();
				$writer->save('php://output');
				$content = ob_get_contents();
				ob_end_clean();
				force_download(basename($this->file), $content);
    		}
    		else if('saveDownload' == $this->downloadType)
    		{
    			// File based
				/*
				 * TODO: need to delete the file after download.
				 * 1. Use /tmp dir (system may clear it automatically)
				 * 2. delete the files by cron
				*/
				$writer->save($this->file);
				force_download($this->file, NULL);
    		}else if('saveFile'== $this->downloadType){
				$writer->save($this->file); // SAVE THE FILE TO THE REPOSITORY
			}
		}catch(Exception $e) {
			log_message('debug', $e->getMessage());
			return false;
		}
    }


	public function doImport($fileName)
    {
	
		try{
			$inputFileType  =   PHPExcel_IOFactory::identify($fileName);
			$objReader      =   PHPExcel_IOFactory::createReader($inputFileType);
			// print_r($objReader);exit;
			$objPHPExcel    =   $objReader->load($fileName);
		}catch(Exception $e){
			die('Error loading file "'.pathinfo($fileName,PATHINFO_BASENAME).'": '.$e->getMessage());
		}

		return $objPHPExcel;

	}



	public function readExcel($objPHPExcel, $highestColumn = null)
    {
		$formatCode = $objPHPExcel->getActiveSheet()
    ->getStyle('A1')
    ->getNumberFormat();

	// print_r($formatCode);
	// exit;
  

		$sheet = $objPHPExcel->getActiveSheet(); 
		$highestRow = $sheet->getHighestRow(); 
		if($highestColumn == null){
			$highestColumn = $sheet->getHighestColumn();
		}

		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
		
        $fileData = array();
		//  Loop through each row of the worksheet.
		for ($row = 1; $row <= $highestRow; $row++){ 

			$cellData = array();
			for($j = 0; $j <= $highestColumnIndex; $j ++){
				$cellData[] = $sheet->getCellByColumnAndRow($j, $row)->getFormattedValue();
			}

			$fileData[] = $cellData;

			// //  Read a row of data into an array
			// $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
			// 								NULL,
			// 								TRUE,
			// 								FALSE);
			// // print_r($rowData[0]);exit;
			// $fileData[] = $rowData[0];
		}

        return $fileData;
	}

	
}
