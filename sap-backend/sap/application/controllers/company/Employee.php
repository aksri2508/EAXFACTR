<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Employee.php
* @Class  			 : Employee
* Model Name         : Employee
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 30 MAY 2019
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : -
* Features           : 
*/
class Employee extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
		$this->config->load('table_config/tbl_employee_profile.php');
        $this->load->model('company/employee_model', 'nativeModel');
    }
	
	
	/**
	* @METHOD NAME 	: saveEmployee()
	*
	* @DESC 		: TO SAVE THE EMPLOYEE DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function saveEmployee()
    {
        // Params from http request
        $this->checkRequestMethod("post"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$getData 		       = $this->currentRequestData;
		$modelOutput 	   	   = $this->nativeModel->saveEmployee($getData);
		
		if (1 == $modelOutput['flag']) {

			if($modelOutput['sendRegMailFlag']==1){
				// SEND REGISTRATION MAIL 
				$userId			 = $modelOutput['userId'];
				$password 		 = $modelOutput['password'];
				$companyName 	 = $modelOutput['companyName'];
				$branchName 	 = $modelOutput['branchName'];
				sendRegUserMail($userId,$password,"",$companyName,$branchName);
			}
			$outputData['sId']      	= $modelOutput['sId'];
			$outputData['status']       = "SUCCESS";
			$outputData['message']      = lang('MSG_29'); // Successfully Inserted
		} else if (2 == $modelOutput['flag']) {
			$outputData['message']      = lang('MSG_353'); // Record Already Exists - Email ID 
		}else if (3 == $modelOutput['flag']) {
			$outputData['message']      = lang('MSG_352'); // Record Already Exists - EMP CODE 
		}else if (4 == $modelOutput['flag']) {
			$outputData['message']      = lang('MSG_96'); // MAXIMUM NUMBER OF USERS LIMIT REACHED
		}
        $this->output->sendResponse($outputData);
    }
    
	
	/**
	* @METHOD NAME 	: updateEmployee()
	*
	* @DESC 		: TO UPDATE THE EMPLOYEE
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function updateEmployee()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";
        
		$modelOutput = $this->nativeModel->updateEmployee($this->currentRequestData);

		if (1 == $modelOutput['flag']) {
			
			if($modelOutput['sendRegMailFlag']==1){ // SEND REGISTRATION MAIL 

				$userId			 = $modelOutput['userId'];
				$password 		 = $modelOutput['password'];
				$companyName 	 = $modelOutput['companyName'];
				$branchName 	 = $modelOutput['branchName'];

				sendRegUserMail($userId,$password,"",$companyName,$branchName);
			}
			
			$outputData['status']       = "SUCCESS";
			$outputData['message']      = lang('MSG_31'); //'Successfully updated
		}else if (2 == $modelOutput['flag']) {
			$outputData['message']      = lang('GLB_010'); // Unable to update the record
		}else if (3 == $modelOutput['flag']) {
			$outputData['message']      = lang('MSG_30'); // Record Already Exists
		}else if (4 == $modelOutput['flag']) {
			$outputData['message']      = lang('MSG_96'); // MAXIMUM NUMBER OF USERS LIMIT REACHED
		}
        $this->output->sendResponse($outputData);
    }
	
	
	/**
	* @METHOD NAME 	: editEmployee()
	*
	* @DESC 		: TO EDIT EMPLOYEE DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function editEmployee()
    {
        $this->checkRequestMethod("put"); 	// CHECK THE REQUEST METHOD
		$outputData['status']  = "FAILURE";
        
         // PARAMS FROM HTTP REQUEST
        if (!empty($this->currentRequestData['id']) && is_numeric($this->currentRequestData['id'])) {
            
            $modelOutput = $this->nativeModel->editEmployee($this->currentRequestData);
            
            if (count($modelOutput) > 0) {
								
				$modelOutput[0]['userImgUrl'] = getFullImgUrl('employee',$modelOutput[0]['profile_img']);
				
				$passData['id'] = $modelOutput[0]['reporting_manager_id'];
				
				$reportingManagerDetails = $this->nativeModel->editEmployee($passData);
				
				if(count($reportingManagerDetails)>0){
					$modelOutput[0]['reportingManager'] = $reportingManagerDetails[0]['first_name']." ".$reportingManagerDetails[0]['last_name'];
				}
				
		
				// Branch Info Details 
				$branchId  		= $modelOutput[0]['branch_id'];
				$branchDetails  = array();
				
				if(!empty($branchId)){
					$branchDetails  = explode(",",$branchId);
					$cnt = 0;
					foreach($branchDetails as $branchKey => $branchValue){
						
						$getBranchDetailsInfo = array(	
														'getBranchList' 	 => $branchValue,
													);
						
						$branchDetailsResult  = getAutoSuggestionListHelper($getBranchDetailsInfo);	
						
						if(!empty($branchDetailsResult['branchInfo']))
						{	
							$branchDetails[$cnt] =  $branchDetailsResult['branchInfo'][0];
							$cnt++;
						}	
					}
				}
				
				$statusInfoDetails	= array();
				$getInfoData = array(	
					'getEmployeeTypeList' 		 => $modelOutput[0]['employee_type_id'],
					'getCommonStatusList' 				 => $modelOutput[0]['status'],
					'getCreatedByDetails~createdByInfo'	 => $modelOutput[0]['created_by']
					
				);
				
				$statusInfoDetails	= getAutoSuggestionListHelper($getInfoData);
				
				// DISTRIBUTION DETAILS 
				$distributionRulesDetails	= array();
				$distributionRulesId  		= $modelOutput[0]['distribution_rules_id'];
				$distributionRulesArray  	= explode(",", $distributionRulesId);

				if (count($distributionRulesArray) > 0) {
					foreach ($distributionRulesArray as $distributionKey => $distributionValue) {
						if (!empty($distributionValue)) {
							$getDistributionRulesInfo =  array(	
															'getDistributionRulesList' 	 => $distributionValue,
														);
														
							$distributionStatusInfoDetails	= getAutoSuggestionListHelper($getDistributionRulesInfo);	
							
							if (is_array($distributionStatusInfoDetails) && count($distributionStatusInfoDetails) > 0) {
								$distributionRulesDetails[$distributionKey]	= $distributionStatusInfoDetails['distributionRulesInfo'][0];
							}
						}
					}
				}
				
				$statusInfoDetails['distributionRulesInfo'] 	= $distributionRulesDetails;	
				$statusInfoDetails['branchInfo'] 				= $branchDetails;	
								
				$result  					= array(array_merge($modelOutput[0],$statusInfoDetails));
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
	* @METHOD NAME 	: getEmployeeList()
	*
	* @DESC 		: TO GET THE USER LIST DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getEmployeeList()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
        $modelOutput           		= $this->nativeModel->getEmployeeList($this->currentRequestData);
				
		// FRAME OUTPUT
        $outputData['results']      = $modelOutput;
        $outputData['status']       = "SUCCESS";
		
        $this->output->sendResponse($outputData);
    }
	
	
	/**
	* @METHOD NAME 	: downloadExcel()
	*
	* @DESC 		: TO DOWNLOAD THE EXCEL FORMAT
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
   public function downloadExcel()
   {
		$modelOutput	= $this->nativeModel->getEmployeeList($this->currentRequestData,1);
		$resultsData 	= $modelOutput['searchResults'];
		$fileName		= $this->config->item('EMPLOYEE_PROFILE')['excel_file_name'];

		$outputData 	= processExcelData($resultsData,$fileName,$this->config->item('EMPLOYEE_PROFILE')['columns_list']);
		$this->output->sendResponse($outputData);
    }
	
	
	/**
	* @METHOD NAME 	: getAnalyticsDetails()
	*
	* @DESC 		: TO GET THE ANALYTICS DETAILS FOR ACTIVITY
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getAnalyticsDetails()
    {
		$this->checkRequestMethod("get"); 	// CHECK THE REQUEST METHOD
		$outputData['status']  = "SUCCESS";
		
		// ACTIVITY STATUS DETAILS
		$passSearchData['category'] = 2;
		$passSearchData['delFlag']  = 1;
		$passActivityStatusData['type']  = 'EMPLOYEE_TYPE';
		$activityStatusDetails  		 = $this->commonModel->getMasterStaticDataAutoList(array_merge($passSearchData,$passActivityStatusData),2);
		
		foreach($activityStatusDetails as $key => $value){
			$totalValue = $this->nativeModel->getAnalyticsCount($value['id']);
			$analyticsData[$key]['name'] 	= $value['name'];
			$analyticsData[$key]['id'] 		= $value['id'];
			$analyticsData[$key]['count']	= $totalValue;
		}
		$key++;
		$analyticsData[$key]['name'] 	= 'All';
		$analyticsData[$key]['id'] 		= 0;
		$analyticsData[$key]['count']	= $this->nativeModel->getAnalyticsCount('');
		$outputData['results']     		= $analyticsData;	
		$this->output->sendResponse($outputData);
	}

	
}
?>