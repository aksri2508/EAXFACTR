<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Transport.php
* @Class  			 : Transport
* Model Name         : Transport_model
* Description        :
* Module             : company/Transport
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 07 JUNE 2024
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : -
* Features           : 
*/
class Transport extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
		$this->config->load('table_config/tbl_transport.php');
		$this->config->load('table_config/tbl_transport_invoices.php');
        $this->load->model('company/Transport_model', 'nativeModel');
		$this->MsgTitle = "Gatepass_barcode";
    }
	
	
	/**
	* @METHOD NAME 	: saveTransport()
	*
	* @DESC 		: TO SAVE THE TRANSPORT DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function saveTransport()
	{
		// Params from http request
		$this->checkRequestMethod("post"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$getData 		       = $this->currentRequestData;

		$modelOutput 	  	   = $this->nativeModel->saveTransport($getData);

		if (1 == $modelOutput['flag']) {
			$outputData['sId']      	= $modelOutput['sId'];
			$outputData['status']       = "SUCCESS";
			$outputData['message']      = lang('MSG_360');  // Successfully Inserted
		} else if (2 == $modelOutput['flag']) {
			$outputData['message']      = lang('GLB_009');  // Unable to save the record
		}
		$this->output->sendResponse($outputData);
	}
	

	/**
	* @METHOD NAME 	: updateTransport()
	*
	* @DESC 		: TO UPDATE THE TRANSPORT DETAILS.
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function updateTransport()
    {

        $this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";

		$getData = $this->currentRequestData;

		if(isset($getData['id'])){

			$transportId = $getData['id'];
			// Check for gatepass entry.
			$gatePassHistoryRecord = $this->nativeModel->checkGatepassHistoryRecord($transportId);
			
			if(count($gatePassHistoryRecord) == 0){
				$modelOutput  = $this->nativeModel->updateTransport($getData);
			
				if (1 == $modelOutput['flag']) {
					$outputData['status']       = "SUCCESS";
					$outputData['message']      = lang('MSG_361'); //'Successfully updated
				}else if (2 == $modelOutput['flag']) {
					$outputData['message']      = lang('GLB_010'); // Unable to update the record
				}else if (3 == $modelOutput['flag'] ) {
					$outputData['message']  = lang('MSG_363'); // Invoice status validation failure.
				}
			}
			else {
				$outputData['message']      = lang('MSG_364'); // UNABLE TO UPDATE, Gatepass entry Avail.	
			}

		}
		else {
			$outputData['message']      = lang('GLB_007'); // INVALID PARAMETERS
		}


        $this->output->sendResponse($outputData);
    }

	/**
	 * @METHOD NAME 	: editTransport()
	 *
	 * @DESC 			: -
	 * @RETURN VALUE 	: $outputdata array
	 * @PARAMETER 		: -
	 * @SERVICE      	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function editTransport($id = '')
	{
		$outputData['status']  = "FAILURE";

		if (empty($id)) {
			$id  = $this->currentRequestData['id'];
		} else {
			$this->checkRequestMethod("put"); 	// CHECK THE REQUEST METHOD
		}

		// PARAMS FROM HTTP REQUEST
		if (!empty($id) && is_numeric($id)) {

			$modelOutput 				= $this->nativeModel->editTransport($id);
			$getItemList 			 	= $this->nativeModel->editInvoicesList($id);
			$gatepassHistory			= $this->commonModel->getGateHistory($id);

			if (count($modelOutput) > 0) {

				$frameEditDetails = $this->frameTransportEditDetails($modelOutput[0]);

				$data 				  = array();
				$data				  = $frameEditDetails;

				// BIND THE LIST SUB ARRAY 
				$data['invoiceListArray'] 	= $this->frameTransportInvoiceEditDetails($getItemList);
				$data['gatepassHistory'] 	= $gatepassHistory;
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
	* @METHOD NAME 	: frameTransportEditDetails()
	*
	* @DESC 		: -
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function frameTransportEditDetails($modelOutput)
    {
		$statusInfoDetails	= array();
		$getInfoData = array(	
			'getTransportStatusList~statusInfo'	 => $modelOutput['status'],
			'getTaxList~taxInfo'				 => $modelOutput['tax_id'],
			'getVehicleList~vehicleInfo'		 => $modelOutput['vehicle_id'],
			'getCreatedByDetails~createdByInfo'	 => $modelOutput['created_by'],
			'getBranchList~branchInfo'			 => $this->currentbranchId,
			'getDocumentNumberingList~documentNumberingInfo'	 => $modelOutput['document_numbering_id'],
			
		);
	
		$statusInfoDetails	= getAutoSuggestionListHelper($getInfoData);
		$result  			= array_merge($modelOutput,$statusInfoDetails);
		return $result;
	}


	/**
	* @METHOD NAME 	: frameTransportInvoiceEditDetails()
	*
	* @DESC 		: -
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function frameTransportInvoiceEditDetails($itemList, $type = null)
    {
		// BIND THE LIST SUB ARRAY 
		if(count($itemList)>0){
			
			$getAllDetails = array();
			foreach($itemList as $key => $value){
				
			
					$statusInfoDetails	= array();
					$getInfoData		= array(	
											'getTransportInvoiceStatusList~statusInfo' 	 => $value['status']
										  );
					$statusInfoDetails	= getAutoSuggestionListHelper($getInfoData);
					
					// GET AR-INVOICE INFORMATION 
					$passArInvoiceData['id'] = $value['sales_ar_invoice_id'];
					//$passArInvoiceData['delFlag'] = 1;
					$saleArInvoiceList['salesArInvoiceInfo'] = $this->commonModel->getARInvoiceDetails($passArInvoiceData);
					
					// Re-Assign Array 
					$itemList[$key] = array_merge($value,$statusInfoDetails,$saleArInvoiceList);
			}
		}
		
		return $itemList;
	}

	/**
	* @METHOD NAME 	: getAnalyticsDetails()
	*
	* @DESC 		: TO GET THE ANALYTICS DETAILS FOR VEHICLE
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
		$passActivityStatusData['type']  = 'TRANSPORT_STATUS';
		$activityStatusDetails  = $this->commonModel->getMasterStaticDataAutoList(array_merge($passSearchData,$passActivityStatusData),2);
		
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
	* @METHOD NAME 	: getTransportList()
	*
	* @DESC 		: TO GET THE VEHICLE LIST DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getTransportList()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
        $modelOutput           = $this->nativeModel->getTransportList($this->currentRequestData);
		
		// FRAME OUTPUT
        $outputData['results']      = $modelOutput;
        $outputData['status']       = "SUCCESS";
		
        $this->output->sendResponse($outputData);
    }
	
	
	/**
	* @METHOD NAME 	: downloadGatePass()
	*
	* @DESC 		: TO DOWNLOAD THE GATEPASS FOR EACH TRANSPORT 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function downloadGatePass()
	{
		$this->checkRequestMethod("put"); 	// CHECK THE REQUEST METHOD
		$outputData['status']  = "FAILURE";
		$companyDetails			= $this->commonModel->getCompanyInformation();
		
		$getData 	 = $this->currentRequestData;
		$barCodeData = $getData['barCodeBlob'];
		$modelOutput = $this->nativeModel->getGatePassDetails($getData['id']);
		
		if (count($modelOutput) > 0) {
			
			$itemRowCountVal = 6;
			if (count($companyDetails) == 1) {
				// Process data - set values.
				$invoiceProcessData['companyDetails'] 	= $companyDetails;
				$invoiceProcessData['modelOutput'] 		= $modelOutput[0];
				$invoiceProcessData['barcodeHtml'] 		= $barCodeData;
				$invoiceProcessData['itemRowCountVal'] 	= 6;
				$invoiceProcessData['fileName']			= $this->MsgTitle; 
				$invoiceProcessData['fileHeadingName']  = strtoupper($this->MsgTitle);
				
				// Generating  Invoice
				$outputData = generateGatepassBarCodePdf($invoiceProcessData);

			}else {
				// Company Details not found.
				$outputData['message']  = lang('MSG_143');  
			}
		} else {
			$outputData['message']  = $this->downloadInvoiceMsg;
		}
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
		 $modelOutput	= $this->nativeModel->getTransportList($this->currentRequestData,1);
		 $resultsData 	= $modelOutput['searchResults'];
		 $fileName		= $this->config->item('TRANSPORT')['excel_file_name']; 
		 $outputData 	= processExcelData($resultsData,$fileName,$this->config->item('TRANSPORT')['columns_list']);
		 $this->output->sendResponse($outputData);
	}
	
	
	/**
	* @METHOD NAME 	: getInvoiceListForTransport()
	*
	* @DESC 		: TO GET THE INVOICE LIST FOR TRANSPORT
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getInvoiceListForTransport()
	{
		$this->checkRequestMethod("get"); 	// CHECK THE REQUEST METHOD
		
		// BIND THE LIST SUB ARRAY 
		$modelOutput 			= $this->nativeModel->getInvoiceListForTransport();
		$outputData['status']   = "SUCCESS";
		$outputData['results']  = $modelOutput;
		$this->output->sendResponse($outputData);
	}
	
	
	/**
	* @METHOD NAME 	: getTransportedDetailsForInvoice()
	*
	* @DESC 		: TO GET THE INVOICE LIST FOR TRANSPORT
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getTransportedDetailsForInvoice()
	{
		$this->checkRequestMethod("put"); 	// CHECK THE REQUEST METHOD
		$id = $this->currentRequestData['id'];
		
		// BIND THE LIST SUB ARRAY 
		$modelOutput 			= $this->nativeModel->getTransportedDetailsForInvoice($id);

		$outputData['status']   = "SUCCESS";
		$outputData['results']  = $modelOutput;
		$this->output->sendResponse($outputData);
	}
	
	
	//////////////////////////////////////////////// TRANSPORT REPORT SECTION ////////////////////////////////////
	/**
	* @METHOD NAME 	: downloadTransportReports()
	*
	* @DESC 		: TO GET TRANSPORT REPORT AS DOWNLOAD 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function downloadTransportReports()
	{
        $this->checkRequestMethod("put"); // Check the Request Method
        $modelOutput   = $this->nativeModel->getTransportReports($this->currentRequestData,1);


		// print_r($modelOutput);
		// exit;
		$totalAmount = 0;
		foreach($modelOutput['searchResults'] as $mKey => $mValue){

			$invoiceNames = explode(",",$mValue['invoice_document_name']);
			$invoiceStatus = explode(",",$mValue['transport_invoice_status']);

			$invoiceNameStatusInfo = array();
            foreach($invoiceStatus as $ikey => $iName){
				if(isset($invoiceNames[$ikey]) && $invoiceStatus[$ikey]){
					$invoiceNameStatusInfo[] = $invoiceNames[$ikey]."-".$invoiceStatus[$ikey];
				}
			}
	
			$modelOutput['searchResults'][$mKey]['transport_invoice_status_info'] = implode(" , ",$invoiceNameStatusInfo);

			$totalAmount = $totalAmount + $mValue["total_amount"];

		}
		
		// FRAME OUTPUT
         $outputData['results']      = $modelOutput;
		 $resultsData 				 = $modelOutput['searchResults'];
		 $fileName					 = time() . '_transport_report.xlsx';
		 
		 $transportReportsConfig	= array(
												array(
													'display_name'		=> 'Date',
													'tbl_field_name' 	=> 'posting_date',
													'excel_flag'		=> 1
												),
												array(
													'display_name'		=> 'Vechicle Code',
													'tbl_field_name' 	=> 'vehicle_code',
													'excel_flag'		=> 1
												),
												array(
													'display_name'		=> 'Vechicle Description',
													'tbl_field_name' 	=> 'description',
													'excel_flag'		=> 1
												),
												array(
													'display_name'		=> 'Reference No',
													'tbl_field_name' 	=> 'reference_number',
													'excel_flag'		=> 1
												),
												array(
													'display_name'		=> 'Document No',
													'tbl_field_name' 	=> 'document_number',
													'excel_flag'		=> 1
												),
												array(
													'display_name'		=> 'Invoices',
													'tbl_field_name' 	=> 'transport_invoice_status_info',
													'excel_flag'		=> 1
												),
												array(
													'display_name'		=> 'Price_Km',
													'tbl_field_name' 	=> 'price',
													'excel_flag'		=> 1
												),
												array(
													'display_name'		=> 'Total Kms',
													'tbl_field_name' 	=> 'total_kms',
													'excel_flag'		=> 1
												),
												array(
													'display_name'		=> 'Tax Percentage',
													'tbl_field_name' 	=> 'tax_percentage',
													'excel_flag'		=> 1
												),
												array(
													'display_name'		=> 'Tax Value',
													'tbl_field_name' 	=> 'tax_value',
													'excel_flag'		=> 1
												),
												
												array(
													'display_name'		=> 'Total Price',
													'tbl_field_name' 	=> 'total_amount',
													'excel_flag'		=> 1
												),
												array(
													'display_name'		=> 'Status',
													'tbl_field_name' 	=> 'transport_status',
													'excel_flag'		=> 1
												),
												array(
													'display_name'		=> 'Remarks',
													'tbl_field_name' 	=> 'remarks',
													'excel_flag'		=> 1
												),
											);
 
		 $outputData 	= processExcelForTransportReport($resultsData, $fileName, $transportReportsConfig, $totalAmount);
		 $this->output->sendResponse($outputData);
    }


	

}
