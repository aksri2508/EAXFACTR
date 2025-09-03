<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : User_defined_fields.php
* @Class  			 : User_defined_fields
* Model Name         : User_defined_fields
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 19 JUNE 2019
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : -
* Features           : 
*/
class User_defined_fields extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
		$this->config->load('table_config/tbl_udf_master_form_controls.php');
		$this->config->load('table_config/tbl_udf_master_form_controls_options.php');
		$this->config->load('table_config/tbl_udf_screen_mapping.php');
        $this->load->model('company/User_defined_fields_model', 'nativeModel');
    }
	
	
	/**
	* @METHOD NAME 	: getUdfScreens()
	*
	* @DESC 		: TO GET THE UDF SCREENS 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getUdfScreens()
    {
		
		$this->checkRequestMethod("put"); // Check the Request Method
		
		$passSearchData['enable_udf'] = 1; 
		$screenDetails   			  = $this->commonModel->getModuleScreenMappingDetails($passSearchData);
		
		// FRAME OUTPUT
        $outputData['results']      = $screenDetails;
        $outputData['status']       = "SUCCESS";
		
        $this->output->sendResponse($outputData);
		
    }
	
	
	/**
	* @METHOD NAME 	: getUdfFieldType()
	*
	* @DESC 		: TO GET THE UDF FIELD TYPE 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getUdfFieldType()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
        $modelOutput           = $this->nativeModel->getUdfFieldType();

		// FRAME OUTPUT
        $outputData['results']      = $modelOutput;
        $outputData['status']       = "SUCCESS";
		
        $this->output->sendResponse($outputData);
    }
	
	
	/**
	* @METHOD NAME 	: saveFormControls()
	*
	* @DESC 		: TO SAVE THE FORM CONTROLS 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function saveFormControls()
    {
        // Params from http request
        $this->checkRequestMethod("post"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$getData 		       = $this->currentRequestData;

			$modelOutput 	   = $this->nativeModel->saveFormControls($getData);
			
			if (1 == $modelOutput['flag']) {
				$outputData['status']       = "SUCCESS";
				$outputData['message']      = lang('MSG_136'); 	// Successfully Inserted
			} else if (3 == $modelOutput['flag']) {
				$outputData['message']      = lang('GLB_009');  // UNABLE TO SAVE THE RECORD
			}else if (2 == $modelOutput['flag']) {
				$outputData['message']      = lang('MSG_141');  // FIELD NAME ALREADY EXISTS 
			}
		
        $this->output->sendResponse($outputData);
    }
	
    /**
	* @METHOD NAME 	: updateFormControls()
	*
	* @DESC 		: TO UPDATE THE FORM CONTROLS 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function updateFormControls()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$modelOutput		   = $this->nativeModel->updateFormControls($this->currentRequestData);
		
		if (1 == $modelOutput['flag']) {
			$outputData['status']       = "SUCCESS";
			$outputData['message']      = lang('MSG_137'); //'Successfully updated
		}else if (2 == $modelOutput['flag']) {
			$outputData['message']      = lang('GLB_010'); // Unable to update the record
		}else if (3 == $modelOutput['flag']) {
			$outputData['message']      = lang('MSG_140'); // 
		}else if (4 == $modelOutput['flag']) {
			$outputData['message']      = lang('MSG_141');  // FIELD NAME ALREADY EXISTS 
		}
        $this->output->sendResponse($outputData);
    }
	
	
	/**
	* @METHOD NAME 	: editFormControls()
	*
	* @DESC 		: TO EDIT THE FORM CONTROLS DETAILS 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function editFormControls()
    {
		$outputData['status']  = "FAILURE";

        // PARAMS FROM HTTP REQUEST
         if (!empty($this->currentRequestData['id']) && is_numeric($this->currentRequestData['id'])) {
            
			$id						= $this->currentRequestData['id'];
			$modelOutput 			= $this->nativeModel->editFormControls($id);
			$getFormControlOptions 	= $this->nativeModel->getFormControlOptionsList($id);
			
			if (count($modelOutput) > 0) {
			
				$data 					= array();
				$fieldTypeDetails   	= $this->nativeModel->getUdfFieldType($modelOutput[0]['field_type_id']);
				
				// PASS EDIT FORMATION		
				$modelOutput[0]['fieldTypeInfo']  = $fieldTypeDetails;
				
				$data				  	= $modelOutput[0];
				$data['selectOptionListArray'] 		= $getFormControlOptions;
				$outputData['status']   = "SUCCESS";
            	$outputData['results']  = $data;
				
            }else {
                $outputData['message'] =  lang('GLB_015');  // INVALID ID PASSED
            }			
        } else { 
            $outputData['message']      = lang('GLB_007'); // INVALID PARAMETERS
        }	
        $this->output->sendResponse($outputData);
    }
	
	
	/**
	* @METHOD NAME 	: deleteFormControls()
	*
	* @DESC 		: TO DELETE THE FORM CONTROLS 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function deleteFormControls()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";
        
        if (!empty($this->currentRequestData['id']) && is_numeric($this->currentRequestData['id'])) {
            $modelOutput = $this->nativeModel->deleteFormControls($this->currentRequestData,1);
            if (1 == $modelOutput['flag'] ) {
                $outputData['status']       = "SUCCESS";
                $outputData['message']      =  lang('MSG_138'); //'Successfully Deleted
            } else if (2 == $modelOutput['flag'] ) {
                $outputData['message']      = lang('GLB_011'); // Unable to delete. Please try again later.
            }
        } else {
            $outputData['message']      = lang('GLB_007'); // Invalid Paremeters
        }
        $this->output->sendResponse($outputData);
    }
	
	
	/**
	* @METHOD NAME 	: getFormControlsList()
	*
	* @DESC 		: TO GET THE FORM CONTROL LIST
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getFormControlsList()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
        $modelOutput           = $this->nativeModel->getFormControlsList($this->currentRequestData);
		
		// FRAME OUTPUT
        $outputData['results']      = $modelOutput;
        $outputData['status']       = "SUCCESS";
		
        $this->output->sendResponse($outputData);
    }
	
	
////////////////////////////// SCREEN MAPPING 	////////////////////////////////////////////////////////////
	/**
	* @METHOD NAME 	: getFormControlsDetailsByScreen()
	*
	* @DESC 		: TO GET THE FORM CONTROLS FOR SCREEN 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getFormControlsDetailsByMappingScreen()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
        $screenFormControlList	= $this->nativeModel->getFormControlsDetailsByMappingScreen($this->currentRequestData);
        $allFormControlList		= $this->nativeModel->getAllFormControls();
		
		$selectedFormControlDetails = array();
		$screenMappingId 			= '';
		
			
		if(count($screenFormControlList)>0){
			
			
			$screenFormControlId 	 = $screenFormControlList[0]['form_controls_id'];
			$screenMappingId		 = $screenFormControlList[0]['id'];
			
			if(!empty($screenFormControlList[0]['form_controls_id'])){
			
				$screenFormControlArray  = explode(",",$screenFormControlId);
				
				// REMOVE SELECTED FORM CONTROLS IN THE LIST
				foreach($allFormControlList as $allKey=> $allValue){
					if(in_array($allValue['id'],$screenFormControlArray)){
						unset($allFormControlList[$allKey]);
					}
				}
				
				
				// FORM SELECTED CONTROLS DATA 
				$selectedFormControlDetails = $this->nativeModel->getAllFormControls($screenFormControlId);
				
				//printr($selectedFormControlDetails);exit;
				
				array_splice($allFormControlList, 0, 0);
			}
		}
		
		
		// Model Output Details 
		$modelOutput['selectedFormControlList']	= $selectedFormControlDetails;
		$modelOutput['allFormControlList']		= $allFormControlList;
		$modelOutput['screenMappingId']			= $screenMappingId;
		
		// FRAME OUTPUT
        $outputData['results']      = $modelOutput;
        $outputData['status']       = "SUCCESS";
		
        $this->output->sendResponse($outputData);
    }
	
	
	/**
	* @METHOD NAME 	: updateScreenMapping()
	*
	* @DESC 		: TO UPDATE THE SCREEN MAPPING 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function updateScreenMapping()
    {
        // Params from http request
        $this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$getData 		       = $this->currentRequestData;

			$modelOutput 	   = $this->nativeModel->updateScreenMapping($getData);
			
			if (1 == $modelOutput['flag']) {
				$outputData['status']       = "SUCCESS";
				$outputData['message']      = lang('MSG_139'); 	// Successfully Updated
			} else if (3 == $modelOutput['flag']) {
				$outputData['message']      = lang('GLB_009');  // UNABLE TO SAVE THE RECORD
			}
		
        $this->output->sendResponse($outputData);
    }

//////////////// END OF SCREEN MAPPING 	////////////////////////////////////////////////////////////
//////////////// ON SCREEN SERVICES 	////////////////////////////////////////////////////////////
	/**
	* @METHOD NAME 	: getOnScreenFormControls()
	*
	* @DESC 		: ON SCREEN FORM CONTROLS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getOnScreenFormControls()
    {
        // Params from http request
        $this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$getData 		       = $this->currentRequestData;
		$modelOutput 	  	   = $this->nativeModel->getOnScreenFormControls($this->currentRequestData);
				
		$outputData['results']      = $modelOutput;
        $outputData['status']       = "SUCCESS";
		
        $this->output->sendResponse($outputData);
    }

}
?>