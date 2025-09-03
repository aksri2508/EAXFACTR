<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Master_cost_center.php
* @Class  			 : Master_cost_center
* Model Name         : Master_cost_center
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : -
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : -
* Features           : 
*/
class Master_cost_center extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('company/master_cost_center_model', 'nativeModel');
    }
	
	
	/**
	* @METHOD NAME 	: saveCostCenter()
	*
	* @DESC 		: TO SAVE THE COST CENTER 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function saveCostCenter()
    {
        // Params from http request
        $this->checkRequestMethod("post"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$getData 		       = $this->currentRequestData;

			$modelOutput 	   = $this->nativeModel->saveCostCenter($getData);
			
			if (1 == $modelOutput['flag']) {
				$outputData['status']       = "SUCCESS";
				$outputData['message']      = lang('MSG_105'); // Successfully Inserted
			} else if (2 == $modelOutput['flag']) {
				$outputData['message']      = lang('MSG_106'); // Record Already Exists
			}
        $this->output->sendResponse($outputData);
    }
    
	
	/**
	* @METHOD NAME 	: updateCostCenter()
	*
	* @DESC 		: TO UPDATE THE COST CENTER
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function updateCostCenter()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";
        
		$modelOutput = $this->nativeModel->updateCostCenter($this->currentRequestData);
		if (1 == $modelOutput['flag']) {
			$outputData['status']       = "SUCCESS";
			$outputData['message']      = lang('MSG_107'); //'Successfully saved
		}else if (2 == $modelOutput['flag']) {
			$outputData['message']      = lang('GLB_010'); // Unable to update the record
		}else if (3 == $modelOutput['flag']) {
			$outputData['message']      = lang('MSG_106'); // Record Already Exists
		}
        $this->output->sendResponse($outputData);
    }
	

	/**
	* @METHOD NAME 	: deleteCostCenter()
	*
	* @DESC 		: TO DELETE THE COST CENTER
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function deleteCostCenter()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";
        
        if (!empty($this->currentRequestData['id']) && is_numeric($this->currentRequestData['id'])) {
            $modelOutput = $this->nativeModel->deleteCostCenter($this->currentRequestData);
            if (1 == $modelOutput['flag'] ) {
                $outputData['status']       = "SUCCESS";
                $outputData['message']      =  lang('MSG_108'); //'Successfully Deleted
            } else if (2 == $modelOutput['flag'] ) {
                $outputData['message']      = lang('GLB_011'); // Unable to delete. Please try again later.
            }
        } else {
            $outputData['message']      = lang('GLB_007'); // Invalid Paremeters
        }
        $this->output->sendResponse($outputData);
    }
    
    
	/**
	* @METHOD NAME 	: editCostCenter()
	*
	* @DESC 		: TO EDIT COST CENTER DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function editCostCenter()
    {
        $this->checkRequestMethod("put"); 	// CHECK THE REQUEST METHOD
		$outputData['status']  = "FAILURE";
        
         // PARAMS FROM HTTP REQUEST
        if (!empty($this->currentRequestData['id']) && is_numeric($this->currentRequestData['id'])) {
            
			$modelOutput = $this->nativeModel->editCostCenter($this->currentRequestData);
			  
			// PASS CURRENCY DATA 
			$passSearchData['category'] = 2;
			$passSearchData['delFlag']  = 0;
			$passDimensionData['id'] 	= $modelOutput[0]['dimension_id'];
			$dimensionDetails   		= $this->commonModel->getDimensionAutoList(
			array_merge($passSearchData,$passDimensionData));
		
			// PASS EMPLOYEE DATA 			
			$passEmployeeData['id'] 	  = $modelOutput[0]['emp_id'];
			$employeeDetails   	 		  = $this->commonModel->getEmployeeAutoList(array_merge($passSearchData,$passEmployeeData));
			
			// EMPLOYEE DETAILS 
			$modelOutput[0]['employeeInfo']			= $employeeDetails;
			$modelOutput[0]['dimensionInfo']		= $dimensionDetails;
			
            if (count($modelOutput) > 0) {
                $outputData['status']       = "SUCCESS";
                $outputData['results']      = $modelOutput;
            } else {
                $outputData['message']      =  lang('GLB_015');  // INVALID ID PASSED 
            }
        } else { 
            $outputData['message']      = lang('GLB_007'); // INVALID PARAMETERS
        }	
        $this->output->sendResponse($outputData);
    }

    
	/**
	* @METHOD NAME 	: getCostCenterList()
	*
	* @DESC 		: TO GET THE DIMENSION LIST DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getCostCenterList()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
        $modelOutput          		= $this->nativeModel->getCostCenterList($this->currentRequestData);
		
		// FRAME OUTPUT
        $outputData['results']      = $modelOutput;
        $outputData['status']       = "SUCCESS";
		
        $this->output->sendResponse($outputData);
    }
	
}