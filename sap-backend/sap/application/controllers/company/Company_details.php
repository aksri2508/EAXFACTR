<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Company_details.php
* @Class  			 : Company_details
* Model Name         : Company_details_model
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 02 JULY 2023
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : -
* Features           : 
*/
class Company_details extends MY_Controller
{
    public function __construct()
    {

        parent::__construct();
		$this->config->load('table_config/tbl_company_details.php');
        $this->load->model('company/Company_details_model', 'nativeModel');
    }
		
	/**
	* @METHOD NAME 	: editCompanyDetails()
	*
	* @DESC 		: TO EDIT/VIEW COMPANY DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function editCompanyDetails()
    {

        $this->checkRequestMethod("put"); 	// CHECK THE REQUEST METHOD
		$outputData['status']  = "FAILURE";
        
         // PARAMS FROM HTTP REQUEST
        if (!empty($this->currentRequestData['id']) && is_numeric($this->currentRequestData['id'])) {
            
            $modelOutput = $this->nativeModel->editCompanyDetails($this->currentRequestData);
            
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
	* @METHOD NAME 	: updateCompanyDetails()
	*
	* @DESC 		: TO UPDATE THE COMPANY DETAILS IN THE STYEM
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function updateCompanyDetails()
    {

		$this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";
        
		$modelOutput = $this->nativeModel->updateCompanyDetails($this->currentRequestData);
		
		if (1 == $modelOutput['flag']) {
		
			$outputData['status']       = "SUCCESS";
			$outputData['message']      = lang('MSG_354'); //'Successfully updated
		}else if (2 == $modelOutput['flag']) {
			$outputData['message']      = lang('GLB_010'); // Unable to update the record
		}else if (3 == $modelOutput['flag']) {
			$outputData['message']      = lang('MSG_30'); // Record Already Exists
		}
        $this->output->sendResponse($outputData);
    }
		
}
?>