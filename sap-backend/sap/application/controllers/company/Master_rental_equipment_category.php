<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Master_rental_equipment_category.php
* @Class  			 : Master_rental_equipment_category
* Model Name         : Master_rental_equipment_category
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 22 MAY 2019
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : -
* Features           : 
*/
class Master_rental_equipment_category extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
		$this->config->load('table_config/tbl_master_rental_equipment_category.php');
        $this->load->model('company/master_rental_equipment_category_model', 'nativeModel');
    }
	
	
	/**
	* @METHOD NAME 	: saveRentalEquipmentCategory()
	*
	* @DESC 		: TO SAVE THE EQUIPMENT CATEGORY
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function saveRentalEquipmentCategory()
    {
        // Params from http request
        $this->checkRequestMethod("post"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$getData 		       = $this->currentRequestData;

			$modelOutput 	   = $this->nativeModel->saveRentalEquipmentCategory($getData);
			
			if (1 == $modelOutput['flag']) {
				$outputData['status']       = "SUCCESS";
				$outputData['message']      = lang('GLB_001'); // Successfully Inserted
			} else if (2 == $modelOutput['flag']) {
				$outputData['message']      = lang('GLB_004'); // Record Already Exists
			}
		
        $this->output->sendResponse($outputData);
    }
}
