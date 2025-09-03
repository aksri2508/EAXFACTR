<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 ** Helper Name : RENTAL HELPER FOR RENTAL TRANSACTIONS
 ** Description : -
 ** Module   	: NA
 **	Actors 	  	:
 **	Features 	: - 
 */
/********************************TRANSACTION FUNCIOTNALITY STARTS ********************/
/**
 * @METHOD NAME 	: F1: processRentalItems()
 *
 * @DESC 			: TO PROCESS THE RENTAL ITEMS THIS IS THE ENTRY POINT OF THE FUNCITONALITY 
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function processRentalItems($oldRecords = '', $newRecords,$currentScreenTableName) {

	//printr($newRecords);
	
	// CONVERTING TO NEW RECORDS 
	if(!empty($oldRecords)){
		toCamelCase($oldRecords);
	}
	
	// CONVERTING TO OLD RECORDS
	if(!empty($newRecords)){
		toCamelCase($newRecords);
	}
	
	// MAINTAIN THE OLD DATA 
	$storeOldRecords = $oldRecords;
	$storeNewRecords = $newRecords;
	
	$CI = &get_instance();
	
	// CHECK THE TRANSACTION PROCESS TO CONTINUE
	$continueFlag  = checkToContinueProcess($oldRecords,$newRecords,$currentScreenTableName);
	
	// CHECK AND UPDATE EQUIPMENT STATUS 
	operateRentalEquipmentRentalStatus($storeOldRecords,$newRecords,$currentScreenTableName);
	
	if($continueFlag == 1){
		
		if (empty($oldRecords)) { 	// SAVE OPERATION
	
			$oldRecords = array();
						
			$newItemListArray 		 = $newRecords['itemListArray'];
			$frameFinalArray 	 	 = array();
			$finalCnt 				 = 0;
			$oldRecords['status'] 	 = 1;
			$newItemListArray 		 = removeEmptyCopyIdRecords($newItemListArray);

			foreach ($newItemListArray as $getItemKey => $newItemValue) {
				$frameFinalArray[$finalCnt]['copyFromId']		= $newItemValue['copyFromId'];
				$frameFinalArray[$finalCnt]['copyFromType']		= $newItemValue['copyFromType'];
				$frameFinalArray[$finalCnt]['isUtilized']		= 1; // isUtilized
				$finalCnt++;
			}
			$finalListArray  = $frameFinalArray;
		} else {  // FOR UPDATE OPERATION 
			preventDocCancellingWhileUtilized($currentScreenTableName,$newRecords);
			checkRecordUpdateCondtion($oldRecords, $newRecords);
			$finalListArray  = compareRentalItemRecords($oldRecords, $newRecords);
		}
		
		//printr($finalListArray);
		
		// FINAL OPERATION 
		if (!empty($finalListArray)) {
			$oldStatus  = $oldRecords['status'];
			$newStatus  = $newRecords['status'];

			// TABLE DETAILS 
			$copyFromType		= $finalListArray[0]['copyFromType'];
			$screenDetails		= $CI->config->item('SCREEN_NAMES')[$copyFromType];
			$childTableName		= $screenDetails['childTableName'];
			$fieldName			= $screenDetails['childRefId'];
			updateSourceChildTableUtilizedStatus($childTableName,$fieldName,$finalListArray);
			updateSourceParentTableStatus($finalListArray,$currentScreenTableName,$copyFromType);
		}
		
			
	}
}

/**
 * @METHOD NAME 	: checkToContinueProcess()
 *
 * @DESC 			: CHECK THE TRANSACTION PROCESS TO PROCEED. 
					  FOR EXAMPLE SOME OF THE SCREENS NOT REQUIRED FOR THE TRANSACTION. 
					  WE CAN ADD MULTIPLE CONDTIONS TO PREVENT THE FLOW OF EXECUTION.
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
 function checkToContinueProcess($oldRecords = '', $newRecords, $currentScreenTableName)
 {
	 $CI = &get_instance();

	 if (empty($oldRecords)) { // SAVE OPERATION
		$itemListArray 		= $newRecords['itemListArray'];
	 }else{
		$itemListArray 	= $oldRecords['itemListArray'];
	 }
 
	 $newItemListArray 		 = removeEmptyCopyIdRecords($itemListArray);

	 if(count($newItemListArray) > 0) {
		//$copyFromType		 = $newItemListArray[0]['copyFromType'];
	
		if(($currentScreenTableName == 'RENTAL_INVOICE')){ 
			return false;
		}else{
			return true;
		}
	 }else{
			return true;
	 }
 }


/**
 * @METHOD NAME 	: updateSourceChildTableUtilizedStatus()
 *
 * @DESC 			: 1. TO UPDATE THE SOURCE CHILD TABLE IS UTILIZED STATUS. 
					  2. THE LOOP ITERATES ALL "COPY_FROM_ID" WHICH ALSO POINTS TO SINGLE TABLE. (EXAMPLE rental_order_items) 
					  3. NO NEED TO CONSIDER ABOUT MULTIPLE PARENT IDS.
					  
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function updateSourceChildTableUtilizedStatus($sourceChildTableName,$sourcefieldName, $itemListArray)
{	
	//printr($itemListArray);exit;
	$CI = &get_instance();
	$data = array();
	
	foreach($itemListArray as $itemKey => $itemValue) {
		$fromId 		= $itemValue['copyFromId'];
		$isUtilized 	= $itemValue['isUtilized'];
		$CI->commonModel->updateSourceChildTableUtilizedStatus($sourceChildTableName, $fromId, $isUtilized);
	}
}


/**
 * @METHOD NAME 	: updateSourceParentTableStatus()
 *
 * @DESC 			: 1. UPDATE THE STATUS TO SOURCE PARENT TABLE WHERE THE DATA IS COPIED FROM. 
					  2. FOR EXAMPLE : FOR RENTAL QUOTE THE PARENT IS RENTAL ORDER.
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function updateSourceParentTableStatus($itemListArray,$currentScreenName,$copyFromType)
{
	$CI = &get_instance();

	if($copyFromType!='RENTAL_ORDER') {

		if (count($itemListArray) > 0) {

			$itemListArray = removeEmptyCopyIdRecords($itemListArray);
			
			if(count($itemListArray) > 0){
				
					$sourceParentTableIds = getUniqueSourceParentTableIdByRefColumn($itemListArray);
		
					$copyFromId    = $itemListArray[0]['copyFromId'];
					$copyFromType  = $itemListArray[0]['copyFromType'];
					$screenDetails	= $CI->config->item('SCREEN_NAMES')[$copyFromType];

					$sourceParentTableName 	= $screenDetails['tableName'];
					$sourceChildTableName	= $screenDetails['childTableName'];
					$sourceChildRefColumn	= $screenDetails['childRefId'];
					
				foreach($sourceParentTableIds as $key => $sourceParentTableId){
					
					// CHECK THE OPEN QUANTITY RECORD EXISTS 
					$chkOpenUtilizedRecord = $CI->commonModel->checkAvailableUtilizedRecordsExists($sourceChildTableName, $sourceChildRefColumn, $sourceParentTableId);
									
					if($chkOpenUtilizedRecord==1){ // UPDATE PARENT TABLE STATUS TO CLOSE 
						$updateArray = array('status' => 2); // CLOSED
					}else{
						$updateArray = array('status' => 1); // OPEN
					}
					$CI->commonModel->updateTransParentTableStatus($sourceParentTableName, $updateArray, $sourceParentTableId);
				}
			}
		}
	}
}


/**
 * @METHOD NAME 	: preventDocCancellingWhileUtilized()
 *
 * @DESC 			: PREVENT CURRENT DOCUMENT CANCELLING WHEN IT IS ALREADY UTILIZED
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function preventDocCancellingWhileUtilized($currentScreenName,$newRecords)
{
	$CI 				= &get_instance();
	$screenDetails		= $CI->config->item('SCREEN_NAMES')[$currentScreenName];
	$parentTableName 	= $screenDetails['tableName'];
	$childTableName		= $screenDetails['childTableName'];
	$fieldName			= $screenDetails['childRefId'];		
	$newStatus			= $newRecords['status'];
	
	if($newStatus==3){
		$parentTableId 				= $newRecords['id'];
		$chkItemUtilizedRecord 		= $CI->commonModel->checkItemInUtilizedStatus($childTableName, $fieldName, $parentTableId);
			
		if($chkItemUtilizedRecord==2){
			echo json_encode(array(
				'status' 		=> 'ERROR',
				'message' 		=> 'Cannot update the status to cancelled because it is referenced to another transactions!',
				"responseCode" 	=> 200
			));
			exit();
		}
	}
}


/**
 * @METHOD NAME 	: checkRecordUpdateCondtion()
 *
 * @DESC 			: CHECK THE CURRENT RECORD STATUS. IF THE STATUS IS 2 OR 3 PREVENT THE USER TO UPDATE THE RECORD.
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function checkRecordUpdateCondtion($oldTblRecords,$reqParamRecords)
{
	$oldStatus	= $oldTblRecords['status'];
	$newStatus	= $reqParamRecords['status'];
	
	if($oldStatus==2 || $oldStatus==3 ){  // CLOSED | CANCELLED
		echo json_encode(array(
			'status' 		=> 'ERROR',
			'message' 		=> 'Cannot update Closed or Cancelled Records!',
			"responseCode" 	=> 200
		));
		exit();
	}
}


/**
 * @METHOD NAME 	: compareRentalItemRecords()
 *
 * @DESC 			: 1. FRAME THE UTILIZED FLAG BASED UPON STATUS OF THE CURRENT RECORD.
					  2. THIS FUNCTION IS USED TO COMPARE THE EXISTING SOTRED TABLE RECORD TO CURRENTLY MODIFIED RECORD.
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function compareRentalItemRecords($oldRecords, $newRecords)
{
	$tblItemListArray 		 = removeEmptyCopyIdRecords($oldRecords['itemListArray']);
	$newItemListArray 		 = removeEmptyCopyIdRecords($newRecords['itemListArray']);
	$deletedItemChildIds	 = $newRecords['deletedItemChildIds'];

	$frameFinalArray		= array();
	$finalCnt 				= 0;

	// UPDATE MANIPULATION OPERATION 
	foreach ($newItemListArray as $getItemKey => $newItemValue) {
		$getTblItemResult = "";
		
		// STATUS - 1 || 2 -> NO MANIPULATION 
		
		// STATUS - 3 [CANCELLED]
		if($newRecords['status']==3){ // STATUS ->3 CANCELLED 
			if (!empty($newItemValue['id'])) {
				$id 			  = $newItemValue['id'];
				$getTblItemResult = findTransArrayValue($id, $tblItemListArray);

				if (!empty($getTblItemResult)) {
					//$frameFinalArray[$finalCnt]['rentalItemId']		= $newItemValue['rentalItemId'];
					$frameFinalArray[$finalCnt]['copyFromId']		= $newItemValue['copyFromId'];
					$frameFinalArray[$finalCnt]['copyFromType']		= $newItemValue['copyFromType'];
					$frameFinalArray[$finalCnt]['isUtilized']		= 0; 
					$finalCnt++;
				}
			}
		}
	}

	// DELETE MANIPULATION OPERATION 
	if (count($deletedItemChildIds) > 0) { // Child values
		foreach ($deletedItemChildIds as $deletedKey => $deletedValue) {
			$getTblItemResult = "";

			$id = $deletedValue;
			$getTblItemResult = findTransArrayValue($id, $tblItemListArray);

			// COPY FROM ID MUST BE EXISTS 
			if (!empty($getTblItemResult)) {
					//$frameFinalArray[$finalCnt]['rentalItemId']		= $newItemValue['rentalItemId'];
					$frameFinalArray[$finalCnt]['copyFromId']		= $newItemValue['copyFromId'];
					$frameFinalArray[$finalCnt]['copyFromType']		= $newItemValue['copyFromType'];
					$frameFinalArray[$finalCnt]['isUtilized']		= 0; // isUtilized
					$finalCnt++;
			}
		}
	}
	return $frameFinalArray;
}


/**
 * @METHOD NAME 	: operateRentalEquipmentRentalStatus()
 *
 * @DESC 			: TO UPDATE THE RENTAL EQUIPMENT RENTAL_STATUS  
					  ALL ITEM LIST ARE NEEDS TO BE MANIPULATED including DIRECT AND COPY FROM RECORDS
					   
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function operateRentalEquipmentRentalStatus($tableRecords = '', $reqParamRecords,$currentScreenTableName)
{
	$CI = &get_instance();

	$screenDetails		= $CI->config->item('SCREEN_NAMES')[$currentScreenTableName];
	$parentTableName 	= $screenDetails['tableName'];
	$childTableName		= $screenDetails['childTableName'];
	$fieldName			= $screenDetails['childRefId'];
	
	$itemListArray 			= $reqParamRecords['itemListArray'];
	$deletedItemChildIds	= $reqParamRecords['deletedItemChildIds'];
	
	$equipmentFlag  	= '';
	
	// ASSIGNING THE VARIABLES FOR MANIPULATION 
	$newStatus = $reqParamRecords['status'];
	if(empty($tableRecords)) { // FOR FIRST TIME SAVE OPERATION ALWAYS STATUS IS 1
		$oldStatus = 1 ; 
	}else {
		$oldStatus = $tableRecords['status'];
	}
		
	// NEW DISCUSSED FORMULA 
	if (($oldStatus == 1 &&  $newStatus == 3)) {  // OPEN  && CANCELLED
		$equipmentFlag  = 'PREVIOUS_RENTAL_STATUS';
	}
	

	if(empty($tableRecords)){
		$processData = 'SAVE';
	}else {
		$processData = 'UPDATE';
	}
	
	foreach($itemListArray as $itemKey => $itemValue) {
		$equipmentId 				= $itemValue['rentalEquipmentId'];
		
		if(!empty($equipmentId)) {
			$copyFromType 				= $itemValue['copyFromType'];
			
			if(empty($copyFromType)){
				$copyFromType = 'DIRECT';
			}
			
			if($processData == 'SAVE') { // ALWAYS STATUS IS OPEN 
				// UPDATE THE RENTAL EQUIPMENT RENTAL STATUS
				updateRentalEquipmentRentalStatus($equipmentId,$currentScreenTableName,$copyFromType,1);
			}else if($processData == 'UPDATE'){
				if(!empty($equipmentFlag) && !empty($itemValue['id'])){
					if($equipmentFlag == 'PREVIOUS_RENTAL_STATUS') {
						// UPDATE THE RENTAL EQUIPMENT RENTAL STATUS
						updateRentalEquipmentRentalStatus($equipmentId,$currentScreenTableName,$copyFromType,2);
					}
				}else if(empty($itemValue['id']) && $newStatus == 1){ // ONLY FOR OPEN STATUS AND ADDING NEW RECORDS
					// UPDATE THE RENTAL EQUIPMENT RENTAL STATUS
					updateRentalEquipmentRentalStatus($equipmentId,$currentScreenTableName,$copyFromType,1);
				}
			}
		}
	}

	
	// DELETE MANIPULATION OPERATION 
	if (count($deletedItemChildIds) > 0) { // Child values
		$tblItemListArray 		 = $tableRecords['itemListArray'];
		foreach ($deletedItemChildIds as $deletedKey => $deletedValue) {
			$getTblItemResult = "";

			$id = $deletedValue;
			$getTblItemResult 	= findTransArrayValue($id, $tblItemListArray);
			
			if (!empty($getTblItemResult)) {
				
				// RE-DECLARE VARIABLES 
				$copyFromType 		= $itemValue['copyFromType'];
				$equipmentId		= $getTblItemResult['rentalEquipmentId'];
				
				if(!empty($equipmentId)) { 
					
					if(empty($copyFromType)){
						$copyFromType = 'DIRECT';
					}
					
					// UPDATE THE RENTAL EQUIPMENT RENTAL STATUS
					updateRentalEquipmentRentalStatus($equipmentId,$currentScreenTableName,$copyFromType,2);
				}	
			}
		}
	}
	// exit;
}


/**
 * @METHOD NAME     : updateRentalEquipmentRentalStatus()
 *
 * @DESC 			: 1. TO UPDATE THE RENTAL EQUIPMENT TABLE RENTAL STATUS COLUMN
  * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function updateRentalEquipmentRentalStatus($equipmentId,$currentScreenTableName,$copyFromType,$rentalStepFlag){
	
	$CI = &get_instance();
	
	$getEquipmentRentalStatus 	= $CI->commonModel->getcurrentEquipmentRentalStatus($equipmentId);
	$rentalStatus				= $getEquipmentRentalStatus[0]['rental_status'];
	$equipmentName 				= $getEquipmentRentalStatus[0]['equipment_name'];

	// EQUIPMENT RENTAL STATUS
	$rentalStatusName				  = $CI->config->item('equipmentRentalStatus')[$rentalStatus];
			
	$chkRentalEquipmentStatusByScreen = $CI->commonModel->getEquipmentRentalStatusByScreen($currentScreenTableName,$copyFromType,$rentalStatusName,$rentalStepFlag);
					
	if($chkRentalEquipmentStatusByScreen['recordExistsFlag'] == 1) {  // PROCEED FOR UPDATE
		$rentalEquipmentResult  = $chkRentalEquipmentStatusByScreen['results'][0];
		
		if($rentalStepFlag == 1){
			$rentalStatusName		= $rentalEquipmentResult['to_status']; // SAVE Operation -> FORWARD
		}else if ($rentalStepFlag == 2){
			$rentalStatusName		= $rentalEquipmentResult['from_status']; // DELETE OPERATION -> BACKWARD
		}
		
		$rentalStatusId 		= array_search($rentalStatusName,$CI->config->item('equipmentRentalStatus'));
		
		$CI->commonModel->updateTblRentalEquipmentRentalStatus($equipmentId,$rentalStatusId);
		
	}else {
			echo json_encode(array(
				'status' 		=> 'FAILURE',
				'message' 		=> $equipmentName.' is Used in Another Transaction',
				"responseCode" 	=> 200
			));
			exit();
	}
}


/**
 * @METHOD NAME     : updateCurrentItemTableOpenQuantity()
 *
 * @DESC 			: 1. CALCULATE AND UPDATE THE CURRENT ITEM TABLE OPEN QUANTITY 
					  2. Function direclty invoked in Rental Transaction Controller
					  3. FORMULA open quantity = quantity - ordered quantity 
  * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function updateCurrentItemTableOpenQuantity($tableName, $fieldName, $whereId,$postData,$currentScreenTableName)
{	
	if(($currentScreenTableName == 'RENTAL_ORDER') || ($currentScreenTableName == 'RENTAL_INVOICE')) {
		$CI = &get_instance();
		$data = array();

		if (!empty($whereId)) { // DIRECT CALL
			$openQuantityFormula  = 'quantity-ordered_quantity';
			$CI->app_db->where($fieldName, $whereId);	
			$CI->app_db->set('open_quantity', $openQuantityFormula, false);
			$CI->app_db->set('updated_on', 'NOW()', false);
			$CI->app_db->set('updated_by', $CI->currentUserId);
			$CI->app_db->update($tableName, $data);
		}
	}
}


/**
 * @METHOD NAME     : updateCurrentItemTableStatus()
 *
 * @DESC 			: 1. UPDATE THE STATUS (1,2 OR 3) BY JUST COPING THE PARENT STATUS TO CHILD STATUS DIRECLTY 
					  2. EXAMPLE from  RENTAL ORDER to RENTAL ORDER ITEMS 
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function updateCurrentItemTableStatus($getPostData,$screenName){
	$CI 			  	= &get_instance();
	$screenDetails		= $CI->config->item('SCREEN_NAMES')[$screenName];
	$parentTableName 	= $screenDetails['tableName'];
	$childTableName		= $screenDetails['childTableName'];
	$fieldName			= $screenDetails['childRefId'];
	
	$id					 = $getPostData['id'];
	$getParentTableRs 	 = $CI->commonModel->getParentTableStatus($id,$parentTableName);
	$status 		  	 = $getParentTableRs[0]['status'];
	$updateChildTableResult	 = $CI->commonModel->updateStatusToChildTable($id,$status,$childTableName,$fieldName);
}


/**
 * @METHOD NAME     : updateRentalEquipmentDocumentDetails()
 *
 * @DESC 			: 1. UPDATE THE RENTAL EQUIPMENT DOCUMENT DETAILS (document_id, document_type_id)
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function updateRentalEquipmentDocumentDetails($tableRecords = '',$reqParamRecords,$screenName,$insertId)
{
	
	$CI 			  	= &get_instance();
	$screenDetails		= $CI->config->item('SCREEN_NAMES')[$screenName];
	$parentTableName 	= $screenDetails['tableName'];
	$childTableName		= $screenDetails['childTableName'];
	$fieldName			= $screenDetails['childRefId'];
	$documentTypeId 	= $screenDetails['id'];
	$documentId			= $insertId;
	
	// ITEM LIST ARRAY
	$itemListArray 			= $reqParamRecords['itemListArray'];
	$deletedItemChildIds	= $reqParamRecords['deletedItemChildIds'];

	if(empty($tableRecords)){
		$processData = 'SAVE';
	}else {
		toCamelCase($tableRecords);
		$processData = 'UPDATE';
	}
	
	if(!empty($insertId)){
		foreach($itemListArray as $itemKey => $itemValue) {
			$equipmentId 				= $itemValue['rentalEquipmentId'];
			if(!empty($equipmentId)) {
				if($processData == 'SAVE') { // SAVE 
						$CI->commonModel->updateTblRentalEquipmentDocumentStatus($equipmentId,$documentTypeId,$documentId);
				} else if ($processData == 'UPDATE'){ // UPDATE 
					if(empty($itemValue['id'])){ // ONLY FOR NEW RECORDS
						$CI->commonModel->updateTblRentalEquipmentDocumentStatus($equipmentId,$documentTypeId,$documentId);
					}
				}
			}
		}
	}
	
	// DELETE MANIPULATION OPERATION 
	if (count($deletedItemChildIds) > 0) { // Child values
		$tblItemListArray 		 = $tableRecords['itemListArray'];
		foreach ($deletedItemChildIds as $deletedKey => $deletedValue) {
			$getTblItemResult = "";

			$id = $deletedValue;
			$getTblItemResult = findTransArrayValue($id, $tblItemListArray);

			// COPY FROM ID MUST BE EXISTS 
			if (!empty($getTblItemResult)) {
				$equipmentId		= $getTblItemResult['rentalEquipmentId'];
				$CI->commonModel->updateTblRentalEquipmentDocumentStatus($equipmentId,'','');
			}
		}
	}

	
}

/******************************** END OF TRANSACTION FUNCIOTNALITY STARTS ********************/