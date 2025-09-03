<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Master_tax.php
* @Class  			 : Master_tax
* Model Name         : Master_tax
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 30 MAY 2019
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : -
* Features           : 
*/
class Master_tax extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
		$this->config->load('table_config/tbl_master_tax.php');
        $this->load->model('company/master_tax_model', 'nativeModel');
    }
	
	
	/**
	* @METHOD NAME 	: saveTax()
	*
	* @DESC 		: TO SAVE THE TAX DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function saveTax()
    {
        // Params from http request
        $this->checkRequestMethod("post"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$getData 		       = $this->currentRequestData;

			$modelOutput 	   = $this->nativeModel->saveTax($getData);
			
			if (1 == $modelOutput['flag']) {
				$outputData['status']       = "SUCCESS";
				$outputData['message']      = lang('MSG_69'); // Successfully Inserted
			} else if (2 == $modelOutput['flag']) {
				$outputData['message']      = lang('MSG_70'); // Record Already Exists
			}
		
        $this->output->sendResponse($outputData);
    }
    
	
	/**
	* @METHOD NAME 	: updateTax()
	*
	* @DESC 		: TO UPDATE THE TAX
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function updateTax()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";
        
		$modelOutput = $this->nativeModel->updateTax($this->currentRequestData);
		if (1 == $modelOutput['flag']) {
			$outputData['status']       = "SUCCESS";
			$outputData['message']      = lang('MSG_71'); //'Successfully saved
		}else if (2 == $modelOutput['flag']) {
			$outputData['message']      = lang('GLB_010'); // Unable to update the record
		}else if (3 == $modelOutput['flag']) {
			$outputData['message']      = lang('MSG_70'); // Record Already Exists
		}
        $this->output->sendResponse($outputData);
    }
	
	
	/**
	* @METHOD NAME 	: editTax()
	*
	* @DESC 		: TO EDIT TAX DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function editTax()
    {
        $this->checkRequestMethod("put"); 	// CHECK THE REQUEST METHOD
		$outputData['status']  = "FAILURE";
        
         // PARAMS FROM HTTP REQUEST
        if (!empty($this->currentRequestData['id']) && is_numeric($this->currentRequestData['id'])) {
            
            $modelOutput = $this->nativeModel->editTax($this->currentRequestData);
            
            if (count($modelOutput) > 0) {
				
				// FRAME ALL THE INFO DATA
				$statusInfoDetails	= array();
				$attributeDetails 	= array();
				$attributeInfo 		= explode(",",$modelOutput[0]['attribute_id']);
				
				if(count($attributeInfo) > 0){
					foreach($attributeInfo as $attrKey => $attrValue){
						
						$getInfoData 		= array(
							'getTaxAttributeList' 		 	=> $attrValue,
						);
						$statusInfoDetails	= getAutoSuggestionListHelper($getInfoData);
						$attributeDetails[] = $statusInfoDetails['taxAttributeInfo'];
					}
				}
				
				$modelOutput[0]['attributeInfo']	= $attributeDetails;
				
                $outputData['status']       = "SUCCESS";
                $outputData['results']      = $modelOutput;
            } else {
                $outputData['message']      =  lang('GLB_015');  // INVALID ID PASSED 
            }
        } else { 
            $outputData['message']      = lang('GLB_007'); // INVALID PARAMETERS
        }	
        $this->output->sendResponse($outputData);
    }
    
	/**
	* @METHOD NAME 	: getTaxList()
	*
	* @DESC 		: TO GET THE TAX LIST DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getTaxList()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
        $modelOutput           = $this->nativeModel->getTaxList($this->currentRequestData);
		
		
		// GET ATTRIBUTE PERCENTAGE 
		
		foreach($modelOutput['searchResults'] as $key => $value){
			
			$getAttributeDetails = $this->nativeModel->getTaxAttributeDetails($value['attribute_id']);
			
			$modelOutput['searchResults'][$key]['totalAttributePercentage'] = $getAttributeDetails['attributeTotPercentage'];
		}
		
		// FRAME OUTPUT
        $outputData['results']      = $modelOutput;
        $outputData['status']       = "SUCCESS";
		
        $this->output->sendResponse($outputData);
    }

}
?>
