<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Smt_team.php
* @Class  			 : Smt_team
* Model Name         : Smt_team
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
class Smt_team extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
		$this->config->load('table_config/tbl_smt_team.php');
        $this->load->model('company/smt_team_model', 'nativeModel');
    }
	
	
	/**
	* @METHOD NAME 	: saveTeam()
	*
	* @DESC 		: TO SAVE THE TEAM DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function saveTeam()
    {
        // Params from http request
        $this->checkRequestMethod("post"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$getData 		       = $this->currentRequestData;

			$modelOutput 	   = $this->nativeModel->saveTeam($getData);
			
			if (1 == $modelOutput['flag']) {
				$outputData['status']       = "SUCCESS";
				$outputData['message']      = lang('MSG_82'); // Successfully Inserted
			} else if (2 == $modelOutput['flag']) {
				$outputData['message']      = lang('MSG_83'); // Record Already Exists
			}
		
        $this->output->sendResponse($outputData);
    }
    
	
	/**
	* @METHOD NAME 	: updateTeam()
	*
	* @DESC 		: TO UPDATE THE TEAM DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function updateTeam()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";
        
		$modelOutput = $this->nativeModel->updateTeam($this->currentRequestData);
		if (1 == $modelOutput['flag']) {
			$outputData['status']       = "SUCCESS";
			$outputData['message']      = lang('MSG_84'); //'Successfully updated
		}else if (2 == $modelOutput['flag']) {
			$outputData['message']      = lang('MSG_83'); // Record Already Exists 
		}else if (3 == $modelOutput['flag']) {
			$outputData['message']      = lang('GLB_010'); // UNABLE TO UPDATE THE RECORD
		}
        $this->output->sendResponse($outputData);
    }
	
    
	/**
	* @METHOD NAME 	: editTeam()
	*
	* @DESC 		: TO EDIT TEAM DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function editTeam()
    {
        $this->checkRequestMethod("put"); 	// CHECK THE REQUEST METHOD
		$outputData['status']  = "FAILURE";
        
         // PARAMS FROM HTTP REQUEST
        if (!empty($this->currentRequestData['id']) && is_numeric($this->currentRequestData['id'])) {
            
            $modelOutput = $this->nativeModel->editTeam($this->currentRequestData);
            
            if (count($modelOutput) > 0) {
				
				// STATUS INFO DETAILS 
				$statusInfoDetails	= array();
				$getInfoData		= array(
											'getEmployeeList~teamHeadInfo' 	 => $modelOutput[0]['team_head_id'],
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
	* @METHOD NAME 	: getTeamList()
	*
	* @DESC 		: TO GET THE TEAM LIST DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getTeamList()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
        $modelOutput           = $this->nativeModel->getTeamList($this->currentRequestData);
				
		if (count($modelOutput['searchResults']) > 0) {
			foreach($modelOutput['searchResults'] as $key => $value ){
				$teamMembersCount =  $this->nativeModel->getTeamMembersCount($value['id']);
				$modelOutput['searchResults'][$key]['totalMembers'] = $teamMembersCount;
			}
		}
		
		// FRAME OUTPUT
        $outputData['results']      = $modelOutput;
        $outputData['status']       = "SUCCESS";
		
        $this->output->sendResponse($outputData);
    }

}
?>