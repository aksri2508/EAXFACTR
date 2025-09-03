<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* @FileName 		 : Master_rental_item_model.php
* @Class  			 : Master_rental_item_model
* Model Name         : Master_rental_item_model
* Description        :
* Module             : company
* Actors 	         : -
* @author 			 : SPECIFY EMAIL ADDRESS
* @CreatedDate 	     : 01 MAY 2021
* @LastModifiedDate  : -
* @LastModifiedBy    : -
* @LastModifiedDesc  : Added comment blocks and header details
* Features           : 
*/
class Master_rental_item_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->tableNameStr 			= 'MASTER_RENTAL_ITEM';
		$this->tableName 				= constant($this->tableNameStr);
	}


	/**
	 * @METHOD NAME 	: saveRentalItem()
	 *
	 * @DESC 			: TO SAVE THE RENTAL ITEM DETAILS
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function saveRentalItem($getPostData)
	{

		$rowData = bindConfigTableValues($this->tableNameStr, 'CREATE', $getPostData);

		// Adding Transaction Start
		$this->app_db->trans_start();

		$whereExistsQry = array(
			'LCASE(rental_item_code)' => strtolower($getPostData['rentalItemCode']),
		);
		$chkRecord = $this->commonModel->isExists(MASTER_RENTAL_ITEM, $whereExistsQry);

		if (0 == $chkRecord) {

			$insertId 	= $this->commonModel->insertQry($this->tableName, $rowData);

			$getPostData['itemId'] 	= $insertId;

			if ($insertId > 0) {

				$this->app_db->trans_complete(); // TRANSACTION COMPLETE
			}

			// Check the transaction status
			if ($this->app_db->trans_status() === FALSE) {
				$modelOutput['flag'] = 2; // Failure
			} else {
				$modelOutput['sId']	 = $insertId;
				$modelOutput['flag'] = 1; // Success
			}
			return $modelOutput;
		} else {
			$modelOutput['flag'] = 3;
		}
		return $modelOutput;
	}


	/**
	 * @METHOD NAME 	: updateRentalItem()
	 *
	 * @DESC 			: TO UPDATE THE RENTAL ITEM.
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function updateRentalItem($getPostData)
	{

		$id   = $getPostData['id'];

		$whereExistsQry = array(
			'LCASE(rental_item_code)' => strtolower($getPostData['rentalItemCode']),
			'id!='				  => $getPostData['id'],
		);

		$totRows = $this->commonModel->isExists(MASTER_RENTAL_ITEM, $whereExistsQry);

		if (0 == $totRows) {

			// ADDING TRANSACTION START.
			$this->app_db->trans_start();

			$whereQry 	 = array('id' => $getPostData['id']);
			$rowData	 = bindConfigTableValues($this->tableNameStr, 'UPDATE', $getPostData);
			$this->commonModel->updateQry($this->tableName, $rowData, $whereQry);

			$this->app_db->trans_complete(); // TRANSACTION COMPLETE.

			$modelOutput['flag'] = 1;
		} else {
			$modelOutput['flag'] = 3;
		}
		return $modelOutput;
	}


	/**
	 * @METHOD NAME 	: deleteRentalItem()
	 *
	 * @DESC 			: TO DELETE THE RENTAL ITEM.
	 * @RETURN VALUE 	: $modelOutput array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function deleteRentalItem($getPostData)
	{
		// DELETE IN RENTAL ITEM. 
		$whereQry  = array('id' => $getPostData['id']);
		$this->commonModel->deleteQry(MASTER_RENTAL_ITEM, $whereQry);

		$modelOutput['flag'] = 1; // Success
		return $modelOutput;
	}


	/**
	 * @METHOD NAME 	: editRentalItem()
	 *
	 * @DESC 			: TO EDIT THE RENTAL ITEM
	 * @RETURN VALUE 	: $rs array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function editRentalItem($getPostData)
	{
		$rowData = bindConfigTableValues($this->tableNameStr, 'EDIT', $getPostData['id']);
		$this->app_db->select($rowData);
		$this->app_db->from(MASTER_RENTAL_ITEM);
		$this->app_db->where('id', $getPostData['id']);
		$this->app_db->where('is_deleted', '0');
		$rs = $this->app_db->get();
		return $rs->result_array();
	}


	/**
	 * @METHOD NAME 	: getRentlItemList()
	 *
	 * @DESC 			: TO GET THE RENTAL ITEM LIST
	 * @RETURN VALUE 	: $modelData array
	 * @PARAMETER 		: $getPostData array
	 * @SERVICE 		: WEB
	 * @ACCESS POINT 	: -
	 **/
	public function getRentlItemList($getPostData, $downloadFlag = '')
	{
		 // SELECT 
		$query =	'SELECT * FROM
						(
							SELECT
							  a.id,
							  a.rental_item_code,
							  a.rental_item_name,
							  a.foreign_name,
							  a.stock,
							  a.item_group_id,
							  a.hsn_id,
							  a.uom_id,
							  a.rental_item_image,
							  a.status,
							  a.sap_id,
							  a.posting_status,
							  a.sap_error,
							  a.referer_created,
							  a.referer_updated,
							  a.is_deleted,
							  a.updated_on,
							  
					  		  CONCAT(cep.first_name," ",cep.last_name) as created_by_name,
					  
							  /* Master ITEM GROUP */
							  mig.group_code as item_group_code,
							  mig.group_name as item_group_name,
							  
							  /* Master STATIC DATA */
							  msd.name as status_name,
							   
							  /* Master UOM DATA */
							  muom.uom_name as uom_name

							FROM '.$this->tableName.' as a 
							  JOIN '.MASTER_ITEM_GROUP.' as mig
								ON mig.id = a.item_group_id
							  JOIN '.MASTER_STATIC_DATA.' as msd
								ON msd.master_id = a.status and msd.type = "COMMON_STATUS"
							 LEFT JOIN '.MASTER_UOM.' as muom
								ON muom.id = a.uom_id 	
							
							 LEFT JOIN '.EMPLOYEE_PROFILE.' as cep 
								ON cep.id = a.created_by
							
							WHERE msd.type = "COMMON_STATUS"
								AND a.is_deleted = 0
						)
					as a WHERE id!=0';
		
			
		// TABLE PROPERTIES AND SEARCH DATA MANUIPULATION
		$tableProperties = $getPostData['tableProperties'];
		$filters         = $getPostData['search'];

		// SEARCH
		if (count($filters) > 0) {
			foreach ($filters as $key => $value) {
				$fieldName  = $key;
				$fieldValue = $value;
				if ($fieldValue!="") {
					if ($fieldName == "rentalItemName") {
						$query .= ' AND LCASE(CONCAT(rental_item_code," ",rental_item_name)) REGEXP LCASE(replace("' . strtolower($fieldValue) . '"," ","|"))';
					} else if ($fieldName == "rentalItemCode") {
						$query .= ' AND LCASE(rental_item_code) REGEXP LCASE(replace("' . strtolower($fieldValue) . '"," ","|"))';
					} else if ($fieldName == "itemGroupId") {
						$query .= ' AND item_group_id =' . $fieldValue;
					} else if ($fieldName == "itemGroupName") {
						$query .= ' AND LCASE(item_group_name) REGEXP LCASE(replace("' . strtolower($fieldValue) . '"," ","|"))';
					} 
					// else if ($fieldName == "foreignName") {
					// 	$query .= ' AND LCASE(foreign_name) REGEXP LCASE(replace("' . strtolower($fieldValue) . '"," ","|"))';
					// } 
					else if ($fieldName == "stock") {
						$query .= ' AND a.stock = "' . $fieldValue . '"';
					} else if ($fieldName == "status") {
							$query .= ' AND status = "' . $fieldValue . '"';
					} else if ($fieldName == "sapId") {
						$query .= ' AND sap_id = "' . $fieldValue . '"';
					} else if ($fieldName == "postingStatus") {
						$query .= ' AND posting_status = "' . $fieldValue . '"';
					} else if ($fieldName == "createdByName") {
						$query .= ' AND LCASE(created_by_name) REGEXP LCASE(replace("' . strtolower($fieldValue) . '"," ","|"))';
					}
				}
			}
		}

		// ORDERING 
		if (isset($tableProperties['sortField'])) {

			$fieldName = $tableProperties['sortField'];
			$sortOrder = ($tableProperties['sortOrder'] == 1) ? "asc" : "desc";

			// GET SORT KEY DETAILS 
			$fieldName	   = getListingParams($this->config->item('MASTER_RENTAL_ITEM')['columns_list'], $fieldName);

			if (!empty($fieldName)) {
				$query .= ' ORDER BY ' . $fieldName . ' ' . $sortOrder;
			}
		} else {
			$query .= ' ORDER BY updated_on desc';
		}

		// CLONE DB QUERY TO GET THE TOTAL RESULT BEFORE PAGINATION.
		$rs = $this->app_db->query($query);
		$totalRecords = $rs->num_rows();


		// PAGINATION
		if (empty($downloadFlag)) {
			if (isset($tableProperties['first'])) {
				$offset = $tableProperties['first'];
				$limit  = $tableProperties['rows'];
			} else {
				$offset = 0;
				$limit  = $tableProperties['rows'];
			}
			$query .= ' LIMIT ' . $offset . ',' . $limit;
		}

		// GET RESULTS 		
		$searchResultSet = $this->app_db->query($query);
		$searchResultSet = $searchResultSet->result_array();

		// FRAME PHOTO URL 
		foreach ($searchResultSet as $key => $value) {
			$value['rentalItemImgUrl'] 	= getFullImgUrl('rentalItemPhoto', $value['rental_item_image']);
			$searchResultSet[$key] 	= $value;
		}

		// MODEL DATA 
		$modelData['searchResults'] = $searchResultSet;
		$modelData['totalRecords']  = $totalRecords;
		return $modelData;
	}
}
