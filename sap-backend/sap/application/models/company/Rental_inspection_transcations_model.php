<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Rental_inspection_transcations_model.php
* @Class  			 : Rental_inspection_transcations_model
* Model Name         : Rental_inspection_transcations_model
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : -
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : Added comment blocks and header details
* Features           : 
*/
class Rental_inspection_transcations_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	
	/**
	 * @METHOD NAME 	: initScreenConfig()
	 *
	 * @DESC 			: This function is called from the constructor in the controller. To set the parameters in the init.
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT	: -
	 **/
	public function initScreenConfig($controllerName){
		$screenDetails		= $this->config->item('SCREEN_NAMES')[strtoupper($controllerName)];
		$childRefTblId  	= $screenDetails['childRefId'];
		$childRefId 		= toCamelCaseSingleWord($childRefTblId);
		$this->tableNameStr 		 = strtoupper(str_replace("tbl_","",$screenDetails['tableName']));
		$this->itemTableNameStr		 = strtoupper(str_replace("tbl_","",$screenDetails['childTableName']));
		$this->itemTableColumnRef 	 = $screenDetails['childRefId'];
		$this->itemTableColumnReqRef = $childRefId;
		$this->tableName 			 =  constant($this->tableNameStr);
		$this->itemTableName 		 =  constant($this->itemTableNameStr);
	}
	
	
	/**
	 * @METHOD NAME 	: saveRentalInspectionTransactions()
	 *
	 * @DESC 			: -
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT	: -
	 **/
	public function saveRentalInspectionTransactions($getPostData)
	{	
		//printr($getPostData); exit;
		
		$rowData			  = bindConfigTableValues($this->tableNameStr, 'CREATE', $getPostData);
		
		// INSPECTION DOES NOT CONTAIN THE DELETED CHILD IDS
		//SO OVERWRITE THE CODE MANUALLY BY PASSING THE deletedItemChildIds & rentalEquipmentId
		$getPostData['deletedItemChildIds'] = array();
		$getPostData['itemListArray'][0]['rentalEquipmentId'] = $getPostData['rentalEquipmentId']; 
		$getPostData['itemListArray'][0]['rentalItemId']	  = $getPostData['rentalItemId']; 

		
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
						
		
		// PROCESS THE ITEMS 
		processRentalItems('', $getPostData, $this->tableNameStr);
	
		// SAVE THE TRANSACTION TRACKING DETAILS
		trackTransaction($this->tableNameStr, $insertId, $getPostData['itemListArray']);
			
		if ($insertId > 0 && isset($getPostData['itemListArray'])) {

			//Sub-Array Formation 
			$getListData = $getPostData['itemListArray'];

			foreach ($getListData as $key => $value) {
				$value[$this->itemTableColumnReqRef] = $insertId;
				$value['status'] 					 = $getPostData['status'];
				$value['isDraft']					 = $getPostData['isDraft'];
				$this->saveItems($value);
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
	 * @DESC 			: -
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	
	public function saveItems($getPostData)
	{
		if (!empty($getPostData[$this->itemTableColumnReqRef])) {

			$rowData 							= bindConfigTableValues($this->itemTableNameStr, 'CREATE', $getPostData);
			$rowData[$this->itemTableColumnRef] = $getPostData[$this->itemTableColumnReqRef];
			$rowData['status'] 					= $getPostData['status'];
			$this->commonModel->insertQry($this->itemTableName, $rowData);
			$modelOutput['flag'] = 1; // Success

		} else {
			$modelOutput['flag'] = 2; // Failure
		}
		return $modelOutput;
	}
	
		
	/**
	 * @METHOD NAME 	: updateRentalInspectionTransactions()
	 *
	 * @DESC 		 	: TO UPDATE THE RENTAL INSPECTION TRANSACTIONS 
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 	 	: $getPostData array
	 * @SERVICE 	 	: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function updateRentalInspectionTransactions($getPostData)
	{
		$id        			 = $getPostData['id'];

		// Adding Transaction Start
		$this->app_db->trans_start();
		$whereQry = array('id' => $id);
		
		// GET PARENT RECORD DETAILS 
		$oldMainDetails  	 			  = $this->editRentalInspectionTransactions($id);
		$oldItemDetails['itemListArray']  = $this->editItemList($id);
		$oldMainDetails					  = array_merge($oldMainDetails[0], $oldItemDetails);
		
		// INSPECTION DOES NOT CONTAIN THE DELETED CHILD IDS
		//SO OVERWRITE THE CODE MANUALLY BY PASSING THE deletedItemChildIds & rentalEquipmentId
		$getPostData['deletedItemChildIds'] = array();
		$getPostData['itemListArray'][0]['rentalEquipmentId'] = $getPostData['rentalEquipmentId']; 
		$getPostData['itemListArray'][0]['rentalItemId']	  = $getPostData['rentalItemId']; 
		
		
		// UPDATE PARENT ITEMS DETAILS 
		processRentalItems($oldMainDetails, $getPostData, $this->tableNameStr);
		
		// ROW DATA MANIPULATION 	
		$rowData			  = bindConfigTableValues($this->tableNameStr, 'UPDATE', $getPostData);
						
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
	
		$rowData['branch_id'] = $this->currentbranchId;
	
		if($getPostData['isDraft'] == 2) {  // DRAFT MOVED TO NORMAL DOCUMENT 
			$rowData['is_draft'] = 0;
		}		
		
		$this->commonModel->updateQry($this->tableName, $rowData, $whereQry);

	
		if(isset($getPostData['itemListArray'])){
			$getListData     = $getPostData['itemListArray'];
			// LIST DATA. 
			foreach ($getListData as $key => $value) {
				//$value[$this->itemTableColumnRef] = $id;
				$value['isDraft']	= $getPostData['isDraft'];
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

		// To Complete the Transaction
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
	 * @DESC 		  : - 
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
	 * @METHOD NAME   : editRentalInspectionTransactions()
	 *
	 * @DESC 		  : -
	 * @RETURN VALUE  : $rs array
	 * @PARAMETER 	  : $getPostData array
	 * @SERVICE 	  : WEB
	 * @ACCESS POINT  : -
	 **/
	public function editRentalInspectionTransactions($id)
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
	 * @DESC 		  : - 
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
		return $parentTableId = $resultData[0][$this->itemTableColumnRef];
	}


	/**
	 * @METHOD NAME 	: getRentalTransactionsList()
	 *
	 * @DESC 			: -
	 * @RETURN VALUE 	: $modelData array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function getRentalInspectionTransactionsList($getPostData, $downloadFlag = '')
	{
		// GET THE EMPLOYEE DISTRIBUTION LIST 
		$empDetails  			= $this->commonModel->getProfileInformation($this->currentUserId);
		$empDistributionRulesId = $empDetails['profileInfo'][0]['distribution_rules_id'];

		$query = 'SELECT * FROM 
					(SELECT
					  a.id,
					  a.document_number,
					  a.customer_bp_id,
					  a.customer_bp_contacts_id,
					  a.currency_id,
					  a.rental_item_id,
					  a.rental_equipment_id,
					  a.reference_number,
					  a.emp_id,
					  a.status,
					  a.remarks,
					  a.inspection_overall_status,
					  a.inspection_template_id,
					  a.udf_fields,
					  a.branch_id,
					  a.is_draft,
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

					  /* BRANCH INFORMATION */
					  mb.branch_code,
					  mb.branch_name,
					  
					  /* MASTER RENTAL ITEM NAME */
					  mri.rental_item_code,
					  mri.rental_item_name,
					  
					  /* MASTER RENTAL EQUIPMENT */
					  mre.equipment_code,
					  mre.equipment_name

					FROM ' . $this->tableName . ' as a	
					
					LEFT JOIN '.BUSINESS_PARTNER.' as bp
						ON bp.id = a.customer_bp_id
						
					LEFT JOIN '.MASTER_CURRENCY.' as mc
						ON mc.id = a.currency_id

					LEFT JOIN '.EMPLOYEE_PROFILE.' as cep 
						ON cep.id = a.created_by
					
					LEFT JOIN '.MASTER_BRANCHES.' as mb 
						ON mb.id = a.branch_id
					
					LEFT JOIN '.MASTER_RENTAL_ITEM.' as mri 
						ON mri.id = a.rental_item_id
					
					LEFT JOIN '.MASTER_RENTAL_EQUIPMENT.' as mre 
						ON mre.id = a.rental_equipment_id
				
					WHERE a.is_deleted = 0
						AND a.branch_id in  (' . $this->currentUserBranchIds . ')
					) as a 
					WHERE id!=0';

		// ADMIN CONDITION & RM Flow 
		if(($this->hierarchyMode==2) && $this->currentAccessControlId!=1){
			$totalGroupUsersCount = count($this->currentgroupUsers);
			if($totalGroupUsersCount>0){
				$query.= ' AND created_by in ('.implode(",",$this->currentgroupUsers).')';
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
						$query.=' AND LCASE(concat(customer_bp_name," ",customer_bp_code)) REGEXP LCASE(replace("'.strtolower($fieldValue).'"," ","|"))';
					} 
					else if ($fieldName == "documentNumber") {
							$query .= ' AND LCASE(document_number) REGEXP LCASE(replace("' . strtolower($fieldValue) . '"," ","|"))';
					} 
					else if ($fieldName == "status") {
						$query .= ' AND status = "' . $fieldValue . '"';
					} 
					else if ($fieldName == "sapId") {
						$query .= ' AND sap_id = "' . $fieldValue . '"';
					}
					else if ($fieldName == "postingStatus") {
						$query .= ' AND posting_status = "' . $fieldValue . '"';
					}
					else if ($fieldName == "fromDate") {
						$query .= ' AND DATE(created_on) >= "' . $fieldValue . '"';
					} 
					else if ($fieldName == "toDate") {
						$query .= ' AND DATE(created_on) <= "' . $fieldValue . '"';
					}
					else if ($fieldName == "rentalItemName") {
						$query.=' AND LCASE(CONCAT(rental_item_name," ",rental_item_code)) REGEXP LCASE(replace("'.strtolower($fieldValue).'"," ","|"))';
					}
					else if($fieldName=="createdByName"){
						$query.=' AND LCASE(created_by_name) REGEXP LCASE(replace("'.strtolower($fieldValue).'"," ","|"))';
					}
					else if($fieldName=="branchName"){
						$query.=' AND LCASE(CONCAT(branch_code," ",branch_name)) REGEXP LCASE(replace("'.strtolower($fieldValue).'"," ","|"))';
					}
					else if ($fieldName == "isDraft") {
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
			$fieldName	   = getListingParams($this->config->item($this->tableNameStr)['columns_list'], $fieldName);

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
		}else if ($this->hierarchyMode==2){ // Hirarchy Mode -> 2 (Reporting Manager Flow)
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
		$StatusList = $this->commonModel->getMasterStaticDataAutoList($passType,2);

		foreach ($searchResultSet as $key => $value) {
			$StatusId 	= array_search($value['status'], array_column($StatusList, 'id'));
			$statusName = "";
			if ($StatusId !== false) {
				$statusName = $StatusList[$StatusId]['name'];
			}

			$searchResultSet[$key]['status_name'] 	= $statusName;
			//$searchResultSet[$key]['emp_img_url'] 	= getFullImgUrl('employee', $value['profile_img']);
			
		}

		// MODEL DATA 
		$modelData['searchResults'] = $searchResultSet;
		$modelData['totalRecords']  = $totalRecords;
		return $modelData;
	}

	/**
	 * @METHOD NAME 	: getItemList()
	 *
	 * @DESC 			: -
	 * @RETURN VALUE 	: $rs array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function getItemList($id)
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
	 * @METHOD NAME   : checkCopyInspectionTemplateStatus()
	 *
	 * @DESC 		  : TO CHECK THE "inspection_overall_status" IS 1 TO PROCEED
	 * @RETURN VALUE  : $rs array
	 * @PARAMETER 	  : $getPostData array
	 * @SERVICE 	  : WEB
	 * @ACCESS POINT  : -
	 **/
	public function checkCopyInspectionTemplateStatus($copiedId)
	{
		$rowData = array('id');
		$this->app_db->select($rowData);
		$this->app_db->from($this->tableName);
		$this->app_db->where_in('id', $copiedId);
		$this->app_db->where('inspection_overall_status!=', 1);
		$this->app_db->where('is_deleted', '0');
		$this->app_db->where('branch_id', $this->currentbranchId);
		$rs = $this->app_db->get();
		$totRows =  $rs->num_rows();
		if($totRows > 0) {
			return 0;
		}else {
			return 1;
		}
	}
	
//////////////////////////////////////////// COPIED FROM FUNCITONALITY CODE ADDED  ////////////////////////////////////////////
	/**
	 * @METHOD NAME   : refEditItemList()
	 *
	 * @DESC 		  : - 
	 * @RETURN VALUE  : $rs array
	 * @PARAMETER 	  : $getPostData array
	 * @SERVICE 	  : WEB
	 * @ACCESS POINT  : -
	 **/
	public function refEditItemList($id,$copyFromType)
	{	
		$screenDetails		= $this->config->item('SCREEN_NAMES')[$copyFromType];
		$parentTableName 	= $screenDetails['tableName'];
		$childTableName		= $screenDetails['childTableName'];
		$fieldName			= $screenDetails['childRefId'];
		$formatBindTableName = strtoupper(str_replace("tbl_","",$childTableName)); // ACCEPT ONLY CONSTANT IN BINDING OPERATION
		$rowData = bindConfigTableValues($formatBindTableName, 'EDIT', $id);
		$this->app_db->select($rowData);
		$this->app_db->from($childTableName);
		$this->app_db->where('id', $id);
		$this->app_db->where('is_deleted', '0');
		$rs = $this->app_db->get();
		return $rs->result_array();
	}

//////////////////////////////////////////// END OF COPIED FROM FUNCITONALITY CODE ADDED  ////////////////////////////////////////////

	
}