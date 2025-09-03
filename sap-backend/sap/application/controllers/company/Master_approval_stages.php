<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Master_approval_stages.php
* @Class  			 : Master_approval_stages
* Model Name         : Master_approval_stages
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
class Master_approval_stages extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
		$this->config->load('table_config/tbl_master_approval_stages.php');
        $this->load->model('company/master_approval_stages_model', 'nativeModel');
    }
	
	
	/**
	* @METHOD NAME 	: saveApprovalStage()
	*
	* @DESC 		: TO SAVE THE APPROVAL STAGE
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function saveApprovalStage()
    {
        // Params from http request
        $this->checkRequestMethod("post"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$getData 		       = $this->currentRequestData;
		
		// ADDITIONAL VALIDATION FOR EMPTY CHECK 
		if($getData['noOfApprovals'] == 0){
			$outputData['message']      = lang('MSG_344'); // Empty 
			return $this->output->sendResponse($outputData);
		}
				
		$modelOutput 	  	   = $this->nativeModel->saveApprovalStage($getData);
		
		if (1 == $modelOutput['flag']) {
			$outputData['status']       = "SUCCESS";
			$outputData['message']      = lang('MSG_331'); // Successfully Inserted
		} else if (2 == $modelOutput['flag']) {
			$outputData['message']      = lang('MSG_332'); // Record Already Exists
		} else if (3 == $modelOutput['flag']) {
			$outputData['message']      = lang('MSG_346');
		}
        $this->output->sendResponse($outputData);
    }
    
	
	/**
	* @METHOD NAME 	: updateApprovalStage()
	*
	* @DESC 		: TO UPDATE THE APPROVAL STAGE
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function updateApprovalStage()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";
        
		$modelOutput = $this->nativeModel->updateApprovalStage($this->currentRequestData);
		if (1 == $modelOutput['flag']) {
			$outputData['status']       = "SUCCESS";
			$outputData['message']      = lang('MSG_333'); //'Successfully saved
		}else if (2 == $modelOutput['flag']) {
			$outputData['message']      = lang('GLB_010'); // Unable to update the record
		}else if (3 == $modelOutput['flag']) {
			$outputData['message']      = lang('MSG_332'); // Record Already Exists
		}
        $this->output->sendResponse($outputData);
    }
	
	
	/**
	* @METHOD NAME 	: editApprovalStage()
	*
	* @DESC 		: TO EDIT STAGE DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function editApprovalStage()
    {
        $this->checkRequestMethod("put"); 	// CHECK THE REQUEST METHOD
		$outputData['status']  = "FAILURE";
        
         // PARAMS FROM HTTP REQUEST
        if (!empty($this->currentRequestData['id']) && is_numeric($this->currentRequestData['id'])) {
            
            $modelOutput = $this->nativeModel->editApprovalStage($this->currentRequestData);
			
			$statusInfoDetails		= array();
			$authorizedEmpDetails 	= array();

            if (count($modelOutput) > 0) {

				if(!empty($modelOutput[0]['authorizer_id'])){

					$authorizerId		 = $modelOutput[0]['authorizer_id'];
					$authorizerDetails   = explode(",",$authorizerId);
					$cnt = 0;
					foreach($authorizerDetails as $authorizerKey => $authorizerValue){
						
						$getAuthorizerDetailsInfo = array(	
														'getEmployeeList' 	 => $authorizerValue,
													);
						
						$getAuthorizerResult  = getAutoSuggestionListHelper($getAuthorizerDetailsInfo);	
						
						if(!empty($getAuthorizerResult['employeeInfo']))
						{	
							$authorizedEmpDetails[$cnt] =  $getAuthorizerResult['employeeInfo'][0];
							$cnt++;
						}	
					}
				}
				
			

				$statusInfoDetails['authorizerInfo'] 	= $authorizedEmpDetails;
				$result  					= array(array_merge($modelOutput[0],$statusInfoDetails));

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
	* @METHOD NAME 	: getApprovalStageList()
	*
	* @DESC 		: TO GET THE APPROVAL STAGE LIST
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getApprovalStageList()
    {
        $this->checkRequestMethod("put");
        $modelOutput           = $this->nativeModel->getApprovalStageList($this->currentRequestData);
		
		// FRAME OUTPUT
        $outputData['results']      = $modelOutput;
        $outputData['status']       = "SUCCESS";
		
        $this->output->sendResponse($outputData);
    }
		
}
