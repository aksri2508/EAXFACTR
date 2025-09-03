<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Rental_Inspection_transcations.php
* @Class  			 : Rental_Inspection_transcations
* Model Name         : Rental_Inspection_transcations
* Description        : RENTAL INSPECTION MODULE FOR 2 SCREENS
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : -
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : -
* Features           : 
*/
class Rental_Inspection_transcations extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
	
		// PROCESS SEGMENTS
		$getSegmentsCnt		= $this->uri->total_segments();

		$segmentMinus = 1;
		if($this->uri->segment(3) == 'downloadInvoice'){
			$segmentMinus = 2;
		}
		
		if($this->uri->segment(2) == "sendMail"){
			if($this->uri->segment(3) == 20){
				$controllerName = "RENTAL_INSPECTION_OUT";
			}
			else if($this->uri->segment(3) == 23)
			{
				$controllerName = "RENTAL_INSPECTION_IN";
			}
		}
		else{
			$controllerName 	= $this->uri->segment($getSegmentsCnt-$segmentMinus);
		}

		$screenDetails		= $this->config->item('SCREEN_NAMES')[strtoupper($controllerName)];
		
		$this->load->model('company/Rental_inspection_transcations_model', 'nativeModel');

	
		if(is_array($screenDetails) && count($screenDetails)>0){
			
			$childRefTblId  = $screenDetails['childRefId'];
			$childRefId 	= toCamelCaseSingleWord($childRefTblId);
		
			$this->tableNameStr 		 = strtoupper(str_replace("tbl_","",$screenDetails['tableName']));
			$this->tableName 			 = constant($this->tableNameStr);
			$this->MsgTitle				 = $screenDetails['MsgTitle'];
			$this->controllerName		 = $controllerName;
			
			// LOAD THE TABLE CONFIG FILES 
			$this->config->load('table_config/'.$screenDetails['tableName']);
			$this->config->load('table_config/'.$screenDetails['childTableName']);


			// SUCCESS / FAILURE - MESSAGE HANDLING 
			$this->saveSuccessMsg  			= str_replace("MODULE_NAME",$this->MsgTitle,lang('MSG_315')[0]);
			$this->saveSuccessMsgItems 		= str_replace("MODULE_NAME",$this->MsgTitle,lang('MSG_318')[0]);
			$this->updateSuccessMsg  		= str_replace("MODULE_NAME",$this->MsgTitle,lang('MSG_316')[0]);
			$this->updateSuccessMsgItems  	= str_replace("MODULE_NAME",$this->MsgTitle,lang('MSG_319')[0]);
			$this->downloadInvoiceMsg  		= str_replace("MODULE_NAME",$this->MsgTitle,lang('MSG_321')[0]);
			$this->deleteSuccessMsg  		= str_replace("MODULE_NAME",$this->MsgTitle,lang('MSG_317')[0]);
			$this->deleteSuccessMsgItems  	= str_replace("MODULE_NAME",$this->MsgTitle,lang('MSG_320')[0]);

			
			// LOAD INITIAL CONFIGURATION TO MODEL FILE 
			$this->nativeModel->initScreenConfig($controllerName);
			
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
	* @METHOD NAME 	: saveRentalInspectionTransactions()
	*
	* @DESC 		: TO SAVE THE RENTAL TRANSACTIONS 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function saveRentalInspectionTransactions()
    {
        $this->checkRequestMethod("post"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$getData 		       = $this->currentRequestData;
		
		$modelOutput 	  	   = $this->nativeModel->saveRentalInspectionTransactions($getData);

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
	* @METHOD NAME 	: updateRentalInspectionTransactions()
	*
	* @DESC 		: TO UPDATE THE RENTAL INSPECTION TRANSACTIONS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function updateRentalInspectionTransactions()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$modelOutput		   = $this->nativeModel->updateRentalInspectionTransactions($this->currentRequestData);
		
		if (1 == $modelOutput['flag']) {
			$outputData['status']       = "SUCCESS";
			$outputData['message']      = $this->updateSuccessMsg;  //'Successfully updated
		}else if (2 == $modelOutput['flag']) {
			$outputData['message']      = lang('GLB_010'); // Unable to update the record
		}
        $this->output->sendResponse($outputData);
    }
	
	
	/**
	 * @METHOD NAME 	: editRentalInspectionTransactions()
	 *
	 * @DESC 			: -
	 * @RETURN VALUE 	: $outputdata array
	 * @PARAMETER 		: -
	 * @SERVICE      	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function editRentalInspectionTransactions($id = '')
	{
		$outputData['status']  = "FAILURE";

		if (empty($id)) {
			$id  = $this->currentRequestData['id'];
		} else {
			$this->checkRequestMethod("put"); 	// CHECK THE REQUEST METHOD
		}

		// PARAMS FROM HTTP REQUEST
		if (!empty($id) && is_numeric($id)) {

			$modelOutput 				= $this->nativeModel->editRentalInspectionTransactions($id);
			$getItemList 			 	= $this->nativeModel->editItemList($id);

			if (count($modelOutput) > 0) {

				$frameEditDetails = $this->frameRentalInspectionTransactionsEditDetails($modelOutput[0]);

				$data 				  = array();
				$data				  = $frameEditDetails;

				// BIND THE LIST SUB ARRAY 
				$data['itemListArray'] 	= $getItemList;
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
	* @METHOD NAME 	: frameRentalInspectionTransactionsEditDetails()
	*
	* @DESC 		: -
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function frameRentalInspectionTransactionsEditDetails($modelOutput)
    {
		$statusInfoDetails	= array();
		$getInfoData = array(	
			'getEmployeeList~empInfo'			 	 => $modelOutput['emp_id'],
			'getRentalTransStatusList~statusInfo'	 => $modelOutput['status'],
			'getCurrencyList' 						 => $modelOutput['currency_id'],
			'getBusinessPartnerList~customerBpInfo'	 => $modelOutput['customer_bp_id'],
			'getBusinessPartnerContactsList~customerBpContactsInfo' => $modelOutput['customer_bp_contacts_id'],
			'getCreatedByDetails~createdByInfo'	 				 => $modelOutput['created_by'],
			'getBranchList~branchInfo'							 => $this->currentbranchId,
			'getDocumentNumberingList~documentNumberingInfo'	 => $modelOutput['document_numbering_id'],
			'getRentalItemList' 								 => $modelOutput['rental_item_id'],
			'getRentalEquipmentList'				 			 => $modelOutput['rental_equipment_id'],
			'getInspectionTemplateList'				 			 => $modelOutput['inspection_template_id'],
		);
		
		$statusInfoDetails	= getAutoSuggestionListHelper($getInfoData);
		$result  			= array_merge($modelOutput,$statusInfoDetails);
		return $result;
	}
	
	
	/**
	* @METHOD NAME 	: getRentalInspectionTransactionsList()
	*
	* @DESC 		: -
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getRentalInspectionTransactionsList()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
        $modelOutput           = $this->nativeModel->getRentalInspectionTransactionsList($this->currentRequestData);
		
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

		$passData['type']		= 'RENTAL_TRANS_STATUS'; 
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
		$modelOutput	= $this->nativeModel->getRentalTransactionsList($this->currentRequestData, 1);
		$resultsData 	= $modelOutput['searchResults'];
		$fileName		= $this->config->item($this->tableNameStr)['excel_file_name'];
		
		$outputData 	= processExcelData($resultsData, $fileName, $this->config->item($this->tableNameStr)['columns_list']);
		$this->output->sendResponse($outputData);
	}
	
	
	
/*************************************** COPIED FUNCITONALITY CODE WITH REFERRED TABLE ***************************************/
	/**
	 * @METHOD NAME 	: copyRentalInspectionTransactions()
	 *
	 * @DESC 			: 1. COPY THE CHILD RECORDS TO ANOTHER SCREEN (RENTAL DELIVERY)
	 * @RETURN VALUE 	: $outputdata array
	 * @PARAMETER 		: -
	 * @SERVICE      	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function copyRentalInspectionTransactions()
	{
		$outputData['status']  = "FAILURE";
		$this->checkRequestMethod("put"); 	// CHECK THE REQUEST METHOD
	
		$parentCopiedIds  = $this->currentRequestData['id'];
		
		// TO CHECK THE RECORDS ARE ELIGIBLE FOR COPY 
		checkRecordsEligibleForCopy($parentCopiedIds,$this->tableNameStr);
		
		// TO CHECK THE WHETHER WE ANY DRAFT RECORD EXISTS WHILE COPYING FUNCTIONTIONALTY
		checkDraftRecordsExists($parentCopiedIds,$this->tableNameStr);
		
		
		// FOR INSPECTION IN AND OUT WE NEED TO CHECK ADDITION CONDITION
		$chkInspectionTemplateOverallStatus = $this->checkCopyInspectionTemplateStatus($parentCopiedIds);
		$frameParentDetails					= array();

		if($chkInspectionTemplateOverallStatus == 0) { 
			$outputData['message']      =  lang('MSG_329'); // INSPECTION TEMPLATES
		}else {
				if(count($parentCopiedIds) > 0) {
					
					// FRAME ITEM LIST ARRAY BY MERGING PARENT RECORDS 
					$parentEditRecord 	 = array();
					$itemListArray		 = array();
					
					$i = 0;
					foreach($parentCopiedIds as $copyKey => $copyValue){
						
						$getRecordDetails		= $this->nativeModel->editRentalInspectionTransactions($copyValue);
						$getItemList 			= $this->nativeModel->editItemList($copyValue);
						$frameEditDetails 		= $this->frameRentalInspectionTransactionsEditDetails($getRecordDetails[0]);
						
						//printr($frameEditDetails); exit;
						
						// FRAME PARENT DETAILS
						$frameParentDetails['customer_bp_id'] 			= $frameEditDetails['customer_bp_id'];
						$frameParentDetails['customerBpInfo'] 			= $frameEditDetails['customerBpInfo'];
						$frameParentDetails['customer_bp_contacts_id'] 	= $frameEditDetails['customer_bp_contacts_id'];
						$frameParentDetails['customerBpContactsInfo'] 	= $frameEditDetails['customerBpContactsInfo'];
						$frameParentDetails['currency_id'] 				= $frameEditDetails['currency_id'];
						$frameParentDetails['currencyInfo'] 			= $frameEditDetails['currencyInfo'];
						$isUtilized										= $getItemList[0]['is_utilized'];
						
						$frameItemChildArray 							= array();
						
						if($getItemList[0]['copy_from_type'] ==''){
							$frameItemChildArray['copy_from_type'] 	= $getItemList[0]['copy_from_type'];
							$frameItemChildArray['copy_from_id'] 	= $getItemList[0]['copy_from_id'];
							$frameItemChildArray['rental_item_id'] 	= $frameEditDetails['rental_item_id'];
							$frameItemChildArray['rentalItemInfo'] 	= $frameEditDetails['rentalItemInfo'];
							$frameItemChildArray['rental_equipment_id'] 	= $frameEditDetails['rental_equipment_id'];
							$frameItemChildArray['rentalEquipmentInfo'] 	= $frameEditDetails['rentalEquipmentInfo'];
							$itemListArray[] 								= $frameItemChildArray;
						}else if($getItemList[0]['copy_from_type'] !=''){ 
							// RENTAL QUOTE OR RENTAL ORDER FOR INSPECTION OUT 
							// RENTAL RETURN FOR INSPECTION IN
							$refCopiedItemInfo = $this->referenceTableItemInformation($getItemList);
						//	printr($refCopiedItemInfo); 
							
							$refCopiedItemInfo[0]['is_utilized'] = $isUtilized;
							$itemListArray 	 					 =  array_merge($itemListArray,$refCopiedItemInfo);
						}
						
						// APPEND PARENT DATA FROM FIRST RECORD
						if($i == 0){
							unset($frameParentDetails['rentalEquipmentInfo']);
							unset($frameParentDetails['rentalItemInfo']);
							$parentEditRecord = $frameParentDetails;
						}
						$i++;
					}
					
					$data 				  = array();
					$data				  = $parentEditRecord;
					$data['itemListArray'] 	= $itemListArray;
					
					$outputData['status']   = "SUCCESS";
					$outputData['results']  = $data;
			
			}else {
				$outputData['message']      = lang('GLB_007'); // INVALID PARAMETERS
			}
		}
		
		$this->output->sendResponse($outputData);
	}
	
	
	/**
	 * @METHOD NAME 	: referenceTableItemInformation()
	 *
	 * @DESC 			: - 
	 * @RETURN VALUE 	: $outputdata array
	 * @PARAMETER 		: -
	 * @SERVICE      	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function referenceTableItemInformation($getItemList)
	{
		$copyFromType 		= $getItemList[0]['copy_from_type'];
		$copyFromId 		= $getItemList[0]['copy_from_id'];
		$refItemListDetails = $this->nativeModel->refEditItemList($copyFromId,$copyFromType);
		$refFramedItemInfo 	= $this->refFrameItemList($refItemListDetails);
		return $refFramedItemInfo;
	}
	
	
	/**
	* @METHOD NAME 	: refFrameItemList()
	*
	* @DESC 		: -
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function refFrameItemList($itemList, $type = null)
    {
		// BIND THE LIST SUB ARRAY 
		if(count($itemList)>0){
			
			$getAllDetails = array();
			$getInfoData   = array(	
									'getTaxList' 	 		=> '',
									'getUomList'	 		=> '',											
									'getHsnList'			=> '',
									'getDistributionRulesList' => ''
									);
										
			$getAllDetails	= getAutoSuggestionListHelper($getInfoData,1);
			
			foreach($itemList as $key => $value){
				
					$statusInfoDetails	= array();
					$getInfoData		= array(	
											'getRentalItemList' 	 => $value['rental_item_id'],
											'getRentalEquipmentList' => $value['rental_equipment_id'],
										  );
					
					// GET THE WORKLOG DETAILS IN THE SCREEN RENTAL INVOICE
					if(isset($value['rental_worklog_id'])){
						$getInfoData['getRentalWorklogList'] =  $value['rental_worklog_id'];
					}
					
					$statusInfoDetails	= getAutoSuggestionListHelper($getInfoData);
					
					// FIND THE PARTICULAR ARRAY INDEX 
					$findTaxId			= array_search($value['tax_id'], array_column($getAllDetails['taxInfo'], 'id'));	
					$findUomId			= array_search($value['uom_id'], array_column($getAllDetails['uomInfo'], 'id'));	
					$findHsnId			= array_search($value['hsn_id'], array_column($getAllDetails['hsnInfo'], 'id'));	
				
					$statusInfoDetails['taxInfo'][0] 		= $getAllDetails['taxInfo'][$findTaxId];
					$statusInfoDetails['uomInfo'][0] 		= $getAllDetails['uomInfo'][$findUomId];
					$statusInfoDetails['hsnInfo'][0] 		= $getAllDetails['hsnInfo'][$findHsnId];
					
					
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
	 * @METHOD NAME 	: checkCopyInspectionTemplateStatus()
	 *
	 * @DESC 			: TO CHECK THE "inspection_overall_status" IS 1 TO PROCEED
	 * @RETURN VALUE 	: -
	 * @PARAMETER 		: -
	 * @SERVICE      	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function checkCopyInspectionTemplateStatus($copiedIds)
	{
		$chkInspectionTemplateOverallStatusFlag 			 	= $this->nativeModel->checkCopyInspectionTemplateStatus($copiedIds);
		return $chkInspectionTemplateOverallStatusFlag;
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
		$modelOutput			= $this->nativeModel->editRentalInspectionTransactions($id);
		$itemListDetails		= $this->nativeModel->editItemList($id);


		if (count($modelOutput) > 0) {
			
			$modelOutput 	 = $this->frameRentalInspectionTransactionsEditDetails($modelOutput[0]);

	
			$itemList = array();
			$templateDetailsArr = json_decode($itemListDetails[0]['template_details']);
			// echo '<pre>';
			// print_r($templateDetailsArr);
			// exit;

			// echo '<pre>';
			foreach($templateDetailsArr as $itemValueArr){

				foreach($itemValueArr as $itemValue){
					$itemList[] = $itemValue;
				}

			}

			// print_r($modelOutput);
			// exit;
			// $itemList		 = $this->refFrameItemList($itemListDetails);
			// echo '<pre>';
			// print_r($itemList);exit;

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
				$outputData = generateRentalInspectionTrasactionInvoice($invoiceProcessData);

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
	

}
/********************************END OF FUNCTIONALITY ************************************************************/