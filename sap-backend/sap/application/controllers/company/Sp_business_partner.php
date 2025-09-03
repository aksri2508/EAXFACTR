<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Sp_business_partner.php
* @Class  			 : Sp_business_partner
* Model Name         : Sp_business_partner
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
class Sp_business_partner extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
		$this->config->load('table_config/tbl_sp_business_partner.php');
        $this->load->model('company/sp_business_partner_model', 'nativeModel');
    }
	
	
	/**
	* @METHOD NAME 	: saveSpBusinessPartner()
	*
	* @DESC 		: TO SAVE THE SPECIAL PRICE BUSINESS PARTNER 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function saveSpBusinessPartner()
    {
        // Params from http request
        $this->checkRequestMethod("post"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$getData 		       = $this->currentRequestData;

			$modelOutput 	   = $this->nativeModel->saveSpBusinessPartner($getData);
			
			if (1 == $modelOutput['flag']) {
				$outputData['status']       = "SUCCESS";
				$outputData['message']      = lang('MSG_277'); // SUCCESSFULLY MASTER PRICE LIST 
			} else if (2 == $modelOutput['flag']) {
				$outputData['message']      = lang('MSG_278'); // Record Already Exists
			}
		
        $this->output->sendResponse($outputData);
    }
    
	
	/**
	* @METHOD NAME 	: updateSpBusinessPartner()
	*
	* @DESC 		: TO UPDATE THE SPECIAL PRICE Business Partner
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function updateSpBusinessPartner()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";
        
		$modelOutput = $this->nativeModel->updateSpBusinessPartner($this->currentRequestData);
		if (1 == $modelOutput['flag']) {
			$outputData['status']       = "SUCCESS";
			$outputData['message']      = lang('MSG_279'); //'Successfully updated
		}else if (2 == $modelOutput['flag']) {
			$outputData['message']      = lang('MSG_278'); // Record Already Exists
		}
        $this->output->sendResponse($outputData);
    }
	
    
	/**
	* @METHOD NAME 	: editSpBusinessPartner()
	*
	* @DESC 		: TO EDIT SPEICAL PRICE BUSINESS PARTNER  
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function editSpBusinessPartner()
    {
        $this->checkRequestMethod("put"); 	// CHECK THE REQUEST METHOD
		$outputData['status']  = "FAILURE";
        
         // PARAMS FROM HTTP REQUEST
        if (!empty($this->currentRequestData['id']) && is_numeric($this->currentRequestData['id'])) {
            
            $modelOutput = $this->nativeModel->editSpBusinessPartner($this->currentRequestData);
	   
			if (count($modelOutput) > 0) {
				
				// FRAME ALL THE INFO DATA
				$statusInfoDetails	= array();
				$getInfoData 		= array(	
					'getItemList' 			  => $modelOutput[0]['item_id'],
					'getPriceList' 			  => $modelOutput[0]['price_list_id'],
					'getBusinessPartnerList'  => $modelOutput[0]['business_partner_id'],
				);
				
				$statusInfoDetails	= getAutoSuggestionListHelper($getInfoData);
				$result 			= array(array_merge($modelOutput[0],$statusInfoDetails));
			
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
	* @METHOD NAME 	: getSpBusinessPartnerList()
	*
	* @DESC 		: TO GET THE SPEICAL PRICE BUSINESS PARTNER LIST 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getSpBusinessPartnerList()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
        $modelOutput           = $this->nativeModel->getSpBusinessPartnerList($this->currentRequestData);
		
		// FRAME OUTPUT
        $outputData['results']      = $modelOutput;
        $outputData['status']       = "SUCCESS";
		
        $this->output->sendResponse($outputData);
    }
}
?>