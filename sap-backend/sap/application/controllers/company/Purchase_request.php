<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Purchase_request.php
* @Class  			 : Purchase_request
* Model Name         : Purchase_request_model
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 03 MAY 2020
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : -
* Features           : 
*/

class Purchase_request extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->config->load('table_config/tbl_purchase_request.php');
		$this->config->load('table_config/tbl_purchase_request_items.php');
		$this->load->model('company/purchase_request_model', 'nativeModel');
		
		$screenDetails				 = $this->config->item('SCREEN_NAMES')['PURCHASE_REQUEST'];
		$this->tableNameStr 		 = strtoupper(str_replace("tbl_","",$screenDetails['tableName']));
		$this->tableName 			 = constant($this->tableNameStr);
		
	}


	/**
	 * @METHOD NAME 	: savePurchaseRequest()
	 *
	 * @DESC 			: TO SAVE THE PURCHASE REQUEST DETAILS.
	 * @RETURN VALUE 	: $outputdata array
	 * @PARAMETER 		: -
	 * @SERVICE      	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function savePurchaseRequest()
	{
		// Params from http request
		$this->checkRequestMethod("post"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$getData = $this->currentRequestData;

		$modelOutput = $this->nativeModel->savePurchaseRequest($getData);

		if (1 == $modelOutput['flag']) {
			$outputData['sId']      	= $modelOutput['sId'];
			$outputData['status']       = "SUCCESS";
			$outputData['message']      = lang('MSG_172');  // Successfully Inserted
		} else if (2 == $modelOutput['flag']) {
			$outputData['message']      = lang('GLB_009');  // Unable to save the record
		} else if (4 == $modelOutput['flag']) {
			$outputData['message'] = lang('MSG_173'); 		// Record Already Exists
		}
		$this->output->sendResponse($outputData);
	}
	
	
	/**
	 * @METHOD NAME 	: updatePurchaseRequest()
	 *
	 * @DESC 			: TO UPDATE THE PURCHASE REQUEST. 
	 * @RETURN VALUE 	: $outputdata array
	 * @PARAMETER 		: -
	 * @SERVICE      	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function updatePurchaseRequest()
	{
		$this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$modelOutput		   = $this->nativeModel->updatePurchaseRequest($this->currentRequestData);
		$mof = $modelOutput['flag'];

		if (1 == $modelOutput['flag']) {
			$outputData['status']       = "SUCCESS";
			$outputData['message']      = lang('MSG_174'); //'Successfully updated
		} else if (2 == $modelOutput['flag']) {
			$outputData['message']      = lang('GLB_010'); // Unable to update the record
		} else if (4 == $modelOutput['flag'] || 3 == $modelOutput['flag']) {
			$outputData['message']      = lang('MSG_173'); // Record Already Exists
		}
		$this->output->sendResponse($outputData);
	}

	
	/**
	 * @METHOD NAME 	: copyPurchaseRequest() // editRentalTransactions
	 *
	 * @DESC 			: COPY MULTIPLE RECORDS FOR TRANSACTIONS SCREENS 
	 * @RETURN VALUE 	: $outputdata array
	 * @PARAMETER 		: -
	 * @SERVICE      	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function copyPurchaseRequest()
	{
		$outputData['status']  = "FAILURE";
		$this->checkRequestMethod("put"); 	// CHECK THE REQUEST METHOD
	
		$copiedIds  = $this->currentRequestData['id'];
		
		// TO CHECK THE RECORDS ARE ELIGIBLE FOR COPY : Closed or Cancelled Document should not be copied
		checkRecordsEligibleForCopy($copiedIds,$this->tableNameStr);
		
		// TO CHECK THE WHETHER WE ANY DRAFT RECORD EXISTS WHILE COPYING FUNCTIONTIONALTY
		checkDraftRecordsExists($copiedIds,$this->tableNameStr);
		
		$frameParentDetails = array();
		
		if(count($copiedIds) == 1) {
			return $this->editPurchaseRequest($copiedIds[0]);
		}else if(count($copiedIds)>1){
			
			$getFirstCopyRecordDetails = $this->nativeModel->editPurchaseRequest($copiedIds[0]);
			
			if(count($getFirstCopyRecordDetails) > 0){
				$frameEditDetails = $this->framePurchaseRequestEditDetails($getFirstCopyRecordDetails[0]);

				$itemListArray = array();
				foreach($copiedIds as $copyKey => $copyValue){
					$getItemList 	 = $this->nativeModel->editPurchaseRequestItemList($copyValue);
					$itemListArray = array_merge($itemListArray,$this->framePurchaseRequestItemList($getItemList));
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
	 * @METHOD NAME 	: editPurchaseRequest()
	 *
	 * @DESC 			: TO EDIT PURCAHSE REQUEST 
	 * @RETURN VALUE 	: $outputdata array
	 * @PARAMETER 		: -
	 * @SERVICE      	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function editPurchaseRequest($id = '')
	{
		$outputData['status']  = "FAILURE";

		if (empty($id)) {
			$id  = $this->currentRequestData['id'];
		} else {
			$this->checkRequestMethod("put"); 	// CHECK THE REQUEST METHOD
		}

		// PARAMS FROM HTTP REQUEST
		if (!empty($id) && is_numeric($id)) {

			$modelOutput 				= $this->nativeModel->editPurchaseRequest($id);
			$getItemList 			 	= $this->nativeModel->editPurchaseRequestItemList($id);

			if (count($modelOutput) > 0) {
				//$id = 77;
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

				$frameEditDetails 	  = $this->framePurchaseRequestEditDetails($modelOutput[0]);
				$data 				  = array();
				$data				  = $frameEditDetails;

				// BIND THE LIST SUB ARRAY 
				$data['showAddButton']	= $showAddButton;
				$data['itemListArray'] 	= $this->framePurchaseRequestItemList($getItemList);
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
	* @METHOD NAME 	: framePurchaseRequestEditDetails()
	*
	* @DESC 		: TO FRAME THE PURCHASE REQUEST EDIT DETAILS 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function framePurchaseRequestEditDetails($modelOutput)
    {
		$statusInfoDetails	= array();
		$getInfoData = array(	
			'getEmployeeList~requesterInfo'	 => $modelOutput['requester_id'],
			'getPurchaseTransStatusList~statusInfo'	 => $modelOutput['status'],
			'getCreatedByDetails~createdByInfo'	 => $modelOutput['created_by'],
			'getBranchList~branchInfo'	 => $this->currentbranchId,
			'getDocumentNumberingList~documentNumberingInfo'	 => $modelOutput['document_numbering_id'],

		);
		$statusInfoDetails	= getAutoSuggestionListHelper($getInfoData);
		$result  			= array_merge($modelOutput,$statusInfoDetails);
		return $result;
	}
	

	/**
	* @METHOD NAME 	: framePurchaseRequestItemList()
	*
	* @DESC 		: TO FRAME THE PURCHASE REQUEST ITEM LIST 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function framePurchaseRequestItemList($itemList)
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
	 * @METHOD NAME 	: getPurchaseRequestList()
	 *
	 * @DESC 		    : TO GET THE PURCHASE REQUEST LIST DETAILS 
	 * @RETURN VALUE    : $outputdata array
	 * @PARAMETER 	    : -
	 * @SERVICE         : WEB
	 * @ACCESS POINT    : -
	 **/
	public function getPurchaseRequestList()
	{
		$this->checkRequestMethod("put"); // Check the Request Method

		$modelOutput = $this->nativeModel->getPurchaseRequestList($this->currentRequestData, 0);

		foreach ($modelOutput['searchResults'] as $key => $value) {
			$getItemList 		= $this->nativeModel->getPurchaseRequestItemList($value['id']);
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
	 * @DESC 			: TO GET THE ANALYTICS DETAILS FOR PURCHASE REQUEST
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
		$passData['tableName']	= PURCHASE_REQUEST; 
		$analyticsData 			= generateTransactionAnalytics($passData);
		
		$outputData['status']  = "SUCCESS";
		$outputData['results'] = $analyticsData;
		$this->output->sendResponse($outputData);
	}


	/**
	 * @METHOD NAME 	: downloadExcel()
	 *
	 * @DESC 			: TO DOWNLOAD THE EXCEL FORMAT
	 * @RETURN VALUE	: $outputdata array
	 * @PARAMETER 		: -
	 * @SERVICE      	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function downloadExcel()
	{
		$modelOutput	= $this->nativeModel->getPurchaseRequestList($this->currentRequestData, 1);
		$resultsData 	= $modelOutput['searchResults'];
		$fileName		= $this->config->item('PURCHASE_REQUEST')['excel_file_name'];

		$outputData 	= processExcelData($resultsData, $fileName, $this->config->item('PURCHASE_REQUEST')['columns_list']);
		$this->output->sendResponse($outputData);
	}


	/**
	 * @METHOD NAME  : downloadInvoice()
	 *
	 * @DESC 		 : DOWNLOAD THE PURCHASE REQUEST INVOICE
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

		$modelOutput = $this->nativeModel->editPurchaseRequest($id);
	
		$purchaseOrderItemList = $this->nativeModel->editPurchaseRequestItemList($id);
		
		if (count($modelOutput) > 0) {
            // Getting Purchase Order Info details.
			$modelOutput = $this->framePurchaseRequestEditDetails($modelOutput[0]);
			$itemList = $this->framePurchaseRequestItemList($purchaseOrderItemList);
		
			$itemRowCountVal = 6;
			if (count($companyDetails) == 1) {
				// Process data - set values.
				$invoiceProcessData['companyDetails'] = $companyDetails;
				$invoiceProcessData['modelOutput'] = $modelOutput;
				$invoiceProcessData['itemList'] = $itemList;
				$invoiceProcessData['itemRowCountVal'] = 6;
				$invoiceProcessData['fileName'] = 'purchase_request';
				$invoiceProcessData['fileHeadingName'] = 'PURCHASE REQUEST';
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
			$outputData['message']  = lang('MSG_195'); 
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
			$getDocumentData 			= $this->nativeModel->editPurchaseRequest($id);
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
