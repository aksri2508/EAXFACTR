<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Activity_Job.php
* @Class  			 : Activity_Job
* Model Name         : Activity_Job
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 01 MAY 2021
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : -
* Features           : 
*/

class Activity_Job extends MY_Controller
{
	public function __construct()
	{
		
		parent::__construct();
		$this->load->model('cronjob/activity_job_model', 'nativeModel');

	}


	/**
	* @METHOD NAME 	: sendNotification()
	*
	* @DESC 		: To Send/Popup-Notification - Used in Cron. 
	*                 (Service exposed directly from here). 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	*/

	public function sendNotification()
	{

		$CI = &get_instance();
		// Get "Activity Notification - Body Conten Template
		$templateName = "activity_mail_notification";
		$mailContent = $CI->config->item($templateName);

		// $templateName = "activity_popup_notification";
		// $popupContent = $CI->config->item($templateName);

		// Getting MailTemplate and MailSubject Name.
		$mailTitle	 = $mailContent['title'];
		$mailSubject = $mailContent['subject'];

		// Getting - Activity Notification data from Model.
		$modelOutput = $this->nativeModel->listActivityRecords();


		if (count($modelOutput) >= 1) {

			// Getting all Records.
			foreach ($modelOutput as $value) {

				$mailBody = "";

				// Making Dynamic Body Content with Model Data and Mailbody template.
				if(isset($value['first_name']) && isset($value['last_name']) && isset($value['remarks'])){
					$replaceBody = [
						'<<NAME>>'              => implode(' ', [$value['first_name'], $value['last_name']]),
						'<<REASON>>'			=> $value['remarks'],   //
					];
					$mailBody = str_replace(array_keys($replaceBody), $replaceBody, $mailContent['body']);
				}

				// Getting Sender Mail Id.
				$toMailId = "";
				if(isset($value['email_id'])){
                     $toMailId = $value['email_id'];
				}

				if (
					$mailBody != "" &&
					$toMailId != "" &&
					$mailSubject != "" &&
					$mailTitle != ""
				) {

					// Making $mailHelperData array to send to helper function.
					$mailHelperData = array(
						"email_body" => "<br>" . $mailBody,
						"email_id" => $toMailId,
						"subject" => $mailSubject,
						"title" => $mailTitle,
						"documentDetails" => array(),
						"email_cc" => null,
						"email_bcc" => null,
					);

					// Calling Helper mail function.
					sendMail($mailHelperData);

					// Calling Helper Popup notification.
					// sendnotification($data);

				}
				
				$outputData = [
					'status'       => 'SUCCESS',
					'message'      => 'Mail Sent.',
				];
			}


			// $this->output->sendResponse($outputData);

		} else {
			$outputData = [
				'status'       => 'FAILURE',
				'message'      => 'Invalid Request Data.',
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
