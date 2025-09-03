<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Master_vehicle.php
* @Class  			 : Master_vehicle
* Model Name         : Master_vehicle_model
* Description        :
* Module             : company/Master_vehicle
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 05 JUNE 2024
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : -
* Features           : 
*/
class Master_vehicle extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
		$this->config->load('table_config/tbl_master_vehicle.php');
        $this->load->model('company/Master_vehicle_model', 'nativeModel');
    }
	
	
	/**
	* @METHOD NAME 	: saveVehicle()
	*
	* @DESC 		: TO SAVE THE MASTER VEHICLE DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function saveVehicle()
    {
        // Params from http request
        $this->checkRequestMethod("post"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$getPostData 		       = $this->currentRequestData;
		//$getPostData['vendorBpId'] = $getPostData['id'];
		if(isset($getPostData['vendorBpId']) && $getPostData['vendorBpId'] != ""){

			if(isset($getPostData['vehicleListArray']) && $getPostData['vehicleListArray'] != ""){

				$modelOutput =  $this->nativeModel->saveAllVehicle($getPostData);
	
				if (1 == $modelOutput['flag']) {
					$outputData['sId']      	= $modelOutput['sId'];
					$outputData['status']       = "SUCCESS";
					$outputData['message']      = lang('MSG_358');  // Successfully Inserted
				} else if (2 == $modelOutput['flag']) {
					$outputData['message']      = lang('GLB_009');  // Unable to save the record
				}
			}
			else{
				$outputData['message']      = lang('GLB_007'); // INVALID PARAMETERS
				$outputData['status'] = 'FAILURE';
			}
			
		}
		else{
			$outputData['message']      = lang('GLB_007'); // INVALID PARAMETERS
			$outputData['status'] = 'FAILURE';
		}

        $this->output->sendResponse($outputData);

    }



	/**
	* @METHOD NAME 	: updateVehicle()
	*
	* @DESC 		: TO UPDATE THE MASTER VEHICLE DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function updateVehicle()
    {
        // Params from http request
        $this->checkRequestMethod("put"); // Check the Request Method.
		$outputData['status']  = "FAILURE";
		$getPostData = $this->currentRequestData;
		//$getPostData['vendorBpId'] = $getPostData['id'];
		if(isset($getPostData['vendorBpId']) && $getPostData['vendorBpId'] != ""){
	
			if(isset($getPostData['vehicleListArray']) && $getPostData['vehicleListArray'] != ""){

				$modelOutput =  $this->nativeModel->updateAllVehicle($getPostData);
	
				if (1 == $modelOutput['flag']) {
					$outputData['sId']      	= $modelOutput['insertId'];
					$outputData['status']       = "SUCCESS";
					$outputData['message']      = lang('MSG_359');  // Successfully Updated.
				} else if (2 == $modelOutput['flag']) {
					$outputData['message']      = lang('GLB_009');  // Unable to save the record
				}
			}
			else{
				$outputData['message']      = lang('GLB_007'); // INVALID PARAMETERS
				$outputData['status'] = 'FAILURE';
			}
			
		}
		else{
			$outputData['message']      = lang('GLB_007'); // INVALID PARAMETERS
			$outputData['status'] = 'FAILURE';
		}

        $this->output->sendResponse($outputData);

    }
	

	/**
	* @METHOD NAME 	: editVehicle()
	*
	* @DESC 		: TO EDIT VEHICLE DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function editVehicle()
    {
        $this->checkRequestMethod("put"); 	// CHECK THE REQUEST METHOD
		$outputData['status']  = "FAILURE";
		$getPostData			   = $this->currentRequestData;
        //$getPostData['vendorBpId'] = $getPostData['id'];
		
         // PARAMS FROM HTTP REQUEST
        if (!empty($getPostData['id']) && is_numeric($getPostData['id'])) {
            
            $modelOutput['vehicleListArray'] = $this->nativeModel->editVehicle($getPostData);
			$outputData['status']       = "SUCCESS";
			$outputData['results']      = $modelOutput;

			if(isset($modelOutput['vehicleListArray'][0]['vendor_bp_id']))
			{
				$frameEditDetails 	   = $this->frameVehicleEditDetails($modelOutput);
				$data 				   = array();
				$data				   = $frameEditDetails;
				$data['vendorBpId']	   =  $getPostData['id'];
				$outputData['results'] = $data;
			}

        } else { 
            $outputData['message']      = lang('GLB_007'); // INVALID PARAMETERS
        }	
        $this->output->sendResponse($outputData);
    }


	/**
	* @METHOD NAME 	: frameVehicleEditDetails()
	*
	* @DESC 		: TO FRAME THE VEHICLE EDIT DETAILS 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function frameVehicleEditDetails($modelOutput)
    {
		// FRAME ALL THE INFO DATA
		$statusInfoDetails	= array();
		$getInfoData = array(	
			'getBusinessPartnerList~vendorBpInfo'	 => $modelOutput['vehicleListArray'][0]['vendor_bp_id'],
		);
		
		$statusInfoDetails	= getAutoSuggestionListHelper($getInfoData);
		$result  			= array_merge($modelOutput,$statusInfoDetails);
		return $result;
	}

	
	/**
	* @METHOD NAME 	: getVehicleList()
	*
	* @DESC 		: TO GET THE VEHICLE LIST DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function getVehicleList()
    {
		$this->checkRequestMethod("put"); 	// CHECK THE REQUEST METHOD
		$result = $this->nativeModel->getVehicleList($this->currentRequestData);
		$outputData['status']  = "SUCCESS";		
		$outputData['results'] =  $result;
		$this->output->sendResponse($outputData);
	}

   
}
