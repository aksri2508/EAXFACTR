<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Master_inspection_template.php
* @Class  			 : Master_inspection_template
* Model Name         : Master_inspection_template
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 29 MAY 2021
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : -
* Features           : 
*/
class Master_inspection_template extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->config->load('table_config/tbl_master_inspection_template.php');
		$this->load->model('company/master_inspection_template_model', 'nativeModel');
	}


	/**
	 * @METHOD NAME 	: saveInspectionTemplate()
	 *
	 * @DESC 			: TO SAVE THE INSPECTION TEMPLATE.
	 * @RETURN VALUE 	: $outputdata array
	 * @PARAMETER 		: -
	 * @SERVICE      	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function saveInspectionTemplate()
	{
		// Params from http request
		$this->checkRequestMethod("post"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$getData 		       = $this->currentRequestData;


		$modelOutput 	   = $this->nativeModel->saveInspectionTemplate($getData);

		if (1 == $modelOutput['flag']) {
			$outputData['sId']      	= $modelOutput['sId'];
			$outputData['status']       = "SUCCESS";
			$outputData['message']      = lang('MSG_326'); // Successfully Inserted
		} else if (2 == $modelOutput['flag']) {
			$outputData['message']      = lang('GLB_009'); // Unable to save record.
		} else if (3 == $modelOutput['flag']) {
			$outputData['message']      = lang('MSG_327'); // Record Already Exists.
		}

		$this->output->sendResponse($outputData);
	}



	/**
	 * @METHOD NAME 	: updateInspectionTemplate()
	 *
	 * @DESC 			: TO UPDATE THE INSPECTION TEMPLATE.
	 * @RETURN VALUE 	: $outputdata array
	 * @PARAMETER 		: -
	 * @SERVICE      	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function updateInspectionTemplate()
	{
		$this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";

		$modelOutput = $this->nativeModel->updateInspectionTemplate($this->currentRequestData);
		if (1 == $modelOutput['flag']) {
			$outputData['status']       = "SUCCESS";
			$outputData['message']      = lang('MSG_328'); //'Successfully saved
		} else if (2 == $modelOutput['flag']) {
			$outputData['message']      = lang('GLB_010'); // Unable to update the record
		} else if (3 == $modelOutput['flag']) {
			$outputData['message']      = lang('MSG_327'); // Record Already Exists
		}
		$this->output->sendResponse($outputData);
	}

}