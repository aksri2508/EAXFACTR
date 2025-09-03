<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Common_services.php
* @Class  			 : Common_services
* Model Name         : Common_services
* Description        :
* Module             : common
* Actors 	         : -
* @author 			 : -
* @CreatedDate 	     : -
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : Added comment blocks and header details
* Features           : 
*/
class Common_services extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
		$this->load->model('common/Common_services_model', 'nativeModel');	
    }
	
	
	/**
	* @METHOD NAME 	: getAutoSuggestionList()
	*
	* @DESC 		: TO GET THE AUTO-SUGGESTION LIST 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getAutoSuggestionList($fieldKey){
		$this->checkRequestMethod("post");
		$getServicesList  	= $this->currentRequestData;
		$time_start = microtime(true); 
		
		// {"fieldValue":"","allData":1}
		
		$result   = array();
		// $fieldValue 				 = $getServicesList['fieldValue'];
		$getServicesList['category'] = 1;
		
		$statusInfoDetails	= array();
		$getInfoData = array(	
								$fieldKey => $getServicesList
							);
		
		
		$result		 = getAutoSuggestionListHelper($getInfoData,0,1);
		
		
		$getKey 	 = array_keys($result);
		$time_end 	 = microtime(true);
		$execution_time = number_format(($time_end - $time_start), 2);
		
		// OUTPUT DATA
		$outputData['results']  = $result[$getKey[0]];
		$outputData['status']   = "SUCCESS";
		$outputData['executionTime']   = $execution_time;
		//echo "Ex".$execution_time;
		$this->output->sendResponse($outputData);

		// $result  			= array_merge($modelOutput,$statusInfoDetails);
		// return $statusInfoDetails;
	}
	
		
	/**
	* @METHOD NAME 	: changePassword()
	*
	* @DESC         : TO CHANGE THE PASSWORD DETAILS
	* @RETURN VALUE : $outputData array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function changePassword()
    {
		
		
        $this->checkRequestMethod("post"); // Check the Request Method
		$outputData['status']  = "FAILURE";
        $modelOutput           = $this->commonModel->changePassword($this->currentRequestData);

        if ($modelOutput['flag'] == 1) {
            $outputData['status']        = "SUCCESS";
            $outputData['message']       = lang('MSG_34'); // Password Changed Successfully
        } else if ($modelOutput['flag'] == 2) {
            $outputData['message']       = lang('MSG_36'); // Old Password does not match
        }
        $this->output->sendResponse($outputData);
    }
	
	
	/**
	* @METHOD NAME 	: getMyProfileInformation()
	*
	* @DESC 		: TO GET THE MYPROFILE INFORMATION OF THE USER
	* @RETURN VALUE : $outputData array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getMyProfileInformation(){
		$this->checkRequestMethod("GET"); // Check the Request Method
		$outputData['status']  = "FAILURE";

		$modelOutput           = $this->commonModel->getProfileInformation($this->currentUserId);
		 if ($modelOutput['flag'] == 1) {
            $outputData['status']        = "SUCCESS";
			$outputData['results']       = $modelOutput['profileInfo'];
        } else if ($modelOutput['flag'] == 2) {
            $outputData['message']       = lang('MSG_29'); // Profile Information Not Found. Please, contact Administrator
        }
		$this->output->sendResponse($outputData);
	}
	

	/**
	* @METHOD NAME 	: updateMyProfile()
	*
	* @DESC 		: COMMON INTERFACE TO UPDATE THE MY-PROFILE FOR ALL USERS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function updateMyProfile() {
		$this->checkRequestMethod("PUT"); // Check the Request Method
		$outputData['status']   = "FAILURE";
	
		$passData		= $this->currentRequestData;
		$passData['id'] = $this->currentUserId;

        $modelOutput    = $this->commonModel->updateprofile($passData);
        if (1 == $modelOutput['flag']) {
            $outputData['status']       = "SUCCESS";
            $outputData['message']      = lang('MSG_81'); // Successfully Updated
        } else if (2 == $modelOutput['flag']) {
            $outputData['message']      = lang('GLB_010'); // Unable to update the record. Please try again later.
        } else if (3 == $modelOutput['flag']) {
            $outputData['message']      = lang('GLB_013'); // email address Already Exists
        }
    	$this->output->sendResponse($outputData);
	}
	
	
	/**
	* @METHOD NAME 	: uploadProfilePhoto()
	*
	* @DESC 		: TO UPLOAD THE PROFILE PHOTO
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function uploadProfilePhoto() {
		 $this->checkRequestMethod("post"); // Check the Request Method
		 
		 $outputData            = array();
		 $outputData['status']  = "FAILURE";	 
		 $tempFilePath	        = $_FILES['file']['tmp_name'];
		 $newFileName           = date('YmdHis')."_".$_FILES['file']['name'];
		 $newFileName           = preg_replace('/\s+/', '_', $newFileName);	
		 
	 
		 $config['upload_path']    = EMPLOYEE_PHOTO_UPLOAD_PATH;
		 $config['allowed_types']  = EMPLOYEE_PHOTO_TYPES;
		 $config['max_size']       = EMPLOYEE_PHOTO_SIZE;
		 $config['max_width']      = EMPLOYEE_PHOTO_WIDTH;
		 $config['encrypt_name']   = false;
		 $config['file_name']      = $newFileName;
		 //$config['remove_spaces']  = true;
		 
		 
		
		 $this->load->library('upload',$config);

		 $profilePicture = $this->upload->do_upload('file', $config);

		if($profilePicture != false) {
			 $outputData['status']   = "SUCCESS";
			 $outputData['results']['fullUrl']  = EMPLOYEE_PHOTO_ACCESS_URL.''.$newFileName;
			 $outputData['results']['fileName'] = $newFileName;
		}
		else {
			$outputData['message']       = $this->upload->display_errors('','');
		}	
		$this->output->sendResponse($outputData);
   }

	
	/**
	* @METHOD NAME 	: getDocumentNumber()
	*
	* @DESC 		: TO GET THE DOCUMENT NUMBER
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getDocumentNumber()
    {
        $this->checkRequestMethod("put"); 	// CHECK THE REQUEST METHOD
		$outputData['status']  = "FAILURE";
        
         // PARAMS FROM HTTP REQUEST
        if (!empty($this->currentRequestData['documentTypeId']) && is_numeric($this->currentRequestData['documentTypeId'])) {

			$modelOutput = $this->nativeModel->getDocumentNumber($this->currentRequestData);
		
			$outputData['status']       = "SUCCESS";
			$outputData['results']      = $modelOutput;
	 
        } else { 
            $outputData['message']      = lang('GLB_007'); // INVALID PARAMETERS
        }	
        $this->output->sendResponse($outputData);
    }
	
	/**
	* @METHOD NAME 	: getMasterDocumentNumber()
	*
	* @DESC 		: TO GET THE DOCUMENT NUMBER FORM MASTER CONFIG FORMAT.
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getMasterDocumentNumbering()
    {
	
        $this->checkRequestMethod("put"); 	// CHECK THE REQUEST METHOD
		$outputData['status']  = "FAILURE";
        
         // PARAMS FROM HTTP REQUEST
        if (!empty($this->currentRequestData['documentTypeId']) && is_numeric($this->currentRequestData['documentTypeId'])) {

			$modelOutput = $this->nativeModel->getMasterDocumentNumbering($this->currentRequestData);
		
		    $resultArr = array();
			foreach($modelOutput as $key => $value){
				$rowArr = array();
				$rowArr['id'] = $value['id'];
				$rowArr['document_numbering_name'] = $value['document_numbering_name'];
				$rowArr['document_numbering_type'] = $value['document_numbering_type'];
				//$rowArr['document_type_id'] = $value['document_type_id'];
				$prefix = $value['prefix'];
				$suffix = $value['suffix'];	
                $digits = $value['digits'];				
				$nextNumber = str_pad($value['next_number'], $digits, '0', STR_PAD_LEFT);
		      
				$rowArr['next_number'] = $prefix."".$nextNumber."".$suffix;
				$rowArr['is_system_config'] = $value['is_system_config'];
				$resultArr[] = $rowArr;
			}

			$outputData['status']       = "SUCCESS";
			$outputData['results']      = $resultArr;
	 
        } else { 
            $outputData['message']  = lang('GLB_007'); // INVALID PARAMETERS
        }	
        $this->output->sendResponse($outputData);
    }
    
	
	/**
	* @METHOD NAME 	: getDpInvoiceDocumentNumberForArInvoice()
	*
	* @DESC 		: TO GET THE DP INVOICE DOCUMENT NUMBER
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getDpInvoiceDocumentNumberForArInvoice()
    {
        $this->checkRequestMethod("put"); 	// CHECK THE REQUEST METHOD
		$outputData['status']  = "FAILURE";
		$modelOutput 			= $this->nativeModel->getDpInvoiceDocumentNumberForArInvoice($this->currentRequestData);
		$outputData['status']   = "SUCCESS";
		$outputData['results']  = $modelOutput;
        $this->output->sendResponse($outputData);
    }
	
	
	/**
	* @METHOD NAME 	: getDpInvoiceDocumentNumber()
	*
	* @DESC 		: TO GET THE DP INVOICE DOCUMENT NUMBER
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getDpInvoiceDocumentNumber()
    {
        $this->checkRequestMethod("put"); 	// CHECK THE REQUEST METHOD
		$outputData['status']  = "FAILURE";
		$modelOutput 			= $this->nativeModel->getDpInvoiceDocumentNumber($this->currentRequestData);
		$outputData['status']   = "SUCCESS";
		$outputData['results']  = $modelOutput;
        $this->output->sendResponse($outputData);
    }
	
	
	/**
	* @METHOD NAME 	: getAlternativeItemsByItemId()
	*
	* @DESC 		: TO GET THE ALTERNATIVE ITEMS BY ITEM ID 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getAlternativeItemsByItemId()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
        $modelOutput          		= $this->nativeModel->getAlternativeItemsByItemId($this->currentRequestData);
		
		// FRAME OUTPUT
        $outputData['results']      = $modelOutput;
        $outputData['status']       = "SUCCESS";
		
        $this->output->sendResponse($outputData);
    }
	
	
	/**
	* @METHOD NAME 	: downloadSecFile()
	*
	* @DESC 		: TO DOWNLOAD THE SECURITY FILE
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function downloadSecFile($urlParams)
    {
		$this->load->helper('MY_download_helper');
		$uriSegments = $this->uri->segment_array();
				
		$fileType = $uriSegments[4];
		$fileName = $uriSegments[5];
		if($fileType==1){ // EXCEL DOWNLOAD 
			$filePath = TEMP_EXCEL_SAVE_PATH.$fileName;
		}
		
		force_download($fileName,$filePath,1); // 1 -> DELETE
    }
	
	
	/**
	* @METHOD NAME 	: downloadPrivatefile()
	*
	* @DESC 		: TO DOWNLOAD THE PRIVATE FILE 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function downloadPrivatefile($urlParams)
    {
		$this->load->helper('MY_download_helper');
		$uriSegments = $this->uri->segment_array();
				
		$fileType = $uriSegments[4];
		$fileName = $uriSegments[5];
		if($fileType==1){ // PRIVATE UPLOADED PATH 
			$filePath = ATTACHMENTS_UPLOAD_PATH.$fileName;
		}
		//echo $filePath; //exit;
		force_download($fileName,$filePath,0); // 0 -> Not delete
    }
	
	
	/**
	* @METHOD NAME 	: getApiTrackerList()
	*
	* @DESC 		: TO GET THE API TRACKER LIST 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getApiTrackerList()
    {
		$this->checkRequestMethod("put"); // Check the Request Method
        $modelOutput           = $this->nativeModel->getApiTrackerList($this->currentRequestData);
		
		// FRAME OUTPUT
        $outputData['results']      = $modelOutput;
        $outputData['status']       = "SUCCESS";
		
        $this->output->sendResponse($outputData);
    }
	
	
	/**
	* @METHOD NAME 	: getDocumentNumberingScreens()
	*
	* @DESC 		: TO GET THE DOCUMENT NUMBERING SCREENS 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getDocumentNumberingScreens()
    {
		$this->checkRequestMethod("get"); // Check the Request Method
		
		$passSearchData['enable_document_numbering'] = 1; 
		$screenDetails   							 = $this->commonModel->getModuleScreenMappingDetails($passSearchData);
		
		// FRAME OUTPUT
        $outputData['results']      = $screenDetails;
        $outputData['status']       = "SUCCESS";
		
        $this->output->sendResponse($outputData);
    }
	
	
	/**
	* @METHOD NAME 	: getNotificationScreens()
	*
	* @DESC 		: TO GET THE NOTIFICATION SCREENS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getNotificationScreens()
    {
		$this->checkRequestMethod("get"); // Check the Request Method
		
		$passSearchData['enable_notification'] = 1; 
		$screenDetails   					   = $this->commonModel->getModuleScreenMappingDetails($passSearchData);
		
		// FRAME OUTPUT
        $outputData['results']      = $screenDetails;
        $outputData['status']       = "SUCCESS";
		
        $this->output->sendResponse($outputData);
		
    }
	

	/**
	* @METHOD NAME 	: getApprovalProcessScreens()
	*
	* @DESC 		: TO GET THE APPROVAL PROCESS SCREEN NAMES  
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getApprovalProcessScreens()
    {
		$this->checkRequestMethod("get"); // Check the Request Method
		
		$passSearchData['enable_approval_process'] = 1; 
		$screenDetails   					   	   = $this->commonModel->getModuleScreenMappingDetails($passSearchData);
		
		// FRAME OUTPUT
        $outputData['results']      = $screenDetails;
        $outputData['status']       = "SUCCESS";
		
        $this->output->sendResponse($outputData);
    }
	
	
/////////////////////////////////// MANAGE ATTACHMENTS ////////////////////////////////////////////////
	/**
	* @METHOD NAME 	: uploadAttachment()
	*
	* @DESC 		: TO UPLOAD THE ATTACHMENTS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function uploadAttachment() {
		 $this->checkRequestMethod("post"); // Check the Request Method
		
		 $outputData['status']  = "FAILURE";	 
		 $tempFilePath	        = $_FILES['file']['tmp_name'];
		 $newFileName           = date('YmdHis')."_".$_FILES['file']['name'];
		 $newFileName           = preg_replace('/\s+/', '_', $newFileName);		 
		 $outputData            = array();
	 
		 $config['upload_path']    = ATTACHMENTS_UPLOAD_PATH;
		 $config['allowed_types']  = ATTACHMENTS_UPLOAD_TYPES;
		 $config['max_size']       = ATTACHMENTS_UPLOAD_SIZE;
		 $config['encrypt_name']   = false;
		 $config['file_name']      = $newFileName;
		 //$config['remove_spaces']  = true;
		
		 $this->load->library('upload',$config);

		 $profilePicture = $this->upload->do_upload('file', $config);

		if($profilePicture != false) {
			 $outputData['status']   = "SUCCESS";
			 $outputData['results']['fileName'] = $newFileName;
		}
		else {
			$outputData['message']       = $this->upload->display_errors('','');
		}	
		$this->output->sendResponse($outputData);
   }
   
	/**
	* @METHOD NAME 	: saveAttachment()
	*
	* @DESC 		: TO SAVE THE ATTACHMENT
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function saveAttachment()
    {
        // Params from http request
        $this->checkRequestMethod("post"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$getData 		       = $this->currentRequestData;

			$modelOutput 	   = $this->nativeModel->saveAttachment($getData);
			
			if (1 == $modelOutput['flag']) {
				$outputData['status']       = "SUCCESS";
				$outputData['message']      = lang('MSG_133'); // Successfully Inserted
			} else if (2 == $modelOutput['flag']) {
				$outputData['message']      = lang('MSG_134'); // Record Already Exists
			}
		
        $this->output->sendResponse($outputData);
    }
	
	
	/**
	* @METHOD NAME 	: getAttachmentList()
	*
	* @DESC 		: TO GET THE ATTACHMENT LIST
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getAttachmentList()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
        $modelOutput           = $this->nativeModel->getAttachmentList($this->currentRequestData);
		
		foreach($modelOutput['searchResults'] as $key => $value){
			$modelOutput['searchResults'][$key]['downloadLink'] = frameSecuredUrl($value['file_name'],1);
		}
		
		// FRAME OUTPUT
        $outputData['results']      = $modelOutput;
        $outputData['status']       = "SUCCESS";
        $this->output->sendResponse($outputData);
    }
	
	
	/**
	* @METHOD NAME 	: deleteAttachment()
	*
	* @DESC 		: TO DELETE THE ATTACHMENT
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function deleteAttachment()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";
        
        if (!empty($this->currentRequestData['id']) && is_numeric($this->currentRequestData['id'])) {
            $modelOutput = $this->nativeModel->deleteAttachment($this->currentRequestData);
            if (1 == $modelOutput['flag'] ) {
                $outputData['status']       = "SUCCESS";
                $outputData['message']      =  lang('MSG_135'); //'Successfully Deleted
            } else if (2 == $modelOutput['flag'] ) {
                $outputData['message']      = lang('GLB_011'); // Unable to delete. Please try again later.
            }
        } else {
            $outputData['message']      = lang('GLB_007'); // Invalid Paremeters
        }
        $this->output->sendResponse($outputData);
    }
/////////////////////////////////// END OF  ATTACHMENTS //////////////////////////////////////	
/////////////////////////////////// OTHER FUNCTIONALITY //////////////////////////////////////	
	/**
	* @METHOD NAME 	: getItemStockList()
	*
	* @DESC 		: TO GET THE ITEM STOCK LIST
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getItemStockList()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
        $modelOutput           = $this->nativeModel->getItemStockList($this->currentRequestData);
		
		foreach($modelOutput as $modelKey => $modelValue){
			
			if($modelValue['availability']==null){
				$modelOutput[$modelKey]['availability'] =0;
			}
			
		}
		
		// FRAME OUTPUT
        $outputData['results']      = $modelOutput;
        $outputData['status']       = "SUCCESS";
        $this->output->sendResponse($outputData);
    }
	
	
	/**
	* @METHOD NAME 	: getItemTransactionDetails()
	*
	* @DESC 		: TO GET THE ITEM TRANSACTION DETAILS WHICH IS USED IN LAST SALES PRICE AND LAST PURCHASE PRICE 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getItemTransactionDetails()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
		$type				= $this->currentRequestData['type'];
		$modelOutput = array();
		if($type == 'PURCHASE'){
			$screenDetails		= $this->config->item('SCREEN_NAMES')['GRPO'];
			$modelOutput        = $this->nativeModel->getItemTransactionDetails($this->currentRequestData,$screenDetails);
		}else if($type == 'SALES'){
			$screenDetails		= $this->config->item('SCREEN_NAMES')['SALES_AR_INVOICE'];
			$arInvoiceDetails   = $this->nativeModel->getItemTransactionDetails($this->currentRequestData,$screenDetails);
			
			$screenDetails		= $this->config->item('SCREEN_NAMES')['SALES_AR_DP_INVOICE'];
			$arDpDetails   		= $this->nativeModel->getItemTransactionDetails($this->currentRequestData,$screenDetails);
			
			$mergeSalesArray	= array_merge($arInvoiceDetails,$arDpDetails);
			
			if(count($mergeSalesArray) > 0){
				array_multisort(array_column($mergeSalesArray, 'updated_on'), SORT_DESC, $mergeSalesArray);
				$modelOutput = array_slice($mergeSalesArray,0,50);
			}
		}	
		
		foreach($modelOutput as $modelKey => $value){
			$getInfoData		= array(
										'getItemList' 	 					 => $value['item_id'],
										'getCreatedByDetails~createdByInfo'	 => $value['created_by'],
										'getBranchList~branchInfo'	 		 => $value['branch_id'],
										);
			
			if($type == 'PURCHASE'){
				$getInfoData = array_merge( $getInfoData, array('getBusinessPartnerList~vendorBpInfo'	 => $value['vendor_bp_id']));
			}else if($type == 'SALES'){
				$getInfoData = array_merge( $getInfoData, array('getBusinessPartnerList~customerBpInfo'	 => $value['customer_bp_id']));
			}
			
			$statusInfoDetails	= getAutoSuggestionListHelper($getInfoData);
			
			if(isset($value['grpo_id'])){
				$modelOutput[$modelKey]['documentType'] = 'GRPO';
			}else if(isset($value['sales_ar_dp_invoice_id'])){
				$modelOutput[$modelKey]['documentType'] = 'SALES_AR_DP_INVOICE';
			}else if(isset($value['sales_ar_invoice_id'])){
				$modelOutput[$modelKey]['documentType'] = 'SALES_AR_INVOICE';
			}
			$modelOutput[$modelKey] = array_merge($modelOutput[$modelKey],$statusInfoDetails);
		}
		
		// FRAME OUTPUT
        $outputData['results']      = $modelOutput;
        $outputData['status']       = "SUCCESS";
        $this->output->sendResponse($outputData);
    }
	
	
	/**
	* @METHOD NAME 	: getItemStockDetailsByItemId()
	*
	* @DESC 		: TO GET THE ITEM STOCK DETAILS BY ITEM ID 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getItemStockDetailsByItemId()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
        $modelOutput           = $this->nativeModel->getItemStockDetailsByItemId($this->currentRequestData);
		
		$frameOutput = array();
		$i = 0;
		foreach($modelOutput as $modelKey => $modelValue){
			if($modelValue['availability']==null){
				$modelOutput[$modelKey]['availability'] =0;
			}
			
			// BIN AND WARE HOUSE INFO DETAILS 
			$statusInfoDetails	= array();
			$getInfoData		= array(
									'getBinList'	 	=> $modelValue['bin_id'],
									'getWarehouseList'	=> $modelValue['warehouse_id'],
									);
			$statusInfoDetails	= getAutoSuggestionListHelper($getInfoData);
			$result  			= array_merge($modelOutput[$modelKey],$statusInfoDetails);
			
			$frameOutput[$i] = $result;
			$i++;
		}
		
		// FRAME OUTPUT
        $outputData['results']      = $frameOutput;
        $outputData['status']       = "SUCCESS";
        $this->output->sendResponse($outputData);
    }
	
	

	/**
	* @METHOD NAME 	: getTransactionRecordDetails()
	*
	* @DESC 		: TO GET THE TRANSACTION RECORD DETAILS 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getTransactionRecordDetails()
    {
		
        $this->checkRequestMethod("put"); // Check the Request Method
		
		$outputData['status']  = "FAILURE";
		
		$transId = $this->currentRequestData['transId'];
		
		//echo "Trans id is ".$transId;
		
		//$getData 			   = "4~PURCHASE_REQUEST";
		$getData 			   = $transId;
        $modelOutput           = $this->nativeModel->getTransactionTrackingDetails($getData);
		
		//if(count($modelOutput) == 1){ // ONLY 1 RECORD MUST EXISTS 
		if(count($modelOutput) > 0){ // ONLY 1 RECORD MUST EXISTS 
			$getTransactionData  = json_decode($modelOutput[0]['transaction_details']);
			$getTransactionData  = json_decode(json_encode($getTransactionData),true);
			$transactionDetails = $this->getTransactionRecordInfo($getTransactionData['transactionInfo']);
			//echo "<pre>"; print_r($transactionDetails); echo "</pre>";
			//exit;
			
			$passTransaction['transactionInfo'] = $transactionDetails;
			$modelOutput[0]['transaction_details'] = $passTransaction;
			
			// FRAME OUTPUT
			$outputData['results']      = $modelOutput;
			$outputData['status']       = "SUCCESS";
		}else{
			 $outputData['message']     = lang('MSG_330'); // INVALID PARAMETERS
		}
        $this->output->sendResponse($outputData);
    }
	
	
	/**
	* @METHOD NAME 	: getTransactionRecordInfo()
	*
	* @DESC 		: TO GET THE TRANSACTION RECORD INFORMATION 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getTransactionRecordInfo($transactionDetails)
    {		
		foreach ($transactionDetails as $key => &$data) {
			
			if (isset($data['id'])) {
				
				$type = $data['type'];
				$getTransactionRecords = $this->nativeModel->getTransactionRecord($data['id'],constant($data['type']));
				
				$data['document_number'] =  "";
				$data['status_name']	 =  "";	
				if(count($getTransactionRecords) > 0){
					$data['document_number'] 	= $getTransactionRecords[0]['document_number'];
					$status						= isset($getTransactionRecords[0]['status']) ? $getTransactionRecords[0]['status'] : '';
					$data['posting_date'] 		= isset($getTransactionRecords[0]['posting_date']) ? $getTransactionRecords[0]['posting_date'] : '';
					$data['document_date'] 		= isset($getTransactionRecords[0]['document_date']) ? $getTransactionRecords[0]['document_date'] : '';
					$data['total_amount'] 		= isset($getTransactionRecords[0]['total_amount']) ? $getTransactionRecords[0]['total_amount'] : '';
					$data['total_before_discount'] = isset($getTransactionRecords[0]['total_before_discount']) ? $getTransactionRecords[0]['total_before_discount'] : '';
					
					$statusInfoDetails	= array();
					if($type == 'PURCHASE_REQUEST' || $type == 'PURCHASE_ORDER' || $type == 'GRPO'){
						$getInfoData['getPurchaseTransStatusList~statusInfo'] = $status;
					}else if ($type == 'INVENTORY_TRANSFER_REQUEST' || $type == 'INVENTORY_TRANSFER'){
						$getInfoData['getInventoryTransStatusList~statusInfo'] = $status;
					}else if ($type == 'SALES_QUOTE' || $type == 'SALES_ORDER' ||  
							  $type == 'SALES_DELIVERY' || $type == 'SALES_AR_INVOICE' 
							 || $type == 'SALES_AR_DP_INVOICE' || $type == 'SALES_AR_CREDIT_MEMO' 
							 || $type == 'SALES_RETURN'){
						$getInfoData['getSalesTransStatusList~statusInfo'] = $status;
					}else if ($type  == 'RENTAL_QUOTE' || $type == 'RENTAL_ORDER'
							|| $type == 'RENTAL_DELIVERY' || $type == 'RENTAL_RETURN' 
							|| $type == 'RENTAL_INVOICE'  || $type == 'RENTAL_INSPECTION_OUT' 
							|| $type == 'RENTAL_INSPECTION_IN' || $type == 'RENTAL_WORKLOG') {
						$getInfoData['getRentalTransStatusList~statusInfo'] = $status;
					}
					
					$statusInfoDetails	= getAutoSuggestionListHelper($getInfoData);					
					$data['status_name'] = $statusInfoDetails['statusInfo'][0]['name'];
					
					// CHECK THE ATTACHMENT EXISTS FOR THE DOCUMENT
					$passAttachmentParam['screenName']   = $type;
					$passAttachmentParam['referenceId']  = $data['id'];
					$attachmentData     = $this->nativeModel->getAttachmentList($passAttachmentParam);
		
					if(count($attachmentData['searchResults']) >0){ 
						$data['is_attachment']  = 1;
					}else{
						$data['is_attachment']  = 0;
					}
				}
			}

			if (isset($data['child']) && is_array($data['child'])) {
				$data['child'] = $this->getTransactionRecordInfo($data['child']);
			}
		}
		return $transactionDetails;
	}
	

	/**
	* @METHOD NAME 	: sendDocumentMail()
	*
	* @DESC 		: TO SEND THE EMAIL - GENERAL PURPOSE MAIL 
	*                 (Service exposed directly from here). 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function sendMail()
    {
		$this->checkRequestMethod("post"); // Check the Request Method
		$getData = $this->currentRequestData;

		if(isset($getData['mailContent']) &&
		   isset($getData['toEmailId']) &&
		   isset($getData['subject'])){
		
		   $mailHelperData = array(
			   "email_body" => "<br>".$getData['mailContent'],
			   "email_id" => $getData['toEmailId'],
			   "subject" => $getData['subject'],
			   "documentDetails" => array(),
			   "email_cc" => null,
			   "email_bcc" => null,
		   );

		   sendMail($mailHelperData);

			$outputData = [
				'status'       => 'SUCCESS',
				'message'      =>'Mail Sent.',
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
	* @METHOD NAME 	: updateLineItemConfiguationForUser()
	*
	* @DESC 		: TO UPDATE THE LINE ITEM CONFIGURATION FOR THE USER
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function updateLineItemConfiguationForUser()
    {
		$this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";
        
		$modelOutput = $this->nativeModel->updateLineItemConfiguationForUser($this->currentRequestData);
		if (1 == $modelOutput['flag']) {
			$outputData['status']       = "SUCCESS";
			$outputData['message']      = lang('MSG_272');  // 'Custom Fields Configured Successfully'
		}
        $this->output->sendResponse($outputData);
    }
	
		
	
	/**
	* @METHOD NAME 	: getItemPriceValue()
	*
	* @DESC 		: TO GET THE ITEM PRICE VALUE BASED UPON THE TYPE 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getItemPriceValue()
    {
		$this->checkRequestMethod("put"); // Check the Request Method
		$getPostData	= $this->currentRequestData;
		$typeArray		= array('SPB','TXN','MASTERITEM');
		
		
		$passResultInfo	= array();
		$systemType		= '';
		$modelOutput['discount_percentage'] = 0;
		$modelOutput['unit_price'] 			= 0;
		$modelOutput['type'] 				= $getPostData['type'];
		
		// PARAMS FROM HTTP REQUEST
        if (!empty($getPostData['type']) && in_array($getPostData['type'],$typeArray)) {

			$reqType = $getPostData['type'];
		
			if($reqType == 'SPB'){
				$passResultInfo = $this->nativeModel->getMasterItemPriceDetails($getPostData);
			} else if ($reqType == 'TXN'){
				
				$businessPartnerPriceListInfo  = array();
				$checkExistInSpBusinessPartner = array();
				
				// CHECK THE BUSINESS PARTNER ID EXISTS 
				if(isset($getPostData['businessPartnerId'])){
					$businessPartnerPriceListInfo = $this->nativeModel->getBusinessPartnerPriceListInfo($getPostData);
					if(count($businessPartnerPriceListInfo)>0){
						$getPostData['priceListId']	   = $businessPartnerPriceListInfo[0]['price_list_id'];
						$checkExistInSpBusinessPartner = $this->nativeModel->getSpBusinessPartnerDetails($getPostData);
					}
				}
				
			//	echo "CHK ARRAY";
				//printr($checkExistInSpBusinessPartner );
			
				if(count($checkExistInSpBusinessPartner) == 0 ) { 
					
					
					if(isset($getPostData['businessPartnerId']) && empty($getPostData['priceListId'])){
						// GET LAST PRICE LIST ID COULMN VALUE FROM MASTER ITEM COLUMN 
						$lastPriceListInfo = $this->nativeModel->getLastPriceIdByItem($getPostData);
						$getPostData['priceListId'] = $lastPriceListInfo[0]['last_price_list_id'];
						$passResultInfo 			= $this->nativeModel->getMasterItemPriceDetails($getPostData);	
					}else{
						$passResultInfo 			= $this->nativeModel->getMasterItemPriceDetails($getPostData);	
					}
					
					// 
					//echo "NOT EXISTS";
					//printr($passResultInfo);
					
					
				}else if(count($checkExistInSpBusinessPartner) == 1){
					$passResultInfo = $checkExistInSpBusinessPartner;
				}
				
				//exit;
				
			} else if ($reqType == 'MASTERITEM') {
				
				$passResultInfo = $this->nativeModel->getMasterItemPriceDetails($getPostData);
				
				if(count($passResultInfo) == 0){ //  Calculate the price list details 
						
						// GET THE CURRENT PASSED PRICE LIST INFORMATION USING "priceListId"
						$passData['id'] = $getPostData['priceListId'];
						$currentPriceListInfo  = $this->nativeModel->getMasterPriceListById($passData);
						
						//echo "1";
						//printr($currentPriceListInfo);
						
						// GET THE DEFAULT PRICE LIST FACTOR INFORMATION BY RECURSIVE FUNCTION 
						$passData['default_price_list_id'] = $getPostData['priceListId'];
						$defaultPriceListInfo 			   = $this->nativeModel->getDefaultPriceListInformation($passData,1);
						
						//echo "2";
						//printr($defaultPriceListInfo);
						
						// CHECK DEFAULT PRICE LIST INFO 
						if(count($defaultPriceListInfo) > 0){
							//echo "3";
							//printr($defaultPriceListInfo);
							$systemType = $defaultPriceListInfo[0]['system_type'];
						}
						
						//$systemType = 'LAST_PURCHASE_PRICE';
						//  CHECK LAST PURHCASE PRICE 
						if($systemType == 'LAST_PURCHASE_PRICE'){
							$getTransactionData['type'] = 'PURCHASE';
							$getTransactionData['itemId'] = $getPostData['itemId'];
							
							$screenDetails		= $this->config->item('SCREEN_NAMES')['GRPO'];
							$passResultInfo		= $this->nativeModel->getItemTransactionDetails($getTransactionData,$screenDetails);
							
							///printr($passResultInfo);
							//exit;
						} else if(count($defaultPriceListInfo)>0 && count($currentPriceListInfo) > 0){
							// GET THE PRICE LIST VALUE 
							
							$passDefaultPriceListParams['priceListId'] 	  = $defaultPriceListInfo[0]['default_price_list_id'];
							$passDefaultPriceListParams['itemId']		  = $getPostData['itemId'];
							$defaultMasterItemPriceList 				  = $this->nativeModel->getMasterItemPriceDetails($passDefaultPriceListParams);
							
							//echo "4";
								//printr($defaultMasterItemPriceList);
							
								if(count($defaultMasterItemPriceList) > 0){
									$unitPrice = $defaultMasterItemPriceList[0]['unit_price'];
									
									//[OLD LOGIC HERE]
									//$currentPriceListDefaultFactor = $currentPriceListInfo[0]['default_factor']; 
									//$passResultInfo[0]['unit_price'] = $unitPrice*$currentPriceListDefaultFactor;
									
									
									// NEW LOGIC 
									$calculatedDefaultFactor = $defaultPriceListInfo[0]['calculated_default_factor'];
									$passResultInfo[0]['unit_price'] = $unitPrice*$calculatedDefaultFactor;
								}
						}
					}
			}
		}
		
		//printr($passResultInfo);exit;
		
		// FINALLY APPEND THE DATA 
		if(count($passResultInfo)>0){
			$modelOutput['unit_price'] = $passResultInfo[0]['unit_price'];
			if(isset($passResultInfo[0]['discount_percentage'])){
				$modelOutput['discount_percentage'] = $passResultInfo[0]['discount_percentage'];
			}
		}
		
		// FRAME OUTPUT
        $outputData['results']      = $modelOutput;
        $outputData['status']       = "SUCCESS";
        $this->output->sendResponse($outputData);
    }
	
	
///////////////////////////////////END OF  OTHER FUNCTIONALITY //////////////////////////////////////	
/////////////////////////////////// RENTAL MODULE //////////////////////////////////////////////////////////////////////
	/**
	* @METHOD NAME 	: getEquipmentDetailsByItemId()
	*
	* @DESC 		: TO GET THE DOCUMENT NUMBER
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getEquipmentDetailsByRentalItemId()
    {
        $this->checkRequestMethod("put"); 	// CHECK THE REQUEST METHOD
		$outputData['status']  = "FAILURE";
        
         // PARAMS FROM HTTP REQUEST
        if (!empty($this->currentRequestData['rentalItemId']) && is_numeric($this->currentRequestData['rentalItemId'])) {

			$modelOutput = $this->nativeModel->getEquipmentDetailsByRentalItemId($this->currentRequestData);
	
			if (count($modelOutput) > 0) {
				
				// PASS SEARCH DATA 
				$passSearchData['category'] = 2;
				$passSearchData['delFlag']  = 0;
				
				foreach($modelOutput as $key => $value){
					
				
					// FRAME ALL THE INFO DATA
					$statusInfoDetails	= array();
					$getInfoData 		= array(	
						'getEquipmentOwnershipList' 			  => $value['ownership_id'],
						'getRentalEquipmentStatusList' 			  => $value['status'],
						'getRentalStatusList' 					  => $value['rental_status'],
						'getDocumentTypeList' 					  => $value['document_type_id'],
					);
					
					// DOCUMENT DETAILS 
					$passDocumenNumbertData['documentNoId']   	= $value['document_id'];
					$passDocumenNumbertData['documentTypeId']   = $value['document_type_id'];
					$documentNumberDetails   	  				= $this->commonModel->getDocumentNumber(array_merge($passSearchData,$passDocumenNumbertData));
					
					
					$statusInfoDetails							= getAutoSuggestionListHelper($getInfoData);
					$statusInfoDetails['documentInfo']		 	= $documentNumberDetails;
						
					$modelOutput[$key] 	= array_merge($value,$statusInfoDetails);
				}			
            }
			
			$outputData['status']       = "SUCCESS";
			$outputData['results']      = $modelOutput;
	 
        } else { 
            $outputData['message']      = lang('GLB_007'); // INVALID PARAMETERS
        }	
        $this->output->sendResponse($outputData);
    }
	
	
	/**
	* @METHOD NAME 	: getAvailableToPromise()
	*
	* @DESC 		: TO GET THE AVAILABLE TO PROMISE
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getAvailableToPromise()
	{
		$this->checkRequestMethod("post"); 	// CHECK THE REQUEST METHOD
		$outputData['status']  = "FAILURE";
		$availability			= $this->nativeModel->getAvailableStock($this->currentRequestData);
		$modelOutput 			= $this->nativeModel->getAvailableToPromise($this->currentRequestData);
		$ordered 				= 0;
		$committed 				= 0;
		
		foreach($modelOutput as $key => $value)
		{
			if($value['document'] === 'SO') 
			{
				$availability 					= $availability - $value['committed'];
				$modelOutput[$key]['available'] = $availability;
				$committed 						= $committed + $value['committed'];
			} 
			else if($value['document'] === 'PO')
			{
				$availability 					= $availability + $value['ordered'];
				$modelOutput[$key]['available'] = $availability;
				$ordered 						= $ordered + $value['ordered'];
			}
		}
		$outputData['status']  	 = "SUCCESS";
		$outputData['results'] 	 = $modelOutput;
		$outputData['available'] = $this->nativeModel->getAvailableStock($this->currentRequestData);
		$outputData['ordered']   = $ordered;
		$outputData['committed'] = $committed;
		
		$this->output->sendResponse($outputData);
	}
	
/////////////////////////////////// END OF RENTAL MODULE //////////////////////////////////////////////////////////////////////
}
?>