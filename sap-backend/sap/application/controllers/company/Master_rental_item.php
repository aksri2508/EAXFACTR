<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Master_rental_item.php
* @Class  			 : Master_rental_item
* Model Name         : Master_rental_item
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 01 MAY 2021
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : -
* Features           : 
*/
class Master_rental_item extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->config->load('table_config/tbl_master_rental_item.php');
		$this->load->model('company/master_rental_item_model', 'nativeModel');
	}


	/**
	 * @METHOD NAME 	: saveRentalItem()
	 *
	 * @DESC 			: TO SAVE THE RENTAL ITEM DETAILS
	 * @RETURN VALUE 	: $outputdata array
	 * @PARAMETER 		: -
	 * @SERVICE      	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function saveRentalItem()
	{
		// Params from http request
		$this->checkRequestMethod("post"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$getData 		       = $this->currentRequestData;

		$modelOutput 	   = $this->nativeModel->saveRentalItem($getData);

		if (1 == $modelOutput['flag']) {
			$outputData['sId']      	= $modelOutput['sId'];
			$outputData['status']       = "SUCCESS";
			$outputData['message']      = lang('MSG_299'); // Successfully Inserted
		} else if (2 == $modelOutput['flag']) {
			$outputData['message']      = lang('GLB_009'); // Unable to save record.
		} else if (3 == $modelOutput['flag']) {
			$outputData['message']      = lang('MSG_300'); // Record Already Exists.
		}

		$this->output->sendResponse($outputData);
	}


	/**
	 * @METHOD NAME 	: updateRentalItem()
	 *
	 * @DESC 			: TO UPDATE THE RENTAL ITEM.
	 * @RETURN VALUE 	: $outputdata array
	 * @PARAMETER 		: -
	 * @SERVICE      	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function updateRentalItem()
	{
		$this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";

		$modelOutput = $this->nativeModel->updateRentalItem($this->currentRequestData);
		if (1 == $modelOutput['flag']) {
			$outputData['status']       = "SUCCESS";
			$outputData['message']      = lang('MSG_301'); //'Successfully saved
		} else if (2 == $modelOutput['flag']) {
			$outputData['message']      = lang('GLB_010'); // Unable to update the record
		} else if (3 == $modelOutput['flag']) {
			$outputData['message']      = lang('MSG_300'); // Record Already Exists
		}
		$this->output->sendResponse($outputData);
	}


	/**
	 * @METHOD NAME 	: editRentalItem()
	 *
	 * @DESC 			: TO EDIT ITEM RENTAL DETAILS
	 * @RETURN VALUE 	: $outputdata array
	 * @PARAMETER 		: -
	 * @SERVICE      	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function editRentalItem()
	{
		$this->checkRequestMethod("put"); 	// CHECK THE REQUEST METHOD
		$outputData['status']  = "FAILURE";

		// PARAMS FROM HTTP REQUEST
		if (!empty($this->currentRequestData['id']) && is_numeric($this->currentRequestData['id'])) {

			$modelOutput 	  = $this->nativeModel->editRentalItem($this->currentRequestData);

			if (count($modelOutput) > 0) {

				// FRAME ALL THE INFO DATA
				$statusInfoDetails	= array();
				$getInfoData = array(
					'getCommonStatusList' 		=> $modelOutput[0]['status'],
					'getUomList' 	 			=> $modelOutput[0]['uom_id'],
					'getItemGroupList' 	 		=> $modelOutput[0]['item_group_id'],
					'getCreatedByDetails'		=> $modelOutput[0]['created_by'],
					'getHsnList'				=> $modelOutput[0]['hsn_id'],
				);

				$statusInfoDetails					  = getAutoSuggestionListHelper($getInfoData);
				$modelOutput[0]['itemImgUrl']		  = getFullImgUrl('rentalItemPhoto', $modelOutput[0]['rental_item_image']);
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
	 * @METHOD NAME 	: getRentlItemList()
	 *
	 * @DESC 			: TO GET THE RENTAL ITEM LIST DETAILS
	 * @RETURN VALUE 	: $outputdata array
	 * @PARAMETER 		: -
	 * @SERVICE      	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function getRentlItemList()
	{
		$this->checkRequestMethod("put"); // Check the Request Method
		$modelOutput           = $this->nativeModel->getRentlItemList($this->currentRequestData);

		// FRAME OUTPUT
		$outputData['results']      = $modelOutput;
		$outputData['status']       = "SUCCESS";

		$this->output->sendResponse($outputData);
	}


	/**
	 * @METHOD NAME 	: deleteRentalItem()
	 *
	 * @DESC 			: TO DELETE THE RENTAL ITEM. 
	 * @RETURN VALUE 	: $outputdata array
	 * @PARAMETER 		: -
	 * @SERVICE      	: WEB
	 * @ACCESS POINT	: -
	 **/
	public function deleteRentalItem()
	{
		$this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";

		if (!empty($this->currentRequestData['id']) && is_numeric($this->currentRequestData['id'])) {
			$modelOutput = $this->nativeModel->deleteRentalItem($this->currentRequestData);
			if (1 == $modelOutput['flag']) {
				$outputData['status']       = "SUCCESS";
				$outputData['message']      =  lang('MSG_302'); //'Successfully Deleted
			} else if (2 == $modelOutput['flag']) {
				$outputData['message']      = lang('GLB_011'); // Unable to delete. Please try again later.
			}
		} else {
			$outputData['message']      = lang('GLB_007'); // Invalid Paremeters
		}
		$this->output->sendResponse($outputData);
	}


	/**
	 * @METHOD NAME 	: rentalItemImageUpload()
	 *
	 * @DESC 			: TO UPLOAD THE ITEM IMAGE
	 * @RETURN VALUE 	: $outputdata array
	 * @PARAMETER 		: -
	 * @SERVICE      	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function rentalItemImageUpload()
	{
		$this->checkRequestMethod("post"); // Check the Request Method

		$outputData            = array();
		$outputData['status']  = "FAILURE";

		$tempFilePath	        = $_FILES['file']['tmp_name'];
		$newFileName           = date('YmdHis') . "_" . $_FILES['file']['name'];
		$newFileName            = preg_replace('/\s+/', '_', $newFileName);
		

		$config['upload_path']    = RENTAL_ITEM_PHOTO_UPLOAD_PATH;
		$config['allowed_types']  = RENTAL_ITEM_PHOTO_TYPES;
		// $config['max_size']       = ITEM_PHOTO_SIZE;
		// $config['max_width']      = ITEM_PHOTO_WIDTH;
		$config['encrypt_name']   = false;
		$config['file_name']      = $newFileName;
		// $config['remove_spaces']  = true;

		$this->load->library('upload', $config);

		$profilePicture = $this->upload->do_upload('file', $config);

		if ($profilePicture != false) {
			$outputData['status']   = "SUCCESS";
			$outputData['results']['fullUrl']  = RENTAL_ITEM_PHOTO_ACCESS_URL . '' . $newFileName;
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
		$modelOutput	= $this->nativeModel->getRentlItemList($this->currentRequestData, 1);
		$resultsData 	= $modelOutput['searchResults'];
		$fileName		= $this->config->item('MASTER_RENTAL_ITEM')['excel_file_name'];

		// print_r($resultsData[0]);
		// exit;
		$outputData 	= processExcelData($resultsData, $fileName, $this->config->item('MASTER_RENTAL_ITEM')['columns_list']);

		$this->output->sendResponse($outputData);
	}
}