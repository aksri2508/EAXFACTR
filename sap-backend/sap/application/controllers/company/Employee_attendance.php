<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Employee_attendance.php
* @Class  			 : Employee_attendance
* Model Name         : Employee_attendance
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 30 
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : -
* Features           : 
*/
class Employee_attendance extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
		$this->config->load('table_config/tbl_employee_attendance.php');
        $this->load->model('company/employee_attendance_model', 'nativeModel');
    }
	
	
	/**
	* @METHOD NAME 	: punchInEmployeeAttendance()
	*
	* @DESC 		: PUNCH IN EMPLOYEE ATTENDANCE 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function punchInEmployeeAttendance()
    {
        // Params from http request
        $this->checkRequestMethod("post"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$getData 		       = $this->currentRequestData;
		
			$modelOutput 	   = $this->nativeModel->punchInEmployeeAttendance($getData);
			
			if (1 == $modelOutput['flag']) {
				$outputData['punchInId']    = $modelOutput['punchInId'];
				$outputData['status']       = "SUCCESS";
				$outputData['message']      = lang('MSG_147');
			} else if (2 == $modelOutput['flag']) {
				$outputData['message']      = lang('MSG_148');
			} else if (3 == $modelOutput['flag']) {
				$outputData['message']      = lang('MSG_149'); 
			} else if (4 == $modelOutput['flag']) {
				$outputData['message']      = lang('MSG_150'); 
			} else if (5 == $modelOutput['flag']) {
				$outputData['message']      = lang('MSG_153'); 
			}
        $this->output->sendResponse($outputData);
    }
    
	
	/**
	* @METHOD NAME 	: punchOutEmployeeAttendance()
	*
	* @DESC 		: PUNCH OUT EMPLOYEE ATTENDANCE 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function punchOutEmployeeAttendance()
    {
        // Params from http request
        $this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$getData 		       = $this->currentRequestData;

			$modelOutput 	   = $this->nativeModel->punchOutEmployeeAttendance($getData);
			
			if (1 == $modelOutput['flag']) {
				$outputData['status']       = "SUCCESS";
				$outputData['message']      = lang('MSG_151');
			} else if (2 == $modelOutput['flag']) {
				$outputData['message']      = lang('MSG_152'); // Record Already Exists
			} else if (3 == $modelOutput['flag']) {
				$outputData['message']      = lang('MSG_154'); // Record Already Exists
			}else if (4 == $modelOutput['flag']) {
				$outputData['message']      = lang('MSG_155'); // Record Already Exists
			}
		
        $this->output->sendResponse($outputData);
    }
	
	
	/**
	* @METHOD NAME 	: getEmployeeAttendanceList()
	*
	* @DESC 		: TO GET THE EMPLOYEE ATTENDANCE LIST
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getEmployeeAttendanceList()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
        $modelOutput           		= $this->nativeModel->getEmployeeAttendanceList($this->currentRequestData);
		
		// FRAME OUTPUT
        $outputData['results']      = $modelOutput;
        $outputData['status']       = "SUCCESS";
		
        $this->output->sendResponse($outputData);
    }
	
	
	/**
	 * @METHOD NAME 	: downloadExcel()
	 *
	 * @DESC 			: TO DOWNLOAD THE EXCEL FORMAT
	 * @RETURN VALUE	: $outputdata array
	 * @PARAMETER 		: -
	 * @SERVICE      	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function downloadExcel()
	{
		$modelOutput	= $this->nativeModel->getEmployeeAttendanceList($this->currentRequestData, 1);
		$resultsData 	= $modelOutput['searchResults'];
		$fileName		= $this->config->item('EMPLOYEE_ATTENDANCE')['excel_file_name'];
		
		foreach ($resultsData as $resultKey => $resultValue) {
			$resultsData[$resultKey]['first_name'] = $resultValue['empName'];	
		}

	
		$outputData 	= processExcelData($resultsData, $fileName, $this->config->item('EMPLOYEE_ATTENDANCE')['columns_list']);
		$this->output->sendResponse($outputData);
	}
	
	
	/**
	* @METHOD NAME 	: getEmployeeLastPunchInId()
	*
	* @DESC 		: TO GET THE EMPLOYEE LAST PUNCH IN ID 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	 public function getEmployeeLastPunchInId()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";
        $modelOutput           = $this->nativeModel->getEmployeeLastPunchInId($this->currentRequestData);
		
		if (1 == $modelOutput['flag']) {
			$outputData['status']       = "SUCCESS";
			$outputData['punchInId']    = $modelOutput['punchInId'];
		} else if (2 == $modelOutput['flag']) {
			$outputData['message']      = lang('MSG_158');
		}
			
        $this->output->sendResponse($outputData);
    }
	
	
}
?>