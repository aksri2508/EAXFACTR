<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Opportunity.php
* @Class  			 : Opportunity
* Model Name         : Opportunity
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 16 JUNE 2019
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : -
* Features           : 
*/
class Opportunity extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
		$this->config->load('table_config/tbl_opportunity.php');
		$this->config->load('table_config/tbl_opportunity_stages.php');
        $this->load->model('company/opportunity_model', 'nativeModel');
    }
	
	
	/**
	* @METHOD NAME 	: saveOpportunity()
	*
	* @DESC 		: TO SAVE THE OPPORTUNITY DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function saveOpportunity()
    {
        // Params from http request
        $this->checkRequestMethod("post"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$getData 		       = $this->currentRequestData;
		
			$modelOutput 	   = $this->nativeModel->saveOpportunity($getData);

			if (1 == $modelOutput['flag']) {
				$outputData['status']       = "SUCCESS";
				$outputData['sId']      	= $modelOutput['sId'];
				$outputData['message']      = lang('MSG_09');  // Successfully Inserted
			} else if (2 == $modelOutput['flag']) {
				$outputData['message']      = lang('GLB_009');  // Unable to save the record
			}else if (4 == $modelOutput['flag']) {
				$outputData['message'] = lang('MSG_10'); 		// Record Already Exists
			}
		
        $this->output->sendResponse($outputData);
    }
    
	
	/**
	* @METHOD NAME 	: saveOpportunityStages()
	*
	* @DESC 		: TO SAVE THE OPPORTUNITY STAGES 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function saveOpportunityStages()
    {
        // Params from http request
        $this->checkRequestMethod("post"); // Check the Request Method
		
			$getData 		       = $this->currentRequestData;
		
			$modelOutput 	   = $this->nativeModel->saveOpportunityStages($getData);

			if (1 == $modelOutput['flag']) {
				$outputData['status']       = "SUCCESS";
				$outputData['message']      = lang('MSG_114');  // Successfully Inserted
			} else if (2 == $modelOutput['flag']) {
				$outputData['message']      = lang('MSG_113');  // Unable to save the record
			}
		
        $this->output->sendResponse($outputData);
	}
	
	
	/**
	* @METHOD NAME 	: updateOpportunity()
	*
	* @DESC 		: TO UPDATE THE OPPORTUNITY
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function updateOpportunity()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";
        
		$modelOutput = $this->nativeModel->updateOpportunity($this->currentRequestData);
		
		
		if (1 == $modelOutput['flag']) {
			$outputData['status']       = "SUCCESS";
			$outputData['message']      = lang('MSG_11'); //'Successfully updated
		}else if (2 == $modelOutput['flag']) {
			$outputData['message']      = lang('GLB_010'); // Unable to update the record
		}else if (4 == $modelOutput['flag']) {
			$outputData['message']      = lang('GLB_004'); // Record Already Exists
		}
        $this->output->sendResponse($outputData);
    }
	
	
	/**
	* @METHOD NAME 	: updateOpportunityStages()
	*
	* @DESC 		: TO UPDATE THE OPPORTUNITY STAGES 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function updateOpportunityStages()
    {
        // Params from http request
        $this->checkRequestMethod("put"); // Check the Request Method
		
			$getData 		       = $this->currentRequestData;
			
			$modelOutput 	   = $this->nativeModel->updateOpportunityStages($getData);

			if (1 == $modelOutput['flag']) {
				$outputData['status']       = "SUCCESS";
				$outputData['message']      = lang('MSG_116');  // OPPORTUNITY STAGES UPDATED UPDATED
			} else if (2 == $modelOutput['flag']) {
				$outputData['message']      = lang('MSG_117');  // UNABLE TO UPDATE THE RECORD
			}
		
        $this->output->sendResponse($outputData);
	}
	
	
	/**
	* @METHOD NAME 	: deleteOpportunityStages()
	*
	* @DESC 		: TO DELETE THE OPPORTUNITY STAGES
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function deleteOpportunityStages()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";
        
        if (!empty($this->currentRequestData['id']) && is_numeric($this->currentRequestData['id'])) {
            $modelOutput = $this->nativeModel->deleteOpportunityStages($this->currentRequestData);
            if (1 == $modelOutput['flag'] ) {
                $outputData['status']       = "SUCCESS";
                $outputData['message']      =  lang('MSG_115'); //'Successfully Deleted
            } else if (2 == $modelOutput['flag'] ) {
                $outputData['message']      = lang('GLB_011'); // Unable to delete. Please try again later.
            }
        } else {
            $outputData['message']      = lang('GLB_007'); // Invalid Paremeters
        }
        $this->output->sendResponse($outputData);
    }
	
	
	/**
	* @METHOD NAME 	: formOpportunityStageInfo()
	*
	* @DESC 		: TO FORM OPPORTUNITY STAGE INFORMATION 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @
	*/
	public function formOpportunityStageInfo($getPostData){
		$getOpportunityStageList = $this->nativeModel->getOpportunityStagesList($getPostData);
		
		// BIND THE LIST SUB ARRAY 
				if(count($getOpportunityStageList)>0){
					
					$passSearchData['category'] = 2;
					$passSearchData['delFlag']  = 0;
				
					foreach($getOpportunityStageList as $key => $value){
						
						// DOCUMENT DETAILS 
						$passDocumentData['documentNoId']   	= $value['document_id'];
						$passDocumentData['documentTypeId']   	= $value['document_type_id'];
						$documentNumberDetails   	  			= $this->commonModel->getDocumentNumber(array_merge($passSearchData,$passDocumentData));
						$value['documentInfo'] 			= $documentNumberDetails;
						
						
						// ACTIVIY DETAILS 
						$passActivityData['documentNoId']   	= $value['activity_id'];
						$passActivityData['documentTypeId']   	= 4;
						$activityNumberDetails   	  			= $this->commonModel->getDocumentNumber(array_merge($passSearchData,$passActivityData));
						$value['activityNumberInfo'] 			= $activityNumberDetails;
						
						// STATUS INFO DETAILS 
						$statusInfoDetails	= array();
						$getInfoData 		= array(	
													'getEmployeeList~empInfo' => $value['emp_id'],
													'getStageList' 	 		  => $value['stage_id'],
												);
						$statusInfoDetails	= getAutoSuggestionListHelper($getInfoData);
					
						// Re-Assign Array 
						$getOpportunityStageList[$key] = array_merge($value,$statusInfoDetails);
					}
				}
				
		//printr($getOpportunityStageList);
		
		return $getOpportunityStageList;		
	}
	
   
	/**
	* @METHOD NAME 	: editOpportunity()
	*
	* @DESC 		: TO EDIT OPPORTUNITY DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function editOpportunity()
    {
        $this->checkRequestMethod("put"); 	// CHECK THE REQUEST METHOD
		$outputData['status']  = "FAILURE";
        
         // PARAMS FROM HTTP REQUEST
        if (!empty($this->currentRequestData['id']) && is_numeric($this->currentRequestData['id'])) {
            
			$modelOutput 			 = $this->nativeModel->editOpportunity($this->currentRequestData);

			if (count($modelOutput) > 0) {
			
				$getOpportunityStageList = $this->formOpportunityStageInfo($this->currentRequestData);			
			
				// FRAME ALL THE INFO DATA
				$statusInfoDetails	= array();
				$getInfoData 		= array(
					'getBusinessPartnerList' 		 	=> $modelOutput[0]['business_partner_id'],
					'getBusinessPartnerContactsList' 	=> $modelOutput[0]['bp_contacts_id'],
					'getEmployeeList~empInfo' 			=> $modelOutput[0]['emp_id'],
					'getLevelofInterestList' 			=> $modelOutput[0]['level_of_interest_id'],
					'getIndustryList' 					=> $modelOutput[0]['industry_id'],
					'getInformationSourceList' 			=> $modelOutput[0]['information_source_id'],
					'getCompetitorList' 				=> $modelOutput[0]['competitor_id'],
					'getReasonList' 					=> $modelOutput[0]['reason_id'],
					'getOpportunityStatusList' 			=> $modelOutput[0]['opportunity_status'],
					'getOpportunityTypeList' 			=> $modelOutput[0]['opportunity_type_id'],
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
				$statusInfoDetails['distributionRulesInfo'] 		= $distributionRulesDetails;
				
		        $data 				  = array();
		        $data				  = array_merge($modelOutput[0],$statusInfoDetails);

	        	$data['stageListArray'] 		= $getOpportunityStageList;
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
		$passActivityStatusData['type']  = 'OPPORTUNITY_STATUS';
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
	* @METHOD NAME 	: getOpportunityList()
	*
	* @DESC 		: TO GET THE OPPORTUNITY LIST DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getOpportunityList()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
        $modelOutput           = $this->nativeModel->getOpportunityList($this->currentRequestData);
		
		
		// OPPORTUNITY STATUS LIST
		$passType['type'] 	  	= 'OPPORTUNITY_STATUS';
		$opportunityStatusList  = $this->commonModel->getMasterStaticDataAutoList($passType,2);
		
		
		
		// PASS COMMON PARAMETERS
		$passSearchData['category'] = 2;
		$passSearchData['delFlag']  = 1;
		
		foreach($modelOutput['searchResults'] as $key => $value){
			$modelOutput['searchResults'][$key]['stageDetails'] 	= $this->formOpportunityStageInfo($value);
		}	
		
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
		$modelOutput	= $this->nativeModel->getOpportunityList($this->currentRequestData,1);
		$resultsData 	= $modelOutput['searchResults'];
		$fileName		= $this->config->item('OPPORTUNITY')['excel_file_name'];

		$outputData 	= processExcelData($resultsData,$fileName,$this->config->item('OPPORTUNITY')['columns_list']);
		$this->output->sendResponse($outputData);
   }
	
}
?>