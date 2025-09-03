<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 ** Helper Name : RENTAL INVOICE HELPER FOR RENTAL TRANSACTIONS
 ** Description : -
 ** Module   	: NA
 **	Actors 	  	: - 
 **	Features 	: - 
 */
/********************************TRANSACTION FUNCIOTNALITY STARTS ********************/
///////////////////////////////////////////// RENTAL INVOICE PROCESS ////////////////////////////////////////////////////////////
/**
 * @METHOD NAME 	: processRentalInvoice()
 *
 * @DESC 			:   1. FUNCTION WILL BE CALLED DIRECTLY FROM RENTAL INVOICE SCREEN.
						2. THIS IS THE ENTRY POINT FOR RENTAL INVOICE 
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function processRentalInvoice($oldRecords = '', $newRecords){
	
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
				$frameFinalArray[$finalCnt]['oldQuantityValue'] = 0; // NOT USED FOR SAVE 
				$frameFinalArray[$finalCnt]['newQuantityValue'] = 0; // NOT USED FOR SAVE 
				$frameFinalArray[$finalCnt]['newArrivedValue']  = $newItemValue['quantity'];
				$frameFinalArray[$finalCnt]['isUtilized']		= 1; // isUtilized
				$frameFinalArray[$finalCnt]['operation'] 		= 'UPDATE';
				$finalCnt++;
			}
			$finalListArray  = $frameFinalArray;
	}else {
		checkRecordUpdateCondtion($oldRecords, $newRecords);
		$finalListArray  = rentalInvoicetransCompareItemRecords($oldRecords, $newRecords);
	}
	
	//echo "IN UPDATE OPERATION";	printr($finalListArray);	exit;
	
	// FINAL OPERATION FOR BOTH SAVE / UPDATE 
	if (!empty($finalListArray)) {
			$oldStatus  = $oldRecords['status'];
			$newStatus  = $newRecords['status'];

			// TABLE DETAILS 
			$copyFromType		= $finalListArray[0]['copyFromType'];
			$screenDetails		= $CI->config->item('SCREEN_NAMES')[$copyFromType];
			$childTableName		= $screenDetails['childTableName'];
			$fieldName			= $screenDetails['childRefId'];
			
			// CF-1: UPDATE THE SOURCE CHILD TABLE UTILLIZED STATUS: FUNCTION INHERITED FROM RENTAL HELPER
			updateSourceChildTableUtilizedStatus($childTableName,$fieldName,$finalListArray);
			
			// CF-2: 
			updateSourceChildTableOrderedQuantity($childTableName, $finalListArray, $oldStatus, $newStatus);
			
			// CF-3:
			updateSourceChildTableOpenQuantity($childTableName,$fieldName,$finalListArray);
			
			// CF-4:
			updateSourceParentTableStatusUsingOpenQuantity($finalListArray);
	}
	
	// ALL: UPDATE THE RENTAL WORK LOG STATUS EVEN FOR UPDATE / SAVE 
	  updateRentalWorkLogStatus($storeOldRecords,$storeNewRecords);
}


/**
 * @METHOD NAME 	: updateRentalWorkLogStatus()
 *
 * @DESC 			: 1. TO UPDATE THE RENTAL WORK LOG STATUS BASED UPON THE RENTAL INVOICE STATUS 
					  2. FOR SAVE: IF THE WORKLOG_ID IS USED WE CAN MAKE THE WORK LOG STATUS IS CLOSED.
					  3. FOR UPDATE: ALWAYS COMPARE THE STATUS OF THE INVOICE AND DO THE MANIPULATION ACCORDINGLY.
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function updateRentalWorkLogStatus($oldRecords, $newRecords)
{
	$CI = &get_instance();
	
	$newItemListArray 		 = $newRecords['itemListArray'];
	$deletedItemChildIds	 = $newRecords['deletedItemChildIds'];
	
	if (empty($oldRecords)) {  // FOR SAVE OPERATION 
		
		foreach ($newItemListArray as $getItemKey => $getItemValue) {
			if(!empty($getItemValue['rentalWorklogId'])) {
				$worklogId 	 = $getItemValue['rentalWorklogId'];
				$status  	 = 2;
				$CI->commonModel->updateWorkLogStatus($worklogId, $status);
			}
		}
	} else { // UPDATE OPERATION 
	
		$tblItemListArray 		 = $oldRecords['itemListArray'];
		
		// UPDATE MANIPULATION OPERATION 
		foreach ($newItemListArray as $getItemKey => $newItemValue) {
			$getTblItemResult = "";
			
			// STATUS - 1 || 2 -> NO MANIPULATION 
			
			// STATUS - 3 [CANCELLED]
			if($newRecords['status']==3){ // STATUS ->3 CANCELLED 
			echo "STatus is ".$newRecords['status'];
				if (!empty($newItemValue['id'])) {
					$id 			  = $newItemValue['id'];
					$getTblItemResult = findTransArrayValue($id, $tblItemListArray);
					
					if (!empty($getTblItemResult)) { // OPEN THE WORKLOG 
						if(!empty($newItemValue['rentalWorklogId'])) {
							$worklogId 	 = $newItemValue['rentalWorklogId'];
							$status  	 = 1; 
							echo "Change worklot status to 1";
							$CI->commonModel->updateWorkLogStatus($worklogId, $status);
						}
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
				if (!empty($getTblItemResult)) { // OPEN THE WORKLOG 
						if(!empty($newItemValue['rentalWorklogId'])) {
						$worklogId 	 = $newItemValue['rentalWorklogId'];
						$status  	 = 1; 
						$CI->commonModel->updateWorkLogStatus($worklogId, $status);
					}
				}
			}
		}
	}
}


/**
 * @METHOD NAME 	: updateSourceChildTableOrderedQuantity()
 *
 * @DESC 			: 1. Update Ordered Quanity in the source items table by looping all the data.
					  2. Formula (ordered_quantity + or - $arrivedValue)
					  3. $openQualityCalc = 'ordered_quantity'+$arrivedValue;
					  4. OldStatus and NewStatus always refers to current(Destination) table records.
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function updateSourceChildTableOrderedQuantity($tableName, $finalListArray, $oldStatus, $newStatus)
{
	$CI = &get_instance();
	/*
	echo "<pre>";printr($finalListArray);echo "</pre>";
	echo "TABLE NAME is :: ".$tableName;
	echo "oldStatus is :: ".$oldStatus;
	echo "newStatus is :: ".$newStatus;
	exit;
	*/
	
	// FLAG DETAILS 
	$updateFlag  = "";
	$deleteFlag  = "MINUS";

	// STATUS BASED FORMULA 
	if ($oldStatus == 1 && ($newStatus == 1 ||  $newStatus == 2)) {  // OPEN && OPEN~CLOSED
		$updateFlag  = 'PLUS';
	}

	if ($oldStatus == 1 && ($newStatus == 3)) {  // OPEN && CANCELLED
		$updateFlag  = 'MINUS';
	}

	// UPDATE: ORDERED QUANITY 
	foreach ($finalListArray as $finalKey => $finalValue) {

		$id 			  = $finalValue['copyFromId'];
		$operation 		  = $finalValue['operation'];
		$arrivedValue 	  = $finalValue['newArrivedValue'];

		if ($operation == "UPDATE") {
			if ($updateFlag == "PLUS") {
				$symbol = '+';
			} else if ($updateFlag == "MINUS") {
				$symbol = '-';
			}
		} else if ($operation == "DELETE") {
			if ($deleteFlag == "MINUS") {
				$symbol = '-';
			}
		}

		$data			= array();
		$whereQry 		= array('id' => $id);
		$orderedQualityCalc = 'ordered_quantity' . $symbol . $arrivedValue;
		$CI->app_db->set('ordered_quantity', $orderedQualityCalc, false);
		$CI->app_db->set('updated_on', 'NOW()', false);
		$CI->app_db->set('updated_by', $CI->currentUserId);
		$CI->app_db->update($tableName, $data, $whereQry);
		$affectedRows = $CI->app_db->affected_rows();
	}
}


/**
 * @METHOD NAME     : updateSourceChildTableOpenQuantity()
 *
 * @DESC 			: 1. We are updating all the rows by using reference id (grpo_id) in source item table. 
					  2. Apply open quanity formula [open_quantity = quantity-ordered_quantity]
					  
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function updateSourceChildTableOpenQuantity($tableName,$fieldName, $itemListArray)
{	

	/*
	echo "Table name is ::".$tableName;
	echo "Table name is ::".$fieldName;
	exit;
	*/
	$CI = &get_instance();
	$itemListArray = removeEmptyCopyIdRecords($itemListArray);
	
	if (count($itemListArray) > 0) {
		
			$sourceParentTableIds = getUniqueSourceParentTableIdByRefColumn($itemListArray);
			$copyFromId   				= $itemListArray[0]['copyFromId'];
			$copyFromType 				= $itemListArray[0]['copyFromType'];
			$screenDetails				= $CI->config->item('SCREEN_NAMES')[$copyFromType];
			$sourceParentTableName	 	= $screenDetails['tableName'];
			$sourceChildTableName		= $screenDetails['childTableName'];
			$sourceChildRefColumn		= $screenDetails['childRefId'];
			$data						= array();
			
			foreach($sourceParentTableIds as $key => $sourceParentTableId){
				$openQuantityFormula  = 'quantity-ordered_quantity';
				$CI->app_db->where($fieldName, $sourceParentTableId);	
				$CI->app_db->set('open_quantity', $openQuantityFormula, false);
				$CI->app_db->set('updated_on', 'NOW()', false);
				$CI->app_db->set('updated_by', $CI->currentUserId);
				$CI->app_db->update($tableName, $data);
			}
	}
}


/**
 * @METHOD NAME 	: updateSourceParentTableStatusUsingOpenQuantity()
 *
 * @DESC 			: 1. GET THE UNIQUE PARENT IDS AND UPDATE THE STATUS (open,closed)
					  2. BASED UPON THE OPEN QUANTITY CHEKCING IN THE SOURCE CHILD TABLE
					  
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function updateSourceParentTableStatusUsingOpenQuantity($itemListArray)
{
	$CI = &get_instance();

	if (count($itemListArray) > 0) {

		$itemListArray = removeEmptyCopyIdRecords($itemListArray);

		if(count($itemListArray) > 0 ) {
			
				$sourceParentTableIds = getUniqueSourceParentTableIdByRefColumn($itemListArray);
				
				$copyFromId   				= $itemListArray[0]['copyFromId'];
				$copyFromType 				= $itemListArray[0]['copyFromType'];
				$screenDetails				= $CI->config->item('SCREEN_NAMES')[$copyFromType];
				$sourceParentTableName	 	= $screenDetails['tableName'];
				$sourceChildTableName		= $screenDetails['childTableName'];
				$sourceChildRefColumn		= $screenDetails['childRefId'];
				
				foreach($sourceParentTableIds as $key => $sourceParentTableId){
					// CHECK THE OPEN QUANTITY RECORD EXISTS 
					$chkOpenQuantityRecord = $CI->commonModel->checkOpenQuantityRecords($sourceChildTableName, $sourceChildRefColumn, $sourceParentTableId);

					if ($chkOpenQuantityRecord == 1) { // UPDATE PARENT TABLE STATUS
						$updateArray = array('status' => 2); // CLOSED
						$CI->commonModel->updateTransParentTableStatus($sourceParentTableName, $updateArray, $sourceParentTableId);
					}else{ 
						$updateArray = array('status' => 1); // OPEN
						$CI->commonModel->updateTransParentTableStatus($sourceParentTableName, $updateArray, $sourceParentTableId);
					}
				}
		}
	}
}


/**
 * @METHOD NAME 	: rentalInvoicetransCompareItemRecords()
 *
 * @DESC 			: TO COMPARE ITEM RECORDS FOR RENTAL INVOICE
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function rentalInvoicetransCompareItemRecords($oldRecords, $newRecords)
{
	$tblItemListArray 		 = removeEmptyCopyIdRecords($oldRecords['itemListArray']);
	$newItemListArray 		 = removeEmptyCopyIdRecords($newRecords['itemListArray']);
	$deletedItemChildIds	 = $newRecords['deletedItemChildIds'];

	$frameFinalArray		= array();
	$finalCnt 				= 0;

	// UPDATE MANIPULATION OPERATION 
	foreach ($newItemListArray as $getItemKey => $newItemValue) {
		$getTblItemResult = "";

		// GENERAL LOGIC TO HANDLE REMAINING QUANITY CHECKUP
		
		if($newRecords['status']==1 || $newRecords['status']==2){ // STATUS -> open or closed
		
			if (!empty($newItemValue['id'])) {
				$id 			  = $newItemValue['id'];
				$getTblItemResult = findTransArrayValue($id, $tblItemListArray);

				// QUANTITY MUST NOT BE EQUAL			
				if (
					!empty($getTblItemResult) &&
					$getTblItemResult['quantity'] != $newItemValue['quantity']
				) {
					$quantityRes  = $newItemValue['quantity'] - $getTblItemResult['quantity'];				
					$frameFinalArray[$finalCnt]['rentalItemId']		= $newItemValue['rentalItemId'];
					$frameFinalArray[$finalCnt]['copyFromId']		= $newItemValue['copyFromId'];
					$frameFinalArray[$finalCnt]['copyFromType']		= $newItemValue['copyFromType'];
					$frameFinalArray[$finalCnt]['oldQuantityValue'] = $getTblItemResult['quantity'];
					$frameFinalArray[$finalCnt]['newQuantityValue'] = $newItemValue['quantity'];
					$frameFinalArray[$finalCnt]['newArrivedValue']  = $quantityRes;
					$frameFinalArray[$finalCnt]['operation'] 		= 'UPDATE';
					$frameFinalArray[$finalCnt]['isUtilized']		= 1; // manaully put 1  FIXME
					$finalCnt++;
				}
			}
		}
		
		// STATUS - 3 [CANCELLED]
		if($newRecords['status']==3){ // STATUS ->3 CANCELLED 
			if (!empty($newItemValue['id'])) {
				$id 			  = $newItemValue['id'];
				$getTblItemResult = findTransArrayValue($id, $tblItemListArray);

				if (!empty($getTblItemResult)) {
					$quantityRes  = $getTblItemResult['quantity'] - 0;
					$frameFinalArray[$finalCnt]['rentalItemId']		= $newItemValue['rentalItemId'];
					$frameFinalArray[$finalCnt]['copyFromId']		= $newItemValue['copyFromId'];
					$frameFinalArray[$finalCnt]['copyFromType']		= $newItemValue['copyFromType'];
					$frameFinalArray[$finalCnt]['oldQuantityValue'] = $getTblItemResult['quantity'];
					$frameFinalArray[$finalCnt]['newQuantityValue'] = 0;
					$frameFinalArray[$finalCnt]['newArrivedValue']  = $quantityRes;
					$frameFinalArray[$finalCnt]['isUtilized']		= 0; 
					$frameFinalArray[$finalCnt]['operation'] 		= 'DELETE';
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
				$quantityRes  = $getTblItemResult['quantity'] - 0;
				$frameFinalArray[$finalCnt]['rentalItemId']		= $newItemValue['rentalItemId'];
				$frameFinalArray[$finalCnt]['copyFromId']		= $getTblItemResult['copyFromId'];
				$frameFinalArray[$finalCnt]['copyFromType']		= $getTblItemResult['copyFromType'];
				$frameFinalArray[$finalCnt]['oldQuantityValue'] = $getTblItemResult['quantity'];
				$frameFinalArray[$finalCnt]['newQuantityValue'] = 0;
				$frameFinalArray[$finalCnt]['newArrivedValue']  = $quantityRes;
				$frameFinalArray[$finalCnt]['isUtilized']		= 0; 
				$frameFinalArray[$finalCnt]['operation'] 		= 'DELETE';
				$finalCnt++;
			}
		}
	}
	//echo "FINAL ARRARY :: <pre>";print_r($frameFinalArray);echo "</pre>"; //exit;
	return $frameFinalArray;
}

/******************************** END OF RENTAL INVOICE PROCESS ********************/