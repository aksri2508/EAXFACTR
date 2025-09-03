<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Grpo.php
* @Class  			 : Grpo
* Model Name         : Grpo_model
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 08 MAY 2020
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : -
* Features           : 
*/
class Grpo extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->config->load('table_config/tbl_grpo.php');
		$this->config->load('table_config/tbl_grpo_items.php');
		$this->load->model('company/grpo_model', 'nativeModel');
		
		$screenDetails				 = $this->config->item('SCREEN_NAMES')['GRPO'];
		$this->tableNameStr 		 = strtoupper(str_replace("tbl_","",$screenDetails['tableName']));
		$this->tableName 			 = constant($this->tableNameStr);
	}


	/**
	 * @METHOD NAME 	: saveGrpo()
	 *
	 * @DESC 			: TO SAVE THE GRPO DETAILS.
	 * @RETURN VALUE 	: $outputdata array
	 * @PARAMETER 		: -
	 * @SERVICE      	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function saveGrpo()
	{
		// Params from http request
		$this->checkRequestMethod("post"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$getData = $this->currentRequestData;

		$modelOutput = $this->nativeModel->saveGrpo($getData);

		if (1 == $modelOutput['flag']) {
			$outputData['sId']      	= $modelOutput['sId'];
			$outputData['status']       = "SUCCESS";
			$outputData['message']      = lang('MSG_188');  // Successfully Inserted
		} else if (2 == $modelOutput['flag']) {
			$outputData['message']      = lang('GLB_009');  // Unable to save the record
		} else if (4 == $modelOutput['flag']) {
			$outputData['message'] = lang('MSG_189'); 		// Record Already Exists
		}
		$this->output->sendResponse($outputData);
	}

	
	/**
	 * @METHOD NAME 	: updateGrpo()
	 *
	 * @DESC 			: TO UPDATE THE GRPO. 
	 * @RETURN VALUE 	: $outputdata array
	 * @PARAMETER 		: -
	 * @SERVICE      	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function updateGrpo()
	{
		$this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$modelOutput		   = $this->nativeModel->updateGrpo($this->currentRequestData);
		$mof = $modelOutput['flag'];

		if (1 == $modelOutput['flag']) {
			$outputData['status']       = "SUCCESS";
			$outputData['message']      = lang('MSG_190'); //'Successfully updated
		} else if (2 == $modelOutput['flag']) {
			$outputData['message']      = lang('GLB_010'); // Unable to update the record
		} else if (4 == $modelOutput['flag'] || 3 == $modelOutput['flag']) {
			$outputData['message']      = lang('MSG_189'); // Record Already Exists
		}
		$this->output->sendResponse($outputData);
	}
	
	
	/**
	 * @METHOD NAME 	: copyGrpo()
	 *
	 * @DESC 			: COPY MULTIPLE RECORDS FOR TRANSACTIONS SCREENS 
	 * @RETURN VALUE 	: $outputdata array
	 * @PARAMETER 		: -
	 * @SERVICE      	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function copyGrpo()
	{
		$outputData['status']  = "FAILURE";
		$this->checkRequestMethod("put"); 	// CHECK THE REQUEST METHOD
	
		$copiedIds  = $this->currentRequestData['id'];
		
		// TO CHECK THE RECORDS ARE ELIGIBLE FOR COPY 
		checkRecordsEligibleForCopy($copiedIds,$this->tableNameStr);
		
		// TO CHECK THE WHETHER WE ANY DRAFT RECORD EXISTS WHILE COPYING FUNCTIONTIONALTY
		checkDraftRecordsExists($copiedIds,$this->tableNameStr);
		
		$frameParentDetails = array();
		
		if(count($copiedIds) == 1) {
			return $this->editGrpo($copiedIds[0]);
		}else if(count($copiedIds)>1){
			
			$getFirstCopyRecordDetails = $this->nativeModel->editGrpo($copiedIds[0]);
			
			if(count($getFirstCopyRecordDetails) > 0){
				$frameEditDetails = $this->frameGrpoEditDetails($getFirstCopyRecordDetails[0]);

				// FRAME PARENT DETAILS
				$frameParentDetails['vendor_bp_id'] 			= $frameEditDetails['vendor_bp_id'];
				$frameParentDetails['vendor_bp_contacts_id'] 	= $frameEditDetails['vendor_bp_contacts_id'];
				$frameParentDetails['currency_id'] 				= $frameEditDetails['currency_id'];
				$frameParentDetails['currencyInfo'] 			= $frameEditDetails['currencyInfo'];
				$frameParentDetails['vendorBpInfo'] 			= $frameEditDetails['vendorBpInfo'];
				$frameParentDetails['vendorBpContactsInfo'] 	= $frameEditDetails['vendorBpContactsInfo'];
				//$frameParentDetails['vendorShipToBpAddressInfo'] 	= $frameEditDetails['vendorShipToBpAddressInfo'];
				//$frameParentDetails['vendorPayToBpAddressInfo'] 	= $frameEditDetails['vendorPayToBpAddressInfo'];
				
				$itemListArray = array();
				foreach($copiedIds as $copyKey => $copyValue){
					$getItemList 	 = $this->nativeModel->editGrpoItemList($copyValue);
					$itemListArray = array_merge($itemListArray,$this->frameGrpoItemList($getItemList));
				}				
				$data 				 	= array();
				$data					= $frameParentDetails;
				$data['itemListArray'] 	= $itemListArray;
				
				$outputData['status']   = "SUCCESS";
				$outputData['results']  = $data;
			}else {
				$outputData['message'] =  lang('GLB_015');  // INVALID ID PASSED
			}
			
		}else {
			$outputData['message']      = lang('GLB_007'); // INVALID PARAMETERS
		}
		$this->output->sendResponse($outputData);
	}
	
	
	
	/**
	 * @METHOD NAME 	: editGrpo()
	 *
	 * @DESC 			: TO EDIT GROP DETAILS 
	 * @RETURN VALUE 	: $outputdata array
	 * @PARAMETER 		: -
	 * @SERVICE      	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function editGrpo($id = '')
	{
		$outputData['status']  = "FAILURE";

		if (empty($id)) {
			$id  = $this->currentRequestData['id'];
		} else {
			$this->checkRequestMethod("put"); 	// CHECK THE REQUEST METHOD
		}

		// PARAMS FROM HTTP REQUEST
		if (!empty($id) && is_numeric($id)) {

			$modelOutput 			= $this->nativeModel->editGrpo($id);
			$getItemList 			= $this->nativeModel->editGrpoItemList($id);

			if (count($modelOutput) > 0) {
				
				// SHOW ADD BUTTON FOR APPROVAL PROCESS 
				$documentCreatedBy		 		= $modelOutput[0]['created_by'];
				$documentApprovalStatus			= $modelOutput[0]['approval_status'];
				$showAddButton 					= "0";
				$overAllApprovalStatus 			= 1;
				$getOverAllApprovalStatus 		= getOverAllApprovalStatusForDocument($this->tableNameStr,$id);
				
				if(count($getOverAllApprovalStatus)>0){
					$overAllApprovalStatus = $getOverAllApprovalStatus[0]['overall_approval_status'];		
				}
				
				
				if(
				  ($overAllApprovalStatus == 2) && 
				  ($documentCreatedBy == $this->currentUserId) && 
				  ($documentApprovalStatus == 1)
				)
				{
					$showAddButton = "1";
				}
				
				$frameEditDetails 	  = $this->frameGrpoEditDetails($modelOutput[0]);			
				$data 				  = array();
				$data				  = $frameEditDetails;
				
				// BIND THE LIST SUB ARRAY 
				$data['showAddButton']	= $showAddButton;
				$data['itemListArray'] 	= $this->frameGrpoItemList($getItemList);
				$outputData['status']   = "SUCCESS";
				$outputData['results']  = $data;
			} else {
				$outputData['message'] =  lang('GLB_015');  // INVALID ID PASSED
			}
		} else {
			$outputData['message']      = lang('GLB_007'); // INVALID PARAMETERS
		}
		$this->output->sendResponse($outputData);
	}
	
		
	/**
	* @METHOD NAME 	: frameGrpoEditDetails()
	*
	* @DESC 		: TO FRAME THE GRPO EDIT DETAILS 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function frameGrpoEditDetails($modelOutput)
    {
		// FRAME ALL THE INFO DATA
		$statusInfoDetails	= array();
		$getInfoData = array(	
			'getPaymentTermsList' 		 => $modelOutput['payment_terms_id'],
			'getPaymentMethodsList' 	 => $modelOutput['payment_method_id'],
			'getPurchaseTransStatusList~statusInfo'  => $modelOutput['status'],
			'getEmployeeList~buyerEmpInfo'			 => $modelOutput['buyer_emp_id'],
			'getCurrencyList' 			 			 => $modelOutput['currency_id'],
			'getBusinessPartnerList~vendorBpInfo'	 => $modelOutput['vendor_bp_id'],
			'getBusinessPartnerContactsList~vendorBpContactsInfo' 	  => $modelOutput['vendor_bp_contacts_id'],
			'getBusinessPartnerAddressList~vendorShipToBpAddressInfo' => $modelOutput['vendor_ship_to_bp_address_id'],
			'getBusinessPartnerAddressList~vendorPayToBpAddressInfo' => $modelOutput['vendor_pay_to_bp_address_id'],
			'getCreatedByDetails~createdByInfo'	 => $modelOutput['created_by'],
			'getBranchList~branchInfo'	 => $this->currentbranchId,
			'getDocumentNumberingList~documentNumberingInfo'	 => $modelOutput['document_numbering_id'],

		);
		
		$statusInfoDetails	= getAutoSuggestionListHelper($getInfoData);
		$result  			= array_merge($modelOutput,$statusInfoDetails);
		return $result;
	}
	
		
	/**
	* @METHOD NAME 	: frameGrpoItemList()
	*
	* @DESC 		: TO FRAME THE GRPO ITEM LIST 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function frameGrpoItemList($itemList)
    {

		// BIND THE LIST SUB ARRAY 
		if(count($itemList)>0){
			
			$getAllDetails = array();
			$getInfoData   = array(	
											'getTaxList' 	 => '',
											'getUomList'	 =>'',
											'getWarehouseList' => '',
											'getHsnList'	 => '',
											'getBinList'	 => '',
											'getDistributionRulesList' => ''
										);
										
			$getAllDetails	= getAutoSuggestionListHelper($getInfoData,1);
			
			foreach($itemList as $key => $value){
								
					$statusInfoDetails	= array();
					$getInfoData		= array(
											'getItemList' 	 => $value['item_id'],
										);
					
					$statusInfoDetails	= getAutoSuggestionListHelper($getInfoData);
					
					// FIND THE PARTICULAR ARRAY INDEX 
					$findTaxId			= array_search($value['tax_id'], array_column($getAllDetails['taxInfo'], 'id'));	
					$findUomId			= array_search($value['uom_id'], array_column($getAllDetails['uomInfo'], 'id'));	
					$findWarehouseId	= array_search($value['warehouse_id'], array_column($getAllDetails['warehouseInfo'], 'id'));	
					$findHsnId			= array_search($value['hsn_id'], array_column($getAllDetails['hsnInfo'], 'id'));	
					$findBinId			= array_search($value['bin_id'], array_column($getAllDetails['binInfo'], 'id')); 
				
					$statusInfoDetails['taxInfo'][0] 		= $getAllDetails['taxInfo'][$findTaxId];
					$statusInfoDetails['uomInfo'][0] 		= $getAllDetails['uomInfo'][$findUomId];
					$statusInfoDetails['warehouseInfo'][0] 	= $getAllDetails['warehouseInfo'][$findWarehouseId];
					$statusInfoDetails['hsnInfo'][0] 		= $getAllDetails['hsnInfo'][$findHsnId];
					$statusInfoDetails['binInfo'][0] 		= $getAllDetails['binInfo'][$findBinId];
					
					// DISTRIBUTION DETAILS 
					$distributionRulesDetails	= array();
					$distributionRulesId  		= $value['distribution_rules_id'];
					$distributionRulesArray  	= explode(",", $distributionRulesId);

					if (count($distributionRulesArray) > 0) {
						foreach ($distributionRulesArray as $distributionKey => $distributionValue) {
							if (!empty($distributionValue)) {
								// NEW CODE
								$findDistributionRulesId = array_search($distributionValue, array_column($getAllDetails['distributionRulesInfo'], 'id'));
								$distributionStatusInfoDetails	= $getAllDetails['distributionRulesInfo'][$findDistributionRulesId];
								if(is_array($distributionStatusInfoDetails)){
									$distributionRulesDetails[$distributionKey]	= $distributionStatusInfoDetails;
								}
							}
						}
					}
					$statusInfoDetails['distributionRulesInfo'] 		= $distributionRulesDetails;	
							
					// Re-Assign Array 
					$itemList[$key] = array_merge($value,$statusInfoDetails);
			}
		}
		return $itemList;
	}
	
	
	/**
	 * @METHOD NAME 	: getGrpoList()
	 *
	 * @DESC 		    : TO GET THE GRPO LIST DETAILS 
	 * @RETURN VALUE    : $outputdata array
	 * @PARAMETER 	    : -
	 * @SERVICE         : WEB
	 * @ACCESS POINT    : -
	 **/
	public function getGrpoList()
	{
		$this->checkRequestMethod("put"); // Check the Request Method

		$modelOutput = $this->nativeModel->getGrpoList($this->currentRequestData, 0);

		foreach ($modelOutput['searchResults'] as $key => $value) {
			$getItemList 								= $this->nativeModel->getGrpoItemList($value['id']);
			$modelOutput['searchResults'][$key]['lineItemInfo'] = $getItemList;
		}

		// FRAME OUTPUT
		$outputData['results']      = $modelOutput;
		$outputData['status']       = "SUCCESS";


		$this->output->sendResponse($outputData);
	}


	/**
	 * @METHOD NAME 	: getAnalyticsDetails()
	 *
	 * @DESC 			: TO GET THE ANALYTICS DETAILS FOR PURCHASE ORDER
	 * @RETURN VALUE 	: $outputdata array
	 * @PARAMETER 		: -
	 * @SERVICE      	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function getAnalyticsDetails()
	{
		// CHECK THE REQUEST METHOD.
		$this->checkRequestMethod("get");

		$passData['type']		= 'PURCHASE_TRANS_STATUS'; 
		$passData['tableName']	= GRPO; 
		$analyticsData 			= generateTransactionAnalytics($passData);
		
		$outputData['status']  = "SUCCESS";
		$outputData['results'] = $analyticsData;
		$this->output->sendResponse($outputData);
	}

	/**
	 * @METHOD NAME 	: downloadExcel()
	 *
	 * @DESC 			: TO DOWNLOAD THE EXCEL FORMAT
	 * @RETURN VALUE 	: $outputdata array
	 * @PARAMETER 		: -
	 * @SERVICE      	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function downloadExcel()
	{
		$modelOutput	= $this->nativeModel->getGrpoList($this->currentRequestData, 1);
		$resultsData 	= $modelOutput['searchResults'];
		$fileName		= $this->config->item('GRPO')['excel_file_name'];

		$outputData 	= processExcelData($resultsData, $fileName, $this->config->item('GRPO')['columns_list']);
		$this->output->sendResponse($outputData);
	}


	/**
	 * @METHOD NAME  : downloadInvoice()
	 *
	 * @DESC 		 : DOWNLOAD THE GRPO
	 * @RETURN VALUE : $outputdata array
	 * @PARAMETER 	 : -
	 * @SERVICE      : WEB
	 * @ACCESS POINT : -
	 **/
	public function downloadInvoice($id = '', $isMailDoc = 0)
	{
	
		$outputData['status'] = "FAILURE";
		if (empty($id)) {
			$id  = $this->currentRequestData['id'];
		} 
		
		$this->load->helper('MY_download_helper');
		// Getting Company Information.
		$companyDetails	= $this->commonModel->getCompanyInformation();
		$modelOutput = $this->nativeModel->editGrpo($id);
		$purchaseOrderItemList = $this->nativeModel->editGrpoItemList($id);

		if (count($modelOutput) > 0) {
            // Getting Purchase Order Info details.
			$modelOutput = $this->frameGrpoEditDetails($modelOutput[0]);
			$itemList = $this->frameGrpoItemList($purchaseOrderItemList);
			
			$itemRowCountVal = 6;
			if (count($companyDetails) == 1) {
				// Process data - set values.
				$invoiceProcessData['companyDetails'] = $companyDetails;
				$invoiceProcessData['modelOutput'] = $modelOutput;
				$invoiceProcessData['itemList'] = $itemList;
				$invoiceProcessData['itemRowCountVal'] = 6;
				$invoiceProcessData['fileName'] = 'grpo';
				$invoiceProcessData['fileHeadingName'] = 'GRPO';
				$invoiceProcessData['isMailDoc'] = 0;
				if($isMailDoc == 1){
					$invoiceProcessData['isMailDoc'] = 1;
				}
                // Generating  Invoice
				$outputData = generatePurchaseInvoice($invoiceProcessData);

				if($isMailDoc == 1){
					return $outputData;
				}
				
			} else {
				// Company Details not found.
				$outputData['message']  = lang('MSG_143');  
			}
		} else {
			// Purchase Order details not found.
			$outputData['message']  = lang('MSG_197'); 
		}
		$this->output->sendResponse($outputData);
	}
	

	/**
	* @METHOD NAME 	: sendDocumentMail()
	*
	* @DESC 		: TO SEND THE EMAIL WITH DOCUMENT. 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function sendDocumentMail()
    {
		$this->checkRequestMethod("post"); // Check the Request Method
		$getData = $this->currentRequestData;

		if(isset($getData['documentId']) &&
		   isset($getData['toEmailId']) &&
		   isset($getData['subject']) &&
		   isset($getData['message'])) {
		
		   $fileDetails = $this->downloadInvoice($getData['documentId'], 1);
		
		   $mailHelperData = array(
			   "email_id" => $getData['toEmailId'],
			   "subject" => $getData['subject'],
			   "message" => $getData['message'],
			   "documentDetails" => array(
				    "filePath" => $fileDetails['url'],
					//"fileName" => "Invoice_File.pdf" // Optional.
			    ),
			   "email_cc" => null,
			   "email_bcc" => null,
		   );

		   sendDocumentMail($mailHelperData);

			$outputData = [
				'status'       => 'SUCCESS',
				'message'      =>'Document Sent in mail.',
			];
	
			$this->output->sendResponse($outputData);
		}
		else{
			$outputData = [
				'status'       => 'FAILURE',
				'message'      =>'Invalid Request Data.',
		    ];
		   $this->output->sendResponse($outputData);
		}
		
	}

	/**
	 * @METHOD NAME 	: proceedApprovalActivityForDocument()
	 *
	 * @DESC 		    : TO PROCEED THE APPROVAL ACTIVITY FOR THE DOCUMENT 
	 * @RETURN VALUE    : $outputdata array
	 * @PARAMETER 	    : -
	 * @SERVICE         : WEB
	 * @ACCESS POINT    : -
	 **/
	public function proceedApprovalActivityForDocument()
	{
		$this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  	= "FAILURE";
		$outputData['message']	= lang('GLB_007'); // INVALID PARAMETERS
		
		// PARAMS FROM HTTP REQUEST
		$id  = $this->currentRequestData['id'];
		
		if (!empty($id) && is_numeric($id)) {
			// CHECK ADDITIONAL FUNCTIONALITY 
			$getDocumentData 			= $this->nativeModel->editGrpo($id);
			$documentApprovalStatus 	= $getDocumentData[0]['approval_status'];
			
			if($documentApprovalStatus == 2 ){
				$outputData['message']      = lang('MSG_349'); // ALREADY IN APPROVAL STATUS
			}else {
				
				$modelOutput = $this->nativeModel->proceedApprovalActivityForDocument($getDocumentData);
				
				if (1 == $modelOutput['flag']) {
					$outputData['status']       = "SUCCESS";
					$outputData['message']      = lang('MSG_350'); // Successfully updated
				} else if (2 == $modelOutput['flag']) {
					$outputData['message']      = lang('GLB_010'); // Unable to update the record
				} else if (3 == $modelOutput['flag']) {
					$outputData['message']      = lang('GLB_005'); // RECORD NOT FOUND. PLEASE CONTACT ADMIN
				}  else if (4 == $modelOutput['flag']) {
					$outputData['message']      = lang('MSG_347'); // PENDING
				}  else if (5 == $modelOutput['flag']) {
					$outputData['message']      = lang('MSG_348'); // REJECTED
				}
			}
		} else {
			$outputData['message']      = lang('GLB_007'); // INVALID PARAMETERS
		}
		$this->output->sendResponse($outputData);
	}
	
}
/* END OF FILE */