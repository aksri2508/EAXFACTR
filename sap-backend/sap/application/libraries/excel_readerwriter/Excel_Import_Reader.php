<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(__DIR__ . DIRECTORY_SEPARATOR
	. implode(DIRECTORY_SEPARATOR, ['third_party','PHPExcel','IOFactory.php'])
);

class Excel_Import_Reader {
	
	protected $columnConverter = [];
	protected $rowValidator    = NULL;
	
	protected $columns     = [];
	protected $activeSheet = NULL;
	protected $rawData     = [];
	
	public function __construct(array $config = [])
	{
		
	}
	
	public function setRowValidator(callable $callback)
	{
		$this->rowValidator = $callback;
		return $this;
	}
	
	public function setColumnConverter($converter)
	{
		if (is_callable($converter) || is_array($converter))
		{
			$this->columnConverter = $converter;
		}
		return $this;
	}
	
	public function read($file)
	{
		try {
			$reader   = PHPExcel_IOFactory::createReaderForFile($file);
			$phpExcel = $reader->setReadDataOnly(true)->load($file);
			
			$this->activeSheet = $phpExcel->getActiveSheet();
			
			return true;
		}catch (Exception $e) {
			log_message('debug', $e->getMessage());
			return false;
		}
	}
	
	public function validateColumns(array $columns)
	{
		return array_diff($columns, $this->getColumns());
	}
	
	protected function getColumns()
	{
		if (empty($this->columns))
		{
			$maxCol = $this->activeSheet->getHighestColumn();
			$this->columns = $this->activeSheet->rangeToArray('A1:'.$maxCol.'1', NULL, false, false, true);
			$this->columns = isset($this->columns[1]) ? $this->columns[1] : [];
			$this->columns = array_map('strtolower', $this->columns);
		}
		
		return $this->columns;
	}
	
	public function rowcount()
	{
		return $this->activeSheet->getHighestRow();
	}
	
	public function getData()
	{
		if (empty($columns = $this->getColumns()))
		{
			return [];
		}
		
        $maxCol = $this->activeSheet->getHighestColumn();
        $maxRow = $this->activeSheet->getHighestRow();
        
        $this->rawData = $this->activeSheet->rangeToArray(
        	'A2:'.$maxCol.$maxRow, NULL, false, false, true
        );
        
        if (empty($this->rawData))
        {
        	return [];
        }
        
		$data = [];
		$call = !is_null($this->rowValidator);
		
		foreach ($this->rawData as $index => $row)
		{
			foreach ($row as $k => $v)
			{
				$row[$k] = $this->value($v, $k);
			}
			$row   = array_combine($columns, $row);
			$valid = $call ? call_user_func_array($this->rowValidator, [$index, $row]) : true;
			if ($valid) {
				$data[$index] = $row;
			}
		}
		
		return $data;
	}
	
	public function value($value, $col)
	{
	
		$value = trim($value);
		
		if (is_callable($this->columnConverter)) {
			$value = call_user_func($this->columnConverter, $value);
		}
		
		if ( 	isset($this->columns[$col])
			&&	isset($this->columnConverter[$this->columns[$col]])
		){
			$value = call_user_func($this->columnConverter[$this->columns[$col]], $value);
		}
		
		return $value;
	}
	
	public function ExcelToPHPDate($value)
	{
		return date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($value));
	}
}
