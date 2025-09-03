<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Master_document_numbering.php
* @Class  			 : Master_document_numbering
* Model Name         : Master_document_numbering
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
class Master_document_numbering extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
		$this->config->load('table_config/tbl_master_document_numbering.php');
        $this->load->model('company/master_document_numbering_model', 'nativeModel');
    }
	
	
	/**
	* @METHOD NAME 	: saveDocumentNumbering()
	*
	* @DESC 		: TO SAVE THE DOCUMENT NUMBERING
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function saveDocumentNumbering()
    {
        // Params from http request
        $this->checkRequestMethod("post"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$getData 		       = $this->currentRequestData;

			$modelOutput 	   = $this->nativeModel->saveDocumentNumbering($getData);
			
			if (1 == $modelOutput['flag']) {
				$outputData['status']       = "SUCCESS";
				$outputData['message']      = lang('MSG_281'); // SUCCESSFULLY SAVED
			} else if (2 == $modelOutput['flag']) {
				$outputData['message']      = lang('MSG_282'); // Record Already Exists
			}
			else if (3 == $modelOutput['flag']) {
				$outputData['message']      = lang('MSG_288'); // Data Validataion checks.
			}
			else if (4 == $modelOutput['flag']) {
				$outputData['message']      = lang('MSG_289'); // Data seris exist, Fails.
			}
		
        $this->output->sendResponse($outputData);
    }
	
	
	/**
	* @METHOD NAME 	: updateDocumentNumbering()
	*
	* @DESC 		: TO UPDATE THE DOCUMENT NUMBERING 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function updateDocumentNumbering()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";
        
		$modelOutput = $this->nativeModel->updateDocumentNumbering($this->currentRequestData);
		if (1 == $modelOutput['flag']) {
			$outputData['status']       = "SUCCESS";
			$outputData['message']      = lang('MSG_283'); // SUCCESSFULLY UPDATED
		}else if (2 == $modelOutput['flag']) {
			$outputData['message']      = lang('MSG_282'); // RECORD ALREADY EXISTS
		}
		else if (3 == $modelOutput['flag']) {
			$outputData['message']      = lang('MSG_288'); // RECORD DATA CONFLICTS
		}
        $this->output->sendResponse($outputData);
    }
	
    
	/**
	* @METHOD NAME 	: editDocumentNumbering()
	*
	* @DESC 		: TO EDIT MASTER PRICE LIST 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function editDocumentNumbering()
    {
        $this->checkRequestMethod("put"); 	// CHECK THE REQUEST METHOD
		$outputData['status']  = "FAILURE";
        
         // PARAMS FROM HTTP REQUEST
        if (!empty($this->currentRequestData['id']) && is_numeric($this->currentRequestData['id'])) {
            
            $modelOutput = $this->nativeModel->editDocumentNumbering($this->currentRequestData);
           
			if (count($modelOutput) > 0) {
				
				// FRAME ALL THE INFO DATA
				$statusInfoDetails	= array();
				
				$getInfoData = array(	
					'getBranchList' 					=> $modelOutput[0]['branch_id'],
					'getDocumentTypeList~screenInfo' 	=> $modelOutput[0]['document_type_id'],
				);
				
				$statusInfoDetails	= getAutoSuggestionListHelper($getInfoData);
				$result  			= array(array_merge($modelOutput[0],$statusInfoDetails));
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
	* @METHOD NAME 	: getDocumentNumberingList()
	*
	* @DESC 		: TO GET THE DOCUMENT NUMBERING List
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getDocumentNumberingList()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
        $modelOutput           = $this->nativeModel->getDocumentNumberingList($this->currentRequestData);
		
		// FRAME OUTPUT
        $outputData['results']      = $modelOutput;
        $outputData['status']       = "SUCCESS";
		
        $this->output->sendResponse($outputData);
    }
	
	/**
	* @METHOD NAME 	: getAnalyticsDetails()
	*
	* @DESC 		: TO GET THE ANALYTICS DETAILS FOR ACTIVITY
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getAnalyticsDetails()
    {
		$this->checkRequestMethod("get"); 	// CHECK THE REQUEST METHOD
		$outputData['status']  = "SUCCESS";
		
		// ACTIVITY STATUS DETAILS
		$docNumberingLockStatus= array( 'Locked' => 1 , 'un-Locked' => '0');
		
		$cnt = 0 ;
		foreach($docNumberingLockStatus as $key => $value){
			$totalValue = $this->nativeModel->getAnalyticsCount($value);
			$analyticsData[$cnt]['name'] 	= $key;
			//$analyticsData[$cnt]['id'] 		= $value['id'];
			$analyticsData[$cnt]['count']	= $totalValue;
			$cnt++;
		}
		
		$analyticsData[$cnt]['name'] 	= 'All';
		//$analyticsData[$cnt]['id'] 	= 0;
		$analyticsData[$cnt]['count']	= $this->nativeModel->getAnalyticsCount('');
		
		$outputData['results']     				= $analyticsData;
		$this->output->sendResponse($outputData);
	}
	
}
?>