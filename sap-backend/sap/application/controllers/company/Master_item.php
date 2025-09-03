<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Master_item.php
* @Class  			 : Master_item
* Model Name         : Master_item
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
class Master_item extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
		$this->config->load('table_config/tbl_master_item.php');
		$this->config->load('table_config/tbl_item_warehouse.php');
        $this->load->model('company/master_item_model', 'nativeModel');
    }
	
	
	/**
	* @METHOD NAME 	: saveItem()
	*
	* @DESC 		: TO SAVE THE ITEM DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function saveItem()
    {
        // Params from http request
        $this->checkRequestMethod("post"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$getData 		       = $this->currentRequestData;

			$modelOutput 	   = $this->nativeModel->saveItem($getData);
			
			if (1 == $modelOutput['flag']) {
				$outputData['sId']      	= $modelOutput['sId'];
				$outputData['status']       = "SUCCESS";
				$outputData['message']      = lang('MSG_21'); // Successfully Inserted
			} else if (2 == $modelOutput['flag']) {
				$outputData['message']      = lang('MSG_22'); // Record Already Exists
			} else if (5 == $modelOutput['flag']) {
				$outputData['message'] = lang('MSG_286'); 	// Fails due to next number limit.
			} else if (6 == $modelOutput['flag']) {
				$outputData['message'] = lang('MSG_287'); 	// Document Num Exist - Manual Type.
			} else if (7 == $modelOutput['flag']) {
				$outputData['message'] = lang('MSG_291'); 	// Issue with document number.
			}
		
        $this->output->sendResponse($outputData);
    }
    
	
	/**
	* @METHOD NAME 	: saveItemWarehouse()
	*
	* @DESC 		: TO SAVE THE ITEM WAREHOUSE 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function saveItemWarehouse()
    {
        $this->checkRequestMethod("post"); // Check the Request Method
		$outputData['status']  = "FAILURE";
		$getData 		       = $this->currentRequestData;
		
			$modelOutput 	   = $this->nativeModel->saveItemWarehouse($getData);

			if (1 == $modelOutput['flag']) {
				$outputData['status']       = "SUCCESS";
				$outputData['message']      = lang('MSG_217');  // Successfully Inserted
			} else if (2 == $modelOutput['flag']) {
				$outputData['message']      = lang('GLB_009');  // Unable to save the record
			}
        $this->output->sendResponse($outputData);
    }
	
	
	/**
	* @METHOD NAME 	: updateItem()
	*
	* @DESC 		: TO UPDATE THE ITEM
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function updateItem()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";
        
		$modelOutput = $this->nativeModel->updateItem($this->currentRequestData);
		if (1 == $modelOutput['flag']) {
			$outputData['status']       = "SUCCESS";
			$outputData['message']      = lang('MSG_23'); //'Successfully saved
		}else if (2 == $modelOutput['flag']) {
			$outputData['message']      = lang('GLB_010'); // Unable to update the record
		}else if (3 == $modelOutput['flag']) {
			$outputData['message']      = lang('MSG_22'); // Record Already Exists
		}
        $this->output->sendResponse($outputData);
    }
	
	
	/**
	* @METHOD NAME 	: updateItemWarehouse()
	*
	* @DESC 		: TO UPDATE THE WAREHOUSE DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function updateItemWarehouse()
    {
        // Params from http request
        $this->checkRequestMethod("put"); // Check the Request Method
		
			$getData 		   = $this->currentRequestData;
			$modelOutput 	   = $this->nativeModel->updateItemWarehouse($getData);

			if (1 == $modelOutput['flag']) {
				$outputData['status']       = "SUCCESS";
				$outputData['message']      = lang('MSG_219');  // UPDATE THE BUSINESS PARTNER CONTACTS 
			} 
        $this->output->sendResponse($outputData);
	}
	
	
	/**
	* @METHOD NAME 	: deleteItemWarehouse()
	*
	* @DESC 		: TO DELETE THE ITEM WAREHOUSE 
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function deleteItemWarehouse()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
		$outputData['status']  = "FAILURE";
        
        if (!empty($this->currentRequestData['id']) && is_numeric($this->currentRequestData['id'])) {
            $modelOutput = $this->nativeModel->deleteItemWarehouse($this->currentRequestData);
            if (1 == $modelOutput['flag'] ) {
                $outputData['status']       = "SUCCESS";
                $outputData['message']      =  lang('MSG_218'); //'Successfully Deleted
            } else if (2 == $modelOutput['flag'] ) {
                $outputData['message']      = lang('GLB_011'); // Unable to delete. Please try again later.
            }
        } else {
            $outputData['message']      = lang('GLB_007'); // Invalid Paremeters
        }
        $this->output->sendResponse($outputData);
    }
	
	
	/**
	* @METHOD NAME 	: editItem()
	*
	* @DESC 		: TO EDIT ITEM DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function editItem()
    {
        $this->checkRequestMethod("put"); 	// CHECK THE REQUEST METHOD
		$outputData['status']  = "FAILURE";
        
         // PARAMS FROM HTTP REQUEST
        if (!empty($this->currentRequestData['id']) && is_numeric($this->currentRequestData['id'])) {
            
            $modelOutput 	  = $this->nativeModel->editItem($this->currentRequestData);
			
            if (count($modelOutput) > 0) {
				$getWarehouseList			= $this->nativeModel->getWarehouseList($this->currentRequestData);
				
				// Assigning Empty values for records not exists in master item price list 
				$modelOutput[0]['unitPrice'] 	= 0;
				$modelOutput[0]['priceListId'] 	= 0;
				
				$passLastPriceDetails['itemId'] 			= $this->currentRequestData['id'];
				$passLastPriceDetails['lastPriceListId'] 	=  $modelOutput[0]['last_price_list_id'];
				$getUnitPriceDetails						= $this->nativeModel->getUnitPriceByLastPriceListId($passLastPriceDetails);
				
				//printr($getUnitPriceDetails); exit;
				
				// FRAME ALL THE INFO DATA
				$statusInfoDetails	= array();
				
				$getInfoData = array(	
					'getCommonStatusList' 		=> $modelOutput[0]['status'],
					'getUomList' 	 			=> $modelOutput[0]['uom_id'],
					'getItemGroupList' 	 		=> $modelOutput[0]['item_group_id'],
					'getHsnList' 	 			=> $modelOutput[0]['hsn_id'],
					'getManufacturerList' 	 	=> $modelOutput[0]['manufacturer_id'],
					'getItemTransactionTypeList' => $modelOutput[0]['item_transaction_type'],
					'getCreatedByDetails~createdByInfo'				 => $modelOutput[0]['created_by'],
					'getPriceList~priceListInfo'					 => $modelOutput[0]['last_price_list_id'],
					'getDocumentNumberingList~documentNumberingInfo' => $modelOutput[0]['document_numbering_id'],
				);
				
				$statusInfoDetails					  = getAutoSuggestionListHelper($getInfoData);
				$modelOutput[0]['itemImgUrl']		  = getFullImgUrl('itemphoto',$modelOutput[0]['item_image']);
				$modelOutput[0]['warehouseListArray'] = $getWarehouseList;
				$modelOutput[0]['unitPrice'] 		  = isset($getUnitPriceDetails[0]['unit_price']) ? 
				$getUnitPriceDetails[0]['unit_price'] : 0 ;
				$modelOutput[0]['priceListId'] 		  = isset($getUnitPriceDetails[0]['price_list_id']) ? 
				$getUnitPriceDetails[0]['price_list_id'] : 0;
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
	* @METHOD NAME 	: getItemList()
	*
	* @DESC 		: TO GET THE ITEM LIST DETAILS
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
    public function getItemList()
    {
        $this->checkRequestMethod("put"); // Check the Request Method
        $modelOutput           = $this->nativeModel->getItemList($this->currentRequestData);
		
		// FRAME OUTPUT
        $outputData['results']      = $modelOutput;
        $outputData['status']       = "SUCCESS";
		
        $this->output->sendResponse($outputData);
    }
	
	
	/**
	* @METHOD NAME 	: downloadExcel()
	*
	* @DESC 		: TO DOWNLOAD THE EXCEL FORMAT
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
   public function downloadExcel()
   {
		$modelOutput	= $this->nativeModel->getItemList($this->currentRequestData,1);
		$resultsData 	= $modelOutput['searchResults'];
		$fileName		= $this->config->item('MASTER_ITEM')['excel_file_name'];

		$outputData 	= processExcelData($resultsData,$fileName,$this->config->item('MASTER_ITEM')['columns_list']);
		$this->output->sendResponse($outputData);
    }
	
	
	/**
	* @METHOD NAME 	: itemImageUpload()
	*
	* @DESC 		: TO UPLOAD THE ITEM IMAGE
	* @RETURN VALUE : $outputdata array
	* @PARAMETER 	: -
	* @SERVICE      : WEB
	* @ACCESS POINT : -
	**/
	public function itemImageUpload() {
		 $this->checkRequestMethod("post"); // Check the Request Method
		
		 $outputData            = array();
		 $outputData['status']  = "FAILURE";
		 
		 $tempFilePath	        = $_FILES['file']['tmp_name'];
		 $newFileName           = date('YmdHis')."_".$_FILES['file']['name'];
		 $newFileName            = preg_replace('/\s+/', '_', $newFileName);	
		 

		 $config['upload_path']    = ITEM_PHOTO_UPLOAD_PATH;
		 $config['allowed_types']  = ITEM_PHOTO_TYPES;
		// $config['max_size']       = ITEM_PHOTO_SIZE;
		// $config['max_width']      = ITEM_PHOTO_WIDTH;
		 $config['encrypt_name']   = false;
		 $config['file_name']      = $newFileName;
		// $config['remove_spaces']  = true;
		
		 $this->load->library('upload',$config);

		 $profilePicture = $this->upload->do_upload('file', $config);

		if($profilePicture != false) {
			 $outputData['status']   = "SUCCESS";
			 $outputData['results']['fullUrl']  = ITEM_PHOTO_ACCESS_URL.''.$newFileName;
			 $outputData['results']['fileName'] = $newFileName;
			 
		}
		else {
			 $outputData['message']       = $this->upload->display_errors('','');
		}
		$this->output->sendResponse($outputData);
   }
   
}
