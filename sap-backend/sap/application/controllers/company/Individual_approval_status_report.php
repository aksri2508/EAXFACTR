<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Individual_approval_status_report.php
* @Class  			 : Individual_approval_status_report
* Model Name         : Individual_approval_status_report
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 16 MAY 2019
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : -
* Features           : 
*/
class Individual_approval_status_report extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
		$this->config->load('table_config/tbl_individual_approval_status_report.php');
        $this->load->model('company/individual_approval_status_report_model', 'nativeModel');
    }
	
    
	/**
	* @METHOD NAME 	: getApprovalStatusHistoryList()
	*
	* @DESC 		: TO GET THE APPROVAL STATUS REPORT
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getApprovalStatusHistoryList()
    {
        $this->checkRequestMethod("put");
        $modelOutput           = $this->nativeModel->getApprovalStatusHistoryList($this->currentRequestData);
        
      
		// FRAME OUTPUT
        $outputData['results']      = $modelOutput;
        $outputData['status']       = "SUCCESS";
		
        $this->output->sendResponse($outputData);
    }


    /**
	* @METHOD NAME 	: updateIndividualApprovalStatus()
	*
	* @DESC 		: TO UPDATE THE INDIVIDUAL APPROVAL STATUS 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
    **/
    public function updateIndividualApprovalStatus()
    {
        $this->checkRequestMethod("put"); // Check the Request Method

		$outputData['status']  = "FAILURE";
		
		$modelOutput = $this->nativeModel->updateIndividualApprovalStatus($this->currentRequestData);

		if (1 == $modelOutput['flag']) {
			$outputData['status']       = "SUCCESS";

			// SEND NOTIFICATIONS WITH PAYLOAD INFORMATION - START.
			if(isset($this->currentRequestData) && 
			$this->currentRequestData['status'] == 2) {
				$notfication_action_msg = 'Approved';
			}
			else if(isset($this->currentRequestData) && 
			$this->currentRequestData['status'] == 3) {
				$notfication_action_msg = 'Rejected';
			}
			else {
				$notfication_action_msg = 'Pending';
			}

			$notification_content = str_replace("STATUS-MSG", $notfication_action_msg, lang('NOTIFY_MSG_02')[0]);

			$receiverIds[] = $modelOutput['receiver_id'];
			$notificationPayload['content']		 = $notification_content;
			$notificationPayload['document_id']		 = $modelOutput['document_id'];
			$notificationPayload['document_type_id'] = $modelOutput['document_type_id'];
			sendNotification('WEB','APPROVAL_MODULE', $receiverIds, $notificationPayload);
			// SEND NOTIFICATIONS WITH PAYLOAD INFORMATION -END.

			$outputData['message']      = lang('MSG_339'); //'Successfully updated
		}else if (2 == $modelOutput['flag']) {
			$outputData['message']      = lang('MSG_340'); // Approval Status Report ID parameter missing
		}else if (3 == $modelOutput['flag']) {
			$outputData['message']      = lang('MSG_341'); // you already updated the document 
		}else if (4 == $modelOutput['flag']) {
			$outputData['message']      = lang('GLB_016'); // Pleae contact admin
		}
        $this->output->sendResponse($outputData);
    }
		
}
