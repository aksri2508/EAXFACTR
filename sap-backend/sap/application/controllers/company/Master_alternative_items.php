<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Master_alternative_items.php
* @Class  			 : Master_alternative_items
* Model Name         : Master_alternative_items
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
class Master_alternative_items extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
		$this->config->load('table_config/tbl_master_alternative_items.php');
		$this->config->load('table_config/tbl_master_alternative_items_list.php');
        $this->load->model('company/master_alternative_items_model', 'nativeModel');
    }
	
	
	/**
	* @METHOD NAME 	: saveAlternativeItems()
	*
	* @DESC 		: TO SAVE THE ALTERNATIVE ITEMS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function saveAlternativeItems()
    {
        // Params from http request
        $this->checkRequestMethod("post"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$getData 		       = $this->currentRequestData;

		$modelOutput 	   = $this->nativeModel->saveAlternativeItems($getData);
			
		if (1 == $modelOutput['flag']) {
			$outputData['status']       = "SUCCESS";
			$outputData['message']      = lang('MSG_255'); // Successfully Inserted
		} else if (2 == $modelOutput['flag']) {
			$outputData['message']      = lang('GLB_009');  // Unable to save the record
		} else if (4 == $modelOutput['flag']) {
			$outputData['message'] = lang('MSG_268'); 		// Record Already Exists
		}
	
        $this->output->sendResponse($outputData);
    }
    
	
    /**
	* @METHOD NAME 	: updateAlternativeItems()
	*
	* @DESC 		: TO UPDATE THE ALTERNATIVE ITMES 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function updateAlternativeItems()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";
        
		$modelOutput = $this->nativeModel->updateAlternativeItems($this->currentRequestData);
		
		if (1 == $modelOutput['flag']) {
			$outputData['status']       = "SUCCESS";
			$outputData['message']      = lang('MSG_269'); //'Successfully updated
		} else if (2 == $modelOutput['flag']) {
			$outputData['message']      = lang('GLB_010'); // Unable to update the record
		} else if (4 == $modelOutput['flag'] || 3 == $modelOutput['flag']) {
			$outputData['message']      = lang('MSG_268'); // Record Already Exists
		}
		$this->output->sendResponse($outputData);
		
    }
	
	
	/**
	* @METHOD NAME 	: editAlternativeItems()
	*
	* @DESC 		: TO EDIT ALTERNATIVE ITMES DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function editAlternativeItems()
    {
        $this->checkRequestMethod("put"); 	// CHECK THE REQUEST METHOD
		$outputData['status']  = "FAILURE";
		
         // PARAMS FROM HTTP REQUEST
        if (!empty($this->currentRequestData['id']) && is_numeric($this->currentRequestData['id'])) {
			
            $modelOutput 	= $this->nativeModel->editAlternativeItems($this->currentRequestData);
			$getAltItemList = $this->nativeModel->editAlternativeItemsList($this->currentRequestData['id']);
			
            if (count($modelOutput) > 0) {
				$statusInfoDetails	= array();
				$getInfoData		= array(
										'getItemList'	=> $modelOutput[0]['item_id'],
									);
				
				$statusInfoDetails			= getAutoSuggestionListHelper($getInfoData);
				$result  					= array_merge($modelOutput[0],$statusInfoDetails);
				$result['altItemListArray'] = $this->frameAlternativeItemList($getAltItemList);
				
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
	* @METHOD NAME 	: frameAlternativeItemList()
	*
	* @DESC 		: TO FRAME THE ALTERNATIVE ITEM LIST 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function frameAlternativeItemList($itemList)
    {
		// BIND THE LIST SUB ARRAY 
		if(count($itemList)>0){

			foreach($itemList as $key => $value){
					$statusInfoDetails	= array();
					$getInfoData		= array(
											'getItemList~alternativeItemInfo'	=> $value['alt_item_id'],
										);
					
					$statusInfoDetails	= getAutoSuggestionListHelper($getInfoData);
					
					// Re-Assign Array 
					$itemList[$key] = array_merge($value,$statusInfoDetails);
			}
		}
		return $itemList;
	}
	
	
	/**
	* @METHOD NAME 	: getAlternativeItemsList()
	*
	* @DESC 		: TO GET THE ALTERNATIVE ITEMS LIST
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getAlternativeItemsList()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
        $modelOutput           = $this->nativeModel->getAlternativeItemsList($this->currentRequestData);
		
		// FRAME OUTPUT
        $outputData['results']      = $modelOutput;
        $outputData['status']       = "SUCCESS";
		
        $this->output->sendResponse($outputData);
    }
	
}
?>