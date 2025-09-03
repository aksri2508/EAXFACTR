<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Smt_team_member.php
* @Class  			 : Smt_team_member
* Model Name         : Smt_team_member
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
class Smt_team_member extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
		$this->config->load('table_config/tbl_smt_team_members.php');
        $this->load->model('company/smt_team_member_model', 'nativeModel');
    }
	
	
	/**
	* @METHOD NAME 	: saveTeamMember()
	*
	* @DESC 		: TO SAVE THE TEAM MEMBER DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function saveTeamMember()
    {
        // Params from http request
        $this->checkRequestMethod("post"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$getData 		       = $this->currentRequestData;

		$modelOutput 	   = $this->nativeModel->saveTeamMember($getData);

		if (1 == $modelOutput['flag']) {
			$outputData['status']    = "SUCCESS";
			$outputData['message']   = lang('MSG_86'); // Successfully Inserted
		} else if (2 == $modelOutput['flag']) {
			$errMsg  = lang('MSG_87');
			
			
			$outputData['message']   = $errMsg[0]." ".$modelOutput['teamName']; // Record Already Exists
		}
		
        $this->output->sendResponse($outputData);
    }
    
	
	/**
	* @METHOD NAME 	: updateTeamMember()
	*
	* @DESC 		: TO UPDATE THE TEAM DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function updateTeamMember()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";
        
		$modelOutput = $this->nativeModel->updateTeamMember($this->currentRequestData);

		
		if (1 == $modelOutput['flag']) {
			$outputData['status']       = "SUCCESS";
			$outputData['message']      = lang('MSG_88'); // Successfully Updated
		}else if (2 == $modelOutput['flag']) { // Team member already exists in another team
				
			// GET THE TEAM MEMBER DETAILS 
			$teamId  = $this->currentRequestData['teamId'];
			
			$statusInfoDetails	= array();
								
			$getInfoData		 = array(	
				'getTeamNameList' 			 => $teamId,
			);

			$statusInfoDetails	= getAutoSuggestionListHelper($getInfoData);
			$teamNameDetails 	= $statusInfoDetails['teamNameInfo'];
			
			//print_r($statusInfoDetails); //print_r(lang('MSG_87'));
			
			$errMsg						= lang('MSG_87')[0];
			$outputData['message']      = $errMsg." ".$teamNameDetails[0]['team_name'];
			
		}else if (3 == $modelOutput['flag']) {
			$outputData['message']      = lang('GLB_010'); // unable to update the record
		}
        $this->output->sendResponse($outputData);
    }
	
	
    /**
	* @METHOD NAME 	: editTeamMember()
	*
	* @DESC 		: TO EDIT TEAM MEMBER DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function editTeamMember()
    {
        $this->checkRequestMethod("put"); 	// CHECK THE REQUEST METHOD
		$outputData['status']  = "FAILURE";
        
         // PARAMS FROM HTTP REQUEST
        if (!empty($this->currentRequestData['id']) && is_numeric($this->currentRequestData['id'])) {
            
            $modelOutput = $this->nativeModel->editTeamMember($this->currentRequestData);
            
            if (count($modelOutput) > 0) {
				
				// STATUS INFO DETAILS 
				$statusInfoDetails	= array();
								
				$getInfoData		= array(	
					'getTeamList' 		 => $modelOutput[0]['team_id'],
					'getEmployeeList' 	 => $modelOutput[0]['emp_id'],
				);
				
				$statusInfoDetails	= getAutoSuggestionListHelper($getInfoData);
				$result  			= array(array_merge($modelOutput[0],$statusInfoDetails));
			
                $outputData['status']     		= "SUCCESS";
                $outputData['results']      	= $result;
            } else {
                $outputData['message']      =  lang('GLB_015');  // INVALID ID PASSED 
            }
        } else { 
            $outputData['message']      = lang('GLB_007'); // INVALID PARAMETERS
        }	
        $this->output->sendResponse($outputData);
    }

	
	/**
	* @METHOD NAME 	: getTeamMemberList()
	*
	* @DESC 		: TO GET THE TEAM MEMBER LIST
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getTeamMemberList()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
        $modelOutput           = $this->nativeModel->getTeamMemberList($this->currentRequestData);
		
		// FRAME OUTPUT
        $outputData['results']      = $modelOutput;
        $outputData['status']       = "SUCCESS";
		
        $this->output->sendResponse($outputData);
    }
	
	
	/**
	* @METHOD NAME 	: getMyTeamMemberList()
	*
	* @DESC 		: TO GET MY TEAM MEMBER LIST
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getMyTeamMemberList()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
        $modelOutput           = $this->nativeModel->getMyTeamMemberList($this->currentRequestData);
		
		// FRAME OUTPUT
        $outputData['results']      = $modelOutput;
        $outputData['status']       = "SUCCESS";
		
        $this->output->sendResponse($outputData);
    }
	
}
?>