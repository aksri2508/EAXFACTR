<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Master_state.php
* @Class  			 : Master_state
* Model Name         : Master_state
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 14 MAY 2021
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : -
* Features           : 
*/
class Master_state extends MY_Controller
{
	 public function __construct()
    {
        parent::__construct();
		$this->config->load('table_config/tbl_master_state.php');
        $this->load->model('company/master_state_model', 'nativeModel');
    }


     
	/**
	* @METHOD NAME 	: getStateList()
	*
	* @DESC 		: TO GET THE state LIST DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getStateList()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
        $modelOutput           = $this->nativeModel->getStateList($this->currentRequestData);
		
		// FRAME OUTPUT
        $outputData['results']      = $modelOutput;
        $outputData['status']       = "SUCCESS";
		
        $this->output->sendResponse($outputData);
    }


}