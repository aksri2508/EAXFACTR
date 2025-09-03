<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Rental_worklog_model.php
* @Class  			 : Rental_worklog_model
* Model Name         : Rental_worklog_model
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
class Rental_worklog_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->tableNameStr = 'RENTAL_WORKLOG';
		if($this->rentalWorklogSheetType == 1){
			$this->itemTableNameStr = 'RENTAL_WORKLOG_ITEMS';
		}else if($this->rentalWorklogSheetType == 2){
			$this->itemTableNameStr = 'RENTAL_WORKLOG_ITEMS_TYPE_2';
		}
		$this->itemTableColumnRef = 'rental_worklog_id';
		$this->itemTableColumnReqRef = 'rentalWorklogId';
		$this->tableName = constant($this->tableNameStr);
		$this->itemTableName = constant($this->itemTableNameStr);
	}


	/**
	 * @METHOD NAME 	: saveRentalWorklog()
	 *
	 * @DESC 			: -
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function saveRentalWorklog($getPostData)
	{

		$rowData = bindConfigTableValues($this->tableNameStr, 'CREATE', $getPostData);

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
		$insertId 			  = $this->commonModel->insertQry($this->tableName, $rowData);
        
		// SAVE THE TRANSACTION TRACKING DETAILS
		trackTransaction($this->tableNameStr, $insertId, $getPostData['itemListArray']);
			
			
		if ($insertId > 0 && isset($getPostData['itemListArray'])) {

			//Sub-Array Formation 
			$getListData = $getPostData['itemListArray'];
			
			foreach ($getListData as $key => $value) {
				$value[$this->itemTableColumnReqRef] = $insertId;
				$value['parentStartDate'] = $getPostData['startDate']." 00:00:00";
				$value['parentEndDate'] = $getPostData['endDate']." 23:59:59";;
				$value['status'] = $getPostData['status'];
				$value['rentalEquipmentId'] = $getPostData['rentalEquipmentId'];
				$value['isDraft']			= $getPostData['isDraft'];
				$itemModelOutput = $this->saveItems($value);
				// print_r($itemModelOutput);exit;
				if (isset($itemModelOutput['status']) && $itemModelOutput['status'] == 'FAIL') {
					$modelOutput['flag'] = $itemModelOutput['StatusNumber']; // Failure.
					return $modelOutput;
				}
			}
		}

		$this->app_db->trans_complete();
		// TRANSACTION COMPLETE

		// Check the transaction status
		if ($this->app_db->trans_status() === FALSE) {
			$modelOutput['flag'] = 2; // Failure
		} else {
			$modelOutput['sId']	 = $insertId;
			$modelOutput['flag'] = 1; // Success
		}
		return $modelOutput;
	}

	
	/**
	 * @METHOD NAME 	: saveItems()
	 *
	 * @DESC 			: TO SAVE THE ITEMS 
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function saveItems($getPostData)
	{
		if($this->rentalWorklogSheetType == 1)
		{
			return $this->saveItemsSheetType1($getPostData);
		}else if ($this->rentalWorklogSheetType == 2)
		{
			return $this->saveItemsSheetType2($getPostData);
		}
	}
	
	
	/**
	 * @METHOD NAME 	: saveItemsSheetType1()
	 *
	 * @DESC 			: TO SAVE THE WORKLOG SHEET TYPE-1
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function saveItemsSheetType1($getPostData){
		if (!empty($getPostData[$this->itemTableColumnReqRef])) {

			$rowData = bindConfigTableValues($this->itemTableNameStr, 'CREATE', $getPostData);
			$rowData[$this->itemTableColumnRef] = $getPostData[$this->itemTableColumnReqRef];
			$rowData['status'] = $getPostData['status'];

			// Check for worklog items already exist.
			$checksConstraints = $this->checkItemConstraints($rowData, 1);

			if($checksConstraints['status'] == 'FAIL'){
				$itemModelOutput = $checksConstraints; // Returns Fail.
			}
            else {
				// Check for worklog items within parent start/end datetime.
				$checksConstraints = $this->checkItemConstraints($rowData, 2, $getPostData);

				if($checksConstraints['status'] == 'FAIL'){
					$itemModelOutput = $checksConstraints; // Returns Fail.
				}
				else {
					$this->commonModel->insertQry($this->itemTableName, $rowData);
					$itemModelOutput['StatusNumber'] = 1; // Success
				}
			}

		} else {
			$itemModelOutput['StatusNumber'] = 2; // Failure
		}
		return $itemModelOutput;
	}
	
	
	/**
	 * @METHOD NAME 	: saveItemsSheetType2()
	 *
	 * @DESC 			: TO SAVE THE WORKLOG SHEET TYPE-2
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function saveItemsSheetType2($getPostData){
		if (!empty($getPostData[$this->itemTableColumnReqRef])) {
			$rowData = bindConfigTableValues($this->itemTableNameStr, 'CREATE', $getPostData);
			$rowData[$this->itemTableColumnRef] = $getPostData[$this->itemTableColumnReqRef];
			$rowData['status'] = $getPostData['status'];
			$this->commonModel->insertQry($this->itemTableName, $rowData);
			$itemModelOutput['StatusNumber'] = 1; // Success
		} else {
			$itemModelOutput['StatusNumber'] = 2; // Failure
		}
		return $itemModelOutput;
	}
	
	
	/**
	 * @METHOD NAME 	: checkItemConstraints()
	 *
	 * @DESC 			: TO CHECK CONSTRAINTS ON ITEMS SAVE/UPDATE
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function checkItemConstraints($itemData, $type = null, $itemPostData = null)
	{

		$checksConstraints = [];

		if ($type == 1) {
			// Checks Constraints1.
			$this->app_db->select(["id"]);
			$this->app_db->from($this->itemTableName);
			$this->app_db->where('rental_equipment_id', $itemData['rental_equipment_id']);

			// FOR DATE-TIME QUOTES ISSUE REFERENCE CHECK - WILL BE REMOVED LATER.
			// -------------------------------------------------------------------
			// $this->app_db->where('(
			// 	((DATE_ADD(start_date_time,INTERVAL 1 MINUTE) >= DATE_ADD("2021-05-19 10:05:00", INTERVAL 1 MINUTE)
			// 	AND DATE_ADD(start_date_time,INTERVAL 1 MINUTE) <= "2021-05-19 10:55:00" ) OR (end_date_time >= DATE_ADD("2021-05-19
			// 		10:05:00", INTERVAL 1 MINUTE)
			// 		AND end_date_time <= "2021-05-19 10:55:00" )) OR ((DATE_ADD("2021-05-19 10:05:00", INTERVAL 1 MINUTE)>=
			// 			DATE_ADD(start_date_time,INTERVAL 1 MINUTE)
			// 			AND DATE_ADD("2021-05-19 10:05:00", INTERVAL 1 MINUTE) <= end_date_time) OR ('."2021-05-19 10:55:00".' >=
			// 				DATE_ADD(start_date_time,INTERVAL 1 MINUTE) AND "2021-05-19 10:55:00" <= end_date_time)))');

			$this->app_db->group_start();
			$this->app_db->group_start();
			$this->app_db->group_start();
			$this->app_db->where("DATE_ADD(start_date_time,INTERVAL 1 MINUTE) >= DATE_ADD('" . $itemData['start_date_time'] . "', INTERVAL 1 MINUTE)");
			$this->app_db->where("DATE_ADD(start_date_time,INTERVAL 1 MINUTE) <= '" . $itemData['end_date_time'] . "')");

			$this->app_db->or_group_start();
			$this->app_db->or_where("end_date_time >= DATE_ADD('" . $itemData['start_date_time'] . "', INTERVAL 1 MINUTE)");

			$this->app_db->where("end_date_time <= '" . $itemData['end_date_time'] . "'");
			$this->app_db->group_end();
			$this->app_db->group_end();

			$this->app_db->or_group_start();
			$this->app_db->group_start();
			$this->app_db->where("DATE_ADD('" . $itemData['start_date_time'] . "', INTERVAL 1 MINUTE) >=
			DATE_ADD(start_date_time,INTERVAL 1 MINUTE)");
			$this->app_db->where("DATE_ADD('" . $itemData['start_date_time'] . "', INTERVAL 1 MINUTE) <= end_date_time)");
			$this->app_db->or_group_start();
			$this->app_db->or_where("'" . $itemData['end_date_time'] . "' >= DATE_ADD(start_date_time,INTERVAL 1 MINUTE)");
			$this->app_db->where("'" . $itemData['end_date_time'] . "' <= end_date_time");
			$this->app_db->group_end();
			$this->app_db->group_end();
			$this->app_db->group_end();

			$this->app_db->where('is_deleted', '0');

			$res = $this->app_db->get();
			// print_r($this->app_db->last_query());exit;


			if ($res && $res->num_rows()) {
				$checksConstraints["status"] = 'FAIL';
				$checksConstraints["StatusNumber"] = 8;
			} else {
				$checksConstraints["status"] = 'SUCCESS';
			}
		}

		
		if ($type == 2) {

			// Checks Constraints1.
			if (($itemData['start_date_time'] >= $itemPostData['parentStartDate'] &&
				$itemData['start_date_time'] <= $itemPostData['parentEndDate']) ||
				($itemData['end_date_time'] >= $itemPostData['parentStartDate'] &&
				$itemData['end_date_time'] <= $itemPostData['parentEndDate'])) {

					$checksConstraints["status"] = 'SUCCESS';
				
			} else {
				
				$checksConstraints["status"] = 'FAIL';
				$checksConstraints["StatusNumber"] = 9;
			}
		}

		return $checksConstraints;
	}


	/**
	 * @METHOD NAME 	: updateRentalWorklog()
	 *
	 * @DESC 		 	: -
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 	 	: $getPostData array
	 * @SERVICE 	 	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function updateRentalWorklog($getPostData)
	{
		$rowData 			 = bindConfigTableValues($this->tableNameStr, 'UPDATE', $getPostData);
		$deletedItemChildIds = $getPostData['deletedItemChildIds'];
		$id        			 = $getPostData['id'];

		// Adding Transaction Start
		$this->app_db->trans_start();

		$whereQry = array('id' => $id);

		$rowData['branch_id'] = $this->currentbranchId;
		
		if($getPostData['isDraft']==2){ // CHANGED FROM IS DRAFT 1 TO 0
	
			// CHECKS FOR DUPLICATE AND PROCESS NEXT NUMBER (BOTH Custom, Manual)
			$DocNumInfo 				= processDocumentNUmber($rowData, $this->tableName);
			
			// Assinging document number after processed
			$rowData['document_number'] = $DocNumInfo['documentNumber'];
		
			// To update next document number
			updateNextNumber($rowData, $rowData['document_numbering_type']);
			
		}
		// Remove document_nubmer_type, as no need for db operation
		unset($rowData['document_numbering_type']);
		
		
		if($getPostData['isDraft'] == 2) {  // DRAFT MOVED TO NORMAL DOCUMENT 
			$rowData['is_draft'] = 0;
		}	
		
		
		
		$this->commonModel->updateQry($this->tableName, $rowData, $whereQry);


		// DELETE OPERATION.
		if (count($deletedItemChildIds) > 0) { // Child values
			foreach ($deletedItemChildIds as $key => $value) {
				$passStageId  = array('id' => $value);
				$this->deleteItems($passStageId);
			}
		}

		if (isset($getPostData['itemListArray'])) {
			$getListData     = $getPostData['itemListArray'];
			// LIST DATA. 
			foreach ($getListData as $key => $value) {

				$value[$this->itemTableColumnReqRef] = $id;
				$value['isDraft']	= $getPostData['isDraft'];
				// print_r($value);exit;
				if (empty($value['id'])) { // INSERT THE RECORD 
					$rowData = bindConfigTableValues($this->itemTableNameStr, 'UPDATE', $value);
					$rowData[$this->itemTableColumnRef] = $id;
					$this->commonModel->insertQry($this->itemTableName, $rowData);
				} else {
					$value['id'] = $value['id'];
					$this->updateItems($value);
				}
			}
		}


		// To Complete the Transaction.
		$this->app_db->trans_complete();

		if ($this->app_db->trans_status() === FALSE) {
			$modelOutput['flag'] = 2; // Failure
		} else {
			$modelOutput['flag'] = 1; // Success
		}
		return $modelOutput;
	}


	/**
	 * @METHOD NAME   : updateItems()
	 *
	 * @DESC 		  : TO UPDATE THE PURCHASE REQUEST ITEMS. 
	 * @RETURN VALUE  : $modelOutput array
	 * @PARAMETER 	  : $getPostData array
	 * @SERVICE 	  : WEB
	 * @ACCESS POINT  : -
	 **/
	public function updateItems($getPostData)
	{
		$whereQry = array('id' => $getPostData['id']);
		$rowData = bindConfigTableValues($this->itemTableNameStr, 'UPDATE', $getPostData);
		$this->commonModel->updateQry($this->itemTableName, $rowData, $whereQry);
		$modelOutput['flag'] = 1; // Success
		return $modelOutput;
	}


	/**
	 * @METHOD NAME   : editRentalWorklog()
	 *
	 * @DESC 		  : -
	 * @RETURN VALUE  : $rs array
	 * @PARAMETER 	  : $getPostData array
	 * @SERVICE 	  : WEB
	 * @ACCESS POINT  : -
	 **/
	public function editRentalWorklog($id)
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
	 * @METHOD NAME   : editItemList()
	 *
	 * @DESC 		  : TO EDIT THE RENTAL WORKLOG ITEM LIST. 
	 * @RETURN VALUE  : $rs array
	 * @PARAMETER 	  : $getPostData array
	 * @SERVICE 	  : WEB
	 * @ACCESS POINT  : -
	 **/
	public function editItemList($id)
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
	 * @METHOD NAME 	: deleteItems()
	 *
	 * @DESC 			: - 
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function deleteItems($getPostData)
	{
		$mainTableId  = $this->getMainTableId($getPostData['id']);
		$whereQry  = array('id' => $getPostData['id']);
		$this->commonModel->deleteQry($this->itemTableName, $whereQry);
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
		if($rs && $rs->num_rows()) {
			$resultData  = $rs->result_array();
			$rentalWorkLogId = $resultData[0][$this->itemTableColumnRef];
		}
		else{
			$rentalWorkLogId = 0;
		}
		return $rentalWorkLogId;
	}


	/**
	 * @METHOD NAME 	: getRentalWorklogList()
	 *
	 * @DESC 			: -
	 * @RETURN VALUE 	: $modelData array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function getRentalWorklogList($getPostData, $downloadFlag = '')
	{
		// GET THE EMPLOYEE DISTRIBUTION LIST 
		$empDetails  = $this->commonModel->getProfileInformation($this->currentUserId);
		$empDistributionRulesId = $empDetails['profileInfo'][0]['distribution_rules_id'];

		$query = 'SELECT * FROM 
					(SELECT
					  a.id,
					  a.document_number,					 
					  a.document_numbering_id,
					  a.rental_item_id,
					  mri.rental_item_code,
					  mri.rental_item_name,
					  a.rental_equipment_id,
					  a.customer_bp_id,
					  a.customer_bp_contacts_id,
					  a.currency_id,
					  a.reference_number,
					  a.start_date,
					  a.end_date,
					  a.shift_start_time,
					  a.shift_end_time,
					  a.document_date,
					  a.emp_id,
					  a.status,
					  a.remarks,
					  a.total_billable_hours,
					  a.udf_fields,
					  a.is_draft,
					  a.branch_id,
					  a.distribution_rules_id,
					  a.created_on,
					  a.updated_on,
					  a.posting_status,
					  a.sap_id,
					  a.sap_error,
					  a.created_by,
					  CONCAT(cep.first_name," ",cep.last_name) as created_by_name,
					  
					  /* BUSINESS PARTNER */
					  bp.partner_name as customer_bp_name,
					  bp.partner_code as customer_bp_code,		
					  
					/* CURRENCY */
					mc.currency_name,
					
					/* BRANCH INFORMATION */
					mb.branch_code,
					mb.branch_name,

					/* Master STATIC DATA */
					msd.name as status_name
					
					FROM ' . $this->tableName . ' as a	
					LEFT JOIN ' . BUSINESS_PARTNER . ' as bp
						ON bp.id = a.customer_bp_id

					LEFT JOIN ' . MASTER_RENTAL_ITEM . ' as mri
						ON mri.id = a.rental_item_id
					
					LEFT JOIN ' . MASTER_CURRENCY . ' as mc
						ON mc.id = a.currency_id

					LEFT JOIN ' . EMPLOYEE_PROFILE . ' as cep 
						ON cep.id = a.created_by
					
					LEFT JOIN ' . MASTER_BRANCHES . ' as mb 
						ON mb.id = a.branch_id
					
					JOIN '.MASTER_STATIC_DATA.' as msd
						ON msd.master_id = a.status and msd.type = "RENTAL_TRANS_STATUS"
					
					WHERE a.is_deleted = 0
						AND a.branch_id in (' . $this->currentUserBranchIds . ')) as a 
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
					if ($fieldName == "customerBpName") {
						$query .= ' AND LCASE(concat(customer_bp_name," ",customer_bp_code)) REGEXP LCASE(replace("' . strtolower($fieldValue) . '"," ","|"))';
					}
					else if ($fieldName == "rentalItemName") {
						$query .= ' AND LCASE(rental_item_name) REGEXP LCASE(replace("' . strtolower($fieldValue) . '"," ","|"))';
					} else if ($fieldName == "documentNumber") {
						$query .= ' AND LCASE(document_number) REGEXP LCASE(replace("' . strtolower($fieldValue) . '"," ","|"))';
					} else if ($fieldName == "startDate") {
						$query .= ' AND DATE(start_date) = "' . $fieldValue . '"';
					} else if ($fieldName == "endDate") {
						$query .= ' AND DATE(end_date) = "' . $fieldValue . '"';
					} else if ($fieldName == "status") {
						$query .= ' AND status = "' . $fieldValue . '"';
					} else if ($fieldName == "sapId") {
						$query .= ' AND sap_id = "' . $fieldValue . '"';
					} else if ($fieldName == "postingStatus") {
						$query .= ' AND posting_status = "' . $fieldValue . '"';
					} else if ($fieldName == "createdByName") {
						$query .= ' AND LCASE(created_by_name) REGEXP LCASE(replace("' . strtolower($fieldValue) . '"," ","|"))';
					} else if ($fieldName == "branchName") {
						$query .= ' AND LCASE(CONCAT(branch_code," ",branch_name)) REGEXP LCASE(replace("' . strtolower($fieldValue) . '"," ","|"))';
					} else if ($fieldName == "isDraft") {
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
			$fieldName	   = getListingParams($this->config->item('RENTAL_WORKLOG')['columns_list'], $fieldName);

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
		$passType['type'] = 'RENTAL_TRANS_STATUS';
		$StatusList = $this->commonModel->getMasterStaticDataAutoList($passType, 2);

		foreach ($searchResultSet as $key => $value) {
			$StatusId 	= array_search($value['status'], array_column($StatusList, 'id'));
			$statusName = "";
			if ($StatusId !== false) {
				$statusName = $StatusList[$StatusId]['name'];
			}

			$searchResultSet[$key]['status_name'] 	= $statusName;

		}

		// MODEL DATA. 
		$modelData['searchResults'] = $searchResultSet;
		$modelData['totalRecords']  = $totalRecords;
		return $modelData;
	}
}
