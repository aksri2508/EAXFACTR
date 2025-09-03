<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Master_country.php
* @Class  			 : Master_country
* Model Name         : Master_country
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
class Master_country extends MY_Controller
{
	 public function __construct()
    {
        parent::__construct();
		$this->config->load('table_config/tbl_master_country.php');
        $this->load->model('company/master_country_model', 'nativeModel');
    }


     
	/**
	* @METHOD NAME 	: getCountryList()
	*
	* @DESC 		: TO GET THE Country LIST DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getCountryList()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
        $modelOutput           = $this->nativeModel->getCountryList($this->currentRequestData);
		
		// FRAME OUTPUT
        $outputData['results']      = $modelOutput;
        $outputData['status']       = "SUCCESS";
		
        $this->output->sendResponse($outputData);
    }


}