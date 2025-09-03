<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Master_dimension.php
* @Class  			 : Master_dimension
* Model Name         : Master_dimension
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
class Master_dimension extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
		
        $this->load->model('company/master_dimension_model', 'nativeModel');
    }
		
	
	/**
	* @METHOD NAME 	: updateDimension()
	*
	* @DESC 		: TO UPDATE THE DIMENSION
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function updateDimension()
    {
        // Params from http request
        $this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$getData 		       = $this->currentRequestData;

			$modelOutput 	   = $this->nativeModel->updateDimension($getData);
			
			if (1 == $modelOutput['flag']) {
				$outputData['status']       = "SUCCESS";
				$outputData['message']      = lang('MSG_103'); // DIMENSION UPDATED
			} else if (2 == $modelOutput['flag']) {
				$outputData['message']      = lang('GLB_009'); // UNABLE TO SAVE THE RECORD
			}
        $this->output->sendResponse($outputData);
    }
    
	
	/**
	* @METHOD NAME 	: getDimensionList()
	*
	* @DESC 		: TO GET THE DIMENSION LIST DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getDimensionList()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
        $modelOutput          		= $this->nativeModel->getDimensionList($this->currentRequestData);
		
		// FRAME OUTPUT
        $outputData['results']      = $modelOutput;
        $outputData['status']       = "SUCCESS";
		
        $this->output->sendResponse($outputData);
    }
	
}
