<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Manage_notifications.php
* @Class  			 : Manage_notifications
* Model Name         : Manage_notifications
* Description        :
* Module             : common
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : -
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : -
* Features           : 
*/
class Manage_notifications extends MY_Controller
{
    public function __construct()
    {
       parent::__construct();
  		$this->config->load('table_config/tbl_notifications.php');
		$this->load->model('common/Manage_notifications_model', 'nativeModel');	
    }
	
	
	/**
	* @METHOD NAME 	: getMyNotifications()
	*
	* @DESC         : List the notifications received by login user
	* @RETURN VALUE : $outputData array
	* @PARAMETER 	: -
	* @Service      : WEB, MOBILE
	* @ACCESS POINT : -
	**/
    public function getMyNotifications()
    {
        $this->checkRequestMethod("PUT"); // CHECK THE REQUEST METHOD
		
        $getPostData = $this->currentRequestData; // PARAMS FROM HTTP REQUEST

		$modelOutput = $this->nativeModel->getMyNotifications($getPostData);
		
		$outputData = ['results' => $modelOutput, 'status' => 'SUCCESS'];
		
		$this->output->sendResponse($outputData);
    }
	
	/**
	* @METHOD NAME 	: markAsRead()
	*
	* @DESC 		: UPDATE THE STATUS OF NOTIFICATION AS READ
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function markAsRead()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";
        
		$modelOutput = $this->nativeModel->markAsRead($this->currentRequestData);
		
		if (1 == $modelOutput['flag']) {
			$outputData['status']       = "SUCCESS";
			$outputData['message']      = lang('MSG_290'); //'Successfully updated
		}else if (2 == $modelOutput['flag']) {
			$outputData['message']      = lang('GLB_010'); // Unable to update the record
		}
        $this->output->sendResponse($outputData);
    }
	
	
	/**
	* @METHOD NAME 	: markAllAsRead()
	*
	* @DESC 		: UPDATE THE STATUS OF ALL NOTIFICATION AS READ
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function markAllAsRead()
    {
        $this->checkRequestMethod("get"); // Check the Request Method
		$outputData['status']  = "FAILURE";
        
		$modelOutput = $this->nativeModel->markAllAsRead($this->currentRequestData);
		
		if (1 == $modelOutput['flag']) {
			$outputData['status']       = "SUCCESS";
			$outputData['message']      = lang('MSG_290'); //'Successfully updated
		}else if (2 == $modelOutput['flag']) {
			$outputData['message']      = lang('GLB_010'); // Unable to update the record
		}
        $this->output->sendResponse($outputData);
    }
	
}
?>