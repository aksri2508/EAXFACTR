<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Smt_map.php
* @Class  			 : Smt_map
* Model Name         : Smt_map
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
class Smt_map extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('company/smt_map_model', 'nativeModel');
    }
	
    
	/**
	* @METHOD NAME 	: getMapVisitList()
	*
	* @DESC 		: TO GET THE LAST VISIT LIST
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getMapVisitList()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
        $modelOutput           = $this->nativeModel->getMapVisitList($this->currentRequestData);
		
		//print_r($modelOutput);exit;
		
		// FRAME OUTPUT
        $outputData['results']      = $modelOutput;
        $outputData['status']       = "SUCCESS";
		
        $this->output->sendResponse($outputData);
    }
	
	
	/**
	* @METHOD NAME 	: getEmployeeMapVisitList()
	*
	* @DESC 		: TO GET THE EMPLOYEE MAP VISIT LIST 
	* @RETURN VALUE : $modelData array
	* @PARAMETER 	: $getPostData array
    * @SERVICE 		: WEB
	* @ACCESS POINT : -
	**/
	public function getEmployeeMapVisitList()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
        $modelOutput           = $this->nativeModel->getEmployeeMapVisitList($this->currentRequestData);
		
		// FRAME OUTPUT
        $outputData['results']      = $modelOutput;
        $outputData['status']       = "SUCCESS";
		
        $this->output->sendResponse($outputData);
    }
	
}
?>