<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Smt_visits.php
* @Class  			 : Smt_visits
* Model Name         : Smt_visits
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 22 MAY 2019
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : -
* Features           : 
*/
class Smt_visits extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
		$this->config->load('table_config/tbl_smt_visits.php');
        $this->load->model('company/smt_visits_model', 'nativeModel');
    }
	
	
	/**
	* @METHOD NAME 	: checkInEmployeeVisit()
	*
	* @DESC 		: CHECK IN EMPLOYEE VISISTS 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function checkInEmployeeVisit()
    {
        // Params from http request
        $this->checkRequestMethod("post"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$getData 		       = $this->currentRequestData;

			$modelOutput 	   = $this->nativeModel->checkInEmployeeVisit($getData);
			
			if (1 == $modelOutput['flag']) {
				$outputData['status']       = "SUCCESS";
				$outputData['checkInId']    = $modelOutput['checkInId'];
				$outputData['message']      =  lang('MSG_90');// "Successfully checked in.";
			} else if (2 == $modelOutput['flag'] || 3 == $modelOutput['flag']) {
				$outputData['message']      =  lang('MSG_91'); // "Please, punch in your attendance." ;
			} else if (4 == $modelOutput['flag']) {
				$outputData['message']      =  lang('MSG_92'); // "you have not checked out your previous entry point." ; 
			}else if (5 == $modelOutput['flag']) {
				$outputData['message']      =  lang('MSG_95'); // "you already checked out for the particular day" ; 
			}
        $this->output->sendResponse($outputData);
    }
    
	
	/**
	* @METHOD NAME 	: checkOutEmployeeVisit()
	*
	* @DESC 		: CHECK OUT EMPLOYEE ATTENDANCE 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function checkOutEmployeeVisit()
    {
        // Params from http request
        $this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$getData 		       = $this->currentRequestData;

		if (!empty($this->currentRequestData['checkInId']) && is_numeric($this->currentRequestData['checkInId'])) {
			
			$modelOutput 	   = $this->nativeModel->checkOutEmployeeVisit($getData);
			
			if (1 == $modelOutput['flag']) {
				$outputData['status']       = "SUCCESS";
				$outputData['message']      = lang('MSG_93'); //"checkedOut Successfully";
			} else if (2 == $modelOutput['flag']) {
				$outputData['message']      =  lang('MSG_94'); //"Please check out Visits and try again";
			} else if (3 == $modelOutput['flag']) {
				$outputData['message']      =  lang('MSG_156'); 	// "InValid Id Passed 
			} else if (4 == $modelOutput['flag']) {
				$outputData['message']      =  lang('MSG_157'); // " Already checked out for the day  
			}
		}else {
            $outputData['message']      = lang('GLB_007'); // Invalid Paremeters
        }
        $this->output->sendResponse($outputData);
    }
	
	
    
	/**
	* @METHOD NAME 	: getVisitList()
	*
	* @DESC 		: TO GET THE VISIT LIST
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getVisitList()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
        $modelOutput           = $this->nativeModel->getVisitList($this->currentRequestData);
		
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
		$modelOutput	= $this->nativeModel->getVisitList($this->currentRequestData, 1);
		$resultsData 	= $modelOutput['searchResults'];
		$fileName		= $this->config->item('SMT_VISITS')['excel_file_name'];


		foreach ($resultsData as $resultKey => $resultValue) {
			$resultsData[$resultKey]['first_name'] = $resultValue['empName'];	
		}
		
		$outputData 	= processExcelData($resultsData, $fileName, $this->config->item('SMT_VISITS')['columns_list']);
		$this->output->sendResponse($outputData);
	}

	
	/**
	* @METHOD NAME 	: getEmployeeLastCheckInId()
	*
	* @DESC 		: TO GET THE EMPLOYEE LAST CHECK IN ID 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	 public function getEmployeeLastCheckInId()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";
        $modelOutput           = $this->nativeModel->getEmployeeLastCheckInId($this->currentRequestData);
		
		if (1 == $modelOutput['flag']) {
			$outputData['status']       = "SUCCESS";
			$outputData['checkInId']    = $modelOutput['checkInId'];
		} else if (2 == $modelOutput['flag']) {
			$outputData['message']      = lang('MSG_159');
		}
			
        $this->output->sendResponse($outputData);
    }
	
	
	
	
	
}
?>