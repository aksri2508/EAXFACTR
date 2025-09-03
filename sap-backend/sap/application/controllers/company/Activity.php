<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Activity.php
* @Class  			 : Activity
* Model Name         : Activity
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
class Activity extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
		$this->config->load('table_config/tbl_activity.php');
        $this->load->model('company/activity_model', 'nativeModel');
    }
	
	
	/**
	* @METHOD NAME 	: saveActivity()
	*
	* @DESC 		: TO SAVE THE ACTIVITY DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function saveActivity()
    {
        // Params from http request
        $this->checkRequestMethod("post"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$getData 		       = $this->currentRequestData;

			$modelOutput 	   = $this->nativeModel->saveActivity($getData);
			
			if (1 == $modelOutput['flag']) {
				$outputData['sId']      	= $modelOutput['sId'];
				$outputData['status']       = "SUCCESS";
				$outputData['message']      = lang('MSG_05'); 	// Successfully Inserted
			} else if (3 == $modelOutput['flag']) {
				$outputData['message']      = lang('GLB_009');  // UNABLE TO SAVE THE RECORD
			}
		
        $this->output->sendResponse($outputData);
    }
    
	
	/**
	* @METHOD NAME 	: updateActivity()
	*
	* @DESC 		: TO UPDATE THE ACTIVITY
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function updateActivity()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";
        
		$modelOutput = $this->nativeModel->updateActivity($this->currentRequestData);
		if (1 == $modelOutput['flag']) {
			$outputData['status']       = "SUCCESS";
			$outputData['message']      = lang('MSG_07'); //Successfully updated
		}else if (2 == $modelOutput['flag']) {
			$outputData['message']      = lang('MSG_06'); //Record Already Exists
		}
        $this->output->sendResponse($outputData);
    }
	
	
	/**
	* @METHOD NAME 	: editActivity()
	*
	* @DESC 		: TO EDIT ACTIVITY DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function editActivity()
    {
        $this->checkRequestMethod("put"); 	// CHECK THE REQUEST METHOD
		$outputData['status']  = "FAILURE";
        
         // PARAMS FROM HTTP REQUEST
        if (!empty($this->currentRequestData['id']) && is_numeric($this->currentRequestData['id'])) {
            
            $modelOutput = $this->nativeModel->editActivity($this->currentRequestData);
            
			
			if (count($modelOutput) > 0) {
			
				$statusInfoDetails	= array();
								
				$getInfoData		 = array(	
					'getBusinessPartnerList' 			 => $modelOutput[0]['business_partner_id'],
					'getBusinessPartnerContactsList' 	 => $modelOutput[0]['bp_contacts_id'],
					'getMasterActivityList'				 => $modelOutput[0]['activity_type_id'],
					'getEmployeeList~assignedToInfo' 			   => $modelOutput[0]['assigned_to_id'],
					'getActivityRecurrenceTypeList~recurrenceInfo' => $modelOutput[0]['recurrence_type_id'],
					'getActivityStatusList'						   => $modelOutput[0]['status'],
					'getActivityPriorityTypeList' 				   => $modelOutput[0]['priority_id'],
					'getActivityReminderTypeList~reminderInfo' 	   => $modelOutput[0]['reminder_type_id'],
					'getDocumentTypeList' 						   => $modelOutput[0]['document_type_id'],
					'getCreatedByDetails~createdByInfo'	 => $modelOutput[0]['created_by']

				);

				$statusInfoDetails	= getAutoSuggestionListHelper($getInfoData);
			
				
				// BUSINESS PARTNER DETAILS
				$passSearchData['category'] = 2;
				$passSearchData['delFlag']  = 0;
				
				// DOCUMENT NUMBER DETAILS 
				$passDocumenNumbertData['documentNoId']   	= $modelOutput[0]['document_id'];
				$passDocumenNumbertData['documentTypeId']   = $modelOutput[0]['document_type_id'];
				$documentNumberDetails   	  				= $this->commonModel->getDocumentNumber(array_merge($passSearchData,$passDocumenNumbertData));
				
		
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
				$statusInfoDetails['documentInfo'] 		= $documentNumberDetails;
				$result  										= array(array_merge($modelOutput[0],$statusInfoDetails));
		
                $outputData['status']      				= "SUCCESS";
                $outputData['results']     				= $result;
            } else {
                $outputData['message']      =  lang('GLB_015');  // INVALID ID PASSED 
            }
        } else { 
            $outputData['message']      = lang('GLB_007'); // INVALID PARAMETERS
        }	
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
		$passActivityStatusData['type']  = 'ACTIVITY_STATUS';
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
		
		$outputData['results']     				= $analyticsData;
		$this->output->sendResponse($outputData);
	}
	
	
	/**
	* @METHOD NAME 	: getActivityList()
	*
	* @DESC 		: TO GET THE ACTIVITY LIST DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getActivityList()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
        $modelOutput           = $this->nativeModel->getActivityList($this->currentRequestData);

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
		$modelOutput	= $this->nativeModel->getActivityList($this->currentRequestData,1);
		$resultsData 	= $modelOutput['searchResults'];
		$fileName		= $this->config->item('ACTIVITY')['excel_file_name'];

		$outputData 	= processExcelData($resultsData,$fileName,$this->config->item('ACTIVITY')['columns_list']);
		$this->output->sendResponse($outputData);
    }
	
}

?>