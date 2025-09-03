<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 ** Helper Name : SPI Helper
 ** Description : -
 ** Module   	: NA
 **	Actors 	  	:
 **	Features 	: - 
 */
/******************************** TRANSACTION FUNCIOTNALITY STARTS ****************************************/
/**
 * @METHOD NAME 	: preventCurrentDocStatusChangeToCancel()
 *
 * @DESC 			: PREVENT CURRENT DOCUMENT CANCELLING WHEN IT IS ALREADY REFRENCED TO ANOTHER TRANSACTION
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function preventCurrentDocStatusChangeToCancel($currentScreenName,$newRecords)
{
	$CI 				= &get_instance();
	$screenDetails		= $CI->config->item('SCREEN_NAMES')[$currentScreenName];
	$parentTableName 	= $screenDetails['tableName'];
	$childTableName		= $screenDetails['childTableName'];
	$fieldName			= $screenDetails['childRefId'];		
	$newStatus			= $newRecords['status'];
	
	if($newStatus==3){
		$parentTableId 				= $newRecords['id'];
		$chkOrderedQuantityRecord 	= $CI->commonModel->checkOrderedQuantityRecords($childTableName, $fieldName, $parentTableId);
			
		if($chkOrderedQuantityRecord==2){
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
 * @METHOD NAME 	: reduceDownPaymentAmount()
 *
 * @DESC 			: Reduce the downpayment amount once utilized in invoice or credit memo
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function reduceDownPaymentAmount($oldRecords = '', $newRecords,$currentScreenTableName)
{
	//printr($oldRecords);printr($newRecords);echo "Current screen Table name ".$currentScreenTableName;
	
	$CI = &get_instance();
	
	/* // Condition commented : SALES_AR_CREDIT_MEMO not reducing the downpayment.
	if(($currentScreenTableName == 'SALES_AR_INVOICE') || 
	   ($currentScreenTableName == 'SALES_AR_CREDIT_MEMO')){
	*/ 

	if(($currentScreenTableName == 'SALES_AR_INVOICE')){
		
		$screenDetails		= $CI->config->item('SCREEN_NAMES')['SALES_AR_DP_INVOICE'];
		$parentTableName 	= $screenDetails['tableName'];
		
		// ALWAYS NEW RECORD APPEARS
		$parentTableId 	 			 = $newRecords['salesArDpInvoiceId'];
		$salesArDpInvoiceUsedAmount  = $newRecords['salesArDpInvoiceUsedAmount']; // USED AMOUNT
		
		// GET THE DP INVOICE DETAILS
		$passData['id'] 	= $parentTableId;
		$dpInvoiceDetails 	= $CI->commonModel->getDpInvoiceDocumentNumber($passData);
		
		
		// DP INVOICE DETAILS 
		if(count($dpInvoiceDetails)>0){
			$tbldpRemainingAmount		 = $dpInvoiceDetails[0]['remaining_amount'];
			$tbldpTotalAmount			 = $dpInvoiceDetails[0]['total_amount'];
		}
			
		if(!empty($oldRecords)){ // FOR UPDATE OPERATION 
			if(isset($oldRecords['salesArDpInvoiceId']) && !empty($oldRecords['salesArDpInvoiceId'])){
				
				if ($newRecords['status']==3){ // CANCELLED 
					$updateUtilizedAmount	 = $tbldpRemainingAmount + $oldRecords['salesArDpInvoiceUsedAmount'];
					
					//echo "updateutilized amount ---->".$updateUtilizedAmount;
					
					if($tbldpTotalAmount >= $updateUtilizedAmount){
						$updateArray 	 = array('remaining_amount' => $updateUtilizedAmount);
						$CI->commonModel->updateTransParentTableStatus($parentTableName, $updateArray, $parentTableId);
					}else{
						echo json_encode(array(
							'status' 		=> 'FAILURE',
							'message' 		=> 'Used Amount exceeds the Dp Invoice Amount!',
							"responseCode" 	=> 200
						));
						exit();
					}
				}
			}
		}else if(!empty($newRecords)) { // ONLY FOR SAVING OPERATION CLOSE THE DOWNPAYMENT DETAILS
			if(isset($newRecords['salesArDpInvoiceId']) && !empty($newRecords['salesArDpInvoiceId'])){
					
				if($newRecords['status']==1 || $newRecords['status']==2){ // STATUS -> open or closed
					

					$updateUtilizedAmount   = $tbldpRemainingAmount-$salesArDpInvoiceUsedAmount;
					
					if($updateUtilizedAmount >= 0){
						$updateArray 	 = array('remaining_amount' => $updateUtilizedAmount);
						$CI->commonModel->updateTransParentTableStatus($parentTableName, $updateArray, $parentTableId);
					}else{
						echo json_encode(array(
							'status' 		=> 'FAILURE',
							'message' 		=> 'Used Amount exceeds the Dp Invoice Amount!',
							"responseCode" 	=> 200
						));
						exit();
					}
				}
			}
		}
	}
}


/**
 * @METHOD NAME 	: reverseDownPaymentAmountForCreditMemo()
 *
 * @DESC 			: 1) Reverse the downpayment amount which is used in ar credit memo. [TABLE: tbl_sales_ar_dp_invoice -> remaining_amount field gets added with used dp invoice amount in credit memo]
					  2) update the used amount in ar invoice table 
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function reverseDownPaymentAmountForCreditMemo($oldRecords = '', $newRecords, $currentScreenTableName){

	$CI = &get_instance();


	if(($currentScreenTableName == 'SALES_AR_CREDIT_MEMO')){
		
		//  
		$screenDetails		= $CI->config->item('SCREEN_NAMES')['SALES_AR_DP_INVOICE'];
		$parentTableName 	= $screenDetails['tableName'];
				
		// ALWAYS NEW RECORD APPEARS
		$parentTableId 	 			 = $newRecords['salesArDpInvoiceId'];
		$salesArDpInvoiceUsedAmount  = $newRecords['salesArDpInvoiceUsedAmount']; // USED AMOUNT
		
		// GET THE DP INVOICE DETAILS
		$passData['id'] 	= $parentTableId;
		$dpInvoiceDetails 	= $CI->commonModel->getDpInvoiceDocumentNumber($passData);
		
		
		// DP INVOICE DETAILS 
		if(count($dpInvoiceDetails)>0){
			$tbldpRemainingAmount		 = $dpInvoiceDetails[0]['remaining_amount'];
			$tbldpTotalAmount			 = $dpInvoiceDetails[0]['total_amount'];
		}
		
		
		if(!empty($oldRecords)){ // FOR UPDATE OPERATION 
			
		}else if(!empty($newRecords)) { // ONLY FOR SAVING OPERATION 
			if(isset($newRecords['salesArDpInvoiceId']) && !empty($newRecords['salesArDpInvoiceId'])){
					
				if($newRecords['status']==1 || $newRecords['status']==2){ // STATUS -> open or closed
					
					//Step 1: Reverse(ADD) in SALES_AR_DP_INVOICE remaining amount additon
					$reverseUtilizedAmount   = $tbldpRemainingAmount+$salesArDpInvoiceUsedAmount;
					
					if(($reverseUtilizedAmount >= 0) &&  ($reverseUtilizedAmount <=  $tbldpTotalAmount)){
						$updateArray 	 = array('remaining_amount' => $reverseUtilizedAmount);
						$CI->commonModel->updateTransParentTableStatus($parentTableName, $updateArray, $parentTableId);
					}else{
						echo json_encode(array(
							'status' 		=> 'FAILURE',
							'message' 		=> 'Used Amount exceeds the Dp Invoice Total Amount!',
							"responseCode" 	=> 200
						));
						exit();
					}
					
					//Step 2: Subtract in tbl_sales_ar_invoice (sales_ar_dp_invoice_used_remaining_amount) field
					$parentId  		=  getParentTableDetails($newRecords);
					
					// GET THE AR INVOICE DETAILS 
					$passData['id']   = $parentId;
					
				
					// AR INVOICE DETAILS
					$arInvoiceDetails 	 = $CI->commonModel->getARInvoiceDetails($passData);
					
					$arDpUsedRemainingAmount = $arInvoiceDetails[0]['sales_ar_dp_invoice_used_remaining_amount'];
					$arDpUsedAmount			 = $arInvoiceDetails[0]['sales_ar_dp_invoice_used_amount'];
					$utilizedAmount 	 	 =  $arDpUsedRemainingAmount-$salesArDpInvoiceUsedAmount; // 50 - 20 
					
					if($utilizedAmount  <= $arDpUsedAmount){
						$screenDetails		= $CI->config->item('SCREEN_NAMES')['SALES_AR_INVOICE'];
						$parentTableName 	= $screenDetails['tableName'];
						$updateArray 	 	 = array('sales_ar_dp_invoice_used_remaining_amount' => $utilizedAmount);
						
						$CI->commonModel->updateTransParentTableStatus($parentTableName, $updateArray, $parentId);
					}else{
						echo json_encode(array(
							'status' 		=> 'FAILURE',
							'message' 		=> 'Used Amount exceeds the Dp Invoice USed Amount!',
							"responseCode" 	=> 200
						));
						exit();
					}
					
					//echo "testing data"; exit;
										
				}
			}
		}
		
	}
}	



/**
 * @METHOD NAME 	: getParentTableDetails()
 *
 * @DESC 			: To Get the Parent Table Details 
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function getParentTableDetails($records){

	$CI = &get_instance();
	$data = array();
	
	$itemListArray = $records['itemListArray'];
	
	foreach($itemListArray as $itemKey => $itemValue){
		$copyFromId    = $itemValue['copyFromId'];
		$copyFromType  = $itemValue['copyFromType'];
		$screenDetails	= $CI->config->item('SCREEN_NAMES')[$copyFromType];
		$parentTableName 	= $screenDetails['tableName'];
		$childTableName		= $screenDetails['childTableName'];
		$fieldName			= $screenDetails['childRefId'];

		break;
	}	

	// GET THE PARENT TABLE ID USING CHILD TABLE ID 
	return $parentId  = $CI->commonModel->getTransTblParentId($childTableName, $fieldName, $copyFromId);
	
}


/**
 * @METHOD NAME 	: SPI_checkToContinueProcess()
 *
 * @DESC 			: CHECK THE TRANSACTION PROCESS TO PROCEED.
					  FOR EXAMPLE SOME OF THE SCREENS NOT REQUIRED FOR THE TRANSACTION.
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
 function SPI_checkToContinueProcess($oldRecords = '', $newRecords, $currentScreenTableName){
	 
	 $CI = &get_instance();

	 if (empty($oldRecords)) { // SAVE OPERATION
		$itemListArray 		= $newRecords['itemListArray'];
	 }else{
		$itemListArray 	= $oldRecords['itemListArray'];
	 }
 
	 $newItemListArray 		 = removeEmptyCopyIdRecords($itemListArray);

	 if(count($newItemListArray) > 0) {
		$copyFromType		 = $newItemListArray[0]['copyFromType'];
	
		if($currentScreenTableName == 'PURCHASE_ORDER' && $copyFromType == 'SALES_ORDER' ){ // From so -> po no changes 
			return false;
		}else{
			return true;
		}
	 }else{
			return true;
	 }
 }
 

/**
 * @METHOD NAME 	: transProcessItems()
 *
 * @DESC 			: Entry Service call from all the controllers.
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function transProcessItems($oldRecords = '', $newRecords,$currentScreenTableName,$documentProcessMode ='')
{	
	
	// CONVERTING TO NEW RECORDS 
	if(!empty($oldRecords)){
		toCamelCase($oldRecords);
	}
	
	// CONVERTING TO OLD RECORDS
	if(!empty($newRecords)){
		toCamelCase($newRecords);
	}
	
	// MAINTAIN THE OLD DATA 
	$storeOldRecords = $oldRecords; // updated 
	$storeNewRecords = $newRecords;
	
	$CI = &get_instance();
	
	// Check the Transaction process for records 	
	$continueTransFlag  = SPI_checkToContinueProcess($oldRecords,$newRecords,$currentScreenTableName);
	
	if($continueTransFlag){ // True continue 
		
		
		
		// PROCEED FOR SOURCE AND CURRENT TABLE QUANTITY MANIPULATION 
		if (empty($oldRecords)) { // SAVE OPERATION
			
			$oldRecords = array();
						
			$newItemListArray 		 = $newRecords['itemListArray'];
			$frameFinalArray 	 	 = array();
			$finalCnt 				 = 0;
			$oldRecords['status'] 	 = '1';

			$newItemListArray 		 = removeEmptyCopyIdRecords($newItemListArray);

			foreach ($newItemListArray as $getItemKey => $newItemValue) {
				$frameFinalArray[$finalCnt]['copyFromId']		= $newItemValue['copyFromId'];
				$frameFinalArray[$finalCnt]['copyFromType']		= $newItemValue['copyFromType'];
				$frameFinalArray[$finalCnt]['oldQuantityValue'] = 0;
				$frameFinalArray[$finalCnt]['newQuantityValue'] = 0;
				$frameFinalArray[$finalCnt]['newArrivedValue']  = $newItemValue['quantity'];
				$frameFinalArray[$finalCnt]['operation'] 		= 'UPDATE';
				$finalCnt++;
			}
			$finalListArray  = $frameFinalArray;
			
		} else { // UPDATE OPERATION 
		
			preventCurrentDocStatusChangeToCancel($currentScreenTableName,$newRecords);
			toCamelCase($oldRecords);
			checkQuantityCalculation($oldRecords, $newRecords,$currentScreenTableName,$documentProcessMode); // Check Quantity Calculation
			$finalListArray  = transCompareItemRecords($oldRecords, $newRecords,$documentProcessMode);
		}
		
		/*
		echo "Current screen name :: ".$currentScreenTableName;
		echo "====";
		echo "<pre>"; print_r($finalListArray);echo "</pre>";
		exit;
		*/
			
			
		// FINAL OPERATION 
		if (!empty($finalListArray)) {
			$oldStatus  = $oldRecords['status'];
			$newStatus  = $newRecords['status'];

			//Table Details 
			$copyFromType		= $finalListArray[0]['copyFromType'];
			$screenDetails		= $CI->config->item('SCREEN_NAMES')[$copyFromType];
			$childTableName		= $screenDetails['childTableName'];
			$fieldName			= $screenDetails['childRefId'];
			
			//printr($finalListArray);echo "Curr screen tbl name ".$currentScreenTableName;exit;
			
			// ONLY FOR SALES AR DP INVOICE 
			$copyFromTypeArray 			= array('SALES_QUOTE','SALES_ORDER','SALES_DELIVERY');
			$skipOpenQuanityUpdateArray = array('SALES_AR_DP_INVOICE');
		
			if(in_array($currentScreenTableName,$skipOpenQuanityUpdateArray) && in_array($copyFromType,$copyFromTypeArray) ){
				//echo "Do nothing"; // SALES AR DP INVOICE screen and No copy of above copytype
			}else { // FOR ALL OTHER SCREENS PROCEED THE FUNCTIONALITY 
				
				// 
				SPI_updateSourceChildTableOrderedQuantity($childTableName, $finalListArray, $oldStatus, $newStatus,$documentProcessMode);
				
				//
				SPI_updateSourceChildTableOpenQuantity($childTableName,$fieldName,$finalListArray,$documentProcessMode);
				
				// 
				SPI_updateSourceParentTableStatusUsingOpenQuantity($finalListArray,$documentProcessMode);
			}
			// CHECK AFTER DELIVERY 
			checkAfterDelivery($finalListArray,$currentScreenTableName,$documentProcessMode);
		}
		
		// ITEM MANAGEMENT 
		SPI_ItemStockMgmt($storeOldRecords,$storeNewRecords,$currentScreenTableName,$documentProcessMode);
		
		// CLOSE DIRECT DOWN PAYMENT ONCE IT IS USED IN AR INVOICE 
		reduceDownPaymentAmount($storeOldRecords,$storeNewRecords,$currentScreenTableName); // For AR-Invoice 
		reverseDownPaymentAmountForCreditMemo($storeOldRecords,$storeNewRecords,$currentScreenTableName);
	}
}


/**
 * @METHOD NAME 	: checkQuantityCalculation()
 *
 * @DESC 			: TO CHECK THE QUANTITY CALCULATION FOR REMANING AND ADDITIONAL QUANITY 
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function checkQuantityCalculation($oldRecords, $newRecords,$currentScreenName,$documentProcessMode)
{	
	$tblItemListArray 		 = $oldRecords['itemListArray'];
	$newItemListArray 		 = $newRecords['itemListArray'];
	
	if($documentProcessMode == 'PROCESS_DOCUMENT' || $documentProcessMode == 'DO_VALDATION_ALONE') {
		
		foreach ($newItemListArray as $getItemKey => $newItemValue) {
			
			$getTblItemResult = "";

			if (!empty($newItemValue['id'])) {
				$id 			  = $newItemValue['id'];
				$getTblItemResult = findTransArrayValue($id, $tblItemListArray);

				// QUANTITY MUST NOT BE EQUAL
				if (
					!empty($getTblItemResult) &&
					$getTblItemResult['quantity'] != $newItemValue['quantity']
				) {
					$tblOrderedQuantity =   $getTblItemResult['orderedQuantity'];
					
					// CHECK QUANTITY VALUE FOR LESSER 
					if($tblOrderedQuantity > $newItemValue['quantity']) {	 // 4 > 5
						
						$itemId				= $newItemValue['itemId'];
						$statusInfoDetails	= array();
						$getInfoData 		= array(	
													'getItemList' 	 => $itemId,
												);
						$statusInfoDetails	= getAutoSuggestionListHelper($getInfoData);
						$itemName 			= $statusInfoDetails['itemInfo'][0]['item_name'];
								
						echo json_encode(array(
								'status' 		=> 'ERROR',
								'message' 		=> 'Should not able to reduce the quantity for the item '.$itemName,
								"responseCode" 	=> 200
							));
						exit();
					}
					
				}
			}
		}
	}
}


/**
 * @METHOD NAME 	: directlyCloseStatus()
 *
 * @DESC 			: DIRECTLY CHANGE THE STATUS TO CLOSE in table
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function directlyCloseStatus($currentScreenName,$itemRecords,$insertId,$documentProcessMode)
{	
	toCamelCase($itemRecords);
	
	if($documentProcessMode == 'PROCESS_DOCUMENT'){
		
		$CI = &get_instance();
		$existFlag = 1;
		foreach ($itemRecords as $getItemKey => $newItemValue) {
			if(!empty($newItemValue['copyFromType'])){
				$copyFromId		= $newItemValue['copyFromId'];
				$copyFromType	= $newItemValue['copyFromType'];
				
				if($currentScreenName == 'SALES_AR_CREDIT_MEMO' && $existFlag==1){
					if($copyFromType == 'SALES_AR_INVOICE' || $copyFromType == 'SALES_AR_DP_INVOICE') {
						$CI->commonModel->directlyCloseStatus($currentScreenName, 'id', $insertId);
						$existFlag = 2;
					}
				}else if($currentScreenName == 'SALES_RETURN' && $existFlag==1){
					if($copyFromType == 'SALES_DELIVERY' || $copyFromType == 'SALES_DELIVERY') {
						$CI->commonModel->directlyCloseStatus($currentScreenName, 'id', $insertId);
						$existFlag = 2;
					}
				}
			}
		}
	}
}


/**
 * @METHOD NAME 	: transCompareItemRecords()
 *
 * @DESC 			: Frame the final array for the Open Quantity / Ordered Quantity
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function transCompareItemRecords($oldRecords, $newRecords)
{
	$tblItemListArray 		 = removeEmptyCopyIdRecords($oldRecords['itemListArray']);
	$newItemListArray 		 = removeEmptyCopyIdRecords($newRecords['itemListArray']);
	$deletedItemChildIds	 = $newRecords['deletedItemChildIds'];

	$frameFinalArray		= array();
	$finalCnt 				= 0;
	
	$getDraftFlag = checkDocumentDraftStatus($oldRecords, $newRecords);
	
	/*
	echo "OLD RECORDS IS :::>>>>>";
	printr($oldRecords);
	
	echo "NEW RECORDS IS :::>>>>>";
	printr($newRecords);
	echo "GET DRAFT FLAG IS ".$getDraftFlag;
	exit;
	*/

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
					!empty($getTblItemResult) 
					//&& $getTblItemResult['quantity'] != $newItemValue['quantity']
				  ) {
					
					if($getDraftFlag==1){
						$quantityRes =  $newItemValue['quantity'];
					}else if($getDraftFlag==0){
						if($getTblItemResult['quantity'] != $newItemValue['quantity']){
							$quantityRes  = $newItemValue['quantity'] - $getTblItemResult['quantity'];	
						}else{
							$quantityRes  = $getTblItemResult['quantity'];
						}
					}
					$frameFinalArray[$finalCnt]['itemId']			= $newItemValue['itemId'];
					$frameFinalArray[$finalCnt]['copyFromId']		= $newItemValue['copyFromId'];
					$frameFinalArray[$finalCnt]['copyFromType']		= $newItemValue['copyFromType'];
					$frameFinalArray[$finalCnt]['oldQuantityValue'] = $getTblItemResult['quantity'];
					$frameFinalArray[$finalCnt]['newQuantityValue'] = $newItemValue['quantity'];
					$frameFinalArray[$finalCnt]['newArrivedValue']  = $quantityRes;
					$frameFinalArray[$finalCnt]['operation'] 		= 'UPDATE';
					
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
					$frameFinalArray[$finalCnt]['itemId']			= $newItemValue['itemId'];
					$frameFinalArray[$finalCnt]['copyFromId']		= $newItemValue['copyFromId'];
					$frameFinalArray[$finalCnt]['copyFromType']		= $newItemValue['copyFromType'];
					$frameFinalArray[$finalCnt]['oldQuantityValue'] = $getTblItemResult['quantity'];
					$frameFinalArray[$finalCnt]['newQuantityValue'] = 0;
					$frameFinalArray[$finalCnt]['newArrivedValue']  = $quantityRes;
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
				$frameFinalArray[$finalCnt]['itemId']			= $newItemValue['itemId'];
				$frameFinalArray[$finalCnt]['copyFromId']		= $getTblItemResult['copyFromId'];
				$frameFinalArray[$finalCnt]['copyFromType']		= $getTblItemResult['copyFromType'];
				$frameFinalArray[$finalCnt]['oldQuantityValue'] = $getTblItemResult['quantity'];
				$frameFinalArray[$finalCnt]['newQuantityValue'] = 0;
				$frameFinalArray[$finalCnt]['newArrivedValue']  = $quantityRes;
				$frameFinalArray[$finalCnt]['operation'] 		= 'DELETE';
				$finalCnt++;
			}
		}
	}
	//echo "FINAL ARRARY :: <pre>";print_r($frameFinalArray);echo "</pre>"; //exit;
	return $frameFinalArray;
}
 

/**
 * @METHOD NAME 	: SPI_updateSourceChildTableOrderedQuantity()
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
function SPI_updateSourceChildTableOrderedQuantity($tableName, $finalListArray, $oldStatus, $newStatus, $documentProcessMode)
{
	$CI = &get_instance();

	//echo "<pre>";printr($finalListArray);echo "</pre>";exit;
	
	// FLAG DETAILS 
	$updateFlag  = "";
	$deleteFlag  = "";

	if($documentProcessMode == 'PROCESS_DOCUMENT'){

		// STATUS BASED FORMULA 
		if ($oldStatus == 1 && ($newStatus == 1 ||  $newStatus == 2)) {  // OPEN && OPEN~CLOSED
			$updateFlag  = 'PLUS';
			$deleteFlag  = 'MINUS';
		}

		if ($oldStatus == 1 && ($newStatus == 3)) {  // OPEN || CANCELLED
			$updateFlag  = 'MINUS';
			$deleteFlag  = 'MINUS';
		}

		// UPDATE: ORDERED QUANITY 
		foreach ($finalListArray as $finalKey => $finalValue) {

			$id 			  = $finalValue['copyFromId'];
			$operation 		  = $finalValue['operation'];
			$arrivedValue 	  = $finalValue['newArrivedValue'];

			if ($operation == "UPDATE") {
				if ($updateFlag == "PLUS") {
					$symbol = '+';
					//$openQualityCalc = 'ordered_quantity'+$arrivedValue;
				} else if ($updateFlag == "MINUS") {
					$symbol = '-';
				}
			} else if ($operation == "DELETE") {
				if ($deleteFlag == "PLUS") {
					$symbol = '+';
				} else if ($deleteFlag == "MINUS") {
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
}


/**
 * @METHOD NAME     : SPI_updateSourceChildTableOpenQuantity()
 *
 * @DESC 			: 1. We are updating all the rows by using reference id (grpo_id) in source item table. 
					  2. Apply open quanity formula [open_quantity = quantity-ordered_quantity]
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function SPI_updateSourceChildTableOpenQuantity($tableName,$fieldName, $itemListArray, $documentProcessMode)
{
	$CI = &get_instance();
	$data = array();
	
	if($documentProcessMode == 'PROCESS_DOCUMENT'){
		if(count($itemListArray)>0){
			
			$sourceParentTableIds = getUniqueSourceParentTableIdByRefColumn($itemListArray);

			$copyFromId    = $itemListArray[0]['copyFromId'];
			$copyFromType  = $itemListArray[0]['copyFromType'];
			$screenDetails	= $CI->config->item('SCREEN_NAMES')[$copyFromType];

			$sourceParentTableName 	= $screenDetails['tableName'];
			$sourceChildTableName	= $screenDetails['childTableName'];
			$sourceChildRefColumn	= $screenDetails['childRefId'];
						
			foreach($sourceParentTableIds as $key => $sourceParentTableId){
					
				// GET THE PARENT TABLE ID USING CHILD TABLE ID 
				$whereId  = $CI->commonModel->getTransTblParentId($tableName, $fieldName, $copyFromId);
				
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
	}
}


/**
 * @METHOD NAME     : transCalcOpenQuantity()
 *
 * @DESC 			: Update the open Quantity for the CHILD. Serivce call directly calling from each controllers.
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function transCalcOpenQuantity($tableName, $fieldName, $whereId)
{
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


/**
 * @METHOD NAME 	: SPI_updateSourceParentTableStatusUsingOpenQuantity()
 *
 * @DESC 			: Check the open quantity and update the parent table STATUS to closed. 
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function SPI_updateSourceParentTableStatusUsingOpenQuantity($itemListArray,$documentProcessMode)
{
	//print_r($itemArray);exit;
	
	$CI = &get_instance();

	if($documentProcessMode == 'PROCESS_DOCUMENT') {

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
}


/**
 * @METHOD NAME 	: checkAfterDelivery()
 *
 * @DESC 			: Check whether quantity exists in the table to processing after the delivery steps.
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function checkAfterDelivery($itemListArray,$screenName,$documentProcessMode){
	
	if($documentProcessMode == 'PROCESS_DOCUMENT' || $documentProcessMode == 'DO_VALDATION_ALONE') {
		$CI = &get_instance();
		$screenNamesArray = array('SALES_AR_INVOICE','SALES_RETURN','SALES_AR_CREDIT_MEMO','SALES_AR_DP_INVOICE');
		
		$chkFlag = 0;
		if(in_array($screenName,$screenNamesArray)){
			
		
			// CHECK NEW ITEM CONDITION
			if(count($itemListArray)>0){
			
				// CHECK OPEN QUANITY CONDITION CHECKING 		
				foreach($itemListArray as $itemKey => $itemValue){
					
		
					$copyFromType		= $itemValue['copyFromType'];
					$copyFromId			= $itemValue['copyFromId'];
					$newItemQuanity		= $itemValue['newQuantityValue'];
					$operation			= $itemValue['operation'];

					$screenDetails		= $CI->config->item('SCREEN_NAMES')[$copyFromType];
					$parentTableName 	= $screenDetails['tableName'];
					$childTableName		= $screenDetails['childTableName'];
				
				
				if($operation=='UPDATE'){
					
					// GET PARENT TABLE RECORDS 
					$getParentTblItemDetails = $CI->commonModel->transGetChildOrderItems($childTableName, $copyFromId);
					
					
					if(count($getParentTblItemDetails)>0){
						
						$parentTblOpenQuantity  = $getParentTblItemDetails[0]['open_quantity'];
						$itemId					= $getParentTblItemDetails[0]['item_id'];
						
							if($parentTblOpenQuantity < 0 ){
								
								$statusInfoDetails	= array();
								$getInfoData 		= array(	
									'getItemList' 	 => $itemId,
								);
								$statusInfoDetails	= getAutoSuggestionListHelper($getInfoData);
								$itemName 			= $statusInfoDetails['itemInfo'][0]['item_name'];
								
								echo json_encode(array(
									'status' 		=> 'ERROR',
									'message' 		=> 'Provide Less Quantity Value for item '.$itemName,
									"responseCode" 	=> 200
								));
								exit();
							}
					}else{
						echo json_encode(array(
							'status' 		=> 'ERROR',
							'message' 		=> 'Something went wrong in parent table details. Please contact admin !',
							"responseCode" 	=> 200
						));
						exit();
					}
				}
					
					
				}
			}else{
				echo json_encode(array(
							'status' 		=> 'ERROR',
							'message' 		=> 'Something went wrong in. Please contact admin !',
							"responseCode" 	=> 200
						));
				exit();
			}
		}
	}
}

////////////////////////// UPDATE LAST PRICE SECTION  ////////////////////////////////////////////////////
/**
 * @METHOD NAME 	: updateLastPriceInItemTable()
 *
 * @DESC 			: DIRECT CALL : UPDATE THE LAST SALES PRICE AND LAST PURCHASE PRICE FROM GROP AND SALES AR INVOICE 
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function updateLastPriceInItemTable($itemListArray,$screenName,$documentProcessMode){
	
	$CI 			  = &get_instance();
	$screenNamesArray = array('GRPO','SALES_AR_INVOICE');
	
	if($documentProcessMode == 'PROCESS_DOCUMENT') {
		if(in_array($screenName,$screenNamesArray)){
			
			// SCREEN DETAILS 
			$screenDetails		= $CI->config->item('SCREEN_NAMES')[$screenName];
			$parentTableName 	= $screenDetails['tableName'];
			$childTableName		= $screenDetails['childTableName'];
			
			// SCREEN DETAILS 
			if($screenName=='GRPO'){
				$fieldFlag = 1;
			}else if ($screenName=='SALES_AR_INVOICE'){
				$fieldFlag = 2;
			}	
			
			// ITEM LOOPING ARRAY 
			if(count($itemListArray)>0){
				foreach($itemListArray as $itemKey => $itemValue){
					//printr($itemValue);exit;
					$CI->commonModel->updateLastPriceInItemTable($itemValue,$fieldFlag);
				}
			}
		}
	}
}


////////////////////////// NEW LOGICS STOCK MANAGEMNET ////////////////////////////////////////////////////
/**
 * @METHOD NAME 	: SPI_ItemStockMgmt()
 *
 * @DESC 			: -
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function SPI_ItemStockMgmt($oldRecords='',$newRecords,$screenName,$documentProcessMode){
	/*
	echo "<pre>";print_r($oldRecords);echo "</pre>";
	echo "<pre>";print_r($newRecords);echo "</pre>";
	echo "Screen name is ::".$screenName;
	exit;
	*/
	
	$CI 			  = &get_instance();
	$screenNamesArray = array('GRPO','SALES_DELIVERY','SALES_RETURN','SALES_AR_CREDIT_MEMO','SALES_AR_INVOICE','INVENTORY_TRANSFER');
	
	if(in_array($screenName,$screenNamesArray)){ // 
	
		// NEW BUSINESS LOGIC ADDED FOR FILETRING BASED UPON THE SCREENS 
		
		// RETURN CONDITION 
		if($screenName == 'SALES_RETURN'){ // SKIP THE NEWLY ADDED ITEMS IT TAKES ONLY COPY FROM OPTIONS
			if(!empty($oldRecords)){
				$oldRecords['itemListArray'] 	= removeEmptyCopyIdRecords($oldRecords['itemListArray']);
			}	
			$newRecords['itemListArray'] 		= removeEmptyCopyIdRecords($newRecords['itemListArray']);
		}
				
	
		
		// SALES_AR_INVOICE 
		if($screenName == 'SALES_AR_INVOICE'){
			/*
				printr($oldRecords['itemListArray']);
				printr($newRecords['itemListArray']);
			*/	
			
			// FOR OLD RECORDS 
			if(!empty($oldRecords)){
				$itemListArray  = $oldRecords['itemListArray'];
				foreach($itemListArray as $itemKey => $itemValue){
					if($itemValue['copyFromType'] == 'SALES_DELIVERY'){
						unset($itemListArray[$itemKey]);
					}
				}
				$oldRecords['itemListArray'] = $itemListArray;
			}
			
			// FOR NEW RECORDS 			
			$itemListArray  = $newRecords['itemListArray'];
			foreach($itemListArray as $itemKey => $itemValue){
					if($itemValue['copyFromType'] == 'SALES_DELIVERY'){
						unset($itemListArray[$itemKey]);
					}
			}
			$newRecords['itemListArray'] 	= $itemListArray;
		
		}
		// END OF NEW BUSINESS LOGICS 
		
		/////////////////////// EXISTING FLOW WORKING MODULE //////////////////////////////////////////////
		if(empty($oldRecords)){ // FOR SAVE OPERATION PERFORM
			$oldRecords = array();
			// NORMAL EXISTING FLOW PROCEED
			$newItemListArray 		 = $newRecords['itemListArray'];
			$frameFinalArray 	 	 = array();
			$finalCnt 				 = 0;
			$oldRecords['status'] 	 = 1;
		
			foreach ($newItemListArray as $getItemKey => $newItemValue) {
				
				$warehouseArray = itemWarehouseBinLogic($newItemValue,$finalCnt);
				
				$frameFinalArray[$finalCnt]['withoutQtyPosting'] = isset($newItemValue['withoutQtyPosting']) ? $newItemValue['withoutQtyPosting'] : "";
				$frameFinalArray[$finalCnt]['itemId']			= $newItemValue['itemId'];
				$frameFinalArray[$finalCnt]['oldQuantityValue'] = 0;
				$frameFinalArray[$finalCnt]['newQuantityValue'] = 0;
				$frameFinalArray[$finalCnt]['newArrivedValue']  = $newItemValue['quantity'];
				$frameFinalArray[$finalCnt]['operation'] 		= 'UPDATE';
				$frameFinalArray[$finalCnt] 					= array_merge($frameFinalArray[$finalCnt],$warehouseArray[$finalCnt]);
				$finalCnt++;
			}
			$finalListArray  = $frameFinalArray;
		}else { // UPDATE RECORDS 
				toCamelCase($oldRecords);
				$finalListArray  					 = transCompareItemStockRecords($oldRecords, $newRecords);
		}
		SPI_itemStockUpdation($finalListArray,$oldRecords,$newRecords,$screenName,$documentProcessMode);
	}
}


/**
 * @METHOD NAME 	: SPI_itemStockUpdation()
 *
 * @DESC 			: - 
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function SPI_itemStockUpdation($finalListArray,$oldRecords,$newRecords,$screenName,$documentProcessMode){

		$CI 			  = &get_instance();

		// FLAG DETAILS 
		$updateFlag  = "";
		$deleteFlag  = "";

		$oldStatus  = $oldRecords['status'];
		$newStatus  = $newRecords['status'];
		
		// STATUS BASED FORMULA 
		if ($oldStatus == 1 && ($newStatus == 1 ||  $newStatus == 2)) {  // OPEN && OPEN~CLOSED
		
			if($screenName=='GRPO' 
			|| $screenName=='SALES_RETURN' 
			||  $screenName=='SALES_AR_CREDIT_MEMO' 
			|| 	$screenName=='INVENTORY_TRANSFER'){
					$updateFlag  = 'PLUS';
					$deleteFlag  = 'MINUS';
				}else if ($screenName=='SALES_DELIVERY' || $screenName=='SALES_AR_INVOICE'){
					$updateFlag  = 'MINUS';
					$deleteFlag  = 'PLUS';
				}
		}

		if ($oldStatus == 1 && ($newStatus == 3)) {  // OPEN || CANCELLED
			if($screenName=='GRPO' 
			|| $screenName=='SALES_RETURN' 
			|| $screenName=='SALES_AR_CREDIT_MEMO' 
			|| $screenName=='INVENTORY_TRANSFER'
			){
				$updateFlag  = 'MINUS';
				$deleteFlag  = 'MINUS';
			}else if ($screenName=='SALES_DELIVERY' || $screenName=='SALES_AR_INVOICE'){
				$updateFlag  = 'PLUS';
				$deleteFlag  = 'PLUS';
			}
		}

		
		// UPDATE: ORDERED QUANITY 
		foreach ($finalListArray as $finalKey => $finalValue) {
						
			$operation 		  = $finalValue['operation'];
			
			if ($operation == "UPDATE") {
				if ($updateFlag == "PLUS") {
					$symbol = '+';
				} else if ($updateFlag == "MINUS") {
					$symbol = '-';
				}
			} else if ($operation == "DELETE") {
				if ($deleteFlag == "PLUS") {
					$symbol = '+';
				} else if ($deleteFlag == "MINUS") {
					$symbol = '-';
				}
			}
			
			// CHECK THE QUANITY EXISTS IN THE ITEM TABLE 
			if($screenName=='SALES_DELIVERY' || $screenName=='SALES_AR_INVOICE'){
				if($documentProcessMode == 'PROCESS_DOCUMENT' || $documentProcessMode == 'DO_VALDATION_ALONE')
				{
					checkReqItemStockExists($finalValue,$symbol);
				}
			}
			
			// PROCESS DOCUMENT 
			if($documentProcessMode == 'PROCESS_DOCUMENT'){
				// AFFECT STOCK LOGIC FOR AR CREDIT MEMO 
				if($screenName == 'SALES_AR_CREDIT_MEMO'){
					$withoutQtyPosting = $finalValue['withoutQtyPosting'];
					
					if($withoutQtyPosting==0 ){ // UPDATE THE STOCK
						//echo "Withoutqtyosting :: ".$withoutQtyPosting;
						$updateItemStockDetails = $CI->commonModel->transUpdateItemStock($finalValue,$symbol);
					}
				}else{ // FOR OTHER SCREENS
					// INSERT OR UPDATE THE ITEM STOCK DETAILS 
					$updateItemStockDetails = $CI->commonModel->transUpdateItemStock($finalValue,$symbol);
				}
			}
		}
	
		// FOR INVENTORY TRANSFER  
		if($screenName == 'INVENTORY_TRANSFER'){
			transProcessInventoryTranferMgmt($finalListArray,$oldStatus,$newStatus,$documentProcessMode);
		}
}


/**
 * @METHOD NAME 	: transProcessInventoryTranferMgmt()
 *
 * @DESC 			: - 
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function transProcessInventoryTranferMgmt($finalListArray,$oldStatus,$newStatus,$documentProcessMode){
		
	// 
		$CI 			  = &get_instance();
	
	// STATUS BASED FORMULA 
		if ($oldStatus == 1 && ($newStatus == 1 ||  $newStatus == 2)) {  // OPEN && OPEN~CLOSED
			$updateFlag  = 'MINUS';
			$deleteFlag  = 'PLUS';
		}

		if ($oldStatus == 1 && ($newStatus == 3)) {  // OPEN || CANCELLED
			$updateFlag  = 'PLUS';
			$deleteFlag  = 'PLUS';
		}
		
		// SWAPING THE FROM WAREHOUSE ID
		foreach($finalListArray as $finalKey => $finalValue){
			$finalListArray[$finalKey]['warehouseId'] = $finalValue['fromWarehouseId'];
			$finalListArray[$finalKey]['binId'] 	  = $finalValue['fromBinId'];
		}
		
		
		// UPDATE: ORDERED QUANITY 
		foreach ($finalListArray as $finalKey => $finalValue) {
			
			$operation 		  = $finalValue['operation'];
			
			if ($operation == "UPDATE") {
				if ($updateFlag == "PLUS") {
					$symbol = '+';
				} else if ($updateFlag == "MINUS") {
					$symbol = '-';
				}
			} else if ($operation == "DELETE") {
				if ($deleteFlag == "PLUS") {
					$symbol = '+';
				} else if ($deleteFlag == "MINUS") {
					$symbol = '-';
				}
			}
			
			
			if($documentProcessMode == 'PROCESS_DOCUMENT' ||
			   $documentProcessMode == 'DO_VALDATION_ALONE'
			   ){
					checkReqItemStockExists($finalValue,$symbol);
				}

			// GET PARENT TABLE RECORDS 			
			if($documentProcessMode == 'PROCESS_DOCUMENT'){	
				$updateStockDetails = $CI->commonModel->transUpdateItemStock($finalValue,$symbol);
			}
		}
}


/**
 * @METHOD NAME 	: checkDocumentDraftStatus()
 *
 * @DESC 			: TO CHECK THE DOCUMENT DRAFT STATUS 
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function checkDocumentDraftStatus($oldRecords, $newRecords){
	
	$flag 			= 0;
	$tblDraftStatus = $oldRecords['isDraft'];
	$newDraftStatus = $newRecords['isDraft'];
	
	if($tblDraftStatus == 1 && $newDraftStatus == 1){ 		// Draft to Draft Document 
		$flag = 0;
	}else if($tblDraftStatus == 0 && $newDraftStatus == 0){ // Normal Document to Normal Document  
		$flag = 0;
	}//else if($tblDraftStatus == 0 && $newDraftStatus == 1) { // Draft to Normal document 
	else if($tblDraftStatus == 1 && $newDraftStatus == 0) { // Draft to Normal document 
		$flag = 1;
	}
	return $flag;
}


/**
 * @METHOD NAME 	: transCompareItemStockRecords()
 *
 * @DESC 			: 
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function transCompareItemStockRecords($oldRecords, $newRecords)
{
	//printr($newRecords); exit;
	
	
	$tblItemListArray 		 = $oldRecords['itemListArray'];
	$newItemListArray 		 = $newRecords['itemListArray'];
	$deletedItemChildIds	 = $newRecords['deletedItemChildIds'];

	$frameFinalArray		= array();
	$finalCnt 				= 0;
	
	$getDraftFlag = checkDocumentDraftStatus($oldRecords, $newRecords);
	
	// UPDATE MANIPULATION OPERATION 
	foreach ($newItemListArray as $getItemKey => $newItemValue) {
		$getTblItemResult = "";

		if($newRecords['status']==1 || $newRecords['status']==2){ // STATUS -> open or closed
		
			if (!empty($newItemValue['id'])) {
				$id 			  = $newItemValue['id'];
				$getTblItemResult = findTransArrayValue($id, $tblItemListArray);

				// QUANTITY MUST NOT BE EQUAL			
				if (!empty($getTblItemResult) 
					//&& $getTblItemResult['quantity'] != $newItemValue['quantity']
					){
					if($getDraftFlag==1){
						$quantityRes =  $newItemValue['quantity'];
					}else if($getDraftFlag==0){
						if($getTblItemResult['quantity'] != $newItemValue['quantity']){
							$quantityRes  = $newItemValue['quantity'] - $getTblItemResult['quantity'];	
						}else{
							$quantityRes  = $getTblItemResult['quantity'];
						}
					}
					
					$warehouseArray = itemWarehouseBinLogic($newItemValue,$finalCnt);
						
					$frameFinalArray[$finalCnt]['itemId']			= $newItemValue['itemId'];
					//$frameFinalArray[$finalCnt]['copyFromId']		= $newItemValue['copyFromId'];
					//$frameFinalArray[$finalCnt]['copyFromType']		= $newItemValue['copyFromType'];
					$frameFinalArray[$finalCnt]['oldQuantityValue'] = $getTblItemResult['quantity'];
					$frameFinalArray[$finalCnt]['newQuantityValue'] = $newItemValue['quantity'];
					$frameFinalArray[$finalCnt]['newArrivedValue']  = $quantityRes;
					$frameFinalArray[$finalCnt]['operation'] 		= 'UPDATE';
					$frameFinalArray[$finalCnt]['withoutQtyPosting'] = isset($newItemValue['withoutQtyPosting']) ? $newItemValue['withoutQtyPosting'] : "";
					$frameFinalArray[$finalCnt] 					 = array_merge($frameFinalArray[$finalCnt],$warehouseArray[$finalCnt]);
					$finalCnt++;
				}
			} else if(empty($newItemValue['id'])){
					// WHILE SAVING THE DRAFT DOCUMENT. THE USER CAN ADD ADDITIONAL RECORDS
					$warehouseArray 								 = itemWarehouseBinLogic($newItemValue,$finalCnt);
					$frameFinalArray[$finalCnt]['withoutQtyPosting'] = isset($newItemValue['withoutQtyPosting']) ? $newItemValue['withoutQtyPosting'] : "";
					$frameFinalArray[$finalCnt]['itemId']			= $newItemValue['itemId'];
					$frameFinalArray[$finalCnt]['oldQuantityValue'] = 0;
					$frameFinalArray[$finalCnt]['newQuantityValue'] = 0;
					$frameFinalArray[$finalCnt]['newArrivedValue']  = $newItemValue['quantity'];
					$frameFinalArray[$finalCnt]['operation'] 		= 'UPDATE';
					$frameFinalArray[$finalCnt] 					= array_merge($frameFinalArray[$finalCnt],$warehouseArray[$finalCnt]);
					$finalCnt++;
			}
		}
		
		// STATUS - 3 [CANCELLED]
		if($newRecords['status']==3){ // STATUS ->3 CANCELLED 
			if (!empty($newItemValue['id'])) {
				$id 			  = $newItemValue['id'];
				$getTblItemResult = findTransArrayValue($id, $tblItemListArray);

				if (!empty($getTblItemResult)) {
					
					$warehouseArray = itemWarehouseBinLogic($newItemValue,$finalCnt);
					
					$quantityRes  = $getTblItemResult['quantity'] - 0;
					$frameFinalArray[$finalCnt]['itemId']			= $newItemValue['itemId'];
					$frameFinalArray[$finalCnt]['copyFromId']		= $newItemValue['copyFromId'];
					$frameFinalArray[$finalCnt]['copyFromType']		= $newItemValue['copyFromType'];
					$frameFinalArray[$finalCnt]['oldQuantityValue'] = $getTblItemResult['quantity'];
					$frameFinalArray[$finalCnt]['newQuantityValue'] = 0;
					$frameFinalArray[$finalCnt]['newArrivedValue']  = $quantityRes;
					$frameFinalArray[$finalCnt]['operation'] 		= 'DELETE';
					$frameFinalArray[$finalCnt]['withoutQtyPosting']		= isset($newItemValue['withoutQtyPosting']) ? $newItemValue['withoutQtyPosting'] : "";
					$frameFinalArray[$finalCnt] 					= array_merge($frameFinalArray[$finalCnt],$warehouseArray[$finalCnt]);
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
				
				$warehouseArray = itemWarehouseBinLogic($newItemValue,$finalCnt);
				
				$frameFinalArray[$finalCnt]['itemId']			= $newItemValue['itemId'];
				$frameFinalArray[$finalCnt]['copyFromId']		= $getTblItemResult['copyFromId'];
				$frameFinalArray[$finalCnt]['copyFromType']		= $getTblItemResult['copyFromType'];
				$frameFinalArray[$finalCnt]['oldQuantityValue'] = $getTblItemResult['quantity'];
				$frameFinalArray[$finalCnt]['newQuantityValue'] = 0;
				$frameFinalArray[$finalCnt]['newArrivedValue']  = $quantityRes;
				$frameFinalArray[$finalCnt]['operation'] 		= 'DELETE';
				$frameFinalArray[$finalCnt]['withoutQtyPosting']		= isset($newItemValue['withoutQtyPosting']) ? $newItemValue['withoutQtyPosting'] : ""; // DELETE STOCK
				$frameFinalArray[$finalCnt] 					= array_merge($frameFinalArray[$finalCnt],$warehouseArray[$finalCnt]);
				$finalCnt++;
			}
		}
	}
	return $frameFinalArray;
}


/**
 * @METHOD NAME 	: checkReqItemStockExists()
 *
 * @DESC 			: TO CHECK THE ITEM EXISTS 
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function checkReqItemStockExists($itemData,$symbol){
	$CI 			  = &get_instance();
	$chkItemStockDetails = $CI->commonModel->checkReqItemStockExists($itemData,$symbol);
	
	// TO GET THE ITEM INFORMATION FOR ERROR MESSAGE 
	$statusInfoDetails	= array();
	$getInfoData 		= array(	
		'getItemList' 	 => $itemData['itemId'],
	);
	$statusInfoDetails	= getAutoSuggestionListHelper($getInfoData);
	$itemName 			= $statusInfoDetails['itemInfo'][0]['item_name'];
	
	if($chkItemStockDetails['flag']==2){
		echo json_encode(array(
					'status' 		=> 'ERROR',
					'message' 		=> $itemName.' not exists in stock !',
					"responseCode" 	=> 200
				));
		exit();
		
	}else if($chkItemStockDetails['flag']==3){
		echo json_encode(array(
					'status' 		=> 'ERROR',
					'message' 		=> 'Stock not available for item '.$itemName,
					"responseCode" 	=> 200
				));
		
		exit();
	}
}

/**
 * @METHOD NAME 	: itemWarehouseBinLogic()
 *
 * @DESC 			: 
 * @RETURN VALUE 	: $outputdata array
 * @PARAMETER 		: -
 * @SERVICE      	: WEB
 * @ACCESS POINT 	: -
 **/
function itemWarehouseBinLogic($newItemValue,$finalCnt){

	$formItemArray = array();
		
		// WARE HOUSE DETAILS 
		$warehouseId	 = '';
		$fromWarehouseId = '';
		$fromBinId 		 = '';
		$binId 			 = '';
						
		// FROM WAREHOUSE
		if(isset($newItemValue['fromWarehouseId'])){
			$fromWarehouseId = $newItemValue['fromWarehouseId'];
		}
		
		// TO WAREHOUSE 
		if(isset($newItemValue['toWarehouseId'])){
			$warehouseId = $newItemValue['toWarehouseId'];
		}else {
			$warehouseId = $newItemValue['warehouseId'];
		}
		
		// ADDED GENERIC LOGIC FOR BIN ID 
		// FROM BIN
		if(isset($newItemValue['fromBinId'])){
			$fromBinId = $newItemValue['fromBinId'];
		}
		
		// TO BIN
		if(isset($newItemValue['toBinId'])){
			$binId = $newItemValue['toBinId'];
		}else {
			$binId = $newItemValue['binId'];
		}

		$formItemArray[$finalCnt]['warehouseId']		= $warehouseId;
		$formItemArray[$finalCnt]['fromWarehouseId']	= $fromWarehouseId;	
		$formItemArray[$finalCnt]['fromBinId']			= $fromBinId;
		$formItemArray[$finalCnt]['binId']				= $binId;	
		return $formItemArray;
}


///////////////////////////////////////////////////////////////////////////////////////////////////////////
function updateOpenQuantityCountToItemTbl($screenName,$childTblName,$getPostData,$documentProcessMode){
	
	$CI 			  = &get_instance();
	$screenNamesArray = array('PURCHASE_ORDER','SALES_ORDER');
	
	toCamelCase($getPostData);
	
	if($documentProcessMode == 'PROCESS_DOCUMENT') {
		if(in_array($screenName,$screenNamesArray)){
				$getListData = $getPostData['itemListArray'];
				
				foreach ($getListData as $key => $value) {
					$openQuantityValue = 0;
					$openQuantityResult  = $CI->commonModel->getItemOpenQuantityCount($value['itemId'],$childTblName);
					
					if(!empty($openQuantityResult[0]['open_quantity_cnt'])){
						$openQuantityValue	 = $openQuantityResult[0]['open_quantity_cnt'];
					}
					// UPDATE ITEM MASTER TABLE 
					$CI->commonModel->updateOpenQuanityCountToItemTable($screenName,$openQuantityValue,$value['itemId']);
				}
				
		}
	}
}


// @Description : UPDATE THE PARENT STATUS TO CHILD 
function updateStatusToChildTable($getPostData,$screenName,$documentProcessMode){
	if($documentProcessMode == 'PROCESS_DOCUMENT') {
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
}

/********************************END OF TRANSACTION FUNCIOTNALITY STARTS ********************/