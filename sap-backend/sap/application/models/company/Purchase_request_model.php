<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Purchase_request_model.php
* @Class  			 : Purchase_request_model
* Model Name         : Purchase_request_model
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 26 APR 2020
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : Added comment blocks and header details
* Features           : 
*/
class Purchase_request_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->tableNameStr = 'PURCHASE_REQUEST';
		$this->itemTableNameStr = 'PURCHASE_REQUEST_ITEMS';
		$this->itemTableColumnRef = 'purchase_request_id';
		$this->itemTableColumnReqRef = 'purchaseRequestId';
		$this->tableName = constant($this->tableNameStr);
		$this->itemTableName = constant($this->itemTableNameStr);
	}


	/**
	 * @METHOD NAME 	: savePurchaseRequest()
	 *
	 * @DESC 			: TO SAVE THE PURCHASE TRANSACTIONS
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT : -
	 **/
	public function savePurchaseRequest($getPostData)
	{
		$rowData 				  = bindConfigTableValues($this->tableNameStr, 'CREATE', $getPostData);

		// APPROVAL PROCESS: APPROVAL STATUS CODE INCLUDED
		$getapprovalProcessStatus = checkApprovalProcessStatus($getPostData, $this->tableNameStr);
		if(($getapprovalProcessStatus['approvalStatus'] == 1) && 
		   ($getPostData['documentNumberingType']!='DRAFT')
		  ){
			// Change Orginal document to Draft Document 
			$getDocumentNumberDetails 			= getDocumentNumberTypeId($this->tableNameStr,'DRAFT');
			$rowData['document_numbering_id'] 	= $getDocumentNumberDetails[0]['id'];
			$rowData['document_numbering_type'] = $getDocumentNumberDetails[0]['document_numbering_type'];
			$rowData['is_draft']				= 1;
			//$getPostData['isDraft']				= 1; // FOR SUB-CHILD TABLE MAKING DRAFT TO 1
			$rowData['approval_status']			= $getapprovalProcessStatus['approvalStatus'];
		}

		// IDENTIFY THE DOCUMENT MODE 
		$documentProcessMode = identityDocumentMode($getPostData,$getapprovalProcessStatus['approvalStatus']);
	
		// Adding Transaction Start
		$this->app_db->trans_start();
		
		// Checks for duplicate and Process next number (both Custom, Manual).
		$DocNumInfo = processDocumentNumber($rowData, $this->tableName);
		// Assinging document number after processed.
		$rowData['document_number'] = $DocNumInfo['documentNumber'];

		// To update next document number.
		updateNextNumber($rowData, $rowData['document_numbering_type']);

		// Remove document_nubmer_type, as no need for db operation.
		unset($rowData['document_numbering_type']);
		

		$rowData['branch_id'] = $this->currentbranchId;
		$insertId 		= $this->commonModel->insertQry($this->tableName, $rowData);

		if ($insertId > 0 && isset($getPostData['itemListArray'])) {
			
			// SAVE PARENT ITEMS DETAILS 
			transProcessItems('', $getPostData, $this->tableNameStr,$documentProcessMode);

			// SAVE THE TRANSACTION TRACKING DETAILS
			trackTransaction($this->tableNameStr, $insertId, $getPostData['itemListArray']);

			//Sub-Array Formation 
			$getListData = $getPostData['itemListArray'];

			foreach ($getListData as $key => $value) {
				$value[$this->itemTableColumnReqRef] = $insertId;
				$value['status'] 					 = $getPostData['status'];
				$value['isDraft']					 = $getPostData['isDraft'];
				$this->savePurchaseRequestItems($value);
			}
		}

		// SAVE OPEN QUALITY 
			transCalcOpenQuantity($this->itemTableName, $this->itemTableColumnRef, $insertId);

		// UPDATE OPEN QUANTITY COUNT TO MASTER ITEM TABLE FOR RELAVANT SCREENS 
			updateOpenQuantityCountToItemTbl($this->tableNameStr, $this->itemTableNameStr, $getPostData,$documentProcessMode);



		// APPROVAL PROCESS: Save the record into approval status report 
		if(($getapprovalProcessStatus['approvalStatus'] == 1) && 
		   ($getPostData['documentNumberingType']!='DRAFT')
		  ){ 
			$passApprovalStatusRecord['document_id']		 = $insertId;
			$passApprovalStatusRecord['approval_stages_id']	 = $getapprovalProcessStatus['approvalStagesId'];
			$passApprovalStatusRecord['document_number']	 = $rowData['document_number'];
			//printr($passApprovalStatusRecord);			exit;
			$approvalResult = saveApprovalStatusReport($passApprovalStatusRecord,$this->tableNameStr);

			// SEND NOTIFICATION WITH PAYLOAD INFORMATION - START. 
			$notification_content = str_replace("STATUS-MSG", 'Created', lang('NOTIFY_MSG_01')[0]);
			$notificationPayload['content']		 = $notification_content;
			$notificationPayload['document_id']		 = $insertId;
			$notificationPayload['document_type_id'] = $approvalResult['document_type_id'];
			$receiverIds 							 = $approvalResult['approversId'];
			$receiverIds = explode("-",$receiverIds); // Comma to array conversion.
			sendNotification('WEB','APPROVAL_REQUEST_MODULE',$receiverIds,$notificationPayload);
			// SEND NOTIFICATION WITH PAYLOAD INFORMATION - END.

		  } 

		$this->app_db->trans_complete();
		// TRANSACTION COMPLETE

		// Check the transaction status
		if ($this->app_db->trans_status() === FALSE) {
			$modelOutput['flag'] = 2; // Failure
		} else {
			$modelOutput['sId']	 = $insertId;
			$modelOutput['flag'] = 1; // Success

			// UPDATE THE DISTRIBUTION DETAILS IN TABLE 
			$this->updateDistributionRulesInPurchaseRequest($insertId);
		}
		return $modelOutput;
	}
	

	/**
	 * @METHOD NAME 	: savePurchaseRequestItems()
	 *
	 * @DESC 			: TO SAVE THE PURCHASE REQUEST
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function savePurchaseRequestItems($getPostData, $mobFlag = '')
	{

		if (!empty($getPostData[$this->itemTableColumnReqRef])) {

			$rowData = bindConfigTableValues($this->itemTableNameStr, 'CREATE', $getPostData);
			$rowData['status']					= $getPostData['status'];
			$rowData[$this->itemTableColumnRef] = $getPostData[$this->itemTableColumnReqRef];

			$this->commonModel->insertQry($this->itemTableName, $rowData);
			$modelOutput['flag'] = 1; // Success

			if ($mobFlag == 1) {
				$this->updateDistributionRulesInPurchaseRequest($getPostData[$this->itemTableColumnReqRef]);
			}
		} else {
			$modelOutput['flag'] = 2; // Failure
		}
		return $modelOutput;
	}

	/**
	 * @METHOD NAME 	: updateDistributionRulesInPurchaseRequest()
	 *
	 * @DESC 			: TO UPDATE THE DISTRIBUTION RULES IN PARENT TABLE
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function updateDistributionRulesInPurchaseRequest($mainTableId)
	{
		$query = 'SELECT id, 
			GROUP_CONCAT(distribution_rules_id) AS dist_id 
			FROM ' . $this->itemTableName . ' 
			WHERE 
			is_deleted = 0 
			AND 
			' . $this->itemTableColumnRef . ' =' . $mainTableId;

		$rs = $this->app_db->query($query);

		if ($rs->num_rows() > 0) {
			$resultData = $rs->result_array();
			$distributionRulesId  = array_unique(explode(",", $resultData[0]['dist_id']));

			// UPDATE IN TABLE 
			$whereQry  = array('id' => $mainTableId);
			$updateData['distribution_rules_id'] = implode(",", $distributionRulesId);

			$this->commonModel->updateQry($this->tableName, $updateData, $whereQry);
		}
		$modelOutput['flag'] = 1; // Success
		return $modelOutput;
	}


	/**
	 * @METHOD NAME 	: updatePurchaseRequest()
	 *
	 * @DESC 		 	: TO UPDATE THE PURCHASE REQUEST
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 	 	: $getPostData array
	 * @SERVICE 	 	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function updatePurchaseRequest($getPostData)
	{

		$deletedItemChildIds = $getPostData['deletedItemChildIds'];
		$id        		 	 = $getPostData['id'];

		// Adding Transaction Start
		$this->app_db->trans_start();
		$whereQry = array('id' => $id);

		// GET PARENT RECORD DETAILS 
		$oldMainDetails  	 			  = $this->editPurchaseRequest($id);
		$oldItemDetails['itemListArray']  = $this->editPurchaseRequestItemList($id);
		$oldMainDetails					  = array_merge($oldMainDetails[0], $oldItemDetails);

		// CHECK DOCUMENT FOR UPDATION  
		checkDocumentForUpdate($oldMainDetails,$this->tableNameStr);
		
		// APPROVAL PROCESS: APPROVAL STATUS CODE INCLUDED
		$getapprovalProcessStatus = checkApprovalProcessStatus($getPostData, $this->tableNameStr);
		$approvalStatus 		  = $getapprovalProcessStatus['approvalStatus'];
		
		$rowData = bindConfigTableValues($this->tableNameStr, 'UPDATE', $getPostData);


		// RE-INITIALIZATION PROCEED FOR APPROVAL ACTVITY ONLY FOR THE DOCUMENT WHICH CONTAINS APPROVAL STATUS AS 1 OR 3 
		$reInitalizeFlag = 	reInitializeApprovalProcess($oldMainDetails, $getPostData, $this->tableNameStr);
		if($reInitalizeFlag == 1){
			$approvalStatus 		  	= 1;
			$rowData['approval_status'] = 1; 		 // Reset Approval status to pending status again
			$rowData['is_draft'] 		= 1;		 // Keep draft to 1 for safety purpose
			$getPostData['isDraft']		= 1; 		 // Making draft again to 1 for safety purpose
		}

				
		if($getPostData['isDraft']==2){ // CHANGED FROM IS DRAFT 1 TO 0
	
			
				if(($getapprovalProcessStatus['approvalStatus'] == 1) && 
				   ($getPostData['documentNumberingType']!='DRAFT')){
						$rowData['is_draft'] 		= 1;
						$rowData['approval_status']	= $getapprovalProcessStatus['approvalStatus'];
						$approvalStatus 		  	= 1;
						
						// UPDATE THE DOCUMENT 
						$passApprovalStatusRecord['document_id']		 = $id;
						$passApprovalStatusRecord['approval_stages_id']	 = $getapprovalProcessStatus['approvalStagesId'];
						$passApprovalStatusRecord['document_number']	 = $oldMainDetails['document_number'];
						$approvalResult = saveApprovalStatusReport($passApprovalStatusRecord,$this->tableNameStr);

						
						// SEND NOTIFICATION WITH PAYLOAD INFORMATION - START. 
						$notification_content = str_replace("STATUS-MSG", 'Updated', lang('NOTIFY_MSG_01')[0]);
						$notificationPayload['content']		 = $notification_content;
						$notificationPayload['document_id']		 = $id;
						$notificationPayload['document_type_id'] = $approvalResult['document_type_id'];
						$receiverIds 							 = $approvalResult['approversId'];
						$receiverIds = explode("-",$receiverIds); // Comma to array conversion.
						sendNotification('WEB','APPROVAL_REQUEST_MODULE',$receiverIds,$notificationPayload);
						// SEND NOTIFICATION WITH PAYLOAD INFORMATION - END.

			// END OF APPROVAL PROCESS: APPROVAL STATUS CODE INCLUDED

			}
			else{
				// Checks for duplicate and Process next number (both Custom, Manual)
				$DocNumInfo 				= processDocumentNUmber($rowData, $this->tableName);
				
				// Assinging document number after processed
				$rowData['document_number'] = $DocNumInfo['documentNumber'];
			
				// To update next document number
				updateNextNumber($rowData, $rowData['document_numbering_type']);
				
				// DRAFT MOVED TO NORMAL DOCUMENT 	
				$rowData['is_draft'] = 0;
			}
		}
		
		// ASSIGNING FINAL VALUE TO DRAFT 
		$getPostData['isDraft']  = $rowData['is_draft'];
		//printr($getPostData);exit;

		// IDENTIFY THE DOCUMENT MODE 
		$documentProcessMode = identityDocumentMode($getPostData,$approvalStatus);
				
		// UPDATE PARENT ITEMS DETAILS 
		transProcessItems($oldMainDetails, $getPostData, $this->tableNameStr,$documentProcessMode);

		// Remove document_nubmer_type, as no need for db operation
		unset($rowData['document_numbering_type']);
	
		$rowData['branch_id'] = $this->currentbranchId;
		
		
		$this->commonModel->updateQry($this->tableName, $rowData, $whereQry);

		// PROCESS STAGES. 
		// DELETE OPERATION.
		if (count($deletedItemChildIds) > 0) { // Child values
			foreach ($deletedItemChildIds as $key => $value) {
				$passStageId  = array('id' => $value);
				$this->deletePurchaseRequestItems($passStageId);
			}
		}

		if (isset($getPostData['itemListArray'])) {
			$getListData     = $getPostData['itemListArray'];
			// LIST DATA. 
			foreach ($getListData as $key => $value) {
				$value['isDraft']	= $getPostData['isDraft'];
				if (empty($value['id'])) { // INSERT THE RECORD 
					$rowData = bindConfigTableValues($this->itemTableNameStr, 'UPDATE', $value);
					$rowData[$this->itemTableColumnRef] = $id;
					$this->commonModel->insertQry($this->itemTableName, $rowData);
				} else {
					$value['id']		= $value['id'];				
					$this->updatePurchaseRequestItems($value);
				}
			}
		}

		// UPDATE OPEN QUALITY 
		transCalcOpenQuantity($this->itemTableName, $this->itemTableColumnRef, $id);

		// UPDATE STATUS TO CHILD TABLES 
		updateStatusToChildTable($getPostData, $this->tableNameStr,$documentProcessMode);

		// UPDATE OPEN QUANTITY COUNT TO MASTER ITEM TABLE FOR RELAVANT SCREENS 
		updateOpenQuantityCountToItemTbl($this->tableNameStr, $this->itemTableNameStr, $getPostData, $documentProcessMode);


		// To Complete the Transaction
		$this->app_db->trans_complete();

		if ($this->app_db->trans_status() === FALSE) {
			$modelOutput['flag'] = 2; // Failure
		} else {
			$modelOutput['flag'] = 1; // Success

			// UPDATE THE DISTRIBUTION DETAILS IN TABLE 
			$this->updateDistributionRulesInPurchaseRequest($id);
		}

		return $modelOutput;
	}


	/**
	 * @METHOD NAME   : updatePurchaseRequestItems()
	 *
	 * @DESC 		  : TO UPDATE THE PURCHASE REQUEST ITEMS. 
	 * @RETURN VALUE  : $modelOutput array
	 * @PARAMETER 	  : $getPostData array
	 * @SERVICE 	  : WEB
	 * @ACCESS POINT  : -
	 **/
	public function updatePurchaseRequestItems($getPostData, $mobFlag = '')
	{
		$whereQry = array('id' => $getPostData['id']);

		$rowData = bindConfigTableValues($this->itemTableNameStr, 'UPDATE', $getPostData);

		$this->commonModel->updateQry($this->itemTableName, $rowData, $whereQry);

		if ($mobFlag == 1) {
			$this->updateDistributionRulesInPurchaseRequest($getPostData[$this->itemTableColumnReqRef]);
		}
		$modelOutput['flag'] = 1; // Success
		return $modelOutput;
	}


	/**
	 * @METHOD NAME   : editPurchaseRequest()
	 *
	 * @DESC 		  : TO EDIT THE PURCHASE TRANSACTION. 
	 * @RETURN VALUE  : $rs array
	 * @PARAMETER 	  : $getPostData array
	 * @SERVICE 	  : WEB
	 * @ACCESS POINT  : -
	 **/
	public function editPurchaseRequest($id)
	{
		$rowData = bindConfigTableValues($this->tableNameStr, 'EDIT', $id);
		$this->app_db->select($rowData);
		$this->app_db->from($this->tableName);
		$this->app_db->where('id', $id);
		$this->app_db->where('is_deleted', '0');
		$this->app_db->where_in('branch_id', explode(",",$this->currentUserBranchIds));
		$rs = $this->app_db->get();
		return  $rs->result_array();
	}


	/**
	 * @METHOD NAME   : editPurchaseRequestItemList()
	 *
	 * @DESC 		  : TO EDIT THE PURCHASE TRANSACTION ITEM LIST. 
	 * @RETURN VALUE  : $rs array
	 * @PARAMETER 	  : $getPostData array
	 * @SERVICE 	  : WEB
	 * @ACCESS POINT  : -
	 **/
	public function editPurchaseRequestItemList($id)
	{
		$rowData = bindConfigTableValues($this->itemTableNameStr, 'EDIT', $id);
		$this->app_db->select($rowData);
		$this->app_db->from($this->itemTableName);
		$this->app_db->where($this->itemTableColumnRef, $id);
		$this->app_db->where('is_deleted', '0');
		$rs = $this->app_db->get();
		return $rs->result_array();
	}


	/**
	 * @METHOD NAME 	: deletePurchaseRequestItems()
	 *
	 * @DESC 			: TO DELETE THE PURCHASE REQUEST ITEMS
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function deletePurchaseRequestItems($getPostData, $mobFlag = '')
	{
		// GET THE PURCHASE REQUEST ID 
		$mainTableId  = $this->getMainTableId($getPostData['id']);

		// DELETE IN ITEM TABLE
		$whereQry  = array('id' => $getPostData['id']);
		$this->commonModel->deleteQry($this->itemTableName, $whereQry);

		if ($mobFlag == 1) {
			$this->updateDistributionRulesInPurchaseRequest($mainTableId);
		}
		$modelOutput['flag'] = 1; // Success
		return $modelOutput;
	}

	/**
	 * @METHOD NAME 	: getMainTableId()
	 *
	 * @DESC 			: TO GET MAIN TABLE ID IN DELETE ITEM FUNCTION.
	 * @RETURN VALUE 	: $rs array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function getMainTableId($id)
	{
		$this->app_db->select(array('id', $this->itemTableColumnRef));
		$this->app_db->from($this->itemTableName);
		$this->app_db->where('id', $id);
		//$this->app_db->where('is_deleted', '0');
		$rs = $this->app_db->get();
		$resultData  = $rs->result_array();
		return $purchaseRequestId = $resultData[0][$this->itemTableColumnRef];
	}


	/**
	 * @METHOD NAME   : proceedApprovalActivityForDocument()
	 *
	 * @DESC 		  : -
	 * @RETURN VALUE  : $modelOutput array
	 * @PARAMETER 	  : $getPostData array
	 * @SERVICE 	  : WEB
	 * @ACCESS POINT  : -
	 **/
	public function proceedApprovalActivityForDocument($getTableData)
	{
		$id								  = $getTableData[0]['id'];
		
		// GET PARENT RECORD DETAILS 
		$oldMainDetails  	 			  = $getTableData[0];
		$oldItemDetails['itemListArray']  = $this->editPurchaseRequestItemList($id);
		$oldMainDetails					  = array_merge($oldMainDetails, $oldItemDetails);

		//printr($getTableData[0]);exit;
		
		// TRANSACTIONS START 
		$this->app_db->trans_start();
		
			$modelOutput = $this->commonModel->convertDraftDocumentToApprovedDocument($this->tableNameStr,$id);
			
			if($modelOutput['flag'] == 1) { // Successfully converted 
			
				$getPostData 			 = $oldMainDetails;
				$getPostData['deletedItemChildIds'] = array();
				$getPostData['is_draft'] = 0; 	// CONVERTING TO NORMAL DOCUMENT 
				$documentProcessMode	 = 'PROCESS_DOCUMENT';
			
				// PROCESS THE DOCUMENT TO NEXT STAGE ACTVITY
				transProcessItems($oldMainDetails, $getPostData, $this->tableNameStr,$documentProcessMode);
				
				// UPDATE OPEN QUALITY TO CURRENT TABLE 
				transCalcOpenQuantity($this->itemTableName, $this->itemTableColumnRef, $id);

				// UPDATE STATUS TO CHILD TABLES 
				updateStatusToChildTable($getPostData, $this->tableNameStr,$documentProcessMode);

				// UPDATE OPEN QUANTITY COUNT TO MASTER ITEM TABLE FOR RELAVANT SCREENS 
				updateOpenQuantityCountToItemTbl($this->tableNameStr, $this->itemTableNameStr, $getPostData, $documentProcessMode);
			
			}else { // FAILURE CRITERIA
				return $modelOutput;
			}
		
		$this->app_db->trans_complete();
		
		 if ($this->app_db->trans_status() === FALSE) {
            $modelOutput['flag'] = 2; // Failure
        } else {
            $modelOutput['flag'] = 1; // Success
        }
		 return $modelOutput;
	}
	

	/**
	 * @METHOD NAME 	: getPurchaseRequestList()
	 *
	 * @DESC 			: TO GET THE PURCHASE REQUEST LIST
	 * @RETURN VALUE 	: $modelData array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function getPurchaseRequestList($getPostData, $downloadFlag = '')
	{
		// GET THE EMPLOYEE DISTRIBUTION LIST 
		$empDetails  			= $this->commonModel->getProfileInformation($this->currentUserId);
		$empDistributionRulesId = $empDetails['profileInfo'][0]['distribution_rules_id'];


		$query = 'SELECT * FROM 
					(SELECT
					  a.id,
					  a.document_number,
					  a.requester_type_id,
					  a.requester_id,
					  a.posting_date,
					  a.valid_until,
					  a.document_date,
					  a.total_amount,
					  a.status,
					  a.remarks,
					  a.udf_fields,
					  a.distribution_rules_id,
					  a.is_draft,
					  a.approval_status,
					  a.branch_id,
					  a.created_on,
					  a.updated_on,
					  a.posting_status,
					  a.sap_id,
					  a.sap_error,
					  a.created_by,
					  CONCAT(cep.first_name," ",cep.last_name) as created_by_name,

					  datediff(DATE(a.valid_until),DATE(NOW())) as over_due_value,
					  
					/* EMPLOYEE MASTER */
					CONCAT(ep.first_name," ",ep.last_name) as requester_name,
					ep.emp_code as requester_code,
					ep.profile_img,
					
					 /* BRANCH INFORMATION */
					mb.branch_code,
					mb.branch_name
					
					FROM ' . $this->tableName . ' as a	
					LEFT JOIN ' . EMPLOYEE_PROFILE . ' as ep 
						ON ep.id = a.requester_id
					LEFT JOIN ' . EMPLOYEE_PROFILE . ' as cep 
						ON cep.id = a.created_by
					LEFT JOIN ' . MASTER_BRANCHES . ' as mb 
						ON mb.id = a.branch_id
					
					WHERE a.is_deleted = 0
						AND a.branch_id in  (' . $this->currentUserBranchIds . ')
					) as a 
					WHERE id!=0';

		// ADMIN CONDITION & RM Flow 
		if (($this->hierarchyMode == 2) && $this->currentAccessControlId != 1) {
			$totalGroupUsersCount = count($this->currentgroupUsers);
			if ($totalGroupUsersCount > 0) {
				$query .= ' AND created_by in (' . implode(",", $this->currentgroupUsers) . ')';
			}
		}


		// TABLE PROPERTIES AND SEARCH DATA MANUIPULATION
		$tableProperties = $getPostData['tableProperties'];
		$filters         = $getPostData['search'];

		// SEARCH
		if (count($filters) > 0) {
			foreach ($filters as $key => $value) {
				$fieldName  = $key;
				$fieldValue = $value;
				if ($fieldValue!="") {
					if ($fieldName == "requesterName") {
						$query .= ' AND LCASE(concat(requester_name," ",requester_code)) REGEXP LCASE(replace("' . strtolower($fieldValue) . '"," ","|"))';
					} else if ($fieldName == "documentNumber") {
						$query .= ' AND document_number = "' . $fieldValue . '"';
					} else if ($fieldName == "postingDate") {
						$query .= ' AND DATE(posting_date) = "' . $fieldValue . '"';
					} else if ($fieldName == "validUntil") {
						$query .= ' AND DATE(valid_until) = "' . $fieldValue . '"';
					} else if ($fieldName == "totalAmount") {
						$query .= ' AND LCASE(total_amount) REGEXP LCASE(replace("' . strtolower($fieldValue) . '"," ","|"))';
					} else if ($fieldName == "status") {
						$query .= ' AND status = "' . $fieldValue . '"';
					} else if ($fieldName == "sapId") {
						$query .= ' AND sap_id = "' . $fieldValue . '"';
					} else if ($fieldName == "postingStatus") {
						$query .= ' AND posting_status = "' . $fieldValue . '"';
					} else if ($fieldName == "fromDate") {
						$query .= ' AND DATE(created_on) >= "' . $fieldValue . '"';
					} else if ($fieldName == "toDate") {
						$query .= ' AND DATE(created_on) <= "' . $fieldValue . '"';
					} else if ($fieldName == "createdByName") {
						$query .= ' AND LCASE(created_by_name) REGEXP LCASE(replace("' . strtolower($fieldValue) . '"," ","|"))';
					} else if ($fieldName == "branchName") {
						$query .= ' AND LCASE(CONCAT(branch_code," ",branch_name)) REGEXP LCASE(replace("' . strtolower($fieldValue) . '"," ","|"))';
					}else if ($fieldName == "isDraft") {
						$query .= ' AND is_draft = "' . $fieldValue . '"';
					} 
				}
			}
		}
		


		// ORDERING 
		if (isset($tableProperties['sortField'])) {
			$fieldName = $tableProperties['sortField'];
			$sortOrder = ($tableProperties['sortOrder'] == 1) ? "asc" : "desc";

			// GET SORT KEY DETAILS 
			$fieldName	   = getListingParams($this->config->item('PURCHASE_REQUEST')['columns_list'], $fieldName);

			if (!empty($fieldName)) {
				$query .= ' ORDER BY ' . $fieldName . ' ' . $sortOrder;
			}
		} else {
			$query .= ' ORDER BY updated_on desc';
		}

		// PAGINATION
		if (isset($tableProperties['first'])) {
			$offset = $tableProperties['first'];
			$limit  = $tableProperties['rows'];
		} else {
			$offset = 0;
			$limit  = $tableProperties['rows'];
		}

		// SEARCH RESULT DATA 
		$rs				   = $this->app_db->query($query);
		$searchResultData  = $rs->result_array();

		// CHECK HIRARACHY MODE
		if (($this->hierarchyMode == 1) && ($this->currentAccessControlId != 1)) {	// TO FIND THE DISTRIBUTION RULES RECORD
			$searchResultData  = processDistributionRulesData($searchResultData, $empDistributionRulesId);
			$totalRecords 	   = count($searchResultData);
		} else if (($this->hierarchyMode == 1) && ($this->currentAccessControlId == 1)) {
			$totalRecords 	= count($searchResultData);
		} else if ($this->hierarchyMode == 2) { // Hirarchy Mode -> 2 (Reporting Manager Flow)
			$totalRecords 					= count($searchResultData);
		}


		// DOWNLOAD BASED OPERATIONS 
		if (empty($downloadFlag)) {
			$searchResultSet = getOffSetRecords($searchResultData, $offset, $limit);
		} else {
			$searchResultSet = $searchResultData;
		}


		// FRAME OTHER DETAIL INFORMATION 
		$passType['type'] = 'PURCHASE_TRANS_STATUS';
		$StatusList = $this->commonModel->getMasterStaticDataAutoList($passType, 2);


		foreach ($searchResultSet as $key => $value) {
			$StatusId 	= array_search($value['status'], array_column($StatusList, 'id'));
			$statusName = "";
			if ($StatusId !== false) {
				$statusName = $StatusList[$StatusId]['name'];
			}

			$searchResultSet[$key]['status_name'] 	= $statusName;
			$searchResultSet[$key]['emp_img_url'] 	= getFullImgUrl('employee', $value['profile_img']);
		}
		// MODEL DATA 
		$modelData['searchResults'] = $searchResultSet;
		$modelData['totalRecords']  = $totalRecords;
		return $modelData;
	}


	/**
	 * @METHOD NAME 	: getPurchaseRequestItemList()
	 *
	 * @DESC 			: TO GET THE PURCHASE REQUEST ITEM LIST 
	 * @RETURN VALUE 	: $rs array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function getPurchaseRequestItemList($id)
	{

		$rowData = bindConfigTableValues($this->itemTableNameStr, 'EDIT', $id);
		$this->app_db->select($rowData);
		$this->app_db->from($this->itemTableName);
		$this->app_db->where($this->itemTableColumnRef, $id);

		$this->app_db->where('is_deleted', '0');
		$rs = $this->app_db->get();
		return $rs->result_array();
	}


	//------------------------------------------------------------------------------------



}
