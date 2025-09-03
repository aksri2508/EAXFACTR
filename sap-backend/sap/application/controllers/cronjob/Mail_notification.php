<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Mail_notification.php
* @Class  			 : Mail_notification
* Model Name         : Mail_notification
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 15 APR 2021
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : -
* Features           : 
*/

class Mail_notification extends MY_Controller
{
	public function __construct()
	{
		
		parent::__construct();
		$this->config->load('table_config/tbl_sales_quote.php');
		$this->config->load('table_config/tbl_sales_quote_items.php');
		// $this->load->model('company/sales_quote_model1', 'nativeModel');
		// $this->load->model('company/mail_notification_model1', 'nativeModel');
		// $this->load->model('cronjob/mail_notification_model2', 'nativeModel');
		$this->load->model('cronjob/mail_notification_model', 'nativeModel');

	}

	/**
	* @METHOD NAME 	: sendDocumentMail()
	*
	* @DESC 		: TO SEND THE EMAIL - USED FOR CRON. 
	*                 (Service exposed directly from here). 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	*/

	public function sendMail($id = '')
	{
	
		$data['toEmailId'] = "jayaprakash.qruize@gmail.com";
		$data['subject'] = "Cron Test.";
		$data['mailContent'] = "This is cron Test mail.";


		if(isset($data['mailContent']) &&
		   isset($data['toEmailId']) &&
		   isset($data['subject'])){
		
		   $mailHelperData = array(
			   "email_body" => "<br>".$data['mailContent'],
			   "email_id" => $data['toEmailId'],
			   "subject" => $data['subject'],
			   "documentDetails" => array(),
			   "email_cc" => null,
			   "email_bcc" => null,
		   );

		   sendCronNotificationMail($mailHelperData);

			$outputData = [
				'status'       => 'SUCCESS',
				'message'      =>'Mail Sent.',
			];
	
			// $this->output->sendResponse($outputData);
		}
		else{
			$outputData = [
				'status'       => 'FAILURE',
				'message'      =>'Invalid Request Data.',
		    ];
		//    $this->output->sendResponse($outputData);
		}

		echo json_encode($outputData);
		exit;
	}




	// /**
	//  * @METHOD NAME 	: getList()
	//  *
	//  * @DESC 			: TO LIST 
	//  * @RETURN VALUE 	: $outputdata array
	//  * @PARAMETER 		: -
	//  * @SERVICE      	: WEB
	//  * @ACCESS POINT 	: -
	//  **/
	// public function getList($id = '')
	// {
	// 	$outputData['status']  = "FAILURE";
	// 	$id = 1;
	// 	// PARAMS FROM HTTP REQUEST
	// 	if (!empty($id) && is_numeric($id)) {

	// 		$modelOutput 				= $this->nativeModel->listRecords($id);

	// 		// BIND THE LIST SUB ARRAY 
	// 		$outputData['status']   = "SUCCESS";
	// 		$outputData['results']  = $modelOutput;
	// 	} else {
	// 		$outputData['message'] =  lang('GLB_015');  // INVALID ID PASSED
	// 	}
	// 	echo json_encode($outputData);
	// 	exit;
	// 	// $this->output->sendResponse($outputData);
	// }


}
