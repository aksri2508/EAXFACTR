<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Sales_transcations.php
* @Class  			 : Sales_transcations
* Model Name         : Sales_transcations
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

class Sales_transcations extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		//$segments = $this->uri->segment_array(); // To print segment
		
		// PROCESS SEGMENTS
		$getSegmentsCnt		= $this->uri->total_segments();
		
		$segmentMinus = 1;
		if($this->uri->segment(3) == 'downloadInvoice' || $this->uri->segment(3) == 'picklist'){
			$segmentMinus = 2;
		}
		
		if($this->uri->segment(2) == "sendMail"){
			if($this->uri->segment(3) == 11){
				$controllerName = "SALES_AR_INVOICE";
			}
			else if($this->uri->segment(3) == 12)
			{
				$controllerName = "SALES_AR_DP_INVOICE";
			}
			else if($this->uri->segment(3) == 13)
			{
				$controllerName = "SALES_AR_CREDIT_MEMO";
			}
			else if($this->uri->segment(3) == 14)
			{
				$controllerName = "SALES_RETURN";
			}
		}
		else{
			$controllerName 	= $this->uri->segment($getSegmentsCnt-$segmentMinus);
		}

		$screenDetails		= $this->config->item('SCREEN_NAMES')[strtoupper($controllerName)];
		
		//print_r($screenDetails);
		
		$this->load->model('company/sales_transcations_model', 'nativeModel');
		
		//printr($screenDetails);
	
		if(is_array($screenDetails) && count($screenDetails)>0){
			
			$childRefTblId  = $screenDetails['childRefId'];
			$childRefId 	= toCamelCaseSingleWord($childRefTblId);
		
			$this->tableNameStr 		 = strtoupper(str_replace("tbl_","",$screenDetails['tableName']));
			$this->tableName 			 = constant($this->tableNameStr);
			$this->MsgTitle				 = $screenDetails['MsgTitle'];
			
			// LOAD THE TABLE CONFIG FILES 
			$this->config->load('table_config/'.$screenDetails['tableName']);
			$this->config->load('table_config/'.$screenDetails['childTableName']);
			
			// SUCCESS / FAILURE - MESSAGE HANDLING 
			$this->saveSuccessMsg  			= str_replace("MODULE_NAME",$this->MsgTitle,lang('MSG_248')[0]);
			$this->saveSuccessMsgItems 		= str_replace("MODULE_NAME",$this->MsgTitle,lang('MSG_251')[0]);
			$this->updateSuccessMsg  		= str_replace("MODULE_NAME",$this->MsgTitle,lang('MSG_249')[0]);
			$this->updateSuccessMsgItems  	= str_replace("MODULE_NAME",$this->MsgTitle,lang('MSG_252')[0]);
			$this->downloadInvoiceMsg  		= str_replace("MODULE_NAME",$this->MsgTitle,lang('MSG_254')[0]);
			$this->deleteSuccessMsg  		= str_replace("MODULE_NAME",$this->MsgTitle,lang('MSG_250')[0]);
			$this->deleteSuccessMsgItems  	= str_replace("MODULE_NAME",$this->MsgTitle,lang('MSG_253')[0]);
			
			
		}else{
			echo json_encode(array(
				'status'		=> 'ERROR',
				'message' 		=> 'Unknown URL not allowed!',
				"responseCode" 	=> 200
			));
			exit();
		}
		
		

	}
	
	
	/**
	* @METHOD NAME 	: saveSalesTransactions()
	*
	* @DESC 		: TO SAVE THE SALES TRANSACTIONS 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function saveSalesTransactions()
    {
        $this->checkRequestMethod("post"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$getData 		       = $this->currentRequestData;
		
		$modelOutput 	  	   = $this->nativeModel->saveSalesTransactions($getData);

		// print_r($modelOutput);exit;

		if (1 == $modelOutput['flag']) {
			$outputData['sId']      	= $modelOutput['sId'];
			$outputData['status']       = "SUCCESS";
			$outputData['message']      = $this->saveSuccessMsg;  // Successfully Inserted
		} else if (2 == $modelOutput['flag']) {
			$outputData['message']      = lang('GLB_009');  // Unable to save the record
		}
        $this->output->sendResponse($outputData);
    }
		
	
	/**
	* @METHOD NAME 	: updateSalesTransactions()
	*
	* @DESC 		: TO UPDATE THE SALES TRANSACTIONS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function updateSalesTransactions()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$modelOutput		   = $this->nativeModel->updateSalesTransactions($this->currentRequestData);
		
		if (1 == $modelOutput['flag']) {
			$outputData['status']       = "SUCCESS";
			$outputData['message']      = $this->updateSuccessMsg;  //'Successfully updated
		}else if (2 == $modelOutput['flag']) {
			$outputData['message']      = lang('GLB_010'); // Unable to update the record
		}
        $this->output->sendResponse($outputData);
    }
	
	
	/**
	 * @METHOD NAME 	: copySalesTransactions()
	 *
	 * @DESC 			: COPY MULTIPLE RECORDS FOR TRANSACTIONS SCREENS 
	 * @RETURN VALUE 	: $outputdata array
	 * @PARAMETER 		: -
	 * @SERVICE      	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function copySalesTransactions()
	{
		$outputData['status']  = "FAILURE";
		$this->checkRequestMethod("put"); 	// CHECK THE REQUEST METHOD
	
		$getCopiedIds  = $this->currentRequestData['id'];
		
		//echo "this->tableNameStr".$this->tableNameStr;
		
		if($this->tableNameStr == 'SALES_AR_INVOICE' 
			//$this->tableNameStr == 'SALES_AR_CREDIT_MEMO'  
			|| $this->tableNameStr == 'SALES_AR_DP_INVOICE'
		) {
			// For both the screens we are neglecting the other ids due to downpayment id 
			$copiedIds[0] = $getCopiedIds[0];
		}
		
		//printr($copiedIds);exit;
		
		// TO CHECK THE RECORDS ARE ELIGIBLE FOR COPY 
		checkRecordsEligibleForCopy($copiedIds,$this->tableNameStr);
		
		// TO CHECK THE WHETHER WE ANY DRAFT RECORD EXISTS WHILE COPYING FUNCTIONTIONALTY
		checkDraftRecordsExists($copiedIds,$this->tableNameStr);
		
		$frameParentDetails = array();
		
		if(count($copiedIds) == 1) {
			return $this->editSalesTransactions($copiedIds[0]);
		}else if(count($copiedIds)>1){
			
			$getFirstCopyRecordDetails = $this->nativeModel->editSalesTransactions($copiedIds[0]);
			
			if(count($getFirstCopyRecordDetails) > 0){
				$frameEditDetails = $this->frameSalesTransactionsEditDetails($getFirstCopyRecordDetails[0]);

				// FRAME PARENT DETAILS
				$frameParentDetails['customer_bp_id'] 			= $frameEditDetails['customer_bp_id'];
				$frameParentDetails['customer_bp_contacts_id'] 	= $frameEditDetails['customer_bp_contacts_id'];
				$frameParentDetails['currency_id'] 				= $frameEditDetails['currency_id'];
				$frameParentDetails['currencyInfo'] 			= $frameEditDetails['currencyInfo'];
				$frameParentDetails['customerBpInfo'] 			= $frameEditDetails['customerBpInfo'];
				$frameParentDetails['customerBpContactsInfo'] 	= $frameEditDetails['customerBpContactsInfo'];
				
				$itemListArray = array();
				foreach($copiedIds as $copyKey => $copyValue){
					$getItemList 	 = $this->nativeModel->editItemList($copyValue);
					$itemListArray 	 = array_merge($itemListArray,$this->frameItemList($getItemList));
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
	 * @METHOD NAME 	: editSalesTransactions()
	 *
	 * @DESC 			: -
	 * @RETURN VALUE 	: $outputdata array
	 * @PARAMETER 		: -
	 * @SERVICE      	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function editSalesTransactions($id = '')
	{
		$outputData['status']  = "FAILURE";

		if (empty($id)) {
			$id  = $this->currentRequestData['id'];
		} else {
			$this->checkRequestMethod("put"); 	// CHECK THE REQUEST METHOD
		}

		// PARAMS FROM HTTP REQUEST
		if (!empty($id) && is_numeric($id)) {

			$modelOutput 				= $this->nativeModel->editSalesTransactions($id);
			$getItemList 			 	= $this->nativeModel->editItemList($id);

			if (count($modelOutput) > 0) {
				// SHOW ADD BUTTON FOR APPROVAL PROCESS 
				$documentCreatedBy		 		= $modelOutput[0]['created_by'];
				$documentApprovalStatus			= $modelOutput[0]['approval_status'];
				$showAddButton 					= "0";
				$overAllApprovalStatus 			= 1;
				$approvalStatusReportId			= "";
				$getOverAllApprovalStatus 		= getOverAllApprovalStatusForDocument($this->tableNameStr,$id);
				
				if(count($getOverAllApprovalStatus)>0){
					$overAllApprovalStatus = $getOverAllApprovalStatus[0]['overall_approval_status'];
					$approvalStatusReportId = $getOverAllApprovalStatus[0]['id'];					
				}

				if(
				  ($overAllApprovalStatus == 2) && 
				  ($documentCreatedBy == $this->currentUserId) && 
				  ($documentApprovalStatus == 1)
				)
				{
					$showAddButton = "1";
				}
				
				
				$frameEditDetails = $this->frameSalesTransactionsEditDetails($modelOutput[0]);

				$data 				  = array();
				$data				  = $frameEditDetails;

				// BIND THE LIST SUB ARRAY 
				$data['approvalStatusReportId'] = $approvalStatusReportId;
				$data['showAddButton']	= $showAddButton;
				$data['itemListArray'] 	= $this->frameItemList($getItemList);
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
	* @METHOD NAME 	: frameSalesTransactionsEditDetails()
	*
	* @DESC 		: -
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function frameSalesTransactionsEditDetails($modelOutput)
    {
		$statusInfoDetails	= array();
		$getInfoData = array(	
			'getPaymentTermsList' 					 => $modelOutput['payment_terms_id'],
			'getPaymentMethodsList' 				 => $modelOutput['payment_method_id'],
			'getEmployeeList~salesEmpInfo'			 => $modelOutput['sales_emp_id'],
			'getSalesTransStatusList~statusInfo'	 => $modelOutput['status'],
			'getCurrencyList' 						 => $modelOutput['currency_id'],
			'getBusinessPartnerList~customerBpInfo'	 => $modelOutput['customer_bp_id'],
			'getBusinessPartnerContactsList~customerBpContactsInfo' => $modelOutput['customer_bp_contacts_id'],
			'getBusinessPartnerAddressList~customerShipToBpAddressInfo' => $modelOutput['customer_ship_to_bp_address_id'],
			'getBusinessPartnerAddressList~customerBillToBpAddressInfo' => $modelOutput['customer_bill_to_bp_address_id'],
			'getCreatedByDetails~createdByInfo'	 				=> $modelOutput['created_by'],
			'getBranchList~branchInfo'	 => $this->currentbranchId,
			'getDocumentNumberingList~documentNumberingInfo'	 => $modelOutput['document_numbering_id'],

		);
		
		// ADDITION FOR AR CREDIT MEMO 
		if($this->tableNameStr == 'SALES_AR_CREDIT_MEMO'){
			$getInfoData['getIssuingNoteList'] = $modelOutput['issuing_note_id'];
		}		 
			
		$statusInfoDetails	= getAutoSuggestionListHelper($getInfoData);
		
		// ONLY FOR SALES AR INVOICE FRAME THE DOCUMENT TYPE INFO DETAILS 
		// INITIALIZE SEARCH DATA INFORMATION 
		$passSearchData['category'] = 2;
		$passSearchData['delFlag']  = 0;

		if($this->tableNameStr == 'SALES_AR_INVOICE' || $this->tableNameStr == 'SALES_AR_CREDIT_MEMO'){
			
			$documentNumberDetails = array();
		
			if(!empty($modelOutput['sales_ar_dp_invoice_id'])){
				// DOCUMENT NUMBER DETAILS 
				$passDocumenNumbertData['documentNoId']   	= $modelOutput['sales_ar_dp_invoice_id'];
				$passDocumenNumbertData['documentTypeId']   = 12; // AR DP INVOICE TYPE 
				$documentNumberDetails   	  				= $this->commonModel->getDocumentNumber(array_merge($passSearchData,$passDocumenNumbertData));
			}
			$statusInfoDetails['salesArDpInvoiceDocumentInfo'] = $documentNumberDetails; 
		}	
		
		$result  			= array_merge($modelOutput,$statusInfoDetails);
		return $result;
	}
	

	/**
	* @METHOD NAME 	: frameItemList()
	*
	* @DESC 		: -
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function frameItemList($itemList, $type = null)
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
	* @METHOD NAME 	: getSalesTransactionsList()
	*
	* @DESC 		: -
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getSalesTransactionsList()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
        $modelOutput           = $this->nativeModel->getSalesTransactionsList($this->currentRequestData);
		
		foreach($modelOutput['searchResults'] as $key => $value){	
			$itemList 		= $this->nativeModel->getItemList($value['id']);
			$modelOutput['searchResults'][$key]['lineItemInfo'] = $itemList;
		}
		
		// FRAME OUTPUT
        $outputData['results']      = $modelOutput;
        $outputData['status']       = "SUCCESS";
		
        $this->output->sendResponse($outputData);
    }
	
	
	/**
	 * @METHOD NAME 	: getAnalyticsDetails()
	 *
	 * @DESC 			: -
	 * @RETURN VALUE 	: $outputdata array
	 * @PARAMETER 		: -
	 * @SERVICE      	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function getAnalyticsDetails()
	{
		// CHECK THE REQUEST METHOD.
		$this->checkRequestMethod("get");

		$passData['type']		= 'SALES_TRANS_STATUS'; 
		$passData['tableName']	= $this->tableName; 
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
		$modelOutput	= $this->nativeModel->getSalesTransactionsList($this->currentRequestData, 1);
		$resultsData 	= $modelOutput['searchResults'];
		$fileName		= $this->config->item($this->tableNameStr)['excel_file_name'];
		
		$outputData 	= processExcelData($resultsData, $fileName, $this->config->item($this->tableNameStr)['columns_list']);
		$this->output->sendResponse($outputData);
	}
	

	/**
	 * @METHOD NAME  : downloadInvoice()
	 *
	 * @DESC 		 : -
	 * @RETURN VALUE : $outputdata array
	 * @PARAMETER 	 : -
	 * @SERVICE      : WEB
	 * @ACCESS POINT : -
	 **/
	public function downloadInvoice($id = '', $isMailDoc = 0)
	{
		$outputData['status'] = "FAILURE";
		/*
		if (empty($id)) {
			$id  = $this->currentRequestData['id'];
		} 
		*/
		if($isMailDoc != 1){
			$getSegmentsCnt		= $this->uri->total_segments();
			$id = $this->uri->segment($getSegmentsCnt);
		}
		$this->load->helper('MY_download_helper');
		
		$companyDetails			= $this->commonModel->getCompanyInformation();
		$modelOutput			= $this->nativeModel->editSalesTransactions($id);
		$itemListDetails		= $this->nativeModel->editItemList($id);

		if (count($modelOutput) > 0) {
			
			$modelOutput 	 = $this->frameSalesTransactionsEditDetails($modelOutput[0]);
			$itemList		 = $this->frameItemList($itemListDetails);

			$itemRowCountVal = 6;
			if (count($companyDetails) == 1) {
				// Process data - set values.
				$invoiceProcessData['companyDetails'] 	= $companyDetails;
				$invoiceProcessData['modelOutput'] 		= $modelOutput;
				$invoiceProcessData['itemList'] 		= $itemList;
				$invoiceProcessData['itemRowCountVal'] 	= 6;
				$invoiceProcessData['fileName']			= $this->MsgTitle; // NEED A WORKAROUND
				$invoiceProcessData['fileHeadingName']  = strtoupper($this->MsgTitle); // NEED A WORKAROUND
				$invoiceProcessData['isMailDoc'] = 0;
				if($isMailDoc == 1){
					$invoiceProcessData['isMailDoc'] = 1;
				}
                // Generating  Invoice
				$outputData = generateSalesTrasactionInvoice($invoiceProcessData);

				if($isMailDoc == 1){
					return $outputData;
				}

			} else {
				// Company Details not found.
				$outputData['message']  = lang('MSG_143');  
			}
		} else {
			$outputData['message']  = $this->downloadInvoiceMsg;
		}
		$this->output->sendResponse($outputData);
	}
	

	/**
	 * @METHOD NAME  : downloadInvoiceTemp()
	 *
	 * @DESC 		 : -
	 * @RETURN VALUE : $outputdata array
	 * @PARAMETER 	 : -
	 * @SERVICE      : WEB
	 * @ACCESS POINT : -
	 **/
	public function downloadPicklistInvoice($id = '', $isMailDoc = 0)
	{
		$outputData['status'] = "FAILURE";
		/*
		if (empty($id)) {
			$id  = $this->currentRequestData['id'];
		}
		*/		
		$getSegmentsCnt		= $this->uri->total_segments();
		$id = $this->uri->segment($getSegmentsCnt);
		$this->load->helper('MY_download_helper');
		
		
		$companyDetails			= $this->commonModel->getCompanyInformation();
		$modelOutput			= $this->nativeModel->editSalesTransactions($id);
		$itemListDetails		= $this->nativeModel->getItemListDetails($id);

		if (count($modelOutput) > 0) {
			$modelOutput 	 = $this->frameSalesTransactionsEditDetails($modelOutput[0]);
			$itemList		 = $this->frameItemList($itemListDetails,'invoice');

		// echo '<pre>';
		// print_r($modelOutput);
		// echo '<br>';
		// print_r($itemList);
		// exit;

			$itemRowCountVal = 6;
			if (count($companyDetails) == 1) {
				// Process data - set values.
				$invoiceProcessData['companyDetails'] 	= $companyDetails;
				$invoiceProcessData['modelOutput'] 		= $modelOutput;
				$invoiceProcessData['itemList'] 		= $itemList;
				$invoiceProcessData['itemRowCountVal'] 	= 6;
				$invoiceProcessData['fileName']			= $this->MsgTitle."_picklist"; // NEED A WORKAROUND
				$invoiceProcessData['fileHeadingName']  = strtoupper($this->MsgTitle); // NEED A WORKAROUND
				$invoiceProcessData['isMailDoc'] = 0;
				if($isMailDoc == 1){
					$invoiceProcessData['isMailDoc'] = 1;
				}
                // Generating  Invoice
				$outputData = generateSalesTrasactionPicklistInvoice($invoiceProcessData);

				if($isMailDoc == 1){
					return $outputData;
				}

			} else {
				// Company Details not found.
				$outputData['message']  = lang('MSG_143');  
			}
		} else {
			$outputData['message']  = $this->downloadInvoiceMsg;
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
			$getDocumentData 			= $this->nativeModel->editSalesTransactions($id);
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
/********************************END OF FUNCTIONALITY ************************************************************/