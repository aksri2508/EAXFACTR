<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Master_reason.php
* @Class  			 : Master_reason
* Model Name         : Master_reason
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 16 MAY 2019
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : -
* Features           : 
*/
class Master_payment_terms extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('company/master_payment_terms_model', 'nativeModel');
    }
	
	
	/**
	* @METHOD NAME 	: saveReason()
	*
	* @DESC 		: TO SAVE THE REASON DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function savePaymentTerms()
    {
        // Params from http request
        $this->checkRequestMethod("post"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$getData 		       = $this->currentRequestData;

			$modelOutput 	   = $this->nativeModel->savePaymentTerms($getData);
			
			if (1 == $modelOutput['flag']) {
				$outputData['status']       = "SUCCESS";
				$outputData['message']      = lang('MSG_160'); // Successfully Inserted
			} else if (2 == $modelOutput['flag']) {
				$outputData['message']      = lang('MSG_161'); // Record Already Exists
			}
		
        $this->output->sendResponse($outputData);
    }
    
	
	/**
	* @METHOD NAME 	: updateReason()
	*
	* @DESC 		: TO UPDATE THE REASON
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function updatePaymentTerms()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";
	
		$modelOutput = $this->nativeModel->updatePaymentTerms($this->currentRequestData);
		if (1 == $modelOutput['flag']) {
			$outputData['status']       = "SUCCESS";
			$outputData['message']      = lang('MSG_162'); //'Successfully saved
		}else if (2 == $modelOutput['flag']) {
			$outputData['message']      = lang('GLB_010'); // Unable to update the record
		}else if (3 == $modelOutput['flag']) {
			$outputData['message']      = lang('MSG_161'); // Record Already Exists
		}
        $this->output->sendResponse($outputData);
    }
	
    
	/**
	* @METHOD NAME 	: editReason()
	*
	* @DESC 		: TO EDIT REASON DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function editPaymentTerms()
    {
        $this->checkRequestMethod("put"); 	// CHECK THE REQUEST METHOD
		$outputData['status']  = "FAILURE";
        
         // PARAMS FROM HTTP REQUEST
        if (!empty($this->currentRequestData['id']) && is_numeric($this->currentRequestData['id'])) {
            
            $modelOutput = $this->nativeModel->editPaymentTerms($this->currentRequestData);
            
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
	* @METHOD NAME 	: getReasonList()
	*
	* @DESC 		: TO GET THE REASON LIST DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getPaymentTermsList()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
        $modelOutput           = $this->nativeModel->getPaymentTermsList($this->currentRequestData);
		
		// FRAME OUTPUT
        $outputData['results']      = $modelOutput;
        $outputData['status']       = "SUCCESS";
		
        $this->output->sendResponse($outputData);
    }

}
?>