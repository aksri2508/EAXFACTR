<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Inventory_transfer_request.php
* @Class  			 : Inventory_transfer_request
* Model Name         : 
* Description        :
* Module             : COMPANY
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 08 MAY 2020
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : -
* Features           : 
*/
class Inventory_transfer_request extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->config->load('table_config/tbl_inventory_transfer_request.php');
		$this->config->load('table_config/tbl_inventory_transfer_request_items.php');
		$this->load->model('company/Inventory_transfer_request_model', 'nativeModel');

		$screenDetails				 = $this->config->item('SCREEN_NAMES')['INVENTORY_TRANSFER_REQUEST'];
		$this->tableNameStr 		 = strtoupper(str_replace("tbl_","",$screenDetails['tableName']));
		$this->tableName 			 = constant($this->tableNameStr);

	}
	
	
	/**
	 * @METHOD NAME 	: saveInventoryTransferRequest()
	 *
	 * @DESC 			: TO SAVE THE INVENTORY TRANSFER REQUEST
	 * @RETURN VALUE 	: $outputdata array
	 * @PARAMETER 		: -
	 * @SERVICE      	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function saveInventoryTransferRequest()
	{
		// Params from http request
		$this->checkRequestMethod("post"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$getData = $this->currentRequestData;

		$modelOutput = $this->nativeModel->saveInventoryTransferRequest($getData);

		if (1 == $modelOutput['flag']) {
			$outputData['sId']      	= $modelOutput['sId'];
			$outputData['status']       = "SUCCESS";
			$outputData['message']      = lang('MSG_223');  // Successfully Inserted
		} else if (2 == $modelOutput['flag']) {
			$outputData['message']      = lang('GLB_009');  // Unable to save the record
		}
		$this->output->sendResponse($outputData);
	}

	
	/**
	 * @METHOD NAME 	: updateInventoryTransferRequest()
	 *
	 * @DESC 			: -
	 * @RETURN VALUE 	: $outputdata array
	 * @PARAMETER 		: -
	 * @SERVICE      	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function updateInventoryTransferRequest()
	{
		$this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$modelOutput		   = $this->nativeModel->updateInventoryTransferRequest($this->currentRequestData);

		if (1 == $modelOutput['flag']) {
			$outputData['status']       = "SUCCESS";
			$outputData['message']      = lang('MSG_224'); // Successfully updated
		} else if (2 == $modelOutput['flag']) {
			$outputData['message']      = lang('GLB_010'); // Unable to update the record
		} 
		$this->output->sendResponse($outputData);
	}

	
	/**
	 * @METHOD NAME 	: editInventoryTransferRequest()
	 *
	 * @DESC 			: 
	 * @RETURN VALUE 	: $outputdata array
	 * @PARAMETER 		: -
	 * @SERVICE      	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function editInventoryTransferRequest($id = '')
	{
		$outputData['status']  = "FAILURE";

		if (empty($id)) {
			$id  = $this->currentRequestData['id'];
		} else {
			$this->checkRequestMethod("put"); 	// CHECK THE REQUEST METHOD
		}

		// PARAMS FROM HTTP REQUEST
		if (!empty($id) && is_numeric($id)) {

			$modelOutput 	= $this->nativeModel->editInventoryTransferRequest($id);
			$getItemList 	= $this->nativeModel->editInventoryTransferRequestItemList($id);

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
			
				$frameEditDetails 	  = $this->frameInventoryTransferRequestEditDetails($modelOutput[0]);			
				$data 				  = array();
				$data				  = $frameEditDetails;

				// BIND THE LIST SUB ARRAY 
				$data['showAddButton']	= $showAddButton;
				$data['itemListArray'] 	= $this->frameInventoryTransferRequestItemList($getItemList);
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
	* @METHOD NAME 	: frameInventoryTransferRequestEditDetails()
	*
	* @DESC 		:
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function frameInventoryTransferRequestEditDetails($modelOutput)
    {
		// FRAME ALL THE INFO DATA
		$statusInfoDetails	= array();
		$getInfoData = array(	
			'getInventoryTransStatusList~statusInfo' 		 => $modelOutput['status'],
			'getDutyStatusList' 			 				 => $modelOutput['duty_status_id'],
			'getBusinessPartnerList~BpInfo'					 => $modelOutput['business_partner_id'],
			'getBusinessPartnerContactsList~BpContactsInfo'  => $modelOutput['bp_contacts_id'],
			'getWarehouseList~fromWarehouseInfo' 			 => $modelOutput['from_warehouse_id'],
			'getWarehouseList~toWarehouseInfo'				 => $modelOutput['to_warehouse_id'],
			'getEmployeeList~salesEmpInfo'				 	 => $modelOutput['sales_emp_id'],
			'getCreatedByDetails~createdByInfo'	     => $modelOutput['created_by'],
			'getBranchList~branchInfo'	 => $this->currentbranchId,
			'getDocumentNumberingList~documentNumberingInfo'	 => $modelOutput['document_numbering_id'],


		);
		
		$statusInfoDetails	= getAutoSuggestionListHelper($getInfoData);
		$result  			= array_merge($modelOutput,$statusInfoDetails);
		return $result;
	}
	
	
	/**
	* @METHOD NAME 	: frameInventoryTransferRequestItemList()
	*
	* @DESC 		:  
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function frameInventoryTransferRequestItemList($itemList)
    {
		// BIND THE LIST SUB ARRAY 
		if(count($itemList)>0){
			
			foreach($itemList as $key => $value){
					$statusInfoDetails	= array();
					$getInfoData = array(	
								'getBinList~fromBinInfo'			 => $value['from_bin_id'],
								'getBinList~toBinInfo'			 	 => $value['to_bin_id'],
								'getItemList' 						 => $value['item_id'],
								'getUomList'						 => $value['uom_id'],
								'getWarehouseList~fromWarehouseInfo' => $value['from_warehouse_id'],
								'getWarehouseList~toWarehouseInfo'	 => $value['to_warehouse_id'],
							);
					
					$statusInfoDetails	= getAutoSuggestionListHelper($getInfoData);
					
							
					// Re-Assign Array 
					$itemList[$key] = array_merge($value,$statusInfoDetails);
			}
		}
		return $itemList;
	}
	
	
	/**
	 * @METHOD NAME 	: getInventoryTransferRequestList()
	 *
	 * @DESC 		    : 
	 * @RETURN VALUE    : $outputdata array
	 * @PARAMETER 	    : -
	 * @SERVICE         : WEB
	 * @ACCESS POINT    : -
	 **/
	public function getInventoryTransferRequestList()
	{
		$this->checkRequestMethod("put"); // Check the Request Method

		$modelOutput = $this->nativeModel->getInventoryTransferRequestList($this->currentRequestData, 0);

		foreach ($modelOutput['searchResults'] as $key => $value) {
			$getItemList 		= $this->nativeModel->getInventoryTransferRequestItemList($value['id']);
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

		$passData['type']		= 'INVENTORY_TRANS_STATUS'; 
		$passData['tableName']	= INVENTORY_TRANSFER_REQUEST; 
		$analyticsData 			= generateTransactionAnalytics($passData,1);
		
		$outputData['status']  = "SUCCESS";
		$outputData['results'] = $analyticsData;
		$this->output->sendResponse($outputData);
	}

	
	/**
	 * @METHOD NAME 	: getFromToWarehouseList()
	 *
	 * @DESC 			: -
	 * @RETURN VALUE 	: $outputdata array
	 * @PARAMETER 		: -
	 * @SERVICE      	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function getFromToWarehouseList(){
		
		$this->checkRequestMethod("get"); // Check the Request Method

		// Branch INFO 
		$warehouseDetails   		= $this->commonModel->getFromToWarehouseList();
		
		$currentBranchWarehouseArray = array();
		$otherBranchWarehouseArray 	 = array();
		
		$i = 0 ;
		$j = 0;
		foreach($warehouseDetails as $warehouseKey => $warehouseValue){
			
				$binData = array();
				$binId	= $warehouseValue['bin_id'];

					
				if($warehouseValue['branch_id'] == $this->currentbranchId){
					$currentBranchWarehouseArray[$i] = $warehouseValue;
					$i++;
				}else{
					$otherBranchWarehouseArray[$j] = $warehouseValue;
					$j++;
				}
			
				
		}
		
		/*
			foreach($result as $warehouseKey => $warehouseValue){
					
					$binData = array();
					$binId = $warehouseValue['bin_id'];

					if(!empty($binId)){
						$binIdArray = explode(",",$binId);
						
						//printr($binIdArray);						exit;
						
						foreach($binIdArray as $binKey => $binValue ){
					
							// BIN INFORMATION DETAILS 
							$this->app_db->select(array(
											MASTER_BIN.'.id',
											MASTER_BIN.'.bin_code',
											MASTER_BIN.'.bin_name'
											)
									);
											
							$this->app_db->from(MASTER_BIN);	
							$this->app_db->where(MASTER_BIN.'.id', $binValue);	// ONLY active warehouse 
							
							$rs = $this->app_db->get();
							$binResult =  $rs->result_array();
							if(!empty($binResult)){
								$binData[$binKey] = $binResult[0];
							}
						}
					}					
					$result[$warehouseKey]['binList'] =  $binData;
			}
		
		*/
		
		
		
		
		$output['fromList'] = $otherBranchWarehouseArray;
		$output['toList']	= $currentBranchWarehouseArray;
		

		$outputData['status']  = "SUCCESS";
		$outputData['results'] = $output;
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
		$modelOutput	= $this->nativeModel->getInventoryTransferRequestList($this->currentRequestData, 1);
		$resultsData 	= $modelOutput['searchResults'];
		$fileName		= $this->config->item('INVENTORY_TRANSFER_REQUEST')['excel_file_name'];

		$outputData 	= processExcelData($resultsData, $fileName, $this->config->item('INVENTORY_TRANSFER_REQUEST')['columns_list']);
		$this->output->sendResponse($outputData);
	}
	
	
	/**
	 * @METHOD NAME  : downloadInvoice()
	 *
	 * @DESC 		 : DOWNLOAD THE INVENTORY TRANSFER 
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
		$companyDetails			= $this->commonModel->getCompanyInformation();
		$modelOutput 			= $this->nativeModel->editInventoryTransferRequest($id);
		$itemListDetails		= $this->nativeModel->editInventoryTransferRequestItemList($id);

		if (count($modelOutput) > 0) {
           
			$modelOutput = $this->frameInventoryTransferRequestEditDetails($modelOutput[0]);
			$itemList 	 = $this->frameInventoryTransferRequestItemList($itemListDetails);

			$itemRowCountVal = 6;
			if (count($companyDetails) == 1) {
				// Process data - set values.
				$invoiceProcessData['companyDetails']	= $companyDetails;
				$invoiceProcessData['modelOutput'] 		= $modelOutput;
				$invoiceProcessData['itemList'] 		= $itemList;
				$invoiceProcessData['itemRowCountVal'] 	= 6;
				$invoiceProcessData['fileName'] 		= 'inventory_transfer_request';
				$invoiceProcessData['fileHeadingName'] 	= 'INVENTORY TRANSFER REQUEST';
				$invoiceProcessData['isMailDoc'] = 0;
				if($isMailDoc == 1){
					$invoiceProcessData['isMailDoc'] = 1;
				}
                // Generating  Invoice
				$outputData = generateInventoryInvoice($invoiceProcessData);

				if($isMailDoc == 1){
					return $outputData;
				}

			} else {
				// Company Details not found.
				$outputData['message']  = lang('MSG_143');  
			}
		} else { // NOT FOUND
			$outputData['message']  = lang('MSG_198'); 
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
			$getDocumentData 			= $this->nativeModel->editInventoryTransferRequest($id);
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