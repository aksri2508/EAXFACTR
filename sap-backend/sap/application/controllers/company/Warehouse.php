<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Warehouse.php
* @Class  			 : Warehouse
* Model Name         : Warehouse_model
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 26 ARR 2020
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : -
* Features           : 
*/
class Warehouse extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
		$this->config->load('table_config/tbl_warehouse.php');
        $this->load->model('company/warehouse_model', 'nativeModel');
    }
	
	
	/**
	* @METHOD NAME 	: saveWarehouse()
	*
	* @DESC 		: TO SAVE THE WAREHOUSE DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function saveWarehouse()
    {
        // Params from http request
        $this->checkRequestMethod("post"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$getData = $this->currentRequestData;

			$modelOutput  = $this->nativeModel->saveWarehouse($getData);
			
			if (1 == $modelOutput['flag']) {
				$outputData['status']       = "SUCCESS";
				$outputData['message']      = lang('MSG_168'); // Successfully Inserted
				$outputData['insert_id']    = $modelOutput['insert_id'];
			} else if (2 == $modelOutput['flag']) {
				$outputData['message']      = lang('MSG_169'); // WAREHOUSE CODE ALREDY EXISTS
			} else if (3 == $modelOutput['flag']) {
				$outputData['message']      = lang('MSG_351'); // WAREHOUSE NAME ALREADY EXISTS 
			}
		
        $this->output->sendResponse($outputData);
    }
    
	
	/**
	* @METHOD NAME 	: updateWarehouse()
	*
	* @DESC 		: TO UPDATE THE WAREHOUSE
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function updateWarehouse()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";
	
		$modelOutput = $this->nativeModel->updateWarehouse($this->currentRequestData);
		
	
		if (1 == $modelOutput['flag']) {
			$outputData['status']       = "SUCCESS";
			$outputData['message']      = lang('MSG_170'); //'Successfully saved
		}else if (2 == $modelOutput['flag']) {
			$outputData['message']      = lang('MSG_169'); // Record Already Exists
		}else if (3 == $modelOutput['flag']) {
			$outputData['message']      = lang('MSG_351'); // Record Already Exists
		}

        $this->output->sendResponse($outputData);
    }
	
	
	/**
	* @METHOD NAME 	: editWarehouse()
	*
	* @DESC 		: TO EDIT WAREHOUSE DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function editWarehouse()
    {
        $this->checkRequestMethod("put"); 	// CHECK THE REQUEST METHOD
		$outputData['status']  = "FAILURE";
        
         // PARAMS FROM HTTP REQUEST
        if (!empty($this->currentRequestData['id']) && is_numeric($this->currentRequestData['id'])) {
            
            $modelOutput = $this->nativeModel->editWarehouse($this->currentRequestData);
            

            if (count($modelOutput) > 0) {
				
					// Branch Info Details 
				$binId  		= $modelOutput[0]['bin_id'];
				$binDetails  = array();
				$binDetailsInfo = array();
				
				if(!empty($binId)){
					$binDetails  = explode(",",$binId);
					$cnt = 0;
					foreach($binDetails as $binKey => $binValue){
						$passBinInfoData['id']  = $binValue;
						
						$statusInfoDetails	= array();
						$getInfoData		= array(	
														'getBinList' 	=> $binValue,
													);
						$statusInfoDetails	 = getAutoSuggestionListHelper($getInfoData);
						
						//printr($statusInfoDetails);
						
						if(count($statusInfoDetails['binInfo'])>0){
							$binDetailsInfo[$cnt] =  $statusInfoDetails['binInfo'][0];
							$cnt++;
						}	
						
						
					}
				}
				$modelOutput[0]['binInfo'] 	= $binDetailsInfo;
				
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
	* @METHOD NAME 	: getWarehouseList()
	*
	* @DESC 		: TO GET THE WAREHOUSE LIST DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getWarehouseList()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
        $modelOutput   = $this->nativeModel->getWarehouseList($this->currentRequestData);
		
		// FRAME OUTPUT
        $outputData['results']      = $modelOutput;
        $outputData['status']       = "SUCCESS";
		
        $this->output->sendResponse($outputData);
    }

}
?>