<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Master_price_list.php
* @Class  			 : Master_price_list
* Model Name         : Master_price_list
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
class Master_price_list extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
		$this->config->load('table_config/tbl_master_price_list.php');
        $this->load->model('company/master_price_list_model', 'nativeModel');
    }
	
	
	/**
	* @METHOD NAME 	: saveMasterPriceList()
	*
	* @DESC 		: TO SAVE THE MASTER PRICE LIST 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function saveMasterPriceList()
    {
        // Params from http request
        $this->checkRequestMethod("post"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$getData 		       = $this->currentRequestData;

			$modelOutput 	   = $this->nativeModel->saveMasterPriceList($getData);
			
			if (1 == $modelOutput['flag']) {
				$outputData['status']       = "SUCCESS";
				$outputData['message']      = lang('MSG_273'); // SUCCESSFULLY MASTER PRICE LIST 
			} else if (2 == $modelOutput['flag']) {
				$outputData['message']      = lang('MSG_274'); // Record Already Exists
			} else if (3 == $modelOutput['flag']) {
				$outputData['message']      = lang('MSG_285'); // 
			}
		
        $this->output->sendResponse($outputData);
    }
    
	
	/**
	* @METHOD NAME 	: updateMasterPriceList()
	*
	* @DESC 		: TO UPDATE THE MASTER PRICE LIST 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function updateMasterPriceList()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";
        
		$modelOutput = $this->nativeModel->updateMasterPriceList($this->currentRequestData);
		if (1 == $modelOutput['flag']) {
			$outputData['status']       = "SUCCESS";
			$outputData['message']      = lang('MSG_275'); //'Successfully saved
		}else if (2 == $modelOutput['flag']) {
			$outputData['message']      = lang('MSG_274'); // Record Already Exists
		}else if (3 == $modelOutput['flag']) {
			$outputData['message']      = lang('MSG_285'); // Record Already Exists
		}
        $this->output->sendResponse($outputData);
    }
	
    
	/**
	* @METHOD NAME 	: editMasterPriceList()
	*
	* @DESC 		: TO EDIT MASTER PRICE LIST 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function editMasterPriceList()
    {
        $this->checkRequestMethod("put"); 	// CHECK THE REQUEST METHOD
		$outputData['status']  = "FAILURE";
        
         // PARAMS FROM HTTP REQUEST
        if (!empty($this->currentRequestData['id']) && is_numeric($this->currentRequestData['id'])) {
            
            $modelOutput = $this->nativeModel->editMasterPriceList($this->currentRequestData);
           
			if (count($modelOutput) > 0) {
				
				// FRAME ALL THE INFO DATA
				$statusInfoDetails	= array();
				
				$getInfoData = array(	
					'getPriceList~defaultPriceListInfo' 	=> $modelOutput[0]['default_price_list_id'],
				);
				
				$statusInfoDetails	= getAutoSuggestionListHelper($getInfoData);
				
				$result  							  = array(array_merge($modelOutput[0],$statusInfoDetails));
				
                $outputData['status']       = "SUCCESS";
                $outputData['results']      = $result;
            } else {
                $outputData['message']      =  lang('GLB_015');  // INVALID ID PASSED 
            }
        } else { 
            $outputData['message']      = lang('GLB_007'); // INVALID PARAMETERS
        }
        $this->output->sendResponse($outputData);
    }

    
	/**
	* @METHOD NAME 	: getMasterPriceList()
	*
	* @DESC 		: TO GET THE MASTER PRICE LIST 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getMasterPriceList()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
        $modelOutput           = $this->nativeModel->getMasterPriceList($this->currentRequestData);
		
		// FRAME OUTPUT
        $outputData['results']      = $modelOutput;
        $outputData['status']       = "SUCCESS";
		
        $this->output->sendResponse($outputData);
    }
	
}
?>