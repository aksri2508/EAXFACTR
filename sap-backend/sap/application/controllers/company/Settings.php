<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Settings.php
* @Class  			 : Settings
* Model Name         : Settings
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 30 MAY 2019
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : -
* Features           : 
*/
class Settings extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
		$this->config->load('table_config/tbl_settings.php');
        $this->load->model('company/settings_model', 'nativeModel');
    }
	
	
	
	/**
	* @METHOD NAME 	: updateSettings()
	*
	* @DESC 		: TO UPDATE THE SETTINGS 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function updateSettings()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";
        
		$modelOutput = $this->nativeModel->updateSettings($this->currentRequestData);
		if (1 == $modelOutput['flag']) {
			$outputData['status']       = "SUCCESS";
			$outputData['message']      = lang('MSG_266'); //'Successfully saved
		}else if (2 == $modelOutput['flag']) {
			$outputData['message']      = lang('MSG_265'); // Record Already Exists
		}
        $this->output->sendResponse($outputData);
    }
	
	
	/**
	* @METHOD NAME 	: getSettingsDetails()
	*
	* @DESC 		: TO GET THE SETTINGS DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getSettingsDetails()
    {
        $this->checkRequestMethod("get"); // Check the Request Method
        $modelOutput   = $this->nativeModel->getSettingsDetails($this->currentRequestData);
		
		if(count($modelOutput) == 1){
		
			// FRAME ALL THE INFO DATA
			$statusInfoDetails	= array();
			
			$getInfoData		= array(
				'getTaxList~salesTaxInfo' 		=> $modelOutput[0]['sales_tax_id'],
				'getTaxList~purchaseTaxInfo' 	=> $modelOutput[0]['purchase_tax_id'],
			);
			
			$statusInfoDetails	= getAutoSuggestionListHelper($getInfoData);
			
			$result  							  = array(array_merge($modelOutput[0],$statusInfoDetails));
			
			// FRAME OUTPUT
			$outputData['results']      = $result;
			$outputData['status']       = "SUCCESS";
		}else{
			$outputData['results']      = [];
			//$outputData['status']       = "FAILURE";
			//$outputData['message']       =  lang('GLB_016');
		}
        $this->output->sendResponse($outputData);
    }

}
?>
