<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Gatepass_history.php
* @Class  			 : Gatepass_history
* Model Name         : 
* Description        :
* Module             : company/Gatepass_history
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 05 JUNE 2024
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : -
* Features           : 
*/
class Gatepass_history extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
		$this->config->load('table_config/tbl_gatepass_history.php');
        $this->load->model('company/Gatepass_history_model', 'nativeModel');
    }
	
	
	/**
	* @METHOD NAME 	: getGatePassByBarCode()
	*
	* @DESC 		: TO get gate pass details by barcode.
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getGatePassByBarCode()
	{
		$this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";	
		
		if(isset($this->currentRequestData['barCode'])){
			$modelOutput		= $this->nativeModel->getGatePassByBarCode($this->currentRequestData['barCode']);
			
			if(count($modelOutput)>0){				
				//printr($modelOutput);				
				$transportId		 = $modelOutput[0]['id'];				
				$documentNumberList  = explode(",",$modelOutput[0]['document_number']);
				$invoiceStatusList   = explode(",",$modelOutput[0]['invoice_status']);
				$invoiceDetail 		 = array();
				
				foreach($documentNumberList as $documentKey => $documentValue){
					$documentValue = $documentValue == '-' ? '' : $documentValue;
					$invoiceDetail[$documentKey] = $documentValue."-".$invoiceStatusList[$documentKey];
				}
				
				//$gatepassHistory	= $this->nativeModel->getGateHistory($transportId);
				$gatepassHistory	= $this->commonModel->getGateHistory($transportId);
				
				$modelOutput[0]['invoicesList'] = $invoiceDetail;
				$modelOutput[0]['gatepassHistory'] = $gatepassHistory;
				$outputData['status']  = "SUCCESS";		
				$outputData['results'] =  $modelOutput[0];
			}
		} else {
			$outputData['message']      = lang('GLB_007'); // INVALID PARAMETERS
		}
		$this->output->sendResponse($outputData);
	}


	/**
	* @METHOD NAME 	: gatePassCheckInOut ()
	*
	* @DESC 		: TO UPDATE THE GATE PASS CHECK-IN-OUT.
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function gatePassCheckInOut ()
    {
        $this->checkRequestMethod("post"); // Check the Request Method
		$outputData['status']  = "FAILURE";

		$getData 				= $this->currentRequestData;
		$getData['transportId'] = $getData['id'];

		$modelOutput = $this->nativeModel->saveGatePassCheckInOut($getData);
		
		if (1 == $modelOutput['flag']) {
			$outputData['sId']      	= $modelOutput['sId'];
			$outputData['status']       = "SUCCESS";
			$outputData['message']      = lang('MSG_362');  // Successfully Inserted
		} else if (2 == $modelOutput['flag']) {
			$outputData['message']      = lang('GLB_009');  // Unable to save the record
		}
        $this->output->sendResponse($outputData);
    }
	
	
}
