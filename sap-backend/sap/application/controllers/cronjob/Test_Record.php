<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Test_record.php
* @Class  			 : Test_record
* Model Name         : Test_record
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 14 APR 2021
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : -
* Features           : 
*/

class Test_record extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->config->load('table_config/tbl_sales_quote.php');
		$this->config->load('table_config/tbl_sales_quote_items.php');
		// $this->load->model('company/sales_quote_model1', 'nativeModel');
		// $this->load->model('company/mail_notification_model1', 'nativeModel');
		// $this->load->model('cronjob/mail_notification_model2', 'nativeModel');
		$this->load->model('cronjob/Test_record_model', 'nativeModel');

	}


	/**
	 * @METHOD NAME 	: getList()
	 *
	 * @DESC 			: TO LIST 
	 * @RETURN VALUE 	: $outputdata array
	 * @PARAMETER 		: -
	 * @SERVICE      	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function getList($id = '')
	{
		$outputData['status']  = "FAILURE";
		$id = 1;
		// PARAMS FROM HTTP REQUEST
		if (!empty($id) && is_numeric($id)) {

			$modelOutput 				= $this->nativeModel->listRecords($id);

			// BIND THE LIST SUB ARRAY 
			$outputData['status']   = "SUCCESS";
			$outputData['results']  = $modelOutput;
		} else {
			$outputData['message'] =  lang('GLB_015');  // INVALID ID PASSED
		}
		echo json_encode($outputData);
		exit;
		// $this->output->sendResponse($outputData);
	}


}
