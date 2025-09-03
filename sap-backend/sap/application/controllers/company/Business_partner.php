<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Business_partner.php
* @Class  			 : Business_partner
* Model Name         : Business_partner
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 22 JUNE 2019
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : -
* Features           : 
*/
class Business_partner extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
		$this->config->load('table_config/tbl_business_partner.php');
		$this->config->load('table_config/tbl_bp_contacts.php');
		$this->config->load('table_config/tbl_bp_address.php');
        $this->load->model('company/business_partner_model', 'nativeModel');
    }
	
	
	/**
	* @METHOD NAME 	: saveBusinessPartner()
	*
	* @DESC 		: TO SAVE THE BUSINESS PARTNER DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function saveBusinessPartner()
    {
        // Params from http request
        $this->checkRequestMethod("post"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$getData 		       = $this->currentRequestData;
		
			$modelOutput 	   = $this->nativeModel->saveBusinessPartner($getData);

			if (1 == $modelOutput['flag']) {
				$outputData['sId']      	= $modelOutput['sId'];
				$outputData['status']       = "SUCCESS";
				$outputData['message']      = lang('MSG_17');  // Successfully Inserted
			} else if (2 == $modelOutput['flag']) {
				$outputData['message']      = lang('GLB_009');  // Unable to save the record
			}
        $this->output->sendResponse($outputData);
    }
	
	
	/**
	* @METHOD NAME 	: saveBusinessPartnerContacts()
	*
	* @DESC 		: TO SAVE THE BUSINESS PARTNER CONTACTS 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function saveBusinessPartnerContacts()
    {
        // Params from http request
        $this->checkRequestMethod("post"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$getData 		       = $this->currentRequestData;
		
			$modelOutput 	   = $this->nativeModel->saveBusinessPartnerContacts($getData);

			if (1 == $modelOutput['flag']) {
				$outputData['status']       = "SUCCESS";
				$outputData['message']      = lang('MSG_129');  // Successfully Inserted
			} else if (2 == $modelOutput['flag']) {
				$outputData['message']      = lang('GLB_009');  // Unable to save the record
			}else if (4 == $modelOutput['flag']) {
				$outputData['message'] = lang('MSG_128'); 		// Record Already Exists
			}
        $this->output->sendResponse($outputData);
    }
    
	
	/**
	* @METHOD NAME 	: saveBusinessPartnerAddress()
	*
	* @DESC 		: TO SAVE THE BUSINESS PARTNER ADDRESS 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function saveBusinessPartnerAddress()
    {
        $this->checkRequestMethod("post"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$getData 		       = $this->currentRequestData;
		
			$modelOutput 	   = $this->nativeModel->saveBusinessPartnerAddress($getData);

			if (1 == $modelOutput['flag']) {
				$outputData['status']       = "SUCCESS";
				$outputData['message']      = lang('MSG_213');  // Successfully Inserted
			} else if (2 == $modelOutput['flag']) {
				$outputData['message']      = lang('GLB_009');  // Unable to save the record
			}
        $this->output->sendResponse($outputData);
    }
	
	
	/**
	* @METHOD NAME 	: updateBusinessPartner()
	*
	* @DESC 		: TO UPDATE THE BUSINESS PARTNER
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function updateBusinessPartner()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";
        
		$modelOutput = $this->nativeModel->updateBusinessPartner($this->currentRequestData);
		
		//print_r($modelOutput);exit;

		if (1 == $modelOutput['flag']) {
			$outputData['status']       = "SUCCESS";
			$outputData['message']      = lang('MSG_19'); //'Successfully updated
		}else if (2 == $modelOutput['flag']) {
			$outputData['message']      = lang('GLB_010'); // Unable to update the record
		}
        $this->output->sendResponse($outputData);
    }
	
	
	/**
	* @METHOD NAME 	: updateBusinessPartnerContacts()
	*
	* @DESC 		: TO UPDATE THE BUSINESS PARTNER CONTACTS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function updateBusinessPartnerContacts()
    {
        // Params from http request
        $this->checkRequestMethod("put"); // Check the Request Method
		
			$getData 		   = $this->currentRequestData;
			
			$modelOutput 	   = $this->nativeModel->updateBusinessPartnerContacts($getData);

			if (1 == $modelOutput['flag']) {
				$outputData['status']       = "SUCCESS";
				$outputData['message']      = lang('MSG_131');  // UPDATE THE BUSINESS PARTNER CONTACTS 
			} else if (2 == $modelOutput['flag']) {
				$outputData['message']      = lang('MSG_132');  // UNABLE TO UPDATE THE RECORD
			}
        $this->output->sendResponse($outputData);
	}
	
	
	/**
	* @METHOD NAME 	: updateBusinessPartnerAddress()
	*
	* @DESC 		: TO UPDATE THE BUSINESS PARTNER ADDRESS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function updateBusinessPartnerAddress()
    {
        // Params from http request
        $this->checkRequestMethod("put"); // Check the Request Method
		
			$getData 		   = $this->currentRequestData;
			
			$modelOutput 	   = $this->nativeModel->updateBusinessPartnerAddress($getData);

			if (1 == $modelOutput['flag']) {
				$outputData['status']       = "SUCCESS";
				$outputData['message']      = lang('MSG_215');  // UPDATE THE BUSINESS PARTNER CONTACTS 
			} else if (2 == $modelOutput['flag']) {
				$outputData['message']      = lang('MSG_216');  // UNABLE TO UPDATE THE RECORD
			}
        $this->output->sendResponse($outputData);
	}
	
	
	/**
	* @METHOD NAME 	: deleteBusinessPartnerContacts()
	*
	* @DESC 		: TO DELETE THE BUSINESS PARTNER CONTACTS 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function deleteBusinessPartnerContacts()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";
        
        if (!empty($this->currentRequestData['id']) && is_numeric($this->currentRequestData['id'])) {
            $modelOutput = $this->nativeModel->deleteBusinessPartnerContacts($this->currentRequestData);
            if (1 == $modelOutput['flag'] ) {
                $outputData['status']       = "SUCCESS";
                $outputData['message']      =  lang('MSG_130'); //'Successfully Deleted
            } else if (2 == $modelOutput['flag'] ) {
                $outputData['message']      = lang('GLB_011'); // Unable to delete. Please try again later.
            }
        } else {
            $outputData['message']      = lang('GLB_007'); // Invalid Paremeters
        }
        $this->output->sendResponse($outputData);
    }
	
	
	/**
	* @METHOD NAME 	: deleteBusinessPartnerAddress()
	*
	* @DESC 		: TO DELETE THE BUSINESS PARTNER ADDRESS 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function deleteBusinessPartnerAddress()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";
        
        if (!empty($this->currentRequestData['id']) && is_numeric($this->currentRequestData['id'])) {
            $modelOutput = $this->nativeModel->deleteBusinessPartnerAddress($this->currentRequestData);
            if (1 == $modelOutput['flag'] ) {
                $outputData['status']       = "SUCCESS";
                $outputData['message']      =  lang('MSG_214'); //'Successfully Deleted
            } else if (2 == $modelOutput['flag'] ) {
                $outputData['message']      = lang('GLB_011'); // Unable to delete. Please try again later.
            }
        } else {
            $outputData['message']      = lang('GLB_007'); // Invalid Paremeters
        }
        $this->output->sendResponse($outputData);
    }
	
    
	/**
	* @METHOD NAME 	: editBusinessPartner()
	*
	* @DESC 		: TO EDIT BUSINESS PARTNER DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function editBusinessPartner()
    {
        $this->checkRequestMethod("put"); 	// CHECK THE REQUEST METHOD
		$outputData['status']  = "FAILURE";
        
         // PARAMS FROM HTTP REQUEST
        if (!empty($this->currentRequestData['id']) && is_numeric($this->currentRequestData['id'])) {
            
			$modelOutput 			 		= $this->nativeModel->editBusinessPartner($this->currentRequestData);
			$getBusinessPartnerContactsList = $this->nativeModel->getBusinessPartnerContactsList($this->currentRequestData);
			$getBusinessPartnerAddressList 	= $this->nativeModel->getBusinessPartnerAddressList($this->currentRequestData);
			
			if (count($modelOutput) > 0) {
				
				// FRAME ALL THE INFO DATA
				$statusInfoDetails	= array();
				$getInfoData = array(	
					'getCurrencyList' 			 => $modelOutput[0]['currency_id'],
					'getCommonStatusList' 		 => $modelOutput[0]['status'],
					'getBusinessPartnerTypeList' => $modelOutput[0]['partner_type_id'],
					'getEmployeeList' 			 => $modelOutput[0]['emp_id'],
					'getIndustryList' 			 => $modelOutput[0]['industry_id'],
					'getTerritoryList'			 => $modelOutput[0]['territory_id'],
					'getPaymentTermsList' 		 => $modelOutput[0]['payment_terms_id'],
					'getPaymentMethodsList' 	 => $modelOutput[0]['payment_method_id'],
					'getPriceList' 	 			 => $modelOutput[0]['price_list_id'],
					'getCreatedByDetails~createdByInfo'	=> $modelOutput[0]['created_by'],
					'getDocumentNumberingList~documentNumberingInfo' => $modelOutput[0]['document_numbering_id']

				);
				$statusInfoDetails	= getAutoSuggestionListHelper($getInfoData);
			
				$data 						= array();
				$data				  		= $modelOutput[0];
				$data['contactListArray']   = $getBusinessPartnerContactsList;
				$data['addressListArray']   = $getBusinessPartnerAddressList;
            	$outputData['status']   	= "SUCCESS";
				$result  			 		= array_merge($data,$statusInfoDetails);
            	$outputData['results']  	= $result;
				
            }else {
                $outputData['message'] =  lang('GLB_015');  // INVALID ID PASSED
            }			
        } else { 
            $outputData['message']      = lang('GLB_007'); // INVALID PARAMETERS
        }	
        $this->output->sendResponse($outputData);
    }

    
	/**
	* @METHOD NAME 	: getBusinessPartnerList()
	*
	* @DESC 		: TO GET THE BUSINESS PARTNER LIST DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getBusinessPartnerList()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
        $modelOutput           = $this->nativeModel->getBusinessPartnerList($this->currentRequestData);
		
		//printr($modelOutput);exit;
		
		// FRAME OUTPUT
        $outputData['results']      = $modelOutput;
        $outputData['status']       = "SUCCESS";
		
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
		$passActivityStatusData['type']  = 'COMMON_STATUS';
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
		$modelOutput	= $this->nativeModel->getBusinessPartnerList($this->currentRequestData,1);
		$resultsData 	= $modelOutput['searchResults'];
		$fileName		= $this->config->item('BUSINESS_PARTNER')['excel_file_name'];

		$outputData 	= processExcelData($resultsData,$fileName,$this->config->item('BUSINESS_PARTNER')['columns_list']);
		$this->output->sendResponse($outputData);
   }
   
   
}
