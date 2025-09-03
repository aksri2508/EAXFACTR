<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Master_location.php
* @Class  			 : Master_location
* Model Name         : Master_location
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
class Master_location extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
		$this->config->load('table_config/tbl_master_location.php');
        $this->load->model('company/master_location_model', 'nativeModel');
    }
	
	
	/**
	* @METHOD NAME 	: saveLocation()
	*
	* @DESC 		: TO SAVE THE Location
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function saveLocation()
    {
        // Params from http request
        $this->checkRequestMethod("post"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$getData 		       = $this->currentRequestData;

			$modelOutput 	   = $this->nativeModel->saveLocation($getData);
			
			if (1 == $modelOutput['flag']) {
				$outputData['status']       = "SUCCESS";
				$outputData['message']      = lang('MSG_205'); // Successfully Inserted
			} else if (2 == $modelOutput['flag']) {
				$outputData['message']      = lang('GLB_009'); // UNABLE TO SAVE THE RECORD 
			} else if (3 == $modelOutput['flag']) {
				$outputData['message']      = lang('MSG_206'); // NAME ALREADY EXISTS 
			} 
        $this->output->sendResponse($outputData);
    }
    
	
	/**
	* @METHOD NAME 	: updateLocation()
	*
	* @DESC 		: TO UPDATE THE LOCATION
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function updateLocation()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";
        
		$modelOutput = $this->nativeModel->updateLocation($this->currentRequestData);
		if (1 == $modelOutput['flag']) {
			$outputData['status']       = "SUCCESS";
			$outputData['message']      = lang('MSG_207'); //'Successfully updated
		}else if (3 == $modelOutput['flag']) {
			$outputData['message']      = lang('MSG_206'); // Record Already Exists
		}
        $this->output->sendResponse($outputData);
    }
	
	
	/**
	* @METHOD NAME 	: editLocation()
	*
	* @DESC 		: TO EDIT LOCATION DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function editLocation()
    {
        $this->checkRequestMethod("put"); 	// CHECK THE REQUEST METHOD
		$outputData['status']  = "FAILURE";
        
         // PARAMS FROM HTTP REQUEST
        if (!empty($this->currentRequestData['id']) && is_numeric($this->currentRequestData['id'])) {
            
            $modelOutput = $this->nativeModel->editLocation($this->currentRequestData);
            
            if (count($modelOutput) > 0) {				

				// FRAME ALL THE INFO DATA :  SHIP TO STATE INFO 
				$statusInfoDetails	= array();
				$getInfoData 		= array(
												'getStateList' 		 	=> $modelOutput[0]['state_id']
											);
				$statusInfoDetails	= getAutoSuggestionListHelper($getInfoData);		
					
				$data 				  = array();
		        $data				  = array_merge($modelOutput[0],$statusInfoDetails);
				
                $outputData['status']       = "SUCCESS";
                $outputData['results']      = $data;
            } else {
                $outputData['message']      =  lang('GLB_015');  // INVALID ID PASSED 
            }
        } else { 
            $outputData['message']      = lang('GLB_007'); // INVALID PARAMETERS
        }
        $this->output->sendResponse($outputData);
    }

    
	/**
	* @METHOD NAME 	: getLocationList()
	*
	* @DESC 		: TO GET THE LOCATION LIST 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getLocationList()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
        $modelOutput           = $this->nativeModel->getLocationList($this->currentRequestData);
		
		// FRAME OUTPUT
        $outputData['results']      = $modelOutput;
        $outputData['status']       = "SUCCESS";
		
        $this->output->sendResponse($outputData);
    }
	
}
?>