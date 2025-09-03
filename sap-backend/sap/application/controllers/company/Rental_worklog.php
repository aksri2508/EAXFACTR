<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Rental_worklog.php
* @Class  			 : Rental_worklog
* Model Name         : Rental_worklog
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 14 MAY 2020
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : -
* Features           : 
*/

class Rental_worklog extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->config->load('table_config/tbl_rental_worklog.php');
		$this->config->load('table_config/tbl_rental_worklog_items.php');
		$this->config->load('table_config/tbl_rental_worklog_items_type_2.php');
		$this->load->model('company/rental_worklog_model', 'nativeModel');
		$this->MsgTitle = "rental_worklog";
	}


	/**
	 * @METHOD NAME 	: saveRentalWorklog()
	 *
	 * @DESC 			: TO SAVE THE RENTAL WORKLOG. 
	 * @RETURN VALUE 	: $outputdata array
	 * @PARAMETER 		: -
	 * @SERVICE      	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function saveRentalWorklog()
	{
		// Params from http request
		$this->checkRequestMethod("post"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$getData 		       = $this->currentRequestData;

		$modelOutput 	   = $this->nativeModel->saveRentalWorklog($getData);
		// print_r($modelOutput['flag']);exit;

		if (1 == $modelOutput['flag']) {
			$outputData['sId']      	= $modelOutput['sId'];
			$outputData['status']       = "SUCCESS";
			$outputData['message']      = lang('MSG_303');  // Successfully Inserted
		} else if (2 == $modelOutput['flag']) {
			$outputData['message']      = lang('GLB_009');  // Unable to save the record
		} else if (4 == $modelOutput['flag']) {
			$outputData['message'] = lang('MSG_304');   // Record Already Exists
		} else if (5 == $modelOutput['flag']) {
			$outputData['message'] = lang('MSG_286'); 	// Fails due to next number limit.
		} else if (6 == $modelOutput['flag']) {
			$outputData['message'] = lang('MSG_287'); 	// Document Num Exist - Manual Type.
		} else if (7 == $modelOutput['flag']) {
			$outputData['message'] = lang('MSG_291'); 	// Issue with document number.
		} else if (8 == $modelOutput['flag']) {
			$outputData['message'] = lang('MSG_322'); 	// Issue with worklog items date constraints1.
		} else if (9 == $modelOutput['flag']) {
			$outputData['message'] = lang('MSG_323'); 	// Issue with worklog items date constraints2.
		}

		$this->output->sendResponse($outputData);
	}


	/**
	 * @METHOD NAME 	: updateRentalWorklog()
	 *
	 * @DESC 			: TO UPDATE THE RENTAL WORKLOG.
	 * @RETURN VALUE 	: $outputdata array
	 * @PARAMETER 		: -
	 * @SERVICE      	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function updateRentalWorklog()
	{
		$this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$modelOutput		   = $this->nativeModel->updateRentalWorklog($this->currentRequestData);

		if (1 == $modelOutput['flag']) {
			$outputData['status']       = "SUCCESS";
			$outputData['message']      = lang('MSG_305'); //'Successfully updated
		} else if (2 == $modelOutput['flag']) {
			$outputData['message']      = lang('GLB_010'); // Unable to update the record
		} else if (4 == $modelOutput['flag'] || 3 == $modelOutput['flag']) {
			$outputData['message']      = lang('MSG_304'); // Record Already Exists
		}
		$this->output->sendResponse($outputData);
	}


	/**
	 * @METHOD NAME 	: editRentalWorklog()
	 *
	 * @DESC 			: TO EDIT THE RENTAL WORKLOG.
	 * @RETURN VALUE 	: $outputdata array
	 * @PARAMETER 		: -
	 * @SERVICE      	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function editRentalWorklog($id = '')
	{
		$outputData['status']  = "FAILURE";

		if (empty($id)) {
			$id  = $this->currentRequestData['id'];
		} else {
			$this->checkRequestMethod("put"); 	// CHECK THE REQUEST METHOD
		}

		// PARAMS FROM HTTP REQUEST
		if (!empty($id) && is_numeric($id)) {

			$modelOutput 				= $this->nativeModel->editRentalWorklog($id);
			$getItemList 			 	= $this->nativeModel->editItemList($id);

			if (count($modelOutput) > 0) {

				$frameEditDetails = $this->frameRentalWorklogEditDetails($modelOutput[0]);
				$data 				  = array();
				$data				  = $frameEditDetails;

				// BIND THE LIST SUB ARRAY 
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
	 * @METHOD NAME 	: frameRentalWorklogEditDetails()
	 *
	 * @DESC 			: -
	 * @RETURN VALUE 	: $outputdata array
	 * @PARAMETER 		: -
	 * @SERVICE      	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function frameRentalWorklogEditDetails($modelOutput)
	{
		$statusInfoDetails	= array();

		$getInfoData = array(
			'getBusinessPartnerList~customerBpInfo'					 => $modelOutput['customer_bp_id'],
			'getBusinessPartnerContactsList~customerBpContactsInfo'  => $modelOutput['customer_bp_contacts_id'],
			'getCurrencyList' 						 				 => $modelOutput['currency_id'],
			'getEmployeeList~employeeInfo'			 				 => $modelOutput['emp_id'],
			'getRentalTransStatusList~statusInfo'					 => $modelOutput['status'],
			'getCreatedByDetails~createdByInfo'	 					 => $modelOutput['created_by'],
			'getBranchList~branchInfo'	 							 => $this->currentbranchId,
			'getDocumentNumberingList~documentNumberingInfo'  		 => $modelOutput['document_numbering_id'],
			'getRentalItemList~rentalItemInfo'	 					 => $modelOutput['rental_item_id'],
			'getRentalEquipmentList~rentalEquipmentInfo'	  		 => $modelOutput['rental_equipment_id'],
		);
		$statusInfoDetails	= getAutoSuggestionListHelper($getInfoData);

		// DISTRIBUTION DETAILS - All.
		$getInfoData   = array(
			'getDistributionRulesList' => ''
		);
		$getAllDetails	= getAutoSuggestionListHelper($getInfoData, 1);

		// DISTRIBUTION DETAILS 
		$distributionRulesDetails	= array();
		$distributionRulesId  		= $modelOutput['distribution_rules_id'];
		$distributionRulesArray  	= explode(",", $distributionRulesId);

		if (count($distributionRulesArray) > 0) {
			foreach ($distributionRulesArray as $distributionKey => $distributionValue) {
				if (!empty($distributionValue)) {

					// NEW CODE
					$findDistributionRulesId = array_search($distributionValue, array_column($getAllDetails['distributionRulesInfo'], 'id'));
					$distributionStatusInfoDetails	= $getAllDetails['distributionRulesInfo'][$findDistributionRulesId];
					if (is_array($distributionStatusInfoDetails)) {
						$distributionRulesDetails[$distributionKey]	= $distributionStatusInfoDetails;
					}
				}
			}
		}
		$statusInfoDetails['distributionRulesInfo'] 		= $distributionRulesDetails;
		$result  			= array_merge($modelOutput, $statusInfoDetails);
		return $result;
	}


	/**
	 * @METHOD NAME 	: frameItemList()
	 *
	 * @DESC 			: -
	 * @RETURN VALUE 	: $outputdata array
	 * @PARAMETER 		: -
	 * @SERVICE      	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function frameItemList($itemList)
	{
		if($this->rentalWorklogSheetType == 1)
		{
			return $this->frameItemListSheetType1($itemList);
		}else if ($this->rentalWorklogSheetType == 2)
		{
			return $this->frameItemListSheetType2($itemList);
		}
	}
	
	
	/**
	 * @METHOD NAME 	: frameItemListSheetType1()
	 *
	 * @DESC 			: -
	 * @RETURN VALUE 	: $outputdata array
	 * @PARAMETER 		: -
	 * @SERVICE      	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function frameItemListSheetType1($itemList)
	{	
		// BIND THE LIST SUB ARRAY 
		if (count($itemList) > 0) {

			$getAllDetails = array();
			$getInfoData   = array(
				'getWorklogItemTypeList' 	 => '',
				'getEmployeeList' 	 => '',
			);

			$getAllDetails	= getAutoSuggestionListHelper($getInfoData, 1);
			// print_r($getAllDetails);exit;


			foreach ($itemList as $key => $value) {
				$statusInfoDetails	= array();
				$getInfoData		= array(
					'getWorklogItemTypeList' 	 => $value['worklog_item_type_id'],
					'getEmployeeList'		 	 => $value['emp_id'],

				);

				$statusInfoDetails	= getAutoSuggestionListHelper($getInfoData);

				// FIND THE PARTICULAR ARRAY INDEX. 
				$findWorkLogTypeId	= array_search($value['worklog_item_type_id'], array_column($getAllDetails['itemWorklogTypeInfo'], 'id'));

				// FIND THE PARTICULAR ARRAY INDEX. 
				$findEmployeeId	= array_search($value['emp_id'], array_column($getAllDetails['employeeInfo'], 'id'));

				$statusInfoDetails['worklogItemTypeId'][0] 		= $getAllDetails['itemWorklogTypeInfo'][$findWorkLogTypeId];
				$statusInfoDetails['employeeInfo'][0] 			= $getAllDetails['employeeInfo'][$findEmployeeId];

				// Re-Assign Array 
				$itemList[$key] = array_merge($value, $statusInfoDetails);
			}
		}
		return $itemList;
	}
	
	
	/**
	 * @METHOD NAME 	: frameItemListSheetType2()
	 *
	 * @DESC 			: -
	 * @RETURN VALUE 	: $outputdata array
	 * @PARAMETER 		: -
	 * @SERVICE      	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function frameItemListSheetType2($itemList)
	{	
		return $itemList;
	}
	

	/**
	 * @METHOD NAME 	: getRentalWorklogList()
	 *
	 * @DESC 			: TO GET THE RENTAL WORKLOG LIST.  
	 * @RETURN VALUE 	: $outputdata array
	 * @PARAMETER 		: -
	 * @SERVICE      	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function getRentalWorklogList()
	{
		$this->checkRequestMethod("put"); // Check the Request Method
		$modelOutput           = $this->nativeModel->getRentalWorklogList($this->currentRequestData);

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
		$passData['tableName']	= RENTAL_WORKLOG;
		$analyticsData 			= generateTransactionAnalytics($passData);
		$outputData['status']  = "SUCCESS";
		$outputData['results'] = $analyticsData;
		$this->output->sendResponse($outputData);
	}


	/**
	 * @METHOD NAME 	: downloadExcel()
	 *
	 * @DESC 			: TO DOWNLOAD THE EXCEL FORMAT.
	 * @RETURN VALUE	: $outputdata array
	 * @PARAMETER 		: -
	 * @SERVICE      	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function downloadExcel()
	{
		$modelOutput	= $this->nativeModel->getRentalWorklogList($this->currentRequestData, 1);
		$resultsData 	= $modelOutput['searchResults'];
		// print_r($resultsData[0]);// exit;
		$fileName		= $this->config->item('RENTAL_WORKLOG')['excel_file_name'];

		$outputData 	= processExcelData($resultsData, $fileName, $this->config->item('RENTAL_WORKLOG')['columns_list']);
		$this->output->sendResponse($outputData);
	}


	/**
	 * @METHOD NAME 	: uploadWorklogTemplate()
	 *
	 * @DESC 			: TO UPLOAD WORKLOG TEMPLATE.
	 * @RETURN VALUE	: $outputdata array
	 * @PARAMETER 		: -
	 * @SERVICE      	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function uploadWorklogTemplate()
	{
		$this->checkRequestMethod("post"); // Check the Request Method.
		
		// Params from http request.
		$outputData['status']  = "FAILURE";

		if(isset($_FILES['file'])){

			$fileDetails = $_FILES['file'];
            
			if($fileDetails['type'] != 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'){
				$outputData['message'] = lang('MSG_325'); // Issue with Excel format issue.
			}
			else{

				$fileName = $fileDetails['name'];
				$fileWithPathName = $fileDetails['tmp_name']."/".$fileName;
				$newFileName           = date('YmdHis') . "_" . $fileName;
				$newFileName            = preg_replace('/\s+/', '_', $newFileName);


				$config['upload_path']    = WORKLOG_TEMPLATE_UPLOAD_PATH;
				$config['allowed_types']  = WORKLOG_TEMPLATE_TYPES;
				$config['encrypt_name']   = false;
				$config['file_name']      = $newFileName;
				// $config['remove_spaces']  = true;
				$this->load->library('upload', $config);
				$profilePicture = $this->upload->do_upload('file', $config);

				if ($profilePicture != false) {
					// $outputData['status']   = "SUCCESS";
					$fullFilePath  = WORKLOG_TEMPLATE_UPLOAD_PATH . '' . $newFileName;
					// $uniquefileName = $newFileName;
					$resultData 	= readExcelData($fullFilePath);

					$outputData['status']       = "SUCCESS";

					// Fetching and assing each excel row and asiging API keys.
					foreach($resultData as $rkey => $rdata){
						 if($rkey != 0 && $rdata[0] != ""){
							$excelresult = array();
							$excelresult['type'] = $rdata[0];

							$newDate1 = date("d-M-Y G:i:s", strtotime(str_replace("/","-",$rdata[1])));
							$excelresult['startDateTime'] = $newDate1;

							$newDate2 = date("d-M-Y G:i:s", strtotime(str_replace("/","-",$rdata[2])));
							$excelresult['endDateTime'] = $newDate2;
	
							$resultDataSet[] = $excelresult;
						 }
					}
					$outputData['data'] = $resultDataSet;

					unlink($fullFilePath);

				} else {
					$outputData['message']       = $this->upload->display_errors('', '');
				}
			}
		}
		else
		{
			$outputData['message'] = lang('MSG_324'); 	// Issue with Excel file-Key issue.
		}
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
		$modelOutput			= $this->nativeModel->editRentalWorklog($id);
	
		$itemListDetails		= $this->nativeModel->editItemList($id);
		
		if (count($modelOutput) > 0) {
			
			$modelOutput 	 = $this->frameRentalWorklogEditDetails($modelOutput[0]);
			$itemList		 = $this->frameItemList($itemListDetails);
			
			//printr($modelOutput);exit;
			

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
				
				//printr($invoiceProcessData);exit;
				
                // Generating  Invoice
				$outputData = generateRentalWorklogInvoice($invoiceProcessData,$this->rentalWorklogSheetType);

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
		    // print_r($fileDetails);exit;
		
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
