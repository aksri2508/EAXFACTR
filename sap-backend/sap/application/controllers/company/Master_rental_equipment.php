<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Master_rental_equipment.php
* @Class  			 : Master_rental_equipment
* Model Name         : Master_rental_equipment
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 15 MAY 2021
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : -
* Features           : 
*/
class Master_rental_equipment extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->config->load('table_config/tbl_master_rental_equipment.php');
		$this->load->model('company/master_rental_equipment_model', 'nativeModel');
	}


	/**
	 * @METHOD NAME 	: saveRentalEquipment()
	 *
	 * @DESC 			: TO SAVE THE RENTAL EQUIPMENT DETAILS
	 * @RETURN VALUE 	: $outputdata array
	 * @PARAMETER 		: -
	 * @SERVICE      	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function saveRentalEquipment()
	{
		// Params from http request
		$this->checkRequestMethod("post"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$getData 		       = $this->currentRequestData;

		$modelOutput 	   = $this->nativeModel->saveRentalEquipment($getData);

		if (1 == $modelOutput['flag']) {
			$outputData['sId']      	= $modelOutput['sId'];
			$outputData['status']       = "SUCCESS";
			$outputData['message']      = lang('MSG_307'); // Successfully Inserted
		} else if (2 == $modelOutput['flag']) {
			$outputData['message']      = lang('GLB_009'); // Unable to save record.
		} else if (3 == $modelOutput['flag']) {
			$outputData['message']      = lang('MSG_308'); // Record Already Exists.
		}

		$this->output->sendResponse($outputData);
	}


	/**
	 * @METHOD NAME 	: updateRentaEquipment()
	 *
	 * @DESC 			: TO UPDATE THE RENTAL EQUIPMENT.
	 * @RETURN VALUE 	: $outputdata array
	 * @PARAMETER 		: -
	 * @SERVICE      	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function updateRentalEquipment()
	{
		$this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";

		$modelOutput = $this->nativeModel->updateRentaEquipment($this->currentRequestData);
		if (1 == $modelOutput['flag']) {
			$outputData['status']       = "SUCCESS";
			$outputData['message']      = lang('MSG_309'); //'Successfully Updated.
		} else if (2 == $modelOutput['flag']) {
			$outputData['message']      = lang('GLB_010'); // Unable to update the record
		} else if (3 == $modelOutput['flag']) {
			$outputData['message']      = lang('MSG_308'); // Record Already Exists
		}
		$this->output->sendResponse($outputData);
	}


	/**
	 * @METHOD NAME 	: editRentalEquipment()
	 *
	 * @DESC 			: TO EDIT RENTAL EQUIPMENT.
	 * @RETURN VALUE 	: $outputdata array
	 * @PARAMETER 		: -
	 * @SERVICE      	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function editRentalEquipment()
	{
		$this->checkRequestMethod("put"); 	// CHECK THE REQUEST METHOD
		$outputData['status']  = "FAILURE";

		// PARAMS FROM HTTP REQUEST
		if (!empty($this->currentRequestData['id']) && is_numeric($this->currentRequestData['id'])) {

			$modelOutput 	  = $this->nativeModel->editRentalEquipment($this->currentRequestData);
			
			if (count($modelOutput) > 0) {

				$passLastPriceDetails['itemId'] 	 = $this->currentRequestData['id'];

				// FRAME ALL THE INFO DATA
				$statusInfoDetails	= array();
				$getInfoData = array(
					'getRentalEquipmentStatusList' 	=> $modelOutput[0]['status'],
					'getRentalStatusList'		 	=> $modelOutput[0]['rental_status'],
					'getCreatedByDetails'			=> $modelOutput[0]['created_by'],
					'getRentalItemList'				=> $modelOutput[0]['rental_item_id'],
                    'getEquipmentOwnershipList'		=> $modelOutput[0]['ownership_id'],
					'getWarehouseList'				=> $modelOutput[0]['warehouse_id'],
					'getDocumentTypeList' 			=> $modelOutput[0]['document_type_id'],
					'getRentalEquipmentCategoryList' => $modelOutput[0]['equipment_category_id'],
					'getMeterReadingList' => $modelOutput[0]['meter_reading_id'],
					'getRentalMaintenancePriorityList' => $modelOutput[0]['maintenance_priority_id']	
				);
				
				// PASS SEARCH DATA 
				$passSearchData['category'] = 2;
				$passSearchData['delFlag']  = 0;
				
				// DOCUMENT NUMBER DETAILS 
				$passDocumenNumbertData['documentNoId']   	= $modelOutput[0]['document_id'];
				$passDocumenNumbertData['documentTypeId']   = $modelOutput[0]['document_type_id'];
				$documentNumberDetails   	  				= $this->commonModel->getDocumentNumber(array_merge($passSearchData,$passDocumenNumbertData));
				
				$statusInfoDetails					  	 = getAutoSuggestionListHelper($getInfoData);
				$statusInfoDetails['documentInfo']		 = $documentNumberDetails;
				
				
				$modelOutput[0]['rentalEquipmentImgUrl']		  = getFullImgUrl('rentalEquipmentPhoto', $modelOutput[0]['equipment_image']);
				$result  							  = array(array_merge($modelOutput[0], $statusInfoDetails));


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
	 * @METHOD NAME 	: getRentlEquipmentList()
	 *
	 * @DESC 			: TO GET THE RENTAL EQUIPMENT LIST DETAILS
	 * @RETURN VALUE 	: $outputdata array
	 * @PARAMETER 		: -
	 * @SERVICE      	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function getRentlEquipmentList()
	{
		$this->checkRequestMethod("put"); // Check the Request Method
		$modelOutput           = $this->nativeModel->getRentlEquipmentList($this->currentRequestData);

		// FRAME OUTPUT
		$outputData['results']      = $modelOutput;
		$outputData['status']       = "SUCCESS";

		$this->output->sendResponse($outputData);
	}


	/**
	 * @METHOD NAME 	: deleteRentalEquipment()
	 *
	 * @DESC 			: TO DELETE THE RENTAL EQUIPMENT. 
	 * @RETURN VALUE 	: $outputdata array
	 * @PARAMETER 		: -
	 * @SERVICE      	: WEB
	 * @ACCESS POINT	: -
	 **/
	public function deleteRentalEquipment()
	{
		$this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";

		if (!empty($this->currentRequestData['id']) && is_numeric($this->currentRequestData['id'])) {
			$modelOutput = $this->nativeModel->deleteRentalItem($this->currentRequestData);
			if (1 == $modelOutput['flag']) {
				$outputData['status']       = "SUCCESS";
				$outputData['message']      =  lang('MSG_310'); //'Successfully Deleted
			} else if (2 == $modelOutput['flag']) {
				$outputData['message']      = lang('GLB_011'); // Unable to delete. Please try again later.
			}
		} else {
			$outputData['message']      = lang('GLB_007'); // Invalid Paremeters
		}
		$this->output->sendResponse($outputData);
	}


	/**
	 * @METHOD NAME 	: rentalEquipmentImageUpload()
	 *
	 * @DESC 			: TO UPLOAD THE EQUIPMENT IMAGE
	 * @RETURN VALUE 	: $outputdata array
	 * @PARAMETER 		: -
	 * @SERVICE      	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function rentalEquipmentImageUpload()
	{
		$this->checkRequestMethod("post"); // Check the Request Method

		$outputData            = array();
		$outputData['status']  = "FAILURE";

		$tempFilePath	        = $_FILES['file']['tmp_name'];
		$newFileName           = date('YmdHis') . "_" . $_FILES['file']['name'];
		$newFileName            = preg_replace('/\s+/', '_', $newFileName);
		

		$config['upload_path']    = RENTAL_EQUIPMENT_PHOTO_UPLOAD_PATH;
		$config['allowed_types']  = RENTAL_EQUIPMENT_PHOTO_TYPES;
		// $config['max_size']       = ITEM_PHOTO_SIZE;
		// $config['max_width']      = ITEM_PHOTO_WIDTH;
		$config['encrypt_name']   = false;
		$config['file_name']      = $newFileName;
		// $config['remove_spaces']  = true;

		$this->load->library('upload', $config);

		$profilePicture = $this->upload->do_upload('file', $config);

		if ($profilePicture != false) {
			$outputData['status']   = "SUCCESS";
			$outputData['results']['fullUrl']  = RENTAL_EQUIPMENT_PHOTO_ACCESS_URL . '' . $newFileName;
			$outputData['results']['fileName'] = $newFileName;
		} else {
			$outputData['message']       = $this->upload->display_errors('', '');
		}
		$this->output->sendResponse($outputData);
	}


	/**
	 * @METHOD NAME 	: downloadExcel()
	 *
	 * @DESC 			: TO DOWNLOAD THE EXCEL FORMAT.
	 * @RETURN VALUE 	: $outputdata array
	 * @PARAMETER 		: -
	 * @SERVICE     	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function downloadExcel()
	{
		$modelOutput	= $this->nativeModel->getRentlEquipmentList($this->currentRequestData, 1);
		$resultsData 	= $modelOutput['searchResults'];
		$fileName		= $this->config->item('MASTER_RENTAL_EQUIPMENT')['excel_file_name'];

		// print_r($resultsData[0]);
		// exit;
		$outputData 	= processExcelData($resultsData, $fileName, $this->config->item('MASTER_RENTAL_EQUIPMENT')['columns_list']);

		$this->output->sendResponse($outputData);
	}
}