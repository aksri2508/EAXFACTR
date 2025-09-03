<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Approval_templates.php
* @Class  			 : Approval_templates
* Model Name         : Approval_templates
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
class Approval_templates extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
		$this->config->load('table_config/tbl_approval_templates.php');
        $this->load->model('company/approval_templates_model', 'nativeModel');
    }
	
	
	/**
	* @METHOD NAME 	: saveApprovalTemplate()
	*
	* @DESC 		: TO SAVE THE APPROVAL TEMPLATE
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function saveApprovalTemplate()
    {
        // Params from http request
        $this->checkRequestMethod("post"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$getData 		       = $this->currentRequestData;
		$modelOutput 	  	   = $this->nativeModel->saveApprovalTemplate($getData);
			
			if (1 == $modelOutput['flag']) {
				$outputData['status']       = "SUCCESS";
				$outputData['message']      = lang('MSG_335'); // Successfully Inserted
			} else if (2 == $modelOutput['flag']) {
				$outputData['message']      = lang('MSG_336'); // Template name Exists
			} else if (3 == $modelOutput['flag']) {
				$outputData['message']      = lang('MSG_342'); // Originator and Document Id Already Exists
			} else if (4 == $modelOutput['flag']) {
				$outputData['message']      = lang('MSG_343'); // Originator and Document Id Already Exists
			}

        $this->output->sendResponse($outputData);
    }
    
	
	/**
	* @METHOD NAME 	: updateApprovalTemplate()
	*
	* @DESC 		: TO UPDATE THE APPROVAL TEMPLATE
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function updateApprovalTemplate()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";
        
		$modelOutput = $this->nativeModel->updateApprovalTemplate($this->currentRequestData);
		if (1 == $modelOutput['flag']) {
			$outputData['status']       = "SUCCESS";
			$outputData['message']      = lang('MSG_337'); //'Successfully saved
		}else if (2 == $modelOutput['flag']) {
			$outputData['message']      = lang('GLB_010'); // Unable to update the record
		}else if (3 == $modelOutput['flag']) {
			$outputData['message']      = lang('MSG_336'); // Record Already Exists
		}
        $this->output->sendResponse($outputData);
    }
	
	
	/**
	* @METHOD NAME 	: editApprovalTemplate()
	*
	* @DESC 		: TO EDIT APPROVAL TEMPLATE
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function editApprovalTemplate()
    {
        $this->checkRequestMethod("put"); 	// CHECK THE REQUEST METHOD
		$outputData['status']  = "FAILURE";
        
         // PARAMS FROM HTTP REQUEST
        if (!empty($this->currentRequestData['id']) && is_numeric($this->currentRequestData['id'])) {
            
            $modelOutput = $this->nativeModel->editApprovalTemplate($this->currentRequestData);
			
			$statusInfoDetails		= array();
			$orginatorEmpDetails 	= array();
			$documentInfoDetails 	= array();
			$approvalStagesInfoDetails = array();


            if (count($modelOutput) > 0) {

				// Get Orginator Details 
				if(!empty($modelOutput[0]['originator_id'])){

					$originatorDetails	=  explode(",",$modelOutput[0]['originator_id']);
					$cnt				= 0;
					foreach($originatorDetails as $originatorKey => $originatorValue){
						
						$getOriginatorDetailsInfo = array(	
														'getEmployeeList' 	 => $originatorValue,
													);
						
						$getOriginatorResult  = getAutoSuggestionListHelper($getOriginatorDetailsInfo);	
						
						if(!empty($getOriginatorResult['employeeInfo']))
						{	
							$orginatorEmpDetails[$cnt] =  $getOriginatorResult['employeeInfo'][0];
							$cnt++;
						}	
					}
				}

				// Get document details 
				if(!empty($modelOutput[0]['document_id'])){

					$documentDetails	=  explode(",",$modelOutput[0]['document_id']);
					$cnt				= 0;
					foreach($documentDetails as $documentKey => $documentValue){
						
						$getDocumentDetailsInfo = array(	
														'getDocumentTypeList' 	 => $documentValue,
													);
						
						$getDocumentResult  = getAutoSuggestionListHelper($getDocumentDetailsInfo);	
						
						if(!empty($getDocumentResult['documentTypeInfo']))
						{	
							$documentInfoDetails[$cnt] =  $getDocumentResult['documentTypeInfo'][0];
							$cnt++;
						}	
					}
				}

				// GET APPROVAL STAGE DETAILS 
				$getApprovalStagesInfo = array(	
					'getApprovalStagesList' 	 => $modelOutput[0]['approval_stages_id'],
				);

				$getApprovalStagesResult  = getAutoSuggestionListHelper($getApprovalStagesInfo);	

				if(isset($getApprovalStagesResult['approvalStagesInfo'])){
						$approvalStagesInfoDetails = $getApprovalStagesResult['approvalStagesInfo'][0];
				}

				$statusInfoDetails['approvalStagesInfo'] 	= $approvalStagesInfoDetails;
				$statusInfoDetails['documentInfo'] 			= $documentInfoDetails;
				$statusInfoDetails['orginatorInfo'] 		= $orginatorEmpDetails;
				$result  									= array(array_merge($modelOutput[0],$statusInfoDetails));

				// printr($authorizedEmpDetails);exit;
                $outputData['status']       = "SUCCESS";
                $outputData['results']      = $result;
            } else {
                $outputData['message']      =  lang('GLB_015');  // INVALID ID PASSED 
            }
        } else { 
            $outputData['message']      = lang('GLB_007'); // INVALID PARAMETERS
        }	
        $this->output->sendResponse($outputData);
    }

    
	/**
	* @METHOD NAME 	: getApprovalTemplateList()
	*
	* @DESC 		: TO GET THE APPROVAL TEMPLATE LIST
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getApprovalTemplateList()
    {
        $this->checkRequestMethod("put");
        $modelOutput           = $this->nativeModel->getApprovalTemplateList($this->currentRequestData);
		
		// FRAME OUTPUT
        $outputData['results']      = $modelOutput;
        $outputData['status']       = "SUCCESS";
		
        $this->output->sendResponse($outputData);
    }
		
}
