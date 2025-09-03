<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Master_opportunity_type.php
* @Class  			 : Master_opportunity_type
* Model Name         : Master_opportunity_type
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 11 MAY 2018
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : -
* Features           : 
*/
class Master_opportunity_type extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
		$this->config->load('table_config/tbl_master_opportunity_type.php');
        $this->load->model('company/master_opportunity_type_model', 'nativeModel');
    }
	
	
	/**
	* @METHOD NAME 	: saveOpportunityType()
	*
	* @DESC 		: TO SAVE THE OPPORTUNITY TYPE DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function saveOpportunityType()
    {
        // Params from http request
        $this->checkRequestMethod("post"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$getData 		       = $this->currentRequestData;

			$modelOutput 	   = $this->nativeModel->saveOpportunityType($getData);
			
			if (1 == $modelOutput['flag']) {
				$outputData['status']       = "SUCCESS";
				$outputData['message']      = lang('MSG_97'); // Successfully Inserted
			} else if (2 == $modelOutput['flag']) {
				$outputData['message']      = lang('MSG_98'); // Record Already Exists
			}
        $this->output->sendResponse($outputData);
    }
    
	
	/**
	* @METHOD NAME 	: updateOpportunityType()
	*
	* @DESC 		: TO UPDATE THE OPPORTUNITY TYPE
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function updateOpportunityType()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";
        
		$modelOutput = $this->nativeModel->updateOpportunityType($this->currentRequestData);
		if (1 == $modelOutput['flag']) {
			$outputData['status']       = "SUCCESS";
			$outputData['message']      = lang('MSG_99'); //'Successfully updated
		}else if (2 == $modelOutput['flag']) {
			$outputData['message']      = lang('GLB_010'); // Unable to update the record
		}else if (3 == $modelOutput['flag']) {
			$outputData['message']      = lang('MSG_98'); // Record Already Exists
		}
        $this->output->sendResponse($outputData);
    }
	
	
	/**
	* @METHOD NAME 	: editOpportunityType()
	*
	* @DESC 		:  TO EDIT THE OPPORTUNITY TYPE
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function editOpportunityType()
    {
        $this->checkRequestMethod("put"); 	// CHECK THE REQUEST METHOD
		$outputData['status']  = "FAILURE";
        
         // PARAMS FROM HTTP REQUEST
        if (!empty($this->currentRequestData['id']) && is_numeric($this->currentRequestData['id'])) {
            
            $modelOutput = $this->nativeModel->editOpportunityType($this->currentRequestData);
            
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
	* @METHOD NAME 	: getOpportunityTypeList()
	*
	* @DESC 		: TO GET THE OPPORTUNITY TYPE DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getOpportunityTypeList()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
        $modelOutput           = $this->nativeModel->getOpportunityTypeList($this->currentRequestData);
		
		// FRAME OUTPUT
        $outputData['results']      = $modelOutput;
        $outputData['status']       = "SUCCESS";
		
        $this->output->sendResponse($outputData);
    }
}
